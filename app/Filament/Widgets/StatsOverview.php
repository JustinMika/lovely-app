<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Vente;
use App\Models\Client;
use App\Models\Article;
use App\Models\Lot;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalVentes = Vente::count();
        $revenusJour = Vente::whereDate('created_at', today())->sum('total');
        $revenusMois = Vente::whereMonth('created_at', now()->month)->sum('total');
        $totalClients = Client::count();
        $totalArticles = Article::where('actif', true)->count();
        $lotsEnRupture = Lot::where('quantite_restante', '<=', DB::raw('seuil_alerte'))->count();

        return [
            Stat::make('Ventes Totales', $totalVentes)
                ->description('Nombre total de ventes')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('success'),
                
            Stat::make('Revenus du Jour', '€' . number_format($revenusJour, 2))
                ->description('Chiffre d\'affaires aujourd\'hui')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary'),
                
            Stat::make('Revenus du Mois', '€' . number_format($revenusMois, 2))
                ->description('Chiffre d\'affaires ce mois')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),
                
            Stat::make('Clients Actifs', $totalClients)
                ->description('Nombre total de clients')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
                
            Stat::make('Articles Actifs', $totalArticles)
                ->description('Articles disponibles')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),
                
            Stat::make('Alertes Stock', $lotsEnRupture)
                ->description('Lots en rupture/seuil')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($lotsEnRupture > 0 ? 'danger' : 'success'),
        ];
    }
}
