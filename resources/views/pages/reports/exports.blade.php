@extends('layouts.app')

@section('title', 'Exports de Rapports')

@section('content')
    <!-- Breadcrumb Start -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">
            Centre d'Exports
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                        href="{{ route('dashboard') }}">Tableau de bord /</a>
                </li>
                <li class="font-medium text-gray-800 dark:text-white/90">Rapports / Exports</li>
            </ol>
        </nav>
    </div>
    <!-- Breadcrumb End -->

    <!-- Content Start -->
    <div class="space-y-6">
        <!-- Export Cards Grid -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <!-- Articles Export -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center gap-4">
                    <div class="rounded-lg bg-blue-100 p-3 dark:bg-blue-900/20">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Articles</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Liste complète des articles</p>
                    </div>
                </div>
                <div class="mt-4 flex gap-2">
                    <a href="{{ route('articles.export.pdf') }}"
                        class="flex-1 rounded-lg bg-red-600 px-4 py-2 text-center text-sm font-medium text-white transition hover:bg-red-700">
                        📄 PDF
                    </a>
                    <a href="{{ route('articles.export.excel') }}"
                        class="flex-1 rounded-lg bg-green-600 px-4 py-2 text-center text-sm font-medium text-white transition hover:bg-green-700">
                        📊 Excel
                    </a>
                </div>
            </div>

            <!-- Clients Export -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center gap-4">
                    <div class="rounded-lg bg-purple-100 p-3 dark:bg-purple-900/20">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Clients</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Liste de tous les clients</p>
                    </div>
                </div>
                <div class="mt-4 flex gap-2">
                    <a href="{{ route('clients.export.pdf') }}"
                        class="flex-1 rounded-lg bg-red-600 px-4 py-2 text-center text-sm font-medium text-white transition hover:bg-red-700">
                        📄 PDF
                    </a>
                </div>
            </div>

            <!-- Ventes Export -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center gap-4">
                    <div class="rounded-lg bg-green-100 p-3 dark:bg-green-900/20">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Ventes</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Historique des ventes</p>
                    </div>
                </div>
                <div class="mt-4 flex gap-2">
                    <a href="{{ route('ventes.export.pdf') }}"
                        class="flex-1 rounded-lg bg-red-600 px-4 py-2 text-center text-sm font-medium text-white transition hover:bg-red-700">
                        📄 PDF
                    </a>
                </div>
            </div>

            <!-- Stock Report -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center gap-4">
                    <div class="rounded-lg bg-orange-100 p-3 dark:bg-orange-900/20">
                        <svg class="h-6 w-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Rapport de Stock</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">État du stock et alertes</p>
                    </div>
                </div>
                <div class="mt-4 flex gap-2">
                    <form method="POST" action="{{ route('reports.stock.pdf') }}" class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full rounded-lg bg-red-600 px-4 py-2 text-center text-sm font-medium text-white transition hover:bg-red-700">
                            📄 PDF
                        </button>
                    </form>
                </div>
            </div>

            <!-- Sales Report -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center gap-4">
                    <div class="rounded-lg bg-blue-100 p-3 dark:bg-blue-900/20">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Rapport de Ventes</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Analyse des ventes</p>
                    </div>
                </div>
                <div class="mt-4 flex gap-2">
                    <form method="POST" action="{{ route('reports.sales.pdf') }}" class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full rounded-lg bg-red-600 px-4 py-2 text-center text-sm font-medium text-white transition hover:bg-red-700">
                            📄 PDF
                        </button>
                    </form>
                </div>
            </div>

            <!-- Financial Report -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center gap-4">
                    <div class="rounded-lg bg-emerald-100 p-3 dark:bg-emerald-900/20">
                        <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Rapport Financier</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Analyse financière</p>
                    </div>
                </div>
                <div class="mt-4 flex gap-2">
                    <form method="POST" action="{{ route('reports.financial.pdf') }}" class="flex-1">
                        @csrf
                        <button type="submit"
                            class="w-full rounded-lg bg-red-600 px-4 py-2 text-center text-sm font-medium text-white transition hover:bg-red-700">
                            📄 PDF
                        </button>
                    </form>
                </div>
            </div>

            <!-- Villes Export -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center gap-4">
                    <div class="rounded-lg bg-indigo-100 p-3 dark:bg-indigo-900/20">
                        <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Villes</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Liste des villes</p>
                    </div>
                </div>
                <div class="mt-4 flex gap-2">
                    <a href="{{ route('settings.cities.export.pdf') }}"
                        class="flex-1 rounded-lg bg-red-600 px-4 py-2 text-center text-sm font-medium text-white transition hover:bg-red-700">
                        📄 PDF
                    </a>
                </div>
            </div>

            <!-- Users Export -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center gap-4">
                    <div class="rounded-lg bg-pink-100 p-3 dark:bg-pink-900/20">
                        <svg class="h-6 w-6 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Utilisateurs</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Liste des utilisateurs</p>
                    </div>
                </div>
                <div class="mt-4 flex gap-2">
                    <a href="{{ route('users.export.pdf') }}"
                        class="flex-1 rounded-lg bg-red-600 px-4 py-2 text-center text-sm font-medium text-white transition hover:bg-red-700">
                        📄 PDF
                    </a>
                </div>
            </div>

            <!-- Global Report -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center gap-4">
                    <div class="rounded-lg bg-gray-100 p-3 dark:bg-gray-700/50">
                        <svg class="h-6 w-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Rapport Global</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Rapport complet</p>
                    </div>
                </div>
                <div class="mt-4 flex gap-2">
                    <a href="{{ route('reports.global.pdf') }}"
                        class="flex-1 rounded-lg bg-red-600 px-4 py-2 text-center text-sm font-medium text-white transition hover:bg-red-700">
                        📄 PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-6 dark:border-blue-800/50 dark:bg-blue-900/10">
            <div class="flex items-start gap-4">
                <div class="rounded-lg bg-blue-100 p-2 dark:bg-blue-900/20">
                    <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-blue-900 dark:text-blue-300">Information sur les exports</h4>
                    <p class="mt-1 text-sm text-blue-700 dark:text-blue-400">
                        Les exports sont générés à la demande avec les données les plus récentes. Les rapports (ventes,
                        financier, stock) peuvent être filtrés par période depuis leurs pages respectives.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
