<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\Client;
use App\Models\Article;
use App\Models\Lot;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
	/**
	 * Display the dashboard overview.
	 */
	public function index(): View
	{
		// Calcul des métriques depuis la base de données
		$totalSales = Vente::count();
		$totalRevenue = Vente::sum('total');
		$totalClients = Client::count();
		$totalArticles = Article::count();

		// Alertes de stock faible
		$lowStockThreshold = app_setting('low_stock_threshold', 10);
		$lowStockAlerts = Lot::where('quantite_restante', '>', 0)
			->where('quantite_restante', '<=', $lowStockThreshold)
			->count();

		// Calcul de la croissance du CA (comparaison mois actuel vs mois précédent)
		$currentMonthRevenue = Vente::whereMonth('created_at', Carbon::now()->month)
			->whereYear('created_at', Carbon::now()->year)
			->sum('total');

		$lastMonthRevenue = Vente::whereMonth('created_at', Carbon::now()->subMonth()->month)
			->whereYear('created_at', Carbon::now()->subMonth()->year)
			->sum('total');

		$revenueGrowth = $lastMonthRevenue > 0
			? round((($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
			: 0;

		// Calcul de la croissance des clients (comparaison mois actuel vs mois précédent)
		$currentMonthClients = Client::whereMonth('created_at', Carbon::now()->month)
			->whereYear('created_at', Carbon::now()->year)
			->count();

		$lastMonthClients = Client::whereMonth('created_at', Carbon::now()->subMonth()->month)
			->whereYear('created_at', Carbon::now()->subMonth()->year)
			->count();

		$clientGrowth = $lastMonthClients > 0
			? round((($currentMonthClients - $lastMonthClients) / $lastMonthClients) * 100, 1)
			: 0;

		$metrics = [
			'total_sales' => $totalSales,
			'total_revenue' => $totalRevenue,
			'total_clients' => $totalClients,
			'total_articles' => $totalArticles,
			'low_stock_alerts' => $lowStockAlerts,
			'revenue_growth' => $revenueGrowth,
			'client_growth' => $clientGrowth,
		];

		// Données pour le graphique (7 derniers jours)
		$chartData = $this->getWeeklyChartData();

		// Activités récentes réelles depuis la base de données
		$recentActivities = $this->getRecentActivities();

		return view('dashboard', compact('metrics', 'recentActivities', 'chartData'));
	}

	/**
	 * Récupérer les données pour le graphique hebdomadaire
	 */
	private function getWeeklyChartData()
	{
		$labels = [];
		$salesData = [];
		$revenueData = [];

		// Générer les 7 derniers jours
		for ($i = 6; $i >= 0; $i--) {
			$date = Carbon::now()->subDays($i);
			$labels[] = $date->format('D d/m');

			// Nombre de ventes
			$salesCount = Vente::whereDate('created_at', $date->format('Y-m-d'))->count();
			$salesData[] = $salesCount;

			// Chiffre d'affaires
			$revenue = Vente::whereDate('created_at', $date->format('Y-m-d'))->sum('total');
			$revenueData[] = (float) $revenue;
		}

		return [
			'labels' => $labels,
			'sales' => $salesData,
			'revenue' => $revenueData,
		];
	}

	/**
	 * Récupérer les activités récentes
	 */
	private function getRecentActivities()
	{
		$activities = collect();

		// Dernières ventes (5 max)
		$recentSales = Vente::with('client')
			->orderBy('created_at', 'desc')
			->limit(5)
			->get();

		foreach ($recentSales as $sale) {
			$activities->push([
				'type' => 'vente',
				'description' => 'Vente #' . $sale->id . ($sale->client ? ' - ' . $sale->client->nom : ''),
				'amount' => $sale->total,
				'time' => $sale->created_at->diffForHumans(),
				'created_at' => $sale->created_at,
			]);
		}

		// Nouveaux clients récents (3 max)
		$recentClients = Client::orderBy('created_at', 'desc')
			->limit(3)
			->get();

		foreach ($recentClients as $client) {
			$activities->push([
				'type' => 'client',
				'description' => 'Nouveau client : ' . $client->nom,
				'amount' => null,
				'time' => $client->created_at->diffForHumans(),
				'created_at' => $client->created_at,
			]);
		}

		// Alertes stock faible (3 max)
		$lowStockThreshold = app_setting('low_stock_threshold', 10);
		$lowStockItems = Lot::with('article')
			->where('quantite_restante', '>', 0)
			->where('quantite_restante', '<=', $lowStockThreshold)
			->orderBy('quantite_restante', 'asc')
			->limit(3)
			->get();

		foreach ($lowStockItems as $lot) {
			$activities->push([
				'type' => 'stock',
				'description' => 'Stock faible : ' . ($lot->article ? $lot->article->designation : 'Article') . ' (' . $lot->quantite_restante . ' unités)',
				'amount' => null,
				'time' => $lot->updated_at->diffForHumans(),
				'created_at' => $lot->updated_at,
			]);
		}

		// Trier par date décroissante et limiter à 10
		return $activities->sortByDesc('created_at')->take(10)->values();
	}
}
