<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function generateInvoice(Vente $vente)
    {
        $vente->load(['client', 'utilisateur', 'ligneVentes.lot.article']);
        
        $pdf = Pdf::loadView('invoices.template', compact('vente'));
        
        return $pdf->download('facture-' . $vente->id . '.pdf');
    }
    
    public function generateClientReport(Client $client)
    {
        $client->load(['ventes.utilisateur', 'ventes.ligneVentes.lot.article']);
        
        $totalDepense = $client->ventes->sum('total');
        $totalAchats = $client->ventes->count();
        $panierMoyen = $totalAchats > 0 ? $totalDepense / $totalAchats : 0;
        $derniereVisite = $client->ventes->max('created_at');
        
        $data = compact('client', 'totalDepense', 'totalAchats', 'panierMoyen', 'derniereVisite');
        
        $pdf = Pdf::loadView('reports.client-individual', $data);
        
        return $pdf->download('fiche-client-' . $client->id . '.pdf');
    }
}
