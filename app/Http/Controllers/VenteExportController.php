<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class VenteExportController extends Controller
{
    public function exportPdf(Request $request)
    {
        // Récupérer les paramètres de filtrage
        $search = $request->get('search', '');
        $sortField = $request->get('sortField', 'created_at');
        $sortDirection = $request->get('sortDirection', 'desc');

        // Construire la requête avec les mêmes filtres que le composant Livewire
        $query = Vente::with(['client', 'utilisateur', 'ligneVentes.article', 'ligneVentes.lot'])
            ->withCount('ligneVentes');

        // Appliquer la recherche
        if (!empty($search)) {
            $query->where(function (Builder $q) use ($search) {
                $q->whereHas('client', function (Builder $clientQuery) use ($search) {
                    $clientQuery->where('nom', 'like', '%' . $search . '%');
                })
                    ->orWhereHas('utilisateur', function (Builder $userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }

        // Appliquer le tri
        $ventes = $query->orderBy($sortField, $sortDirection)->get();

        // Calculer les statistiques
        $totalVentes = $ventes->count();
        $montantTotal = $ventes->sum('total');
        $montantPaye = $ventes->sum('montant_paye');
        $montantRestant = $ventes->sum(function ($vente) {
            return $vente->montant_restant;
        });
        $beneficeTotal = $ventes->sum(function ($vente) {
            return $vente->benefice;
        });
        $ventesPayees = $ventes->where('montant_restant', 0)->count();
        $ventesPartielles = $ventes->where('montant_restant', '>', 0)->count();

        // Récupérer le symbole de devise
        $settings = AppSetting::getInstance();
        $currencySymbol = $settings->currency_symbol ?? 'USD';
        
        // Créer le PDF avec FPDF
        $pdf = new \FPDF('L', 'mm', 'A4'); // Orientation paysage pour plus d'espace
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // En-tête
        $pdf->Cell(0, 10, 'RAPPORT DES VENTES', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 5, 'Genere le ' . date('d/m/Y a H:i'), 0, 1, 'C');
        $pdf->Ln(5);

        // Statistiques générales
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'STATISTIQUES GENERALES', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 10);

        // Première ligne de statistiques
        $pdf->Cell(60, 6, 'Nombre total de ventes:', 0, 0, 'L');
        $pdf->Cell(40, 6, number_format($totalVentes), 0, 0, 'L');
        $pdf->Cell(60, 6, 'Montant total des ventes:', 0, 0, 'L');
        $pdf->Cell(40, 6, number_format($montantTotal, 0, ',', ' ') . ' ' . $currencySymbol, 0, 1, 'L');

        // Deuxième ligne de statistiques
        $pdf->Cell(60, 6, 'Ventes entierement payees:', 0, 0, 'L');
        $pdf->Cell(40, 6, number_format($ventesPayees), 0, 0, 'L');
        $pdf->Cell(60, 6, 'Montant paye:', 0, 0, 'L');
        $pdf->Cell(40, 6, number_format($montantPaye, 0, ',', ' ') . ' ' . $currencySymbol, 0, 1, 'L');

        // Troisième ligne de statistiques
        $pdf->Cell(60, 6, 'Ventes partiellement payees:', 0, 0, 'L');
        $pdf->Cell(40, 6, number_format($ventesPartielles), 0, 0, 'L');
        $pdf->Cell(60, 6, 'Montant restant a payer:', 0, 0, 'L');
        $pdf->Cell(40, 6, number_format($montantRestant, 0, ',', ' ') . ' ' . $currencySymbol, 0, 1, 'L');

        // Quatrième ligne de statistiques
        $pdf->Cell(60, 6, '', 0, 0, 'L');
        $pdf->Cell(40, 6, '', 0, 0, 'L');
        $pdf->Cell(60, 6, 'Benefice total:', 0, 0, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(40, 6, number_format($beneficeTotal, 0, ',', ' ') . ' ' . $currencySymbol, 0, 1, 'L');
        $pdf->SetFont('Arial', '', 10);

        $pdf->Ln(5);

        // Filtres appliqués
        if (!empty($search)) {
            $pdf->SetFont('Arial', 'I', 9);
            $pdf->Cell(0, 5, 'Filtre applique: ' . $search, 0, 1, 'L');
            $pdf->Ln(2);
        }

        // En-tête du tableau
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(230, 230, 230);

        $pdf->Cell(20, 8, 'N° Vente', 1, 0, 'C', true);
        $pdf->Cell(45, 8, 'Client', 1, 0, 'C', true);
        $pdf->Cell(35, 8, 'Vendeur', 1, 0, 'C', true);
        $pdf->Cell(20, 8, 'Articles', 1, 0, 'C', true);
        $pdf->Cell(30, 8, 'Total (' . $currencySymbol . ')', 1, 0, 'C', true);
        $pdf->Cell(30, 8, 'Paye (' . $currencySymbol . ')', 1, 0, 'C', true);
        $pdf->Cell(30, 8, 'Restant (' . $currencySymbol . ')', 1, 0, 'C', true);
        $pdf->Cell(25, 8, 'Statut', 1, 0, 'C', true);
        $pdf->Cell(30, 8, 'Date', 1, 1, 'C', true);

        // Données du tableau
        $pdf->SetFont('Arial', '', 8);
        $fill = false;

        foreach ($ventes as $vente) {
            // Vérifier si on a assez de place pour une nouvelle ligne
            if ($pdf->GetY() > 180) {
                $pdf->AddPage();

                // Répéter l'en-tête du tableau
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->SetFillColor(230, 230, 230);

                $pdf->Cell(20, 8, 'N° Vente', 1, 0, 'C', true);
                $pdf->Cell(45, 8, 'Client', 1, 0, 'C', true);
                $pdf->Cell(35, 8, 'Vendeur', 1, 0, 'C', true);
                $pdf->Cell(20, 8, 'Articles', 1, 0, 'C', true);
                $pdf->Cell(30, 8, 'Total (' . $currencySymbol . ')', 1, 0, 'C', true);
                $pdf->Cell(30, 8, 'Paye (' . $currencySymbol . ')', 1, 0, 'C', true);
                $pdf->Cell(30, 8, 'Restant (' . $currencySymbol . ')', 1, 0, 'C', true);
                $pdf->Cell(25, 8, 'Statut', 1, 0, 'C', true);
                $pdf->Cell(30, 8, 'Date', 1, 1, 'C', true);

                $pdf->SetFont('Arial', '', 8);
            }

            $pdf->SetFillColor(245, 245, 245);

            $pdf->Cell(20, 6, '#' . str_pad($vente->id, 6, '0', STR_PAD_LEFT), 1, 0, 'C', $fill);
            $pdf->Cell(45, 6, $this->truncateText($vente->client->nom, 25), 1, 0, 'L', $fill);
            $pdf->Cell(35, 6, $this->truncateText($vente->utilisateur->name, 20), 1, 0, 'L', $fill);
            $pdf->Cell(20, 6, $vente->ligne_ventes_count, 1, 0, 'C', $fill);
            $pdf->Cell(30, 6, number_format($vente->total, 0, ',', ' '), 1, 0, 'R', $fill);
            $pdf->Cell(30, 6, number_format($vente->montant_paye, 0, ',', ' '), 1, 0, 'R', $fill);
            $pdf->Cell(30, 6, number_format($vente->montant_restant, 0, ',', ' '), 1, 0, 'R', $fill);
            $pdf->Cell(25, 6, $vente->montant_restant > 0 ? 'Partielle' : 'Payee', 1, 0, 'C', $fill);
            $pdf->Cell(30, 6, $vente->created_at->format('d/m/Y H:i'), 1, 1, 'C', $fill);

            $fill = !$fill;
        }

        // Ligne de total
        if (!$ventes->isEmpty()) {
            $pdf->Ln(2);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(120, 8, 'TOTAUX', 1, 0, 'C', true);
            $pdf->Cell(20, 8, $totalVentes, 1, 0, 'C', true);
            $pdf->Cell(30, 8, number_format($montantTotal, 0, ',', ' '), 1, 0, 'R', true);
            $pdf->Cell(30, 8, number_format($montantPaye, 0, ',', ' '), 1, 0, 'R', true);
            $pdf->Cell(30, 8, number_format($montantRestant, 0, ',', ' '), 1, 0, 'R', true);
            $pdf->Cell(55, 8, '', 1, 1, 'C', true);
        }

        // Pied de page avec informations supplémentaires
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(0, 5, 'Ce rapport contient ' . $totalVentes . ' vente(s) pour un montant total de ' . number_format($montantTotal, 0, ',', ' ') . ' ' . $currencySymbol, 0, 1, 'L');
        $pdf->Cell(0, 5, 'Benefice total realise: ' . number_format($beneficeTotal, 0, ',', ' ') . ' ' . $currencySymbol, 0, 1, 'L');

        // Générer le nom du fichier
        $filename = 'ventes_' . date('Y-m-d_H-i-s') . '.pdf';

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
