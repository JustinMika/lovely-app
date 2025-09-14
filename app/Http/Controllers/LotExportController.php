<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use FPDF;

class LotExportController extends Controller
{
    public function exportPdf(Request $request)
    {
        // Récupérer les paramètres de filtrage
        $search = $request->get('search', '');
        $sortField = $request->get('sortField', 'created_at');
        $sortDirection = $request->get('sortDirection', 'desc');

        // Construire la requête avec les mêmes filtres que le composant Livewire
        $lots = Lot::with(['article', 'ville', 'approvisionnement.utilisateur'])
            ->when($search, function (Builder $query) use ($search) {
                $query->where(function (Builder $q) use ($search) {
                    $q->where('numero_lot', 'like', '%' . $search . '%')
                        ->orWhereHas('article', function (Builder $articleQuery) use ($search) {
                            $articleQuery->where('designation', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('ville', function (Builder $villeQuery) use ($search) {
                            $villeQuery->where('nom', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('approvisionnement', function (Builder $approvQuery) use ($search) {
                            $approvQuery->where('fournisseur', 'like', '%' . $search . '%');
                        });
                });
            })
            ->orderBy($sortField, $sortDirection)
            ->get();

        // Créer le PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // En-tête du document
        $pdf->Cell(0, 10, 'LOVELY BOUTIQUE', 0, 1, 'C');
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Liste des Lots', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 5, 'Date d\'export: ' . now()->format('d/m/Y H:i'), 0, 1, 'C');

        if ($search) {
            $pdf->Cell(0, 5, 'Filtre applique: ' . $search, 0, 1, 'C');
        }

        $pdf->Ln(10);

        // Statistiques
        $totalLots = $lots->count();
        $stockTotal = $lots->sum('quantite_restante');
        $valeurStock = $lots->sum(function ($lot) {
            return $lot->quantite_restante * $lot->prix_vente;
        });

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'Statistiques', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(60, 6, 'Nombre total de lots:', 0, 0, 'L');
        $pdf->Cell(40, 6, $totalLots, 0, 1, 'L');
        $pdf->Cell(60, 6, 'Stock total (unites):', 0, 0, 'L');
        $pdf->Cell(40, 6, number_format($stockTotal, 0, ',', ' '), 0, 1, 'L');
        $pdf->Cell(60, 6, 'Valeur du stock (FC):', 0, 0, 'L');
        $pdf->Cell(40, 6, number_format($valeurStock, 0, ',', ' '), 0, 1, 'L');
        $pdf->Ln(10);

        // En-tête du tableau
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetFillColor(230, 230, 230);

        // Largeurs des colonnes
        $w = array(25, 35, 20, 30, 20, 25, 25, 20);

        // En-têtes
        $headers = array('N° Lot', 'Article', 'Ville', 'Fournisseur', 'Stock', 'Prix Achat', 'Prix Vente', 'Date');

        for ($i = 0; $i < count($headers); $i++) {
            $pdf->Cell($w[$i], 7, $headers[$i], 1, 0, 'C', true);
        }
        $pdf->Ln();

        // Données du tableau
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetFillColor(245, 245, 245);
        $fill = false;

        foreach ($lots as $lot) {
            // Vérifier si on a besoin d'une nouvelle page
            if ($pdf->GetY() > 250) {
                $pdf->AddPage();

                // Répéter l'en-tête du tableau
                $pdf->SetFont('Arial', 'B', 8);
                $pdf->SetFillColor(230, 230, 230);
                for ($i = 0; $i < count($headers); $i++) {
                    $pdf->Cell($w[$i], 7, $headers[$i], 1, 0, 'C', true);
                }
                $pdf->Ln();
                $pdf->SetFont('Arial', '', 7);
                $pdf->SetFillColor(245, 245, 245);
            }

            // Données de la ligne
            $pdf->Cell($w[0], 6, $this->truncateText($lot->numero_lot, 12), 1, 0, 'L', $fill);
            $pdf->Cell($w[1], 6, $this->truncateText($lot->article->designation, 18), 1, 0, 'L', $fill);
            $pdf->Cell($w[2], 6, $this->truncateText($lot->ville->nom, 12), 1, 0, 'L', $fill);
            $pdf->Cell($w[3], 6, $this->truncateText($lot->approvisionnement->fournisseur, 15), 1, 0, 'L', $fill);
            $pdf->Cell($w[4], 6, $lot->quantite_restante . '/' . $lot->quantite_initiale, 1, 0, 'C', $fill);
            $pdf->Cell($w[5], 6, number_format($lot->prix_achat, 0, ',', ' '), 1, 0, 'R', $fill);
            $pdf->Cell($w[6], 6, number_format($lot->prix_vente, 0, ',', ' '), 1, 0, 'R', $fill);
            $pdf->Cell($w[7], 6, $lot->date_arrivee->format('d/m/Y'), 1, 1, 'C', $fill);

            $fill = !$fill;
        }

        // Pied de page avec informations supplémentaires
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(0, 5, 'Document genere automatiquement par Lovely Boutique', 0, 1, 'C');
        $pdf->Cell(0, 5, 'Total: ' . $totalLots . ' lot(s) - Exporte le ' . now()->format('d/m/Y à H:i'), 0, 1, 'C');

        // Générer le nom du fichier
        $filename = 'lots_' . now()->format('Y-m-d_H-i-s') . '.pdf';

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
