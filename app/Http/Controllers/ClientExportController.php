<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\AppSetting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ClientExportController extends Controller
{
    public function exportPdf(Request $request)
    {
        // Récupérer les paramètres de filtrage
        $search = $request->get('search', '');
        $sortField = $request->get('sortField', 'nom');
        $sortDirection = $request->get('sortDirection', 'asc');

        // Construire la requête avec les mêmes filtres que le composant Livewire
        $query = Client::with(['ventes']);

        // Appliquer la recherche
        if (!empty($search)) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('nom', 'like', '%' . $search . '%')
                    ->orWhere('telephone', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Appliquer le tri
        $clients = $query->orderBy($sortField, $sortDirection)->get();

        // Calculer les statistiques
        $totalClients = $clients->count();
        $clientsActifs = $clients->filter(function ($client) {
            return $client->ventes->count() > 0;
        })->count();
        $montantTotalAchats = $clients->sum(function ($client) {
            return $client->ventes->sum('total');
        });
        $nombreTotalVentes = $clients->sum(function ($client) {
            return $client->ventes->count();
        });

        $stats = [
            'total_clients' => $totalClients,
            'clients_actifs' => $clientsActifs,
            'clients_inactifs' => $totalClients - $clientsActifs,
            'montant_total_achats' => $montantTotalAchats,
            'nombre_total_ventes' => $nombreTotalVentes,
            'panier_moyen' => $nombreTotalVentes > 0 ? $montantTotalAchats / $nombreTotalVentes : 0,
        ];

        // Créer le PDF avec FPDF
        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // En-tête
        $pdf->Cell(0, 10, 'Liste des Clients', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 5, 'Genere le ' . date('d/m/Y à H:i'), 0, 1, 'C');
        $pdf->Ln(10);

        // Statistiques
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, 'Statistiques Generales', 0, 1);
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(50, 6, 'Total clients:', 0, 0);
        $pdf->Cell(40, 6, number_format($stats['total_clients']), 0, 1);
        $pdf->Cell(50, 6, 'Clients actifs:', 0, 0);
        $pdf->Cell(40, 6, number_format($stats['clients_actifs']), 0, 1);
        // Récupérer le symbole de devise
        $settings = AppSetting::getInstance();
        $currencySymbol = $settings->currency_symbol ?? 'USD';
        $currencyPosition = $settings->currency_position ?? 'after';
        
        $pdf->Cell(50, 6, 'Montant total achats:', 0, 0);
        $montantFormatted = number_format($stats['montant_total_achats'], 0, ',', ' ');
        $pdf->Cell(40, 6, ($currencyPosition === 'before' ? $currencySymbol . ' ' . $montantFormatted : $montantFormatted . ' ' . $currencySymbol), 0, 1);
        $pdf->Cell(50, 6, 'Panier moyen:', 0, 0);
        $panierFormatted = number_format($stats['panier_moyen'], 0, ',', ' ');
        $pdf->Cell(40, 6, ($currencyPosition === 'before' ? $currencySymbol . ' ' . $panierFormatted : $panierFormatted . ' ' . $currencySymbol), 0, 1);
        $pdf->Ln(10);

        // En-tête du tableau
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(240, 240, 240);
        $pdf->Cell(60, 8, 'Nom', 1, 0, 'L', true);
        $pdf->Cell(40, 8, 'Telephone', 1, 0, 'L', true);
        $pdf->Cell(50, 8, 'Email', 1, 0, 'L', true);
        $pdf->Cell(20, 8, 'Ventes', 1, 0, 'C', true);
        $pdf->Cell(20, 8, 'Total', 1, 1, 'C', true);

        // Données
        $pdf->SetFont('Arial', '', 9);
        foreach ($clients as $client) {
            $totalVentes = $client->ventes->count();
            $montantTotal = $client->ventes->sum('total');

            $pdf->Cell(60, 6, substr($client->nom, 0, 25), 1, 0, 'L');
            $pdf->Cell(40, 6, $client->telephone ?: '-', 1, 0, 'L');
            $pdf->Cell(50, 6, substr($client->email ?: '-', 0, 20), 1, 0, 'L');
            $pdf->Cell(20, 6, $totalVentes, 1, 0, 'C');
            $pdf->Cell(20, 6, number_format($montantTotal, 0, ',', ' '), 1, 1, 'R');
        }

        // Footer avec informations de filtrage
        if (!empty($search)) {
            $pdf->Ln(5);
            $pdf->SetFont('Arial', 'I', 8);
            $pdf->Cell(0, 4, 'Filtre applique: ' . $search, 0, 1);
        }

        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(0, 4, 'Tri: ' . $sortField . ' (' . ($sortDirection === 'asc' ? 'croissant' : 'decroissant') . ')', 0, 1);

        // Retourner le PDF
        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="clients_' . date('Y-m-d_H-i-s') . '.pdf"');
    }
}
