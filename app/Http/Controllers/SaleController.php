<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\LigneVente;
use App\Models\Client;
use App\Models\Article;
use App\Models\Lot;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
	/**
	 * Display a listing of the sales.
	 */
	public function index(): View
	{
		// The Livewire SalesManager component handles all the data and display logic
		return view('pages.sales.index');
	}

	/**
	 * Show the form for creating a new sale.
	 */
	public function create(): View
	{
		// The Livewire SaleForm component handles all the form logic
		return view('pages.sales.create');
	}

	/**
	 * Store a newly created sale in storage.
	 */
	public function store(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'client_id' => 'required|exists:clients,id',
			'items' => 'required|array|min:1',
			'items.*.lot_id' => 'required|exists:lots,id',
			'items.*.quantite' => 'required|integer|min:1',
			'items.*.prix_unitaire' => 'required|numeric|min:0',
			'items.*.remise_ligne' => 'nullable|numeric|min:0',
			'remise_totale' => 'nullable|numeric|min:0',
			'montant_paye' => 'nullable|numeric|min:0',
		]);

		DB::transaction(function () use ($validated) {
			// Vérifier la disponibilité du stock
			foreach ($validated['items'] as $item) {
				$lot = Lot::findOrFail($item['lot_id']);
				if ($lot->quantite_restante < $item['quantite']) {
					throw new \Exception("Stock insuffisant pour le lot {$lot->numero_lot}. Disponible: {$lot->quantite_restante}, Demandé: {$item['quantite']}");
				}
			}

			// Calculer le total
			$total = 0;
			foreach ($validated['items'] as $item) {
				$sousTotal = ($item['prix_unitaire'] * $item['quantite']) - ($item['remise_ligne'] ?? 0);
				$total += $sousTotal;
			}

			// Créer la vente
			$vente = Vente::create([
				'utilisateur_id' => Auth::id(),
				'client_id' => $validated['client_id'],
				'total' => $total,
				'remise_totale' => $validated['remise_totale'] ?? 0,
				'montant_paye' => $validated['montant_paye'] ?? 0,
			]);

			// Créer les lignes de vente et réduire le stock
			foreach ($validated['items'] as $item) {
				$lot = Lot::findOrFail($item['lot_id']);

				LigneVente::create([
					'vente_id' => $vente->id,
					'article_id' => $lot->article_id,
					'lot_id' => $lot->id,
					'quantite' => $item['quantite'],
					'prix_unitaire' => $item['prix_unitaire'],
					'prix_achat' => $lot->prix_achat,
					'remise_ligne' => $item['remise_ligne'] ?? 0,
				]);

				// Réduire le stock
				$lot->reduireStock($item['quantite']);
			}
		});

		return redirect()->route('sales.index')
			->with('success', 'Vente créée avec succès.');
	}

	/**
	 * Display the specified sale.
	 */
	public function show(Vente $sale): View
	{
		$sale->load(['client', 'utilisateur', 'ligneVentes.article', 'ligneVentes.lot']);

		return view('pages.sales.show', compact('sale'));
	}

	/**
	 * Show the form for editing the specified sale.
	 */
	public function edit(Vente $sale): View
	{
		// The Livewire SaleForm component handles all the form logic
		return view('pages.sales.edit', compact('sale'));
	}

	/**
	 * Update the specified sale in storage.
	 */
	public function update(Request $request, Vente $sale): RedirectResponse
	{
		$validated = $request->validate([
			'client_id' => 'required|exists:clients,id',
			'items' => 'required|array|min:1',
			'items.*.lot_id' => 'required|exists:lots,id',
			'items.*.quantite' => 'required|integer|min:1',
			'items.*.prix_unitaire' => 'required|numeric|min:0',
			'items.*.remise_ligne' => 'nullable|numeric|min:0',
			'remise_totale' => 'nullable|numeric|min:0',
			'montant_paye' => 'nullable|numeric|min:0',
		]);

		DB::transaction(function () use ($validated, $sale) {
			// Restaurer le stock des anciennes lignes
			foreach ($sale->ligneVentes as $ligne) {
				$lot = $ligne->lot;
				$lot->quantite_restante += $ligne->quantite;
				$lot->save();
			}

			// Supprimer les anciennes lignes
			$sale->ligneVentes()->delete();

			// Vérifier la disponibilité du stock pour les nouvelles lignes
			foreach ($validated['items'] as $item) {
				$lot = Lot::findOrFail($item['lot_id']);
				if ($lot->quantite_restante < $item['quantite']) {
					throw new \Exception("Stock insuffisant pour le lot {$lot->numero_lot}. Disponible: {$lot->quantite_restante}, Demandé: {$item['quantite']}");
				}
			}

			// Calculer le nouveau total
			$total = 0;
			foreach ($validated['items'] as $item) {
				$sousTotal = ($item['prix_unitaire'] * $item['quantite']) - ($item['remise_ligne'] ?? 0);
				$total += $sousTotal;
			}

			// Mettre à jour la vente
			$sale->update([
				'client_id' => $validated['client_id'],
				'total' => $total,
				'remise_totale' => $validated['remise_totale'] ?? 0,
				'montant_paye' => $validated['montant_paye'] ?? 0,
			]);

			// Créer les nouvelles lignes de vente et réduire le stock
			foreach ($validated['items'] as $item) {
				$lot = Lot::findOrFail($item['lot_id']);

				LigneVente::create([
					'vente_id' => $sale->id,
					'article_id' => $lot->article_id,
					'lot_id' => $lot->id,
					'quantite' => $item['quantite'],
					'prix_unitaire' => $item['prix_unitaire'],
					'prix_achat' => $lot->prix_achat,
					'remise_ligne' => $item['remise_ligne'] ?? 0,
				]);

				// Réduire le stock
				$lot->reduireStock($item['quantite']);
			}
		});

		return redirect()->route('sales.index')
			->with('success', 'Vente mise à jour avec succès.');
	}

	/**
	 * Remove the specified sale from storage.
	 */
	public function destroy(Vente $sale): RedirectResponse
	{
		DB::transaction(function () use ($sale) {
			// Restaurer le stock pour chaque ligne de vente
			foreach ($sale->ligneVentes as $ligne) {
				$lot = $ligne->lot;
				$lot->quantite_restante += $ligne->quantite;
				$lot->save();
			}

			// Supprimer les lignes de vente puis la vente
			$sale->ligneVentes()->delete();
			$sale->delete();
		});

		return redirect()->route('sales.index')
			->with('success', 'Vente supprimée avec succès.');
	}

	/**
	 * Generate invoice for the specified sale.
	 */
	public function invoice(Vente $sale): View
	{
		$sale->load(['client', 'utilisateur', 'ligneVentes.article', 'ligneVentes.lot']);

		return view('pages.sales.invoice', compact('sale'));
	}
}
