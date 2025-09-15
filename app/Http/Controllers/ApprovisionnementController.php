<?php

namespace App\Http\Controllers;

use App\Models\Approvisionnement;
use App\Models\Lot;
use App\Models\Article;
use App\Models\Ville;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApprovisionnementController extends Controller
{
	/**
	 * Display a listing of approvisionnements.
	 */
	public function index(): View
	{
		$approvisionnements = Approvisionnement::with(['user', 'lots.article'])
			->orderBy('created_at', 'desc')
			->paginate(15);

		$stats = [
			'total' => Approvisionnement::count(),
			'this_month' => Approvisionnement::whereMonth('created_at', now()->month)->count(),
			'total_value' => Lot::sum(DB::raw('quantite_initiale * prix_achat')),
			'pending_lots' => Lot::whereNull('date_arrivee')->count()
		];

		return view('pages.approvisionnements.index', compact('approvisionnements', 'stats'));
	}

	/**
	 * Show the form for creating a new approvisionnement.
	 */
	public function create(): View
	{
		$articles = Article::where('actif', true)->get();
		$villes = Ville::all();

		return view('pages.approvisionnements.create', compact('articles', 'villes'));
	}

	/**
	 * Store a newly created approvisionnement in storage.
	 */
	public function store(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'date_approvisionnement' => 'required|date',
			'fournisseur' => 'required|string|max:255',
			'reference_facture' => 'nullable|string|max:255',
			'notes' => 'nullable|string',
			'lots' => 'required|array|min:1',
			'lots.*.article_id' => 'required|exists:articles,id',
			'lots.*.ville_id' => 'required|exists:villes,id',
			'lots.*.numero_lot' => 'required|string|max:255|unique:lots,numero_lot',
			'lots.*.quantite_initiale' => 'required|integer|min:1',
			'lots.*.unite' => 'required|string|max:50',
			'lots.*.prix_achat' => 'required|numeric|min:0',
			'lots.*.prix_vente' => 'required|numeric|min:0',
			'lots.*.date_arrivee' => 'nullable|date',
			'lots.*.date_expiration' => 'nullable|date|after:date_arrivee',
			'lots.*.seuil_alerte' => 'required|integer|min:0',
		]);

		DB::transaction(function () use ($validated) {
			// Créer l'approvisionnement
			$approvisionnement = Approvisionnement::create([
				'user_id' => Auth::id(),
				'date_approvisionnement' => $validated['date_approvisionnement'],
				'fournisseur' => $validated['fournisseur'],
				'reference_facture' => $validated['reference_facture'],
				'notes' => $validated['notes'],
			]);

			// Créer les lots associés
			foreach ($validated['lots'] as $lotData) {
				$lot = new Lot($lotData);
				$lot->quantite_restante = $lotData['quantite_initiale'];
				$lot->approvisionnement_id = $approvisionnement->id;
				$lot->save();
			}
		});

		return redirect()->route('approvisionnements.index')
			->with('success', 'Approvisionnement créé avec succès.');
	}

	/**
	 * Display the specified approvisionnement.
	 */
	public function show(Approvisionnement $approvisionnement): View
	{
		$approvisionnement->load(['user', 'lots.article', 'lots.ville', 'lots.ligneVentes']);

		$totalValue = $approvisionnement->lots->sum(function ($lot) {
			return $lot->quantite_initiale * $lot->prix_achat;
		});

		$totalSold = $approvisionnement->lots->sum(function ($lot) {
			return $lot->quantite_initiale - $lot->quantite_restante;
		});

		return view('pages.approvisionnements.show', compact('approvisionnement', 'totalValue', 'totalSold'));
	}

	/**
	 * Show the form for editing the specified approvisionnement.
	 */
	public function edit(Approvisionnement $approvisionnement): View
	{
		$approvisionnement->load(['lots.article', 'lots.ville']);
		$articles = Article::where('actif', true)->get();
		$villes = Ville::all();

		return view('pages.approvisionnements.edit', compact('approvisionnement', 'articles', 'villes'));
	}

	/**
	 * Update the specified approvisionnement in storage.
	 */
	public function update(Request $request, Approvisionnement $approvisionnement): RedirectResponse
	{
		$validated = $request->validate([
			'date_approvisionnement' => 'required|date',
			'fournisseur' => 'required|string|max:255',
			'reference_facture' => 'nullable|string|max:255',
			'notes' => 'nullable|string',
		]);

		$approvisionnement->update($validated);

		return redirect()->route('approvisionnements.show', $approvisionnement)
			->with('success', 'Approvisionnement mis à jour avec succès.');
	}

	/**
	 * Remove the specified approvisionnement from storage.
	 */
	public function destroy(Approvisionnement $approvisionnement): RedirectResponse
	{
		// Vérifier s'il y a des lots avec des ventes
		$hasActiveLots = $approvisionnement->lots()
			->whereHas('ligneVentes')
			->exists();

		if ($hasActiveLots) {
			return redirect()->back()
				->with('error', 'Impossible de supprimer cet approvisionnement car il contient des lots avec des ventes.');
		}

		DB::transaction(function () use ($approvisionnement) {
			// Supprimer les lots associés
			$approvisionnement->lots()->delete();
			// Supprimer l'approvisionnement
			$approvisionnement->delete();
		});

		return redirect()->route('approvisionnements.index')
			->with('success', 'Approvisionnement supprimé avec succès.');
	}

	/**
	 * Display approvisionnement statistics.
	 */
	public function stats(): View
	{
		$monthlyStats = Approvisionnement::selectRaw('MONTH(created_at) as month, COUNT(*) as count, SUM((SELECT SUM(quantite_initiale * prix_achat) FROM lots WHERE lots.approvisionnement_id = approvisionnements.id)) as value')
			->whereYear('created_at', now()->year)
			->groupBy('month')
			->orderBy('month')
			->get();

		$topSuppliers = Approvisionnement::selectRaw('fournisseur, COUNT(*) as count, SUM((SELECT SUM(quantite_initiale * prix_achat) FROM lots WHERE lots.approvisionnement_id = approvisionnements.id)) as total_value')
			->groupBy('fournisseur')
			->orderByDesc('total_value')
			->limit(10)
			->get();

		$recentApprovisionnements = Approvisionnement::with(['user', 'lots'])
			->orderBy('created_at', 'desc')
			->limit(5)
			->get();

		return view('pages.approvisionnements.stats', compact('monthlyStats', 'topSuppliers', 'recentApprovisionnements'));
	}
}
