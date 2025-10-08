<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ClientController extends Controller
{
	/**
	 * Display a listing of the clients.
	 */
	public function index(): View
	{
		$clients = Client::latest()->paginate(10);

		$metrics = [
			'total_clients' => Client::count(),
			'new_this_month' => Client::where('created_at', '>=', now()->subMonth())->count(),
			'active_clients' => Client::whereHas('ventes')->count(),
			'total_revenue' => Client::whereHas('ventes')->withSum('ventes', 'total')->get()->sum('ventes_sum_total') ?? 0
		];

		return view('pages.clients.index', compact('clients', 'metrics'));
	}

	/**
	 * Show the form for creating a new client.
	 */
	public function create(): View
	{
		return view('pages.clients.create');
	}

	/**
	 * Store a newly created client in storage.
	 */
	public function store(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'nom' => 'required|string|max:255',
			'email' => 'nullable|email|unique:clients,email',
			'telephone' => 'nullable|string|max:14',
		]);

		Client::create($validated);

		return redirect()->route('clients.index')
			->with('success', 'Client créé avec succès.');
	}

	/**
	 * Display the specified client.
	 */
	public function show(Client $client): View
	{
		$client->load(['ventes.ligneVentes']);

		$clientStats = [
			'total_ventes' => $client->ventes->count(),
			'total_depense' => $client->ventes->sum('total'),
			'derniere_vente' => $client->ventes->first()?->created_at,
			'articles_achetes' => $client->ventes->flatMap->ligneVentes->sum('quantite')
		];

		return view('pages.clients.show', compact('client', 'clientStats'));
	}

	/**
	 * Show the form for editing the specified client.
	 */
	public function edit(Client $client): View
	{
		return view('pages.clients.edit', compact('client'));
	}

	/**
	 * Update the specified client in storage.
	 */
	public function update(Request $request, Client $client): RedirectResponse
	{
		$validated = $request->validate([
			'nom' => 'required|string|max:255',
			'email' => 'nullable|email|unique:clients,email,' . $client->id,
			'telephone' => 'nullable|string|max:14',
		]);

		$client->update($validated);

		return redirect()->route('clients.index')
			->with('success', 'Client mis à jour avec succès.');
	}

	/**
	 * Remove the specified client from storage.
	 */
	public function destroy(Client $client): RedirectResponse
	{
		// Vérifier si le client a des ventes associées
		if ($client->ventes()->count() > 0) {
			return redirect()->route('clients.index')
				->with('error', 'Impossible de supprimer ce client car il a des ventes associées.');
		}

		$client->delete();

		return redirect()->route('clients.index')
			->with('success', 'Client supprimé avec succès.');
	}

	/**
	 * Display clients history with transactions.
	 */
	public function history(Request $request): View
	{
		$search = $request->get('search', '');
		$dateFrom = $request->get('date_from');
		$dateTo = $request->get('date_to');
		$perPage = $request->get('per_page', 15);

		// Récupérer tous les clients avec leurs ventes
		$query = Client::with(['ventes' => function ($query) use ($dateFrom, $dateTo) {
			$query->with(['ligneVentes.article', 'utilisateur'])
				->latest();
			
			if ($dateFrom) {
				$query->whereDate('created_at', '>=', $dateFrom);
			}
			if ($dateTo) {
				$query->whereDate('created_at', '<=', $dateTo);
			}
		}])
		->withCount('ventes')
		->withSum('ventes', 'total')
		->withSum('ventes', 'montant_paye');

		// Appliquer la recherche
		if (!empty($search)) {
			$query->where(function ($q) use ($search) {
				$q->where('nom', 'like', "%{$search}%")
					->orWhere('telephone', 'like', "%{$search}%")
					->orWhere('email', 'like', "%{$search}%");
			});
		}

		// Trier par clients ayant des ventes
		$clients = $query->having('ventes_count', '>', 0)
			->orderByDesc('ventes_sum_total')
			->paginate($perPage);

		// Calculer les statistiques globales
		$stats = [
			'total_transactions' => \App\Models\Vente::when($dateFrom, function ($q) use ($dateFrom) {
					return $q->whereDate('created_at', '>=', $dateFrom);
				})
				->when($dateTo, function ($q) use ($dateTo) {
					return $q->whereDate('created_at', '<=', $dateTo);
				})
				->count(),
			'total_revenue' => \App\Models\Vente::when($dateFrom, function ($q) use ($dateFrom) {
					return $q->whereDate('created_at', '>=', $dateFrom);
				})
				->when($dateTo, function ($q) use ($dateTo) {
					return $q->whereDate('created_at', '<=', $dateTo);
				})
				->sum('total'),
			'total_paid' => \App\Models\Vente::when($dateFrom, function ($q) use ($dateFrom) {
					return $q->whereDate('created_at', '>=', $dateFrom);
				})
				->when($dateTo, function ($q) use ($dateTo) {
					return $q->whereDate('created_at', '<=', $dateTo);
				})
				->sum('montant_paye'),
			'active_clients' => Client::whereHas('ventes', function ($q) use ($dateFrom, $dateTo) {
					if ($dateFrom) {
						$q->whereDate('created_at', '>=', $dateFrom);
					}
					if ($dateTo) {
						$q->whereDate('created_at', '<=', $dateTo);
					}
				})
				->count(),
		];

		return view('pages.clients.history', compact('clients', 'stats', 'search', 'dateFrom', 'dateTo', 'perPage'));
	}
}
