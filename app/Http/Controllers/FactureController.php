<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FactureController extends Controller
{
    /**
     * Générer et afficher la facture PDF
     */
    public function genererFacture($venteId)
    {
        $vente = Vente::with([
            'client',
            'utilisateur',
            'ligneVentes.article',
            'ligneVentes.lot.ville'
        ])->findOrFail($venteId);

        // Créer le PDF avec FPDF
        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // En-tête de la facture
        $pdf->Cell(0, 10, 'FACTURE DE VENTE', 0, 1, 'C');
        $pdf->Ln(5);

        // Informations de l'entreprise (à personnaliser)
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'LOVELY SHOP', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, 'Adresse: Kinshasa, RDC', 0, 1, 'L');
        $pdf->Cell(0, 6, 'Tel: +243 XXX XXX XXX', 0, 1, 'L');
        $pdf->Ln(5);

        // Informations de la vente
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 6, 'Facture N°:', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, str_pad($vente->id, 6, '0', STR_PAD_LEFT), 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 6, 'Date:', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, $vente->created_at->format('d/m/Y H:i'), 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 6, 'Vendeur:', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, $vente->utilisateur->name, 0, 1, 'L');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 6, 'Client:', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 6, $vente->client->nom, 0, 1, 'L');

        if ($vente->client->telephone) {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(50, 6, 'Téléphone:', 0, 0, 'L');
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell(0, 6, $vente->client->telephone, 0, 1, 'L');
        }

        $pdf->Ln(10);

        // En-tête du tableau des articles
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Cell(80, 8, 'Article', 1, 0, 'L', true);
        $pdf->Cell(20, 8, 'Qté', 1, 0, 'C', true);
        $pdf->Cell(25, 8, 'Prix Unit.', 1, 0, 'R', true);
        $pdf->Cell(25, 8, 'Remise', 1, 0, 'R', true);
        $pdf->Cell(30, 8, 'Sous-total', 1, 1, 'R', true);

        // Lignes des articles
        $pdf->SetFont('Arial', '', 8);
        $totalGeneral = 0;
        $totalRemises = 0;

        foreach ($vente->ligneVentes as $ligne) {
            $sousTotal = ($ligne->prix_unitaire * $ligne->quantite) - $ligne->remise_ligne;
            $totalGeneral += $sousTotal;
            $totalRemises += $ligne->remise_ligne;

            // Nom de l'article (tronqué si trop long)
            $nomArticle = strlen($ligne->article->designation) > 35
                ? substr($ligne->article->designation, 0, 32) . '...'
                : $ligne->article->designation;

            $pdf->Cell(80, 6, $nomArticle, 1, 0, 'L');
            $pdf->Cell(20, 6, $ligne->quantite, 1, 0, 'C');
            $pdf->Cell(25, 6, number_format($ligne->prix_unitaire, 0, ',', ' ') . ' FC', 1, 0, 'R');
            $pdf->Cell(25, 6, number_format($ligne->remise_ligne, 0, ',', ' ') . ' FC', 1, 0, 'R');
            $pdf->Cell(30, 6, number_format($sousTotal, 0, ',', ' ') . ' FC', 1, 1, 'R');
        }

        // Totaux
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 10);

        // Sous-total
        $pdf->Cell(130, 6, '', 0, 0);
        $pdf->Cell(25, 6, 'Sous-total:', 0, 0, 'R');
        $pdf->Cell(30, 6, number_format($totalGeneral + $totalRemises, 0, ',', ' ') . ' FC', 1, 1, 'R');

        // Remises
        if ($totalRemises > 0) {
            $pdf->Cell(130, 6, '', 0, 0);
            $pdf->Cell(25, 6, 'Remises:', 0, 0, 'R');
            $pdf->Cell(30, 6, '- ' . number_format($totalRemises, 0, ',', ' ') . ' FC', 1, 1, 'R');
        }

        // Remise totale additionnelle
        if ($vente->remise_totale > 0) {
            $pdf->Cell(130, 6, '', 0, 0);
            $pdf->Cell(25, 6, 'Remise totale:', 0, 0, 'R');
            $pdf->Cell(30, 6, '- ' . number_format($vente->remise_totale, 0, ',', ' ') . ' FC', 1, 1, 'R');
        }

        // Total final
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(130, 8, '', 0, 0);
        $pdf->Cell(25, 8, 'TOTAL:', 1, 0, 'R');
        $pdf->Cell(30, 8, number_format($vente->total, 0, ',', ' ') . ' FC', 1, 1, 'R');

        // Montant payé
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(130, 6, '', 0, 0);
        $pdf->Cell(25, 6, 'Payé:', 0, 0, 'R');
        $pdf->Cell(30, 6, number_format($vente->montant_paye, 0, ',', ' ') . ' FC', 1, 1, 'R');

        // Monnaie à rendre
        $monnaie = $vente->montant_paye - $vente->total;
        if ($monnaie > 0) {
            $pdf->Cell(130, 6, '', 0, 0);
            $pdf->Cell(25, 6, 'Monnaie:', 0, 0, 'R');
            $pdf->Cell(30, 6, number_format($monnaie, 0, ',', ' ') . ' FC', 1, 1, 'R');
        } elseif ($monnaie < 0) {
            $pdf->Cell(130, 6, '', 0, 0);
            $pdf->Cell(25, 6, 'Reste à payer:', 0, 0, 'R');
            $pdf->Cell(30, 6, number_format(abs($monnaie), 0, ',', ' ') . ' FC', 1, 1, 'R');
        }

        // Pied de page
        $pdf->Ln(15);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(0, 4, 'Merci pour votre confiance!', 0, 1, 'C');
        $pdf->Cell(0, 4, 'Cette facture a été générée automatiquement le ' . now()->format('d/m/Y à H:i'), 0, 1, 'C');

        // Retourner le PDF
        return response($pdf->Output('S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="facture_' . str_pad($vente->id, 6, '0', STR_PAD_LEFT) . '.pdf"'
        ]);
    }

    /**
     * Imprimer automatiquement la facture (pour impression directe)
     */
    public function imprimerFacture($venteId)
    {
        $vente = Vente::findOrFail($venteId);

        // Générer le PDF et le retourner avec headers pour impression automatique
        $response = $this->genererFacture($venteId);

        // Ajouter des headers JavaScript pour déclencher l'impression
        $response->headers->set('Content-Disposition', 'inline; filename="facture_' . str_pad($vente->id, 6, '0', STR_PAD_LEFT) . '.pdf"');

        return $response;
    }

    /**
     * Télécharger la facture
     */
    public function telechargerFacture($venteId)
    {
        $vente = Vente::findOrFail($venteId);

        $response = $this->genererFacture($venteId);
        $response->headers->set('Content-Disposition', 'attachment; filename="facture_' . str_pad($vente->id, 6, '0', STR_PAD_LEFT) . '.pdf"');

        return $response;
    }
}
