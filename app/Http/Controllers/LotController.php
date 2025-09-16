<?php

namespace App\Http\Controllers;

use App\Models\Lot;
use App\Models\Article;
use App\Models\Ville;
use App\Models\Approvisionnement;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class LotController extends Controller
{
	/**
	 * Display a listing of the lots.
	 */
	public function index(): View
	{
		$lots = Lot::with(['article', 'ville', 'approvisionnement'])
			->orderBy('created_at', 'desc')
			->paginate(15);

		$totalLots = Lot::count();
		$totalValue = Lot::sum(DB::raw('quantite_restante * prix_vente'));
		$expiredLots = Lot::where('date_arrivee', '<', now()->subDays(365))->count();
		$lowStockLots = Lot::where('quantite_restante', '<=', 10)->count();

		$metrics = [
			'total_lots' => $totalLots,
			'total_value' => $totalValue,
			'expired_lots' => $expiredLots,
			'low_stock_lots' => $lowStockLots
		];

		return view('pages.stock.lots', compact('lots', 'metrics'));
	}

	/**
	 * Show the form for creating a new lot.
	 */
	public function create(): View
	{
		$articles = Article::where('actif', true)->orderBy('designation')->get();
		$villes = Ville::orderBy('nom')->get();
		$approvisionnements = Approvisionnement::orderBy('date', 'desc')->get();

		return view('pages.stock.create-lot', compact('articles', 'villes', 'approvisionnements'));
	}

	/**
	 * Store a newly created lot in storage.
	 */
	public function store(Request $request)
	{
		$validated = $request->validate([
			'approvisionnement_id' => 'required|exists:approvisionnements,id',
			'article_id' => 'required|exists:articles,id',
			'ville_id' => 'required|exists:villes,id',
			'numero_lot' => 'required|string|max:255|unique:lots,numero_lot',
			'quantite_initiale' => 'required|integer|min:1',
			'seuil_alerte' => 'required|integer|min:0',
			'prix_achat' => 'required|numeric|min:0',
			'prix_vente' => 'required|numeric|min:0',
			'date_arrivee' => 'required|date',
			'date_expiration' => 'nullable|date|after:date_arrivee',
		]);

		$validated['quantite_restante'] = $validated['quantite_initiale'];

		Lot::create($validated);

		return redirect()->route('lots.index')
			->with('success', 'Lot créé avec succès.');
	}

	/**
	 * Display the specified lot.
	 */
	public function show(string $id): View
	{
		$lot = Lot::with(['article', 'ville', 'approvisionnement', 'ligneVentes'])->findOrFail($id);

		return view('pages.stock.show-lot', compact('lot'));
	}

	/**
	 * Show the form for editing the specified lot.
	 */
	public function edit(string $id): View
	{
		$lot = Lot::findOrFail($id);
		$articles = Article::where('actif', true)->orderBy('designation')->get();
		$villes = Ville::orderBy('nom')->get();
		$approvisionnements = Approvisionnement::orderBy('date', 'desc')->get();

		return view('pages.stock.edit-lot', compact('lot', 'articles', 'villes', 'approvisionnements'));
	}

	/**
	 * Update the specified lot in storage.
	 */
	public function update(Request $request, string $id)
	{
		$lot = Lot::findOrFail($id);

		$validated = $request->validate([
			'approvisionnement_id' => 'required|exists:approvisionnements,id',
			'article_id' => 'required|exists:articles,id',
			'ville_id' => 'required|exists:villes,id',
			'numero_lot' => 'required|string|max:255|unique:lots,numero_lot,' . $id,
			'quantite_initiale' => 'required|integer|min:1',
			'seuil_alerte' => 'required|integer|min:0',
			'prix_achat' => 'required|numeric|min:0',
			'prix_vente' => 'required|numeric|min:0',
			'date_arrivee' => 'required|date',
			'date_expiration' => 'nullable|date|after:date_arrivee',
		]);

		$lot->update($validated);

		return redirect()->route('lots.index')
			->with('success', 'Lot mis à jour avec succès.');
	}

	/**
	 * Remove the specified lot from storage.
	 */
	public function destroy(string $id)
	{
		$lot = Lot::findOrFail($id);

		// Vérifier s'il y a des ventes associées
		if ($lot->ligneVentes()->count() > 0) {
			return redirect()->route('lots.index')
				->with('error', 'Impossible de supprimer ce lot car il a des ventes associées.');
		}

		$lot->delete();

		return redirect()->route('lots.index')
			->with('success', 'Lot supprimé avec succès.');
	}
}
