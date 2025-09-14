<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class LotController extends Controller
{
	/**
	 * Display a listing of the lots.
	 */
	public function index(): View
	{
		// TODO: Implement lot retrieval logic
		$lots = collect(); // Placeholder for lots

		$metrics = [
			'total_lots' => 456,
			'total_value' => 125000,
			'expired_lots' => 12,
			'low_stock_lots' => 34
		];

		return view('pages.stock.lots', compact('lots', 'metrics'));
	}

	/**
	 * Show the form for creating a new lot.
	 */
	public function create(): View
	{
		// TODO: Get articles and suppliers for dropdowns
		$articles = collect(); // Placeholder
		$villes = collect(); // Placeholder

		return view('pages.stock.create-lot', compact('articles', 'villes'));
	}

	/**
	 * Store a newly created lot in storage.
	 */
	public function store(Request $request)
	{
		// TODO: Implement lot creation logic
		$validated = $request->validate([
			'approvisionnement_id' => 'required|exists:approvisionnements,id',
			'article_id' => 'required|exists:articles,id',
			'ville_id' => 'required|exists:villes,id',
			'numero_lot' => 'required|string|max:255',
			'quantite_initiale' => 'required|integer|min:1',
			'prix_achat' => 'required|numeric|min:0',
			'prix_vente' => 'required|numeric|min:0',
			'date_arrivee' => 'required|date',
		]);

		// Lot::create($validated);

		return redirect()->route('lots.index')
			->with('success', 'Lot créé avec succès.');
	}

	/**
	 * Display the specified lot.
	 */
	public function show(string $id): View
	{
		// TODO: Implement lot retrieval logic
		// $lot = Lot::with(['article', 'ville', 'approvisionnement'])->findOrFail($id);

		return view('pages.stock.show-lot', compact('id'));
	}

	/**
	 * Show the form for editing the specified lot.
	 */
	public function edit(string $id): View
	{
		// TODO: Implement lot retrieval logic
		// $lot = Lot::findOrFail($id);
		$articles = collect(); // Placeholder
		$villes = collect(); // Placeholder

		return view('pages.stock.edit-lot', compact('id', 'articles', 'villes'));
	}

	/**
	 * Update the specified lot in storage.
	 */
	public function update(Request $request, string $id)
	{
		// TODO: Implement lot update logic
		$validated = $request->validate([
			'numero_lot' => 'required|string|max:255',
			'quantite_initiale' => 'required|integer|min:1',
			'prix_achat' => 'required|numeric|min:0',
			'prix_vente' => 'required|numeric|min:0',
			'date_arrivee' => 'required|date',
		]);

		// $lot = Lot::findOrFail($id);
		// $lot->update($validated);

		return redirect()->route('lots.index')
			->with('success', 'Lot mis à jour avec succès.');
	}

	/**
	 * Remove the specified lot from storage.
	 */
	public function destroy(string $id)
	{
		// TODO: Implement lot deletion logic
		// $lot = Lot::findOrFail($id);
		// $lot->delete();

		return redirect()->route('lots.index')
			->with('success', 'Lot supprimé avec succès.');
	}
}
