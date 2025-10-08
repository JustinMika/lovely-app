@extends('layouts.app')

@section('title', 'Rapport des Ventes')

@section('content')
    <!-- Breadcrumb Start -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">
            Rapport des Ventes
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                        href="{{ route('dashboard') }}">Tableau de bord /</a>
                </li>
                <li class="font-medium text-gray-800 dark:text-white/90">Rapports / Ventes</li>
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
                        Analyse des Ventes
                    </h2>
                    <div class="flex items-center gap-3">
                        <button
                            class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l4-4m-4 4l-4-4m8 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Exporter PDF
                        </button>
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
                        <form method="POST" action="{{ route('reports.sales.pdf') }}" class="w-full">
                            @csrf
                            <input type="hidden" id="export_date_from" name="date_from" value="">
                            <input type="hidden" id="export_date_to" name="date_to" value="">
                            <button type="submit"
                                class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 focus:bg-brand-600 h-11 w-full rounded-lg px-4 py-2.5 text-sm font-medium text-white transition focus:outline-hidden">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l4-4m-4 4l-4-4m8 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Exporter PDF
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Metrics Cards -->
                <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-4">
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Ventes</p>
                                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ \App\Models\Vente::count() }}
                                </p>
                            </div>
                            <div class="rounded-full bg-blue-100 p-2 dark:bg-blue-900/20">
                                <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Chiffre d'Affaires</p>
                                <p class="text-2xl font-bold text-gray-800 dark:text-white">
                                    {{ currency(\App\Models\Vente::sum('total')) }}</p>
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
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Ventes Aujourd'hui</p>
                                <p class="text-2xl font-bold text-gray-800 dark:text-white">
                                    {{ \App\Models\Vente::whereDate('created_at', today())->count() }}</p>
                            </div>
                            <div class="rounded-full bg-purple-100 p-2 dark:bg-purple-900/20">
                                <svg class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h4a2 2 0 012 2v1m-6 0h8m-9 0h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2z">
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
                                    @php
                                        $totalVentes = \App\Models\Vente::count();
                                        $totalCA = \App\Models\Vente::sum('total');
                                        $panierMoyen = $totalVentes > 0 ? $totalCA / $totalVentes : 0;
                                    @endphp
                                    {{ currency($panierMoyen) }}
                                </p>
                            </div>
                            <div class="rounded-full bg-orange-100 p-2 dark:bg-orange-900/20">
                                <svg class="h-5 w-5 text-orange-600 dark:text-orange-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Chart -->
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-800/50">
                    <h3 class="mb-4 text-lg font-medium text-gray-800 dark:text-white">Évolution des Ventes</h3>
                    <div class="h-80">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Top Products Chart -->
                <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-800/50">
                        <h3 class="mb-4 text-lg font-medium text-gray-800 dark:text-white">Top Articles Vendus</h3>
                        <div class="h-64">
                            <canvas id="topProductsChart"></canvas>
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-800/50">
                        <h3 class="mb-4 text-lg font-medium text-gray-800 dark:text-white">Ventes par Mois</h3>
                        <div class="h-64">
                            <canvas id="monthlyChart"></canvas>
                        </div>
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

                    // Données réelles depuis le contrôleur Laravel
                    const salesData = {
                        labels: @json($salesData['labels']),
                        datasets: [{
                            label: 'Ventes (' + @json(app_setting('currency_symbol', 'FC')) + ')',
                            data: @json($salesData['data']),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    };

                    const topProductsData = {
                        labels: @json($topProductsData['labels']),
                        datasets: [{
                            data: @json($topProductsData['data']),
                            backgroundColor: [
                                '#3b82f6',
                                '#10b981',
                                '#f59e0b',
                                '#ef4444',
                                '#8b5cf6'
                            ]
                        }]
                    };

                    const monthlyData = {
                        labels: @json($monthlyData['labels']),
                        datasets: [{
                            label: 'Ventes',
                            data: @json($monthlyData['data']),
                            backgroundColor: 'rgba(16, 185, 129, 0.8)',
                            borderColor: '#10b981',
                            borderWidth: 1
                        }]
                    };

                    // Configuration commune
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
                                    color: textColor
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

                    // Graphique d'évolution des ventes
                    const salesCtx = document.getElementById('salesChart').getContext('2d');
                    new Chart(salesCtx, {
                        type: 'line',
                        data: salesData,
                        options: {
                            ...commonOptions,
                            plugins: {
                                ...commonOptions.plugins,
                                title: {
                                    display: true,
                                    text: 'Évolution mensuelle du chiffre d\'affaires',
                                    color: textColor
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return 'Ventes: ' + new Intl.NumberFormat('fr-FR').format(context
                                                .parsed.y) + ' ' + @json(app_setting('currency_symbol', 'FC'));
                                        }
                                    }
                                }
                            },
                            scales: {
                                ...commonOptions.scales,
                                y: {
                                    ...commonOptions.scales.y,
                                    ticks: {
                                        ...commonOptions.scales.y.ticks,
                                        callback: function(value) {
                                            return new Intl.NumberFormat('fr-FR', {
                                                notation: 'compact',
                                                compactDisplay: 'short'
                                            }).format(value) + ' ' + @json(app_setting('currency_symbol', 'FC'));
                                        }
                                    }
                                }
                            }
                        }
                    });

                    // Graphique des top produits
                    const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
                    new Chart(topProductsCtx, {
                        type: 'doughnut',
                        data: topProductsData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        color: textColor,
                                        padding: 20
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.parsed || 0;
                                            return label + ': ' + new Intl.NumberFormat('fr-FR').format(value) +
                                                ' unités';
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
    </script>
@endpush
