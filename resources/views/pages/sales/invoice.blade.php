@extends('layouts.app')

@section('title', 'Facture #' . $sale->id)

@section('content')
    <div class="space-y-6">
        <!-- Header with Print Button -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 print:hidden">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Facture #{{ $sale->id }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Générée le {{ now()->format('d/m/Y à H:i') }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="window.print()"
                    class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Imprimer
                </button>
                <a href="{{ route('sales.show', $sale) }}"
                    class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                    Retour
                </a>
            </div>
        </div>

        <!-- Invoice Content -->
        <div
            class="bg-white rounded-2xl border border-gray-200 p-8 dark:border-gray-800 dark:bg-white/[0.03] print:border-0 print:shadow-none print:bg-white print:text-black">
            <!-- Company Header -->
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white print:text-black">LOVELY APP</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 print:text-gray-600 mt-1">
                        Système de Gestion Commerciale<br>
                        Kinshasa, République Démocratique du Congo<br>
                        Tél: +243 XXX XXX XXX
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 text-right">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white print:text-black">FACTURE</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 print:text-gray-600 mt-1">
                        N° {{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}<br>
                        Date: {{ $sale->created_at->format('d/m/Y') }}
                    </p>
                </div>
            </div>

            <!-- Client Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white print:text-black mb-2">FACTURÉ À:</h4>
                    <div class="text-sm text-gray-600 dark:text-gray-400 print:text-gray-600">
                        <p class="font-medium text-gray-900 dark:text-white print:text-black">{{ $sale->client->nom }}
                            {{ $sale->client->prenom }}</p>
                        @if ($sale->client->telephone)
                            <p>Tél: {{ $sale->client->telephone }}</p>
                        @endif
                        @if ($sale->client->email)
                            <p>Email: {{ $sale->client->email }}</p>
                        @endif
                        @if ($sale->client->adresse)
                            <p>{{ $sale->client->adresse }}</p>
                        @endif
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white print:text-black mb-2">VENDEUR:</h4>
                    <div class="text-sm text-gray-600 dark:text-gray-400 print:text-gray-600">
                        <p class="font-medium text-gray-900 dark:text-white print:text-black">
                            {{ $sale->utilisateur->name }}</p>
                        <p>Date de vente: {{ $sale->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="mb-8">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b-2 border-gray-200 dark:border-gray-700 print:border-gray-300">
                            <th class="text-left py-3 font-semibold text-gray-900 dark:text-white print:text-black">Article
                            </th>
                            <th class="text-left py-3 font-semibold text-gray-900 dark:text-white print:text-black">Lot</th>
                            <th class="text-right py-3 font-semibold text-gray-900 dark:text-white print:text-black">Qté
                            </th>
                            <th class="text-right py-3 font-semibold text-gray-900 dark:text-white print:text-black">Prix
                                unit.</th>
                            <th class="text-right py-3 font-semibold text-gray-900 dark:text-white print:text-black">Remise
                            </th>
                            <th class="text-right py-3 font-semibold text-gray-900 dark:text-white print:text-black">Total
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sale->ligneVentes as $ligne)
                            <tr class="border-b border-gray-100 dark:border-gray-800 print:border-gray-200">
                                <td class="py-3 text-gray-900 dark:text-white print:text-black">
                                    {{ $ligne->article->designation }}
                                </td>
                                <td class="py-3 text-gray-600 dark:text-gray-400 print:text-gray-600">
                                    {{ $ligne->lot->numero_lot }}
                                </td>
                                <td class="py-3 text-right text-gray-900 dark:text-white print:text-black">
                                    {{ number_format($ligne->quantite, 0, ',', ' ') }}
                                </td>
                                <td class="py-3 text-right text-gray-900 dark:text-white print:text-black">
                                    {{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="py-3 text-right text-gray-600 dark:text-gray-400 print:text-gray-600">
                                    {{ number_format($ligne->remise_ligne, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="py-3 text-right font-medium text-gray-900 dark:text-white print:text-black">
                                    {{ number_format($ligne->sous_total, 0, ',', ' ') }} FCFA
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div class="flex justify-end">
                <div class="w-full max-w-sm">
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400 print:text-gray-600">Sous-total:</span>
                            <span class="text-gray-900 dark:text-white print:text-black font-medium">
                                {{ number_format($sale->ligneVentes->sum('sous_total'), 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                        @if ($sale->remise_totale > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400 print:text-gray-600">Remise totale:</span>
                                <span class="text-red-600 font-medium">
                                    -{{ number_format($sale->remise_totale, 0, ',', ' ') }} FCFA
                                </span>
                            </div>
                        @endif
                        <hr class="border-gray-200 dark:border-gray-700 print:border-gray-300">
                        <div class="flex justify-between text-lg font-bold">
                            <span class="text-gray-900 dark:text-white print:text-black">TOTAL:</span>
                            <span class="text-gray-900 dark:text-white print:text-black">
                                {{ number_format($sale->total, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400 print:text-gray-600">Montant payé:</span>
                            <span class="text-green-600 font-medium">
                                {{ number_format($sale->montant_paye, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                        @if ($sale->restant_a_payer > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400 print:text-gray-600">Restant à payer:</span>
                                <span class="text-red-600 font-medium">
                                    {{ number_format($sale->restant_a_payer, 0, ',', ' ') }} FCFA
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Status -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 print:border-gray-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 print:text-gray-600">Statut du paiement:</p>
                        @if ($sale->restant_a_payer <= 0)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 print:bg-green-100 print:text-green-800">
                                ✓ Payé intégralement
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 print:bg-red-100 print:text-red-800">
                                ⚠ Paiement partiel
                            </span>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 dark:text-gray-400 print:text-gray-500">
                            Bénéfice: {{ number_format($sale->benefice, 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 print:border-gray-300 text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400 print:text-gray-500">
                    Merci pour votre confiance ! Cette facture a été générée automatiquement par Lovely App.
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 print:text-gray-500 mt-1">
                    Pour toute question, veuillez nous contacter.
                </p>
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .print\:block {
                display: block !important;
            }

            .print\:hidden {
                display: none !important;
            }

            .print\:text-black {
                color: black !important;
            }

            .print\:text-gray-600 {
                color: #4b5563 !important;
            }

            .print\:text-gray-500 {
                color: #6b7280 !important;
            }

            .print\:border-0 {
                border: 0 !important;
            }

            .print\:shadow-none {
                box-shadow: none !important;
            }

            .print\:bg-white {
                background-color: white !important;
            }

            .print\:border-gray-200 {
                border-color: #e5e7eb !important;
            }

            .print\:border-gray-300 {
                border-color: #d1d5db !important;
            }

            .print\:bg-green-100 {
                background-color: #dcfce7 !important;
            }

            .print\:text-green-800 {
                color: #166534 !important;
            }

            .print\:bg-red-100 {
                background-color: #fee2e2 !important;
            }

            .print\:text-red-800 {
                color: #991b1b !important;
            }

            #content,
            #content * {
                visibility: visible;
            }

            #content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            @page {
                margin: 1cm;
                size: A4;
            }
        }
    </style>
@endsection
