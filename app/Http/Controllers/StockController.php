<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
	/**
	 * Display stock overview.
	 */
	public function view(): View
	{
		$totalArticles = Article::where('actif', true)->count();
		$totalLots = Lot::count();
		$lowStockCount = Lot::where('quantite_restante', '<=', DB::raw('seuil_alerte'))
			->where('quantite_restante', '>', 0)
			->count();
		$totalStockValue = Lot::sum(DB::raw('quantite_restante * prix_vente'));
		$todayMovements = Lot::whereDate('created_at', today())->count();

		// Get recent lots for the overview table
		$recentLots = Lot::with(['article', 'ville'])
			->where('quantite_restante', '>', 0)
			->orderBy('created_at', 'desc')
			->limit(10)
			->get();

		return view('pages.stock.view', compact(
			'totalArticles',
			'totalLots',
			'lowStockCount',
			'totalStockValue',
			'todayMovements',
			'recentLots'
		));
	}

	/**
	 * Display stock alerts.
	 */
	public function alerts(): View
	{
		$lowStockLots = Lot::with(['article', 'ville'])
			->where('quantite_restante', '<=', 10)
			->where('quantite_restante', '>', 0)
			->orderBy('quantite_restante', 'asc')
			->get();

		$expiredLots = Lot::with(['article', 'ville'])
			->where('date_arrivee', '<', now()->subDays(365)) // Articles de plus d'un an
			->where('quantite_restante', '>', 0)
			->get();

		$alerts = [
			'low_stock' => $lowStockLots,
			'expired' => $expiredLots
		];

		return view('pages.stock.alerts', compact('alerts'));
	}

	/**
	 * Display stock movements.
	 */
	public function movements(): View
	{
		$recentLots = Lot::with(['article', 'ville', 'approvisionnement'])
			->orderBy('created_at', 'desc')
			->paginate(20);

		$movements = $recentLots;

		return view('pages.stock.movements', compact('movements'));
	}

	/**
	 * Update stock quantity for a lot.
	 */
	public function updateQuantity(Request $request, string $lotId)
	{
		$validated = $request->validate([
			'quantity' => 'required|integer|min:0',
			'movement_type' => 'required|in:in,out,adjustment',
			'reason' => 'nullable|string|max:255',
		]);

		$lot = Lot::findOrFail($lotId);

		switch ($validated['movement_type']) {
			case 'in':
				$lot->quantite_restante += $validated['quantity'];
				break;
			case 'out':
				if ($lot->quantite_restante >= $validated['quantity']) {
					$lot->quantite_restante -= $validated['quantity'];
				} else {
					return redirect()->back()
						->with('error', 'Quantité insuffisante en stock.');
				}
				break;
			case 'adjustment':
				$lot->quantite_restante = $validated['quantity'];
				break;
		}

		$lot->save();

		return redirect()->back()
			->with('success', 'Stock mis à jour avec succès.');
	}
}
