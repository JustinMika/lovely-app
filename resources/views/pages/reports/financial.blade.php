@extends('layouts.app')

@section('title', 'Rapport Financier')

@section('content')
    <!-- Breadcrumb Start -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">
            Rapport Financier
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
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-medium text-gray-800 dark:text-white">
                        Analyse Financière
                    </h2>
                    <div class="flex items-center gap-3">
                        <form method="POST" action="{{ route('reports.financial.pdf') }}" class="inline">
                            @csrf
                            <input type="hidden" id="export_date_from" name="date_from" value="">
                            <input type="hidden" id="export_date_to" name="date_to" value="">
                            <button type="submit"
                                class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l4-4m-4 4l-4-4m8 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Exporter PDF
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="p-4 sm:p-6">
                <!-- Filters -->
                <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Date de début
                        </label>
                        <input type="date" id="date_from" name="date_from"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Date de fin
                        </label>
                        <input type="date" id="date_to" name="date_to"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                    </div>
                    <div class="flex items-end">
                        <button
                            class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 h-11 w-full rounded-lg px-4 py-2.5 text-sm font-medium text-white transition">
                            Générer le rapport
                        </button>
                    </div>
                </div>

                <!-- Financial Metrics Cards -->
                <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-4">
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Chiffre d'Affaires</p>
                                <p class="text-2xl font-bold text-gray-800 dark:text-white">
                                    {{ currency($financialMetrics['total_revenue']) }}
                                </p>
                            </div>
                            <div class="rounded-full bg-green-100 p-2 dark:bg-green-900/20">
                                <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Bénéfice Brut</p>
                                <p class="text-2xl font-bold text-gray-800 dark:text-white">
                                    {{ currency($financialMetrics['gross_profit']) }}
                                </p>
                            </div>
                            <div class="rounded-full bg-blue-100 p-2 dark:bg-blue-900/20">
                                <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Marge Bénéficiaire</p>
                                <p class="text-2xl font-bold text-gray-800 dark:text-white">
                                    {{ number_format($financialMetrics['profit_margin'], 1) }}%
                                </p>
                            </div>
                            <div class="rounded-full bg-purple-100 p-2 dark:bg-purple-900/20">
                                <svg class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Panier Moyen</p>
                                <p class="text-2xl font-bold text-gray-800 dark:text-white">
                                    {{ currency($financialMetrics['average_basket']) }}
                                </p>
                            </div>
                            <div class="rounded-full bg-orange-100 p-2 dark:bg-orange-900/20">
                                <svg class="h-5 w-5 text-orange-600 dark:text-orange-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Charts -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Revenue Chart -->
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-800/50">
                        <h3 class="mb-4 text-lg font-medium text-gray-800 dark:text-white">Évolution du Chiffre d'Affaires</h3>
                        <div class="h-80">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>

                    <!-- Profit Chart -->
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-800/50">
                        <h3 class="mb-4 text-lg font-medium text-gray-800 dark:text-white">Évolution des Bénéfices</h3>
                        <div class="h-80">
                            <canvas id="profitChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Expense Chart -->
                <div class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-800/50">
                    <h3 class="mb-4 text-lg font-medium text-gray-800 dark:text-white">Analyse des Coûts d'Achat</h3>
                    <div class="h-80">
                        <canvas id="expenseChart"></canvas>
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
            // Configuration des couleurs pour le thème sombre
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#f9fafb' : '#374151';
            const gridColor = isDark ? '#374151' : '#e5e7eb';

            @php
                $currencySymbol = app_setting('currency_symbol', 'FCFA');
            @endphp

            // Configuration commune pour les graphiques
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: textColor
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
                                }).format(value) + ' {{ $currencySymbol }}';
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

            // Graphique des revenus
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueData = {
                labels: @json($revenueData['labels'] ?? []),
                datasets: [{
                    label: 'Chiffre d\'affaires ({{ $currencySymbol }})',
                    data: @json($revenueData['data'] ?? []),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            };

            new Chart(revenueCtx, {
                type: 'line',
                data: revenueData,
                options: {
                    ...commonOptions,
                    plugins: {
                        ...commonOptions.plugins,
                        title: {
                            display: true,
                            text: 'Évolution mensuelle du chiffre d\'affaires',
                            color: textColor
                        }
                    }
                }
            });

            // Graphique des bénéfices
            const profitCtx = document.getElementById('profitChart').getContext('2d');
            const profitData = {
                labels: @json($profitData['labels'] ?? []),
                datasets: [{
                    label: 'Bénéfices ({{ $currencySymbol }})',
                    data: @json($profitData['data'] ?? []),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            };

            new Chart(profitCtx, {
                type: 'line',
                data: profitData,
                options: {
                    ...commonOptions,
                    plugins: {
                        ...commonOptions.plugins,
                        title: {
                            display: true,
                            text: 'Évolution mensuelle des bénéfices',
                            color: textColor
                        }
                    }
                }
            });

            // Graphique des dépenses
            const expenseCtx = document.getElementById('expenseChart').getContext('2d');
            const expenseData = {
                labels: @json($expenseData['labels'] ?? []),
                datasets: [{
                    label: 'Coûts d\'achat ({{ $currencySymbol }})',
                    data: @json($expenseData['data'] ?? []),
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            };

            new Chart(expenseCtx, {
                type: 'line',
                data: expenseData,
                options: {
                    ...commonOptions,
                    plugins: {
                        ...commonOptions.plugins,
                        title: {
                            display: true,
                            text: 'Évolution mensuelle des coûts d\'achat',
                            color: textColor
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
