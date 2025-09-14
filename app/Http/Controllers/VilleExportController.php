<?php

namespace App\Http\Controllers;

use App\Models\Ville;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class VilleExportController extends Controller
{
    public function exportPdf(Request $request)
    {
        // Récupérer les paramètres de filtrage
        $search = $request->get('search', '');
        $sortField = $request->get('sortField', 'nom');
        $sortDirection = $request->get('sortDirection', 'asc');

        // Construire la requête avec les mêmes filtres que le composant Livewire
        $query = Ville::withCount(['lots']);

        // Appliquer la recherche
        if (!empty($search)) {
            $query->where('nom', 'like', '%' . $search . '%');
        }

        // Appliquer le tri
        $villes = $query->orderBy($sortField, $sortDirection)->get();

        // Calculer les statistiques
        $totalVilles = $villes->count();
        $totalLots = $villes->sum('lots_count');

        // Statistiques détaillées
        $villesAvecLots = $villes->where('lots_count', '>', 0)->count();
        $villesSansLots = $villes->where('lots_count', 0)->count();
        $moyenneLots = $totalVilles > 0 ? round($totalLots / $totalVilles, 2) : 0;

        // Créer le PDF avec FPDF
        $pdf = new \FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // En-tête
        $pdf->Cell(0, 10, 'RAPPORT DES VILLES', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 5, 'Genere le ' . date('d/m/Y a H:i'), 0, 1, 'C');
        $pdf->Ln(5);

        // Statistiques générales
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'STATISTIQUES GENERALES', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 10);

        // Première ligne de statistiques
        $pdf->Cell(60, 6, 'Nombre total de villes:', 0, 0, 'L');
        $pdf->Cell(40, 6, number_format($totalVilles), 0, 0, 'L');
        $pdf->Cell(60, 6, 'Total des lots:', 0, 0, 'L');
        $pdf->Cell(30, 6, number_format($totalLots), 0, 1, 'L');

        // Deuxième ligne de statistiques
        $pdf->Cell(60, 6, 'Villes avec lots:', 0, 0, 'L');
        $pdf->Cell(40, 6, number_format($villesAvecLots), 0, 0, 'L');
        $pdf->Cell(60, 6, 'Villes sans lots:', 0, 0, 'L');
        $pdf->Cell(30, 6, number_format($villesSansLots), 0, 1, 'L');

        // Troisième ligne de statistiques
        $pdf->Cell(60, 6, 'Moyenne lots par ville:', 0, 0, 'L');
        $pdf->Cell(40, 6, $moyenneLots, 0, 1, 'L');

        $pdf->Ln(5);

        // Top 5 des villes avec le plus de lots
        $topVilles = $villes->sortByDesc('lots_count')->take(5);
        if ($topVilles->count() > 0 && $topVilles->first()->lots_count > 0) {
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 8, 'TOP 5 DES VILLES (par nombre de lots)', 0, 1, 'L');
            $pdf->SetFont('Arial', '', 10);

            foreach ($topVilles as $index => $ville) {
                if ($ville->lots_count > 0) {
                    $pdf->Cell(10, 6, ($index + 1) . '.', 0, 0, 'L');
                    $pdf->Cell(80, 6, $ville->nom, 0, 0, 'L');
                    $pdf->Cell(30, 6, $ville->lots_count . ' lot(s)', 0, 1, 'L');
                }
            }
            $pdf->Ln(3);
        }

        // Filtres appliqués
        if (!empty($search)) {
            $pdf->SetFont('Arial', 'I', 9);
            $pdf->Cell(0, 5, 'Filtre applique - Recherche: ' . $search, 0, 1, 'L');
            $pdf->Ln(2);
        }

        // En-tête du tableau
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(230, 230, 230);

        $pdf->Cell(80, 8, 'Nom de la Ville', 1, 0, 'C', true);
        $pdf->Cell(30, 8, 'Nb Lots', 1, 0, 'C', true);
        $pdf->Cell(40, 8, 'Date Creation', 1, 0, 'C', true);
        $pdf->Cell(40, 8, 'Statut', 1, 1, 'C', true);

        // Données du tableau
        $pdf->SetFont('Arial', '', 8);
        $fill = false;

        foreach ($villes as $ville) {
            // Vérifier si on a assez de place pour une nouvelle ligne
            if ($pdf->GetY() > 250) {
                $pdf->AddPage();

                // Répéter l'en-tête du tableau
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->SetFillColor(230, 230, 230);

                $pdf->Cell(80, 8, 'Nom de la Ville', 1, 0, 'C', true);
                $pdf->Cell(30, 8, 'Nb Lots', 1, 0, 'C', true);
                $pdf->Cell(40, 8, 'Date Creation', 1, 0, 'C', true);
                $pdf->Cell(40, 8, 'Statut', 1, 1, 'C', true);

                $pdf->SetFont('Arial', '', 8);
            }

            $pdf->SetFillColor(245, 245, 245);

            $pdf->Cell(80, 6, $this->truncateText($ville->nom, 45), 1, 0, 'L', $fill);
            $pdf->Cell(30, 6, $ville->lots_count, 1, 0, 'C', $fill);
            $pdf->Cell(40, 6, $ville->created_at->format('d/m/Y'), 1, 0, 'C', $fill);

            // Statut basé sur le nombre de lots
            $statut = $ville->lots_count > 0 ? 'Active' : 'Inactive';
            $pdf->Cell(40, 6, $statut, 1, 1, 'C', $fill);

            $fill = !$fill;
        }

        // Ligne de total
        if (!$villes->isEmpty()) {
            $pdf->Ln(2);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(80, 8, 'TOTAUX', 1, 0, 'C', true);
            $pdf->Cell(30, 8, $totalLots, 1, 0, 'C', true);
            $pdf->Cell(80, 8, $totalVilles . ' ville(s)', 1, 1, 'C', true);
        }

        // Pied de page avec informations supplémentaires
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(0, 5, 'Ce rapport contient ' . $totalVilles . ' ville(s)', 0, 1, 'L');
        $pdf->Cell(0, 5, 'Repartition: ' . $villesAvecLots . ' ville(s) active(s), ' . $villesSansLots . ' ville(s) inactive(s)', 0, 1, 'L');
        $pdf->Cell(0, 5, 'Total des lots repartis: ' . number_format($totalLots) . ' lot(s)', 0, 1, 'L');

        // Générer le nom du fichier
        $filename = 'villes_' . date('Y-m-d_H-i-s') . '.pdf';

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
