<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class UserExportController extends Controller
{
    public function exportPdf(Request $request)
    {
        // Récupérer les paramètres de filtrage
        $search = $request->get('search', '');
        $roleFilter = $request->get('roleFilter', '');
        $sortField = $request->get('sortField', 'created_at');
        $sortDirection = $request->get('sortDirection', 'desc');

        // Construire la requête avec les mêmes filtres que le composant Livewire
        $query = User::with(['role'])
            ->withCount(['ventes', 'approvisionnements']);

        // Appliquer la recherche
        if (!empty($search)) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhereHas('role', function (Builder $roleQuery) use ($search) {
                        $roleQuery->where('nom', 'like', '%' . $search . '%');
                    });
            });
        }

        // Appliquer le filtre de rôle
        if (!empty($roleFilter)) {
            $query->where('role_id', $roleFilter);
        }

        // Appliquer le tri
        $users = $query->orderBy($sortField, $sortDirection)->get();

        // Calculer les statistiques
        $totalUsers = $users->count();
        $totalVentes = $users->sum('ventes_count');
        $totalApprovisionnements = $users->sum('approvisionnements_count');

        // Statistiques par rôle
        $roles = Role::all();
        $statsParRole = [];
        foreach ($roles as $role) {
            $usersInRole = $users->where('role_id', $role->id);
            $statsParRole[$role->nom] = [
                'count' => $usersInRole->count(),
                'ventes' => $usersInRole->sum('ventes_count'),
                'approvisionnements' => $usersInRole->sum('approvisionnements_count'),
            ];
        }

        // Créer le PDF avec FPDF
        $pdf = new \FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // En-tête
        $pdf->Cell(0, 10, 'RAPPORT DES UTILISATEURS', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 5, 'Genere le ' . date('d/m/Y a H:i'), 0, 1, 'C');
        $pdf->Ln(5);

        // Statistiques générales
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'STATISTIQUES GENERALES', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 10);

        // Première ligne de statistiques
        $pdf->Cell(60, 6, 'Nombre total d\'utilisateurs:', 0, 0, 'L');
        $pdf->Cell(40, 6, number_format($totalUsers), 0, 0, 'L');
        $pdf->Cell(60, 6, 'Total des ventes:', 0, 0, 'L');
        $pdf->Cell(30, 6, number_format($totalVentes), 0, 1, 'L');

        // Deuxième ligne de statistiques
        $pdf->Cell(60, 6, 'Total des approvisionnements:', 0, 0, 'L');
        $pdf->Cell(40, 6, number_format($totalApprovisionnements), 0, 1, 'L');

        $pdf->Ln(5);

        // Statistiques par rôle
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'REPARTITION PAR ROLE', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 10);

        foreach ($statsParRole as $roleName => $stats) {
            if ($stats['count'] > 0) {
                $pdf->Cell(40, 6, $roleName . ':', 0, 0, 'L');
                $pdf->Cell(30, 6, $stats['count'] . ' utilisateur(s)', 0, 0, 'L');
                $pdf->Cell(30, 6, $stats['ventes'] . ' vente(s)', 0, 0, 'L');
                $pdf->Cell(40, 6, $stats['approvisionnements'] . ' appro(s)', 0, 1, 'L');
            }
        }

        $pdf->Ln(5);

        // Filtres appliqués
        if (!empty($search) || !empty($roleFilter)) {
            $pdf->SetFont('Arial', 'I', 9);
            $filtres = [];
            if (!empty($search)) {
                $filtres[] = 'Recherche: ' . $search;
            }
            if (!empty($roleFilter)) {
                $role = Role::find($roleFilter);
                if ($role) {
                    $filtres[] = 'Role: ' . $role->nom;
                }
            }
            $pdf->Cell(0, 5, 'Filtres appliques: ' . implode(', ', $filtres), 0, 1, 'L');
            $pdf->Ln(2);
        }

        // En-tête du tableau
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(230, 230, 230);

        $pdf->Cell(50, 8, 'Nom', 1, 0, 'C', true);
        $pdf->Cell(60, 8, 'Email', 1, 0, 'C', true);
        $pdf->Cell(25, 8, 'Role', 1, 0, 'C', true);
        $pdf->Cell(20, 8, 'Ventes', 1, 0, 'C', true);
        $pdf->Cell(20, 8, 'Appros', 1, 0, 'C', true);
        $pdf->Cell(25, 8, 'Cree le', 1, 1, 'C', true);

        // Données du tableau
        $pdf->SetFont('Arial', '', 8);
        $fill = false;

        foreach ($users as $user) {
            // Vérifier si on a assez de place pour une nouvelle ligne
            if ($pdf->GetY() > 250) {
                $pdf->AddPage();

                // Répéter l'en-tête du tableau
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->SetFillColor(230, 230, 230);

                $pdf->Cell(50, 8, 'Nom', 1, 0, 'C', true);
                $pdf->Cell(60, 8, 'Email', 1, 0, 'C', true);
                $pdf->Cell(25, 8, 'Role', 1, 0, 'C', true);
                $pdf->Cell(20, 8, 'Ventes', 1, 0, 'C', true);
                $pdf->Cell(20, 8, 'Appros', 1, 0, 'C', true);
                $pdf->Cell(25, 8, 'Cree le', 1, 1, 'C', true);

                $pdf->SetFont('Arial', '', 8);
            }

            $pdf->SetFillColor(245, 245, 245);

            $pdf->Cell(50, 6, $this->truncateText($user->name, 30), 1, 0, 'L', $fill);
            $pdf->Cell(60, 6, $this->truncateText($user->email, 35), 1, 0, 'L', $fill);
            $pdf->Cell(25, 6, $this->truncateText($user->role->nom, 15), 1, 0, 'C', $fill);
            $pdf->Cell(20, 6, $user->ventes_count, 1, 0, 'C', $fill);
            $pdf->Cell(20, 6, $user->approvisionnements_count, 1, 0, 'C', $fill);
            $pdf->Cell(25, 6, $user->created_at->format('d/m/Y'), 1, 1, 'C', $fill);

            $fill = !$fill;
        }

        // Ligne de total
        if (!$users->isEmpty()) {
            $pdf->Ln(2);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(135, 8, 'TOTAUX', 1, 0, 'C', true);
            $pdf->Cell(20, 8, $totalVentes, 1, 0, 'C', true);
            $pdf->Cell(20, 8, $totalApprovisionnements, 1, 0, 'C', true);
            $pdf->Cell(25, 8, '', 1, 1, 'C', true);
        }

        // Pied de page avec informations supplémentaires
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(0, 5, 'Ce rapport contient ' . $totalUsers . ' utilisateur(s)', 0, 1, 'L');
        $pdf->Cell(0, 5, 'Total des activites: ' . $totalVentes . ' vente(s) et ' . $totalApprovisionnements . ' approvisionnement(s)', 0, 1, 'L');

        // Générer le nom du fichier
        $filename = 'utilisateurs_' . date('Y-m-d_H-i-s') . '.pdf';

        // Retourner le PDF
        return response($pdf->Output('S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Tronquer le texte si trop long
     */
    private function truncateText($text, $maxLength)
    {
        if (strlen($text) <= $maxLength) {
            return $text;
        }
        return substr($text, 0, $maxLength - 3) . '...';
    }
}
