<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
	/**
	 * Display the dashboard overview.
	 */
	public function index(): View
	{
		// TODO: Implement dashboard metrics logic
		$metrics = [
			'total_sales' => 3456,
			'total_revenue' => 245680,
			'total_clients' => 856,
			'total_articles' => 1245,
			'low_stock_alerts' => 23,
			'new_orders_today' => 45,
			'revenue_growth' => 12.5, // percentage
			'client_growth' => 8.3, // percentage
		];

		// Recent activities placeholder
		$recentActivities = collect([
			[
				'type' => 'sale',
				'description' => 'Nouvelle vente #3456',
				'amount' => 125.50,
				'time' => '2 minutes ago'
			],
			[
				'type' => 'client',
				'description' => 'Nouveau client ajouté',
				'client_name' => 'Jean Dupont',
				'time' => '15 minutes ago'
			],
			[
				'type' => 'stock',
				'description' => 'Stock faible: Article XYZ',
				'quantity' => 5,
				'time' => '1 hour ago'
			]
		]);

		return view('dashboard', compact('metrics', 'recentActivities'));
	}
}
