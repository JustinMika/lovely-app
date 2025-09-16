<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Models\Vente;
use App\Models\Client;
use App\Models\Lot;
use App\Models\Article;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    
    protected static ?string $navigationGroup = 'Rapports';
    
    protected static ?string $title = 'Rapports & Exports';
    
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.reports-page';
    
    protected function getHeaderActions(): array
    {
        return [
            Action::make('financial_report')
                ->label('Rapport Financier')
                ->icon('heroicon-o-banknotes')
                ->color('success')
                ->action(function () {
                    return $this->generateFinancialReport();
                }),
                
            Action::make('stock_report')
                ->label('État du Stock')
                ->icon('heroicon-o-cube')
                ->color('warning')
                ->action(function () {
                    return $this->generateStockReport();
                }),
                
            Action::make('clients_report')
                ->label('Liste des Clients')
                ->icon('heroicon-o-users')
                ->color('info')
                ->action(function () {
                    return $this->generateClientsReport();
                }),
                
            Action::make('sales_report')
                ->label('Ventes par Période')
                ->icon('heroicon-o-shopping-cart')
                ->color('primary')
                ->action(function () {
                    return $this->generateSalesReport();
                }),
        ];
    }
    
    public function generateFinancialReport()
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        $ventes = Vente::whereBetween('created_at', [$startDate, $endDate])
            ->with(['client', 'utilisateur', 'ligneVentes.lot.article'])
            ->get();
            
        $totalRevenu = $ventes->sum('total');
        $totalBenefice = $ventes->sum('benefice');
        $nombreVentes = $ventes->count();
        $panierMoyen = $nombreVentes > 0 ? $totalRevenu / $nombreVentes : 0;
        
        $data = compact('ventes', 'totalRevenu', 'totalBenefice', 'nombreVentes', 'panierMoyen', 'startDate', 'endDate');
        
        $pdf = Pdf::loadView('reports.financial', $data);
        return response()->streamDownload(
            fn () => print($pdf->output()),
            'rapport-financier-' . now()->format('Y-m-d') . '.pdf'
        );
    }
    
    public function generateStockReport()
    {
        $lots = Lot::with(['article', 'ville'])
            ->orderBy('quantite_restante', 'asc')
            ->get();
            
        $lotsEnRupture = $lots->filter(fn($lot) => $lot->quantite_restante <= $lot->seuil_alerte);
        $lotsExpires = $lots->filter(fn($lot) => $lot->date_expiration <= now());
        $lotsProchesExpiration = $lots->filter(fn($lot) => 
            $lot->date_expiration > now() && 
            $lot->date_expiration <= now()->addDays(30)
        );
        
        $data = compact('lots', 'lotsEnRupture', 'lotsExpires', 'lotsProchesExpiration');
        
        $pdf = Pdf::loadView('reports.stock', $data);
        return response()->streamDownload(
            fn () => print($pdf->output()),
            'rapport-stock-' . now()->format('Y-m-d') . '.pdf'
        );
    }
    
    public function generateClientsReport()
    {
        $clients = Client::withCount('ventes')
            ->with(['ventes' => function($query) {
                $query->selectRaw('client_id, SUM(total) as total_achats, COUNT(*) as nombre_achats')
                    ->groupBy('client_id');
            }])
            ->orderBy('ventes_count', 'desc')
            ->get();
            
        $data = compact('clients');
        
        $pdf = Pdf::loadView('reports.clients', $data);
        return response()->streamDownload(
            fn () => print($pdf->output()),
            'rapport-clients-' . now()->format('Y-m-d') . '.pdf'
        );
    }
    
    public function generateSalesReport()
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        $ventes = Vente::whereBetween('created_at', [$startDate, $endDate])
            ->with(['client', 'utilisateur'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $ventesParJour = $ventes->groupBy(function($vente) {
            return $vente->created_at->format('Y-m-d');
        });
        
        $articlesVendus = Vente::join('ligne_ventes', 'ventes.id', '=', 'ligne_ventes.vente_id')
            ->join('lots', 'ligne_ventes.lot_id', '=', 'lots.id')
            ->join('articles', 'lots.article_id', '=', 'articles.id')
            ->selectRaw('articles.designation, SUM(ligne_ventes.quantite) as total_vendu')
            ->whereBetween('ventes.created_at', [$startDate, $endDate])
            ->groupBy('articles.id', 'articles.designation')
            ->orderBy('total_vendu', 'desc')
            ->limit(10)
            ->get();
        
        $data = compact('ventes', 'ventesParJour', 'articlesVendus', 'startDate', 'endDate');
        
        $pdf = Pdf::loadView('reports.sales', $data);
        return response()->streamDownload(
            fn () => print($pdf->output()),
            'rapport-ventes-' . now()->format('Y-m-d') . '.pdf'
        );
    }
}
