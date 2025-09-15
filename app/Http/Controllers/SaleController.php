<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class SaleController extends Controller
{
	/**
	 * Display a listing of the sales.
	 */
	public function index(): View
	{
		// TODO: Implement sale retrieval logic
		$sales = collect(); // Placeholder for sales

		$metrics = [
			'total_sales' => 3456,
			'revenue_today' => 12450,
			'revenue_this_month' => 245680,
			'average_order' => 89.50
		];

		return view('pages.sales.index', compact('sales', 'metrics'));
	}

	/**
	 * Show the form for creating a new sale.
	 */
	public function create(): View
	{
		// Get clients and articles for dropdowns
		$clients = \App\Models\Client::orderBy('nom')->get();
		$articles = \App\Models\Article::where('actif', true)->orderBy('designation')->get();

		return view('pages.sales.create', compact('clients', 'articles'));
	}

	/**
	 * Store a newly created sale in storage.
	 */
	public function store(Request $request)
	{
		$validated = $request->validate([
			'client_id' => 'required|exists:clients,id',
			'sale_date' => 'required|date',
			'items' => 'required|array|min:1',
			'items.*.article_id' => 'required|exists:articles,id',
			'items.*.quantity' => 'required|integer|min:1',
			'items.*.price' => 'required|numeric|min:0',
			'discount' => 'nullable|numeric|min:0|max:100',
			'tax_rate' => 'nullable|numeric|min:0|max:100',
		]);

		// TODO: Implement sale creation logic
		// $sale = Sale::create($validated);
		// foreach ($validated['items'] as $item) {
		//     SaleItem::create([...]);
		// }

		return redirect()->route('sales.index')
			->with('success', 'Vente créée avec succès.');
	}

	/**
	 * Display the specified sale.
	 */
	public function show(string $id): View
	{
		// TODO: Implement sale retrieval logic
		// $sale = Sale::with(['client', 'items.article'])->findOrFail($id);

		return view('pages.sales.show', compact('id'));
	}

	/**
	 * Show the form for editing the specified sale.
	 */
	public function edit(string $id): View
	{
		// TODO: Implement sale retrieval logic
		// $sale = Sale::with(['client', 'items.article'])->findOrFail($id);
		$clients = \App\Models\Client::orderBy('nom')->get();
		$articles = \App\Models\Article::where('actif', true)->orderBy('designation')->get();

		return view('pages.sales.edit', compact('id', 'clients', 'articles'));
	}

	/**
	 * Update the specified sale in storage.
	 */
	public function update(Request $request, string $id)
	{
		$validated = $request->validate([
			'client_id' => 'required|exists:clients,id',
			'sale_date' => 'required|date',
			'items' => 'required|array|min:1',
			'items.*.article_id' => 'required|exists:articles,id',
			'items.*.quantity' => 'required|integer|min:1',
			'items.*.price' => 'required|numeric|min:0',
			'discount' => 'nullable|numeric|min:0|max:100',
			'tax_rate' => 'nullable|numeric|min:0|max:100',
		]);

		// TODO: Implement sale update logic
		// $sale = Sale::findOrFail($id);
		// $sale->update($validated);

		return redirect()->route('sales.index')
			->with('success', 'Vente mise à jour avec succès.');
	}

	/**
	 * Remove the specified sale from storage.
	 */
	public function destroy(string $id)
	{
		// TODO: Implement sale deletion logic
		// $sale = Sale::findOrFail($id);
		// $sale->delete();

		return redirect()->route('sales.index')
			->with('success', 'Vente supprimée avec succès.');
	}

	/**
	 * Generate invoice for the specified sale.
	 */
	public function invoice(string $id): View
	{
		// TODO: Implement invoice generation logic
		// $sale = Sale::with(['client', 'items.article'])->findOrFail($id);

		return view('pages.sales.invoice', compact('id'));
	}
}
