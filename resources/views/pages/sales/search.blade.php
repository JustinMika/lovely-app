@extends('layouts.app')

@section('title', 'Recherche de Factures')

@section('content')
    @php
        $clients = \App\Models\Client::orderBy('nom')->get();
        $sales = [];

        if (request()->hasAny(['client_id', 'status', 'date_from', 'date_to', 'amount_min'])) {
            $query = \App\Models\Vente::with(['client', 'utilisateur']);

            if (request('client_id')) {
                $query->where('client_id', request('client_id'));
            }

            if (request('status')) {
                $query->where('statut', request('status'));
            }

            if (request('date_from')) {
                $query->whereDate('created_at', '>=', request('date_from'));
            }

            if (request('date_to')) {
                $query->whereDate('created_at', '<=', request('date_to'));
            }

            if (request('amount_min')) {
                $query->where('total', '>=', request('amount_min'));
            }

            $sales = $query->latest()->paginate(15);
        }
    @endphp

    <!-- Breadcrumb Start -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">
            Recherche de Factures
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                        href="{{ route('dashboard') }}">Tableau de bord /</a>
                </li>
                <li>
                    <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                        href="{{ route('invoices.index') }}">Factures /</a>
                </li>
                <li class="font-medium text-gray-800 dark:text-white/90">Recherche</li>
            </ol>
        </nav>
    </div>
    <!-- Breadcrumb End -->

    <!-- Content Start -->
    <div class="space-y-6">
        <!-- Search Form -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <h2 class="text-lg font-medium text-gray-800 dark:text-white">
                    Critères de Recherche
                </h2>
            </div>
            <div class="p-4 sm:p-6">
                <form method="GET" action="{{ route('invoices.search') }}">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label for="client_id"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Client
                            </label>
                            <select id="client_id" name="client_id"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <option value="">Tous les clients</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}"
                                        {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->nom }} {{ $client->prenom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Statut
                            </label>
                            <select id="status" name="status"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <option value="">Tous les statuts</option>
                                <option value="payée" {{ request('status') == 'payée' ? 'selected' : '' }}>Payée</option>
                                <option value="partiellement_payée"
                                    {{ request('status') == 'partiellement_payée' ? 'selected' : '' }}>
                                    Partiellement payée
                                </option>
                                <option value="impayée" {{ request('status') == 'impayée' ? 'selected' : '' }}>Impayée
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="date_from"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Date de début
                            </label>
                            <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                        </div>

                        <div>
                            <label for="date_to" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Date de fin
                            </label>
                            <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                        </div>

                        <div>
                            <label for="amount_min"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Montant minimum (€)
                            </label>
                            <input type="number" id="amount_min" name="amount_min" value="{{ request('amount_min') }}"
                                min="0" step="0.01" placeholder="0.00"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                        <a href="{{ route('invoices.search') }}"
                            class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                            Réinitialiser
                        </a>
                        <button type="submit"
                            class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Rechercher
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Search Results -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <h2 class="text-lg font-medium text-gray-800 dark:text-white">
                    Résultats de la Recherche
                    @if ($sales && $sales->count() > 0)
                        <span class="text-sm font-normal text-gray-500">({{ $sales->total() }} résultat(s))</span>
                    @endif
                </h2>
            </div>
            <div class="p-4 sm:p-6">
                @if ($sales && $sales->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="border-b border-gray-200 dark:border-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-400">Date</th>
                                    <th class="px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-400">Client</th>
                                    <th class="px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-400">Total</th>
                                    <th class="px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-400">Statut</th>
                                    <th class="px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-400">Vendeur</th>
                                    <th class="px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-400 text-center">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                @foreach ($sales as $sale)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                            {{ $sale->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                            {{ $sale->client->nom ?? 'N/A' }} {{ $sale->client->prenom ?? '' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                            {{ currency($sale->total) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($sale->statut == 'payée')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                                    Payée
                                                </span>
                                            @elseif($sale->statut == 'partiellement_payée')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300">
                                                    Partielle
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                                    Impayée
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $sale->utilisateur->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('sales.show', $sale->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                    title="Voir">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ route('sales.invoice', $sale->id) }}"
                                                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                                    title="Facture">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $sales->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="mx-auto h-12 w-12 text-gray-400">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Aucun résultat</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Utilisez les critères de recherche ci-dessus pour trouver des factures.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
