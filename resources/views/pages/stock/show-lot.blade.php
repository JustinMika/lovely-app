@extends('layouts.app')

@section('title', 'Détails du Lot')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb -->
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                    <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L9 5.414V17a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1V5.414l2.293 2.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    Tableau de bord
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <a href="{{ route('lots.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Lots</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ $lot->numero_lot }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Lot {{ $lot->numero_lot }}</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Détails complets du lot de stock</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('lots.edit', $lot->id) }}" class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Modifier
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informations Générales</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Numéro de Lot</label>
                        <p class="text-gray-900 dark:text-white font-medium">{{ $lot->numero_lot }}</p>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Article</label>
                        <p class="text-gray-900 dark:text-white">{{ $lot->article->designation ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Ville</label>
                        <p class="text-gray-900 dark:text-white">{{ $lot->ville->nom ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Fournisseur</label>
                        <p class="text-gray-900 dark:text-white">{{ $lot->approvisionnement->fournisseur ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Date d'Arrivée</label>
                        <p class="text-gray-900 dark:text-white">{{ $lot->date_arrivee->format('d/m/Y') }}</p>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Date de Création</label>
                        <p class="text-gray-900 dark:text-white">{{ $lot->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Stock Information -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informations Stock</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Quantité Initiale</label>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($lot->quantite_initiale) }}</p>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Quantité Restante</label>
                        <p class="text-2xl font-bold @if($lot->quantite_restante <= 10) text-orange-600 @else text-green-600 @endif">
                            {{ number_format($lot->quantite_restante) }}
                        </p>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Quantité Vendue</label>
                        <p class="text-xl font-semibold text-blue-600 dark:text-blue-400">
                            {{ number_format($lot->quantite_initiale - $lot->quantite_restante) }}
                        </p>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pourcentage Vendu</label>
                        @php
                            $percentage = $lot->quantite_initiale > 0 ? (($lot->quantite_initiale - $lot->quantite_restante) / $lot->quantite_initiale) * 100 : 0;
                        @endphp
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ number_format($percentage, 1) }}%</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Information -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informations Tarifaires</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Prix d'Achat Unitaire</label>
                        <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ number_format($lot->prix_achat, 0, ',', ' ') }} FCFA</p>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Prix de Vente Unitaire</label>
                        <p class="text-xl font-semibold text-green-600 dark:text-green-400">{{ number_format($lot->prix_vente, 0, ',', ' ') }} FCFA</p>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Marge Unitaire</label>
                        <p class="text-xl font-semibold text-blue-600 dark:text-blue-400">
                            {{ number_format($lot->prix_vente - $lot->prix_achat, 0, ',', ' ') }} FCFA
                        </p>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pourcentage de Marge</label>
                        @php
                            $marginPercentage = $lot->prix_achat > 0 ? (($lot->prix_vente - $lot->prix_achat) / $lot->prix_achat) * 100 : 0;
                        @endphp
                        <p class="text-xl font-semibold text-purple-600 dark:text-purple-400">
                            {{ number_format($marginPercentage, 1) }}%
                        </p>
                    </div>
                </div>
            </div>

            <!-- Sales History -->
            @if($lot->ligneVentes->count() > 0)
                <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Historique des Ventes</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Date</th>
                                    <th scope="col" class="px-4 py-3">Quantité</th>
                                    <th scope="col" class="px-4 py-3">Prix Unitaire</th>
                                    <th scope="col" class="px-4 py-3">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lot->ligneVentes as $ligne)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-4 py-3">
                                            {{ $ligne->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $ligne->quantite }}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA
                                        </td>
                                        <td class="px-4 py-3 font-medium">
                                            {{ number_format($ligne->quantite * $ligne->prix_unitaire, 0, ',', ' ') }} FCFA
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Statut du Lot</h3>
                
                <div class="space-y-4">
                    @if($lot->quantite_restante == 0)
                        <div class="flex items-center gap-3">
                            <div class="rounded-lg bg-red-50 p-2 dark:bg-red-500/10">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-red-600 dark:text-red-400">Épuisé</p>
                                <p class="text-sm text-gray-500">Stock complètement vendu</p>
                            </div>
                        </div>
                    @elseif($lot->quantite_restante <= 10)
                        <div class="flex items-center gap-3">
                            <div class="rounded-lg bg-orange-50 p-2 dark:bg-orange-500/10">
                                <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-orange-600 dark:text-orange-400">Stock Faible</p>
                                <p class="text-sm text-gray-500">Réapprovisionnement recommandé</p>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-3">
                            <div class="rounded-lg bg-green-50 p-2 dark:bg-green-500/10">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-green-600 dark:text-green-400">Disponible</p>
                                <p class="text-sm text-gray-500">Stock suffisant</p>
                            </div>
                        </div>
                    @endif

                    @if($lot->date_arrivee < now()->subDays(365))
                        <div class="flex items-center gap-3">
                            <div class="rounded-lg bg-red-50 p-2 dark:bg-red-500/10">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-red-600 dark:text-red-400">Lot Ancien</p>
                                <p class="text-sm text-gray-500">Plus d'un an en stock</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Résumé Financier</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Investissement Total</span>
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ number_format($lot->quantite_initiale * $lot->prix_achat, 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Valeur Stock Restant</span>
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ number_format($lot->quantite_restante * $lot->prix_vente, 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">CA Potentiel Total</span>
                        <span class="font-medium text-green-600 dark:text-green-400">
                            {{ number_format($lot->quantite_initiale * $lot->prix_vente, 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-900 dark:text-white">Marge Potentielle</span>
                        <span class="font-semibold text-blue-600 dark:text-blue-400">
                            {{ number_format(($lot->prix_vente - $lot->prix_achat) * $lot->quantite_initiale, 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
