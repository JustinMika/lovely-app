<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\Client;
use App\Models\Lot;
use App\Models\User;
use App\Models\Vente;
use App\Models\Ville;
use App\Models\Approvisionnement;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Carbon\Carbon;

class DashboardReports extends Component
{
    public $dateRange = '30'; // 7, 30, 90, 365 jours
    public $selectedPeriod = 'month'; // week, month, quarter, year

    public function mount()
    {
        $this->dateRange = '30';
        $this->selectedPeriod = 'month';
    }

    public function updatedDateRange()
    {
        $this->dispatch('refreshCharts');
    }

    public function updatedSelectedPeriod()
    {
        $this->dispatch('refreshCharts');
    }

    public function getGeneralStats()
    {
        $dateFrom = Carbon::now()->subDays($this->dateRange);

        return [
            'total_articles' => Article::count(),
            'articles_actifs' => Article::where('actif', true)->count(),
            'total_clients' => Client::count(),
            'total_users' => User::count(),
            'total_villes' => Ville::count(),
            'total_lots' => Lot::count(),
            'lots_en_stock' => Lot::where('quantite_restante', '>', 0)->count(),
            'total_ventes' => Vente::where('created_at', '>=', $dateFrom)->count(),
            'total_approvisionnements' => Approvisionnement::where('created_at', '>=', $dateFrom)->count(),
        ];
    }

    public function getFinancialStats()
    {
        $dateFrom = Carbon::now()->subDays($this->dateRange);

        $ventesStats = Vente::where('created_at', '>=', $dateFrom)
            ->selectRaw('
                COUNT(*) as nombre_ventes,
                SUM(total) as chiffre_affaires,
                SUM(montant_paye) as montant_encaisse,
                AVG(total) as panier_moyen
            ')
            ->first();

        $beneficeTotal = DB::table('ligne_ventes')
            ->join('ventes', 'ligne_ventes.vente_id', '=', 'ventes.id')
            ->where('ventes.created_at', '>=', $dateFrom)
            ->selectRaw('SUM((ligne_ventes.prix_unitaire - ligne_ventes.prix_achat) * ligne_ventes.quantite) as benefice_total')
            ->first();

        $stockValue = Lot::where('quantite_restante', '>', 0)
            ->selectRaw('SUM(quantite_restante * prix_achat) as valeur_stock_achat, SUM(quantite_restante * prix_vente) as valeur_stock_vente')
            ->first();

        return [
            'nombre_ventes' => $ventesStats->nombre_ventes ?? 0,
            'chiffre_affaires' => $ventesStats->chiffre_affaires ?? 0,
            'montant_encaisse' => $ventesStats->montant_encaisse ?? 0,
            'panier_moyen' => $ventesStats->panier_moyen ?? 0,
            'benefice_total' => $beneficeTotal->benefice_total ?? 0,
            'valeur_stock_achat' => $stockValue->valeur_stock_achat ?? 0,
            'valeur_stock_vente' => $stockValue->valeur_stock_vente ?? 0,
        ];
    }

    public function getTopArticles()
    {
        $dateFrom = Carbon::now()->subDays($this->dateRange);

        return DB::table('ligne_ventes')
            ->join('ventes', 'ligne_ventes.vente_id', '=', 'ventes.id')
            ->join('articles', 'ligne_ventes.article_id', '=', 'articles.id')
            ->where('ventes.created_at', '>=', $dateFrom)
            ->groupBy('articles.id', 'articles.designation')
            ->selectRaw('
                articles.id,
                articles.designation,
                SUM(ligne_ventes.quantite) as quantite_vendue,
                SUM(ligne_ventes.quantite * ligne_ventes.prix_unitaire) as chiffre_affaires,
                SUM((ligne_ventes.prix_unitaire - ligne_ventes.prix_achat) * ligne_ventes.quantite) as benefice
            ')
            ->orderByDesc('quantite_vendue')
            ->limit(5)
            ->get();
    }

    public function getTopClients()
    {
        $dateFrom = Carbon::now()->subDays($this->dateRange);

        return Client::withCount(['ventes' => function ($query) use ($dateFrom) {
            $query->where('created_at', '>=', $dateFrom);
        }])
            ->withSum(['ventes' => function ($query) use ($dateFrom) {
                $query->where('created_at', '>=', $dateFrom);
            }], 'total')
            ->having('ventes_count', '>', 0)
            ->orderByDesc('ventes_sum_total')
            ->limit(5)
            ->get();
    }

    public function getTopVilles()
    {
        return Ville::withCount('lots')
            ->having('lots_count', '>', 0)
            ->orderByDesc('lots_count')
            ->limit(5)
            ->get();
    }

    public function getVentesParPeriode()
    {
        $dateFrom = Carbon::now()->subDays($this->dateRange);

        $groupBy = match ($this->selectedPeriod) {
            'week' => "DATE_FORMAT(created_at, '%Y-%u')",
            'month' => "DATE_FORMAT(created_at, '%Y-%m')",
            'quarter' => "CONCAT(YEAR(created_at), '-Q', QUARTER(created_at))",
            'year' => "YEAR(created_at)",
            default => "DATE(created_at)"
        };

        return Vente::where('created_at', '>=', $dateFrom)
            ->selectRaw("
                {$groupBy} as periode,
                COUNT(*) as nombre_ventes,
                SUM(total) as chiffre_affaires,
                AVG(total) as panier_moyen
            ")
            ->groupByRaw($groupBy)
            ->orderBy('periode')
            ->get();
    }

    public function getStockAlerts()
    {
        // Articles avec stock faible (moins de 10 unités)
        $stockFaible = Lot::with(['article', 'ville'])
            ->where('quantite_restante', '>', 0)
            ->where('quantite_restante', '<=', 10)
            ->orderBy('quantite_restante')
            ->limit(10)
            ->get();

        // Articles en rupture de stock
        $ruptureStock = Article::whereDoesntHave('lots', function ($query) {
            $query->where('quantite_restante', '>', 0);
        })
            ->where('actif', true)
            ->limit(5)
            ->get();

        return [
            'stock_faible' => $stockFaible,
            'rupture_stock' => $ruptureStock,
        ];
    }

    public function exportGlobalReport()
    {
        return redirect()->route('reports.export.global', [
            'dateRange' => $this->dateRange,
            'selectedPeriod' => $this->selectedPeriod,
        ]);
    }

    public function render()
    {
        return view('livewire.dashboard-reports', [
            'generalStats' => $this->getGeneralStats(),
            'financialStats' => $this->getFinancialStats(),
            'topArticles' => $this->getTopArticles(),
            'topClients' => $this->getTopClients(),
            'topVilles' => $this->getTopVilles(),
            'ventesParPeriode' => $this->getVentesParPeriode(),
            'stockAlerts' => $this->getStockAlerts(),
        ]);
    }
}
