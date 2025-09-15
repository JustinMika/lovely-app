@extends('layouts.app')

@section('title', 'Mouvements de Stock')

@section('content')
    <div class="space-y-6">
        <!-- Breadcrumb -->
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L9 5.414V17a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1V5.414l2.293 2.293a1 1 0 0 0 1.414-1.414Z" />
                        </svg>
                        Tableau de bord
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <a href="{{ route('stock.index') }}"
                            class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Stock</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Mouvements</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Mouvements de Stock</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Historique des entrées et sorties de stock</p>
            </div>
        </div>

        <!-- Movements Table -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="p-4 sm:p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Date</th>
                                <th scope="col" class="px-6 py-3">N° Lot</th>
                                <th scope="col" class="px-6 py-3">Article</th>
                                <th scope="col" class="px-6 py-3">Ville</th>
                                <th scope="col" class="px-6 py-3">Fournisseur</th>
                                <th scope="col" class="px-6 py-3">Quantité</th>
                                <th scope="col" class="px-6 py-3">Prix Achat</th>
                                <th scope="col" class="px-6 py-3">Prix Vente</th>
                                <th scope="col" class="px-6 py-3">Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movements as $lot)
                                <tr
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-4">
                                        {{ $lot->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ $lot->numero_lot }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $lot->article->designation ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $lot->ville->nom ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $lot->approvisionnement->fournisseur ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm">Initial: {{ $lot->quantite_initiale }}</span>
                                            <span class="text-xs text-gray-500">Restant:
                                                {{ $lot->quantite_restante }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ number_format($lot->prix_achat, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ number_format($lot->prix_vente, 0, ',', ' ') }} FCFA
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">
                                            Entrée
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Aucun
                                                mouvement trouvé</h3>
                                            <p class="text-gray-500 dark:text-gray-400">Les mouvements de stock apparaîtront
                                                ici</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($movements->hasPages())
                    <div class="mt-6">
                        {{ $movements->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
