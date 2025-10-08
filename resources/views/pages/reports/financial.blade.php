@extends('layouts.app')

@section('title', 'Rapport Financier')

@section('content')
    <!-- Breadcrumb Start -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">
            Analyse Financière & Rentabilité
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                        href="{{ route('dashboard') }}">Tableau de bord /</a>
                </li>
                <li class="font-medium text-gray-800 dark:text-white/90">Rapports / Financier</li>
            </ol>
        </nav>
    </div>
    <!-- Breadcrumb End -->

    <!-- Content Start -->
    <div class="space-y-6">
        <!-- Filters -->
        <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6">
            <div class="mb-2 flex items-center gap-2">
                <svg class="h-5 w-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                    </path>
                </svg>
                <h3 class="text-sm font-semibold text-gray-800 dark:text-white">Filtres de période</h3>
            </div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Date de début
                    </label>
                    <input type="date" id="date_from" name="date_from"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Date de fin
                    </label>
                    <input type="date" id="date_to" name="date_to"
                        class="shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                </div>
                <div class="flex items-end md:col-span-2">
                    <form method="POST" action="{{ route('reports.financial.pdf') }}" class="w-full">
                        @csrf
                        <input type="hidden" id="export_date_from" name="date_from" value="">
                        <input type="hidden" id="export_date_to" name="date_to" value="">
                        <button type="submit"
                            class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 focus:bg-brand-600 h-11 w-full rounded-lg px-4 py-2.5 text-sm font-medium text-white transition focus:outline-hidden">
                            <svg class="mr-2 inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l4-4m-4 4l-4-4m8 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Exporter le Rapport PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Financial Metrics Cards -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
            <!-- Revenue Card -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Revenus Totaux</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ currency($financialMetrics['total_revenue']) }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Chiffre d'affaires</p>
                    </div>
                    <div class="rounded-full bg-gradient-to-br from-green-400 to-green-600 p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Cost Card -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Coûts Totaux</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ currency($financialMetrics['total_cost']) }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Prix d'achat des articles</p>
                    </div>
                    <div class="rounded-full bg-gradient-to-br from-red-400 to-red-600 p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Profit Card -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Bénéfice Brut</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ currency($financialMetrics['gross_profit']) }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Revenus - Coûts</p>
                    </div>
                    <div class="rounded-full bg-gradient-to-br from-blue-400 to-blue-600 p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Margin Card -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Marge Bénéficiaire</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($financialMetrics['profit_margin'], 1) }}%
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Taux de rentabilité</p>
                    </div>
                    <div class="rounded-full bg-gradient-to-br from-purple-400 to-purple-600 p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Revenue vs Cost Chart -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Revenus vs Coûts</h3>
                    <span
                        class="rounded-lg bg-blue-100 px-3 py-1 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">12
                        mois</span>
                </div>
                <div class="h-80">
                    <canvas id="revenueVsCostChart"></canvas>
                </div>
            </div>

            <!-- Profit Evolution Chart -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Évolution des Bénéfices</h3>
                    <span
                        class="rounded-lg bg-green-100 px-3 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-300">Tendance</span>
                </div>
                <div class="h-80">
                    <canvas id="profitChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Full Width Margin Analysis Chart -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Analyse de la Marge Bénéficiaire</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Pourcentage de bénéfice par rapport au chiffre
                        d'affaires</p>
                </div>
                <span
                    class="rounded-lg bg-purple-100 px-3 py-1 text-xs font-medium text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">%</span>
            </div>
            <div class="h-80">
                <canvas id="marginChart"></canvas>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center gap-3">
                    <div class="rounded-lg bg-orange-100 p-2 dark:bg-orange-900/20">
                        <svg class="h-5 w-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Panier Moyen</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ currency($financialMetrics['average_basket']) }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center gap-3">
                    <div class="rounded-lg bg-indigo-100 p-2 dark:bg-indigo-900/20">
                        <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Nombre de Ventes</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($financialMetrics['total_sales']) }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center gap-3">
                    <div class="rounded-lg bg-teal-100 p-2 dark:bg-teal-900/20">
                        <svg class="h-5 w-5 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Bénéfice par Vente</p>
                        @php
                            $profitPerSale =
                                $financialMetrics['total_sales'] > 0
                                    ? $financialMetrics['gross_profit'] / $financialMetrics['total_sales']
                                    : 0;
                        @endphp
                        <p class="text-xl font-bold text-gray-900 dark:text-white">
                            {{ currency($profitPerSale) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#f9fafb' : '#374151';
            const gridColor = isDark ? '#374151' : '#e5e7eb';

            @php
                $currencySymbol = app_setting('currency_symbol', 'FCFA');
            @endphp

            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: textColor,
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            color: textColor,
                            callback: function(value) {
                                return new Intl.NumberFormat('fr-FR', {
                                    notation: 'compact',
                                    compactDisplay: 'short'
                                }).format(value);
                            }
                        },
                        grid: {
                            color: gridColor
                        }
                    },
                    x: {
                        ticks: {
                            color: textColor
                        },
                        grid: {
                            color: gridColor
                        }
                    }
                }
            };

            // Revenue vs Cost Chart
            const revenueVsCostCtx = document.getElementById('revenueVsCostChart').getContext('2d');
            new Chart(revenueVsCostCtx, {
                type: 'line',
                data: {
                    labels: @json($revenueData['labels'] ?? []),
                    datasets: [{
                            label: 'Revenus ({{ $currencySymbol }})',
                            data: @json($revenueData['data'] ?? []),
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Coûts ({{ $currencySymbol }})',
                            data: @json($expenseData['data'] ?? []),
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: commonOptions
            });

            // Profit Chart
            const profitCtx = document.getElementById('profitChart').getContext('2d');
            new Chart(profitCtx, {
                type: 'bar',
                data: {
                    labels: @json($profitData['labels'] ?? []),
                    datasets: [{
                        label: 'Bénéfices ({{ $currencySymbol }})',
                        data: @json($profitData['data'] ?? []),
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: '#3b82f6',
                        borderWidth: 1
                    }]
                },
                options: commonOptions
            });

            // Margin Chart
            const marginCtx = document.getElementById('marginChart').getContext('2d');
            const revenueValues = @json($revenueData['data'] ?? []);
            const expenseValues = @json($expenseData['data'] ?? []);
            const marginValues = revenueValues.map((rev, idx) => {
                return rev > 0 ? ((rev - expenseValues[idx]) / rev * 100) : 0;
            });

            new Chart(marginCtx, {
                type: 'line',
                data: {
                    labels: @json($revenueData['labels'] ?? []),
                    datasets: [{
                        label: 'Marge Bénéficiaire (%)',
                        data: marginValues,
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    ...commonOptions,
                    scales: {
                        ...commonOptions.scales,
                        y: {
                            ...commonOptions.scales.y,
                            ticks: {
                                ...commonOptions.scales.y.ticks,
                                callback: function(value) {
                                    return value.toFixed(1) + '%';
                                }
                            }
                        }
                    }
                }
            });

            // Synchroniser les valeurs pour l'export PDF
            document.getElementById('date_from').addEventListener('change', function() {
                document.getElementById('export_date_from').value = this.value;
            });

            document.getElementById('date_to').addEventListener('change', function() {
                document.getElementById('export_date_to').value = this.value;
            });
        });
    </script>
@endpush
