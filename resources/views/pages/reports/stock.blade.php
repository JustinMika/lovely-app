@extends('layouts.app')

@section('title', 'Rapport de Stock')

@section('content')
    <!-- Breadcrumb Start -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">
            Rapport de Stock
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                        href="{{ route('dashboard') }}">Tableau de bord /</a>
                </li>
                <li class="font-medium text-gray-800 dark:text-white/90">Rapports / Stock</li>
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
                        Analyse du Stock
                    </h2>
                    <div class="flex items-center gap-3">
                        <form method="POST" action="{{ route('reports.stock.pdf') }}" class="inline">
                            @csrf
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
                <!-- Stock Metrics Cards -->
                <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-3 lg:grid-cols-6">
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                        <div class="flex flex-col">
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Total Produits</p>
                            <p class="text-xl font-bold text-gray-800 dark:text-white">
                                {{ number_format($stockMetrics['total_products']) }}
                            </p>
                            <div class="mt-2">
                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                        <div class="flex flex-col">
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Total Stock</p>
                            <p class="text-xl font-bold text-gray-800 dark:text-white">
                                {{ number_format($stockMetrics['total_stock']) }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">unités</p>
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                        <div class="flex flex-col">
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Valeur Stock</p>
                            <p class="text-xl font-bold text-gray-800 dark:text-white">
                                {{ currency($stockMetrics['stock_value']) }}
                            </p>
                            <div class="mt-2">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                        <div class="flex flex-col">
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Stock Moyen</p>
                            <p class="text-xl font-bold text-gray-800 dark:text-white">
                                {{ number_format($stockMetrics['average_stock'], 1) }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">par produit</p>
                        </div>
                    </div>

                    <div class="rounded-lg border border-red-200 bg-red-50 p-4 dark:border-red-700/50 dark:bg-red-900/10">
                        <div class="flex flex-col">
                            <p class="text-xs font-medium text-red-600 dark:text-red-400 mb-1">Rupture Stock</p>
                            <p class="text-xl font-bold text-red-800 dark:text-red-300">
                                {{ number_format($stockMetrics['out_of_stock']) }}
                            </p>
                            <div class="mt-2">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-lg border border-orange-200 bg-orange-50 p-4 dark:border-orange-700/50 dark:bg-orange-900/10">
                        <div class="flex flex-col">
                            <p class="text-xs font-medium text-orange-600 dark:text-orange-400 mb-1">Stock Bas</p>
                            <p class="text-xl font-bold text-orange-800 dark:text-orange-300">
                                {{ number_format($stockMetrics['low_stock']) }}
                            </p>
                            <div class="mt-2">
                                <svg class="h-6 w-6 text-orange-600 dark:text-orange-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stock Tables -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Low Stock Products -->
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-800/50">
                        <h3 class="mb-4 text-lg font-medium text-gray-800 dark:text-white">Produits en Stock Bas</h3>
                        @if (count($lowStockProducts) > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-gray-200 dark:border-gray-700">
                                            <th class="pb-2 text-left font-medium text-gray-600 dark:text-gray-400">Produit
                                            </th>
                                            <th class="pb-2 text-center font-medium text-gray-600 dark:text-gray-400">Stock
                                            </th>
                                            <th class="pb-2 text-center font-medium text-gray-600 dark:text-gray-400">Seuil
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lowStockProducts as $product)
                                            <tr class="border-b border-gray-100 dark:border-gray-700/50">
                                                <td class="py-2 text-gray-800 dark:text-white">
                                                    {{ $product['designation'] }}</td>
                                                <td
                                                    class="py-2 text-center text-orange-600 dark:text-orange-400 font-medium">
                                                    {{ number_format($product['stock_quantity']) }}</td>
                                                <td class="py-2 text-center text-gray-600 dark:text-gray-400">
                                                    {{ number_format($product['seuil_alerte']) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-center text-gray-500 dark:text-gray-400 py-4">Aucun produit en stock bas</p>
                        @endif
                    </div>

                    <!-- Expiring Products -->
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-800/50">
                        <h3 class="mb-4 text-lg font-medium text-gray-800 dark:text-white">Produits Proches Expiration</h3>
                        @if (count($expiringProducts) > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="border-b border-gray-200 dark:border-gray-700">
                                            <th class="pb-2 text-left font-medium text-gray-600 dark:text-gray-400">Produit
                                            </th>
                                            <th class="pb-2 text-center font-medium text-gray-600 dark:text-gray-400">N°
                                                Lot
                                            </th>
                                            <th class="pb-2 text-center font-medium text-gray-600 dark:text-gray-400">
                                                Expiration</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($expiringProducts as $product)
                                            <tr class="border-b border-gray-100 dark:border-gray-700/50">
                                                <td class="py-2 text-gray-800 dark:text-white">
                                                    {{ $product['designation'] }}</td>
                                                <td class="py-2 text-center text-gray-600 dark:text-gray-400">
                                                    {{ $product['numero_lot'] }}</td>
                                                <td class="py-2 text-center text-red-600 dark:text-red-400 font-medium">
                                                    {{ \Carbon\Carbon::parse($product['date_expiration'])->format('d/m/Y') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-center text-gray-500 dark:text-gray-400 py-4">Aucun produit proche de
                                l'expiration</p>
                        @endif
                    </div>
                </div>

                <!-- Stock Distribution Chart -->
                <div
                    class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-800/50">
                    <h3 class="mb-4 text-lg font-medium text-gray-800 dark:text-white">Répartition du Stock</h3>
                    <div class="h-80">
                        <canvas id="stockChart"></canvas>
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

            // Données de répartition du stock
            const stockData = {
                labels: @json(array_column($stockByCategory, 'category')),
                datasets: [{
                    label: 'Nombre de Produits',
                    data: @json(array_column($stockByCategory, 'product_count')),
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: '#3b82f6',
                    borderWidth: 1
                }, {
                    label: 'Stock Total',
                    data: @json(array_column($stockByCategory, 'total_stock')),
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderColor: '#10b981',
                    borderWidth: 1
                }]
            };

            // Graphique de répartition
            const stockCtx = document.getElementById('stockChart').getContext('2d');
            new Chart(stockCtx, {
                type: 'bar',
                data: stockData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: textColor
                            }
                        },
                        title: {
                            display: true,
                            text: 'Répartition du stock par catégorie',
                            color: textColor
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
                }
            });
        });
    </script>
@endpush
