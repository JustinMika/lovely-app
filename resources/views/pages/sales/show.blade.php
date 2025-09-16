@extends('layouts.app')

@section('title', 'Détails de la vente #' . $sale->id)

@section('content')
    <div class="space-y-6">
        <!-- Breadcrumb -->
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-brand-600 dark:text-gray-400 dark:hover:text-white">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                        </svg>
                        Tableau de bord
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <a href="{{ route('sales.index') }}"
                            class="ms-1 text-sm font-medium text-gray-700 hover:text-brand-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">Ventes</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Vente
                            #{{ $sale->id }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Vente #{{ $sale->id }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Créée le {{ $sale->created_at->format('d/m/Y à H:i') }} par {{ $sale->utilisateur->name }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('sales.invoice', $sale) }}"
                    class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Facture
                </a>
                <a href="{{ route('sales.edit', $sale) }}"
                    class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Modifier
                </a>
            </div>
        </div>

        <!-- Sale Information -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Client Information -->
            <div class="lg:col-span-2 space-y-6">
                <div
                    class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informations Client</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Nom</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $sale->client->nom }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Prénom</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $sale->client->prenom }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Téléphone</label>
                            <p class="text-sm text-gray-900 dark:text-white">
                                {{ $sale->client->telephone ?? 'Non renseigné' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Email</label>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $sale->client->email ?? 'Non renseigné' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Sale Items -->
                <div
                    class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Articles vendus</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-4 py-3">Article</th>
                                    <th class="px-4 py-3">Lot</th>
                                    <th class="px-4 py-3 text-right">Qté</th>
                                    <th class="px-4 py-3 text-right">Prix unitaire</th>
                                    <th class="px-4 py-3 text-right">Remise</th>
                                    <th class="px-4 py-3 text-right">Sous-total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sale->ligneVentes as $ligne)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                            {{ $ligne->article->designation }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
                                            {{ $ligne->lot->numero_lot }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-gray-900 dark:text-white">
                                            {{ number_format($ligne->quantite, 0, ',', ' ') }}
                                        </td>
                                        <td class="px-4 py-3 text-right text-gray-900 dark:text-white">
                                            {{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA
                                        </td>
                                        <td class="px-4 py-3 text-right text-gray-500 dark:text-gray-400">
                                            {{ number_format($ligne->remise_ligne, 0, ',', ' ') }} FCFA
                                        </td>
                                        <td class="px-4 py-3 text-right font-medium text-gray-900 dark:text-white">
                                            {{ number_format($ligne->sous_total, 0, ',', ' ') }} FCFA
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sale Summary -->
            <div class="space-y-6">
                <div
                    class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Résumé</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Sous-total</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ number_format($sale->ligneVentes->sum('sous_total'), 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                        @if ($sale->remise_totale > 0)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Remise totale</span>
                                <span class="text-sm font-medium text-red-600">
                                    -{{ number_format($sale->remise_totale, 0, ',', ' ') }} FCFA
                                </span>
                            </div>
                        @endif
                        <hr class="border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between">
                            <span class="text-base font-semibold text-gray-900 dark:text-white">Total</span>
                            <span class="text-base font-bold text-gray-900 dark:text-white">
                                {{ number_format($sale->total, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Montant payé</span>
                            <span class="text-sm font-medium text-green-600">
                                {{ number_format($sale->montant_paye, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                        @if ($sale->restant_a_payer > 0)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Restant à payer</span>
                                <span class="text-sm font-medium text-red-600">
                                    {{ number_format($sale->restant_a_payer, 0, ',', ' ') }} FCFA
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sale Status -->
                <div
                    class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Statut</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Paiement</span>
                            @if ($sale->restant_a_payer <= 0)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                    Payé
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                    Impayé
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Bénéfice</span>
                            <span class="text-sm font-medium text-green-600">
                                {{ number_format($sale->benefice, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
