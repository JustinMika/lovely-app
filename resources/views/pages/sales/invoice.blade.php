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
                <a href="{{ route('sales.invoice.pdf', $sale->id) }}"
                    class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                    Télécharger PDF
                </a>
                <button onclick="window.print()"
                    class="bg-gray-600 shadow-theme-xs hover:bg-gray-700 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Imprimer
                </button>
                <a href="{{ url('/sales/' . $sale->id) }}"
                    class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                    Retour
                </a>
            </div>
        </div>

        <!-- Invoice Content -->
        <div id="invoice-content"
            class="bg-white rounded-2xl border border-gray-200 p-8 dark:border-gray-800 dark:bg-white/[0.03] print:border-0 print:shadow-none print:bg-white print:text-black">
            <!-- Company Header -->
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-8">
                <div>
                    @if ($appSettings->app_logo && file_exists(public_path('storage/' . $appSettings->app_logo)))
                        <img src="{{ asset('storage/' . $appSettings->app_logo) }}" alt="Logo" class="h-16 mb-3">
                    @endif
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white print:text-black">
                        {{ $appSettings->company_name ?? $appSettings->app_name }}
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 print:text-gray-600 mt-1">
                        {{ $appSettings->app_description ?? 'Système de Gestion Commerciale' }}<br>
                        @if ($appSettings->company_address)
                            {{ $appSettings->company_address }}<br>
                        @endif
                        @if ($appSettings->company_phone)
                            Tél: {{ $appSettings->company_phone }}<br>
                        @endif
                        @if ($appSettings->company_email)
                            Email: {{ $appSettings->company_email }}
                        @endif
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 text-right">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white print:text-black">FACTURE</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 print:text-gray-600 mt-1">
                        N° {{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}<br>
                        Date: {{ date('d/m/Y à H:i', strtotime($sale->created_at)) }}
                    </p>
                </div>
            </div>

            <!-- Client Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white print:text-black mb-2">FACTURÉ À:</h4>
                    <div class="text-sm text-gray-600 dark:text-gray-400 print:text-gray-600">
                        @if ($sale->client)
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
                        @else
                            <p class="font-medium text-gray-900 dark:text-white print:text-black">Client non spécifié</p>
                        @endif
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white print:text-black mb-2">VENDEUR:</h4>
                    <div class="text-sm text-gray-600 dark:text-gray-400 print:text-gray-600">
                        <p class="font-medium text-gray-900 dark:text-white print:text-black">
                            {{ $sale->utilisateur->name ?? 'N/A' }}</p>
                        <p>Date de vente: {{ date('d/m/Y à H:i', strtotime($sale->created_at)) }}</p>
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
                                    {{ currency($ligne->prix_unitaire) }}
                                </td>
                                <td class="py-3 text-right text-gray-600 dark:text-gray-400 print:text-gray-600">
                                    {{ currency($ligne->remise_ligne) }}
                                </td>
                                <td class="py-3 text-right font-medium text-gray-900 dark:text-white print:text-black">
                                    {{ currency($ligne->sous_total) }}
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
                                {{ currency($sale->ligneVentes->sum('sous_total')) }}
                            </span>
                        </div>
                        @if ($sale->remise_totale > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400 print:text-gray-600">Remise totale:</span>
                                <span class="text-red-600 font-medium">
                                    -{{ currency($sale->remise_totale) }}
                                </span>
                            </div>
                        @endif
                        <hr class="border-gray-200 dark:border-gray-700 print:border-gray-300">
                        <div class="flex justify-between text-lg font-bold">
                            <span class="text-gray-900 dark:text-white print:text-black">TOTAL:</span>
                            <span class="text-gray-900 dark:text-white print:text-black">
                                {{ currency($sale->total) }}
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400 print:text-gray-600">Montant payé:</span>
                            <span class="text-green-600 font-medium">
                                {{ currency($sale->montant_paye) }}
                            </span>
                        </div>
                        @if ($sale->restant_a_payer > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400 print:text-gray-600">Restant à payer:</span>
                                <span class="text-red-600 font-medium">
                                    {{ currency($sale->restant_a_payer) }}
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
                        @if ($sale->statut == 'payée')
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 print:bg-green-100 print:text-green-800">
                                ✓ Payé intégralement
                            </span>
                        @elseif($sale->statut == 'partiellement_payée')
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800 print:bg-orange-100 print:text-orange-800">
                                ⚠ Paiement partiel
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 print:bg-red-100 print:text-red-800">
                                ✗ Impayée
                            </span>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 dark:text-gray-400 print:text-gray-500">
                            Bénéfice: {{ currency($sale->benefice) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 print:border-gray-300 text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400 print:text-gray-500">
                    Merci pour votre confiance ! Cette facture a été générée automatiquement par
                    {{ $appSettings->app_name }}.
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 print:text-gray-500 mt-1">
                    Pour toute question, veuillez nous
                    contacter{{ $appSettings->company_phone ? ' au ' . $appSettings->company_phone : '' }}.
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

            #invoice-content,
            #invoice-content * {
                visibility: visible;
            }

            #invoice-content {
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
