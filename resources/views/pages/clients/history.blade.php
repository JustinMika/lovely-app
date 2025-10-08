@extends('layouts.app')

@section('title', 'Historique des Clients')

@section('content')
    <!-- Breadcrumb Start -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">
            Historique des Clients
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                        href="{{ route('dashboard') }}">Tableau de bord /</a>
                </li>
                <li>
                    <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                        href="{{ route('clients.index') }}">Clients /</a>
                </li>
                <li class="font-medium text-gray-800 dark:text-white/90">Historique</li>
            </ol>
        </nav>
    </div>
    <!-- Breadcrumb End -->

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 xl:grid-cols-4 mb-6">
        <!-- Total Transactions -->
        <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-6 flex items-center gap-3">
                <div class="h-10 w-10">
                    <svg class="fill-blue-600 dark:fill-blue-400" width="40" height="40" viewBox="0 0 40 40">
                        <path
                            d="M20 5C11.716 5 5 11.716 5 20s6.716 15 15 15 15-6.716 15-15S28.284 5 20 5Zm0 27.5c-6.893 0-12.5-5.607-12.5-12.5S13.107 7.5 20 7.5 32.5 13.107 32.5 20 26.893 32.5 20 32.5Z" />
                        <path d="M20 12.5v7.5l5 5" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Transactions</h3>
                    <span class="block text-theme-xs text-gray-500 dark:text-gray-400">Total</span>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    {{ number_format($stats['total_transactions']) }}
                </h4>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-6 flex items-center gap-3">
                <div class="h-10 w-10">
                    <svg class="fill-green-600 dark:fill-green-400" width="40" height="40" viewBox="0 0 40 40">
                        <path
                            d="M20 5C11.716 5 5 11.716 5 20s6.716 15 15 15 15-6.716 15-15S28.284 5 20 5Zm0 27.5c-6.893 0-12.5-5.607-12.5-12.5S13.107 7.5 20 7.5 32.5 13.107 32.5 20 26.893 32.5 20 32.5Z" />
                        <path
                            d="M20 12.5c-4.142 0-7.5 3.358-7.5 7.5s3.358 7.5 7.5 7.5 7.5-3.358 7.5-7.5-3.358-7.5-7.5-7.5Z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Revenus</h3>
                    <span class="block text-theme-xs text-gray-500 dark:text-gray-400">Total</span>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    {{ currency($stats['total_revenue']) }}
                </h4>
            </div>
        </div>

        <!-- Total Paid -->
        <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-6 flex items-center gap-3">
                <div class="h-10 w-10">
                    <svg class="fill-purple-600 dark:fill-purple-400" width="40" height="40" viewBox="0 0 40 40">
                        <path
                            d="M20 5C11.716 5 5 11.716 5 20s6.716 15 15 15 15-6.716 15-15S28.284 5 20 5Zm0 27.5c-6.893 0-12.5-5.607-12.5-12.5S13.107 7.5 20 7.5 32.5 13.107 32.5 20 26.893 32.5 20 32.5Z" />
                        <path d="M16.25 20l2.5 2.5 5-5" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Encaissé</h3>
                    <span class="block text-theme-xs text-gray-500 dark:text-gray-400">Montant payé</span>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    {{ currency($stats['total_paid']) }}
                </h4>
            </div>
        </div>

        <!-- Active Clients -->
        <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-6 flex items-center gap-3">
                <div class="h-10 w-10">
                    <svg class="fill-orange-600 dark:fill-orange-400" width="40" height="40" viewBox="0 0 40 40">
                        <path
                            d="M20 5C11.716 5 5 11.716 5 20s6.716 15 15 15 15-6.716 15-15S28.284 5 20 5Zm0 27.5c-6.893 0-12.5-5.607-12.5-12.5S13.107 7.5 20 7.5 32.5 13.107 32.5 20 26.893 32.5 20 32.5Z" />
                        <path d="M20 12.5c-2.761 0-5 2.239-5 5s2.239 5 5 5 5-2.239 5-5-2.239-5-5-5Z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">Clients actifs</h3>
                    <span class="block text-theme-xs text-gray-500 dark:text-gray-400">Avec achats</span>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    {{ number_format($stats['active_clients']) }}
                </h4>
            </div>
        </div>
    </div>

    <!-- Filters and Table -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <!-- Filters -->
        <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
            <form method="GET" action="{{ route('clients.history') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Rechercher
                    </label>
                    <input type="text" name="search" id="search" value="{{ $search }}"
                        placeholder="Nom, téléphone, email..."
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-800">
                </div>

                <!-- Date From -->
                <div>
                    <label for="date_from" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Date début
                    </label>
                    <input type="date" name="date_from" id="date_from" value="{{ $dateFrom }}"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-800">
                </div>

                <!-- Date To -->
                <div>
                    <label for="date_to" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Date fin
                    </label>
                    <input type="date" name="date_to" id="date_to" value="{{ $dateTo }}"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-800">
                </div>

                <!-- Submit -->
                <div class="flex items-end">
                    <button type="submit"
                        class="h-11 w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Clients List -->
        <div class="p-6">
            @forelse($clients as $client)
                <div class="mb-6 rounded-lg border border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800/50">
                    <!-- Client Header -->
                    <div class="border-b border-gray-200 bg-white px-4 py-3 dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                                    <svg class="h-5 w-5 fill-blue-600 dark:fill-blue-400" viewBox="0 0 20 20">
                                        <path
                                            d="M10 2a4 4 0 100 8 4 4 0 000-8zM10 12c-4.42 0-8 1.79-8 4v2h16v-2c0-2.21-3.58-4-8-4z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $client->nom }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        @if ($client->telephone)
                                            {{ $client->telephone }}
                                        @endif
                                        @if ($client->email)
                                            · {{ $client->email }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $client->ventes_count }} transaction(s)
                                </p>
                                <p class="text-lg font-bold text-green-600 dark:text-green-400">
                                    {{ currency($client->ventes_sum_total ?? 0) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Transactions Table -->
                    @if ($client->ventes->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300">
                                            Date</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300">
                                            Articles</th>
                                        <th
                                            class="px-4 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300">
                                            Total</th>
                                        <th
                                            class="px-4 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300">
                                            Payé</th>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300">
                                            Statut</th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300">
                                            Vendeur</th>
                                        <th
                                            class="px-4 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($client->ventes as $vente)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                                {{ $vente->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="text-sm text-gray-900 dark:text-white">
                                                    {{ $vente->ligneVentes->count() }} article(s)
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    @foreach ($vente->ligneVentes->take(2) as $ligne)
                                                        {{ $ligne->article->designation ?? 'N/A' }}@if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                    @if ($vente->ligneVentes->count() > 2)
                                                        ...
                                                    @endif
                                                </div>
                                            </td>
                                            <td
                                                class="px-4 py-3 text-right text-sm font-medium text-gray-900 dark:text-white">
                                                {{ currency($vente->total) }}
                                            </td>
                                            <td class="px-4 py-3 text-right text-sm text-green-600 dark:text-green-400">
                                                {{ currency($vente->montant_paye) }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if ($vente->montant_paye >= $vente->total)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                                        Payé
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                                        Partiel
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $vente->utilisateur->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <a href="{{ route('sales.show', $vente->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                    title="Voir détails">
                                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                @empty
                    <div class="text-center py-12">
                        <div class="mx-auto h-12 w-12 text-gray-400">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Aucun historique</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Aucune transaction trouvée pour les critères sélectionnés.
                        </p>
                    </div>
                @endforelse

                <!-- Pagination -->
                @if ($clients->hasPages())
                    <div class="mt-6">
                        {{ $clients->links() }}
                    </div>
                @endif
            </div>
        </div>
    @endsection
