@extends('layouts.app')

@section('title', 'Recherche de Factures')

@section('content')
    <!-- Breadcrumb Start -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">
            Recherche de Factures
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                        href="{{ route('dashboard') }}">Tableau de bord /</a>
                </li>
                <li>
                    <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                        href="{{ route('invoices.index') }}">Factures /</a>
                </li>
                <li class="font-medium text-gray-800 dark:text-white/90">Recherche</li>
            </ol>
        </nav>
    </div>
    <!-- Breadcrumb End -->

    <!-- Content Start -->
    <div class="space-y-6">
        <!-- Search Form -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <h2 class="text-lg font-medium text-gray-800 dark:text-white">
                    Critères de Recherche
                </h2>
            </div>
            <div class="p-4 sm:p-6">
                <form method="GET" action="{{ route('invoices.search') }}">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <label for="invoice_number"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Numéro de Facture
                            </label>
                            <input type="text" id="invoice_number" name="invoice_number"
                                value="{{ request('invoice_number') }}" placeholder="Ex: FAC-2024-001"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        </div>

                        <div>
                            <label for="client_name"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Nom du Client
                            </label>
                            <input type="text" id="client_name" name="client_name" value="{{ request('client_name') }}"
                                placeholder="Nom du client"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        </div>

                        <div>
                            <label for="status" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Statut
                            </label>
                            <select id="status" name="status"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <option value="">Tous les statuts</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Payée</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente
                                </option>
                                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>En retard
                                </option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée
                                </option>
                            </select>
                        </div>

                        <div>
                            <label for="date_from"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Date de début
                            </label>
                            <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                        </div>

                        <div>
                            <label for="date_to" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Date de fin
                            </label>
                            <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" />
                        </div>

                        <div>
                            <label for="amount_min"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Montant minimum (€)
                            </label>
                            <input type="number" id="amount_min" name="amount_min" value="{{ request('amount_min') }}"
                                min="0" step="0.01" placeholder="0.00"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                        <a href="{{ route('invoices.search') }}"
                            class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                            Réinitialiser
                        </a>
                        <button type="submit"
                            class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Rechercher
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Search Results -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <h2 class="text-lg font-medium text-gray-800 dark:text-white">
                    Résultats de la Recherche
                </h2>
            </div>
            <div class="p-4 sm:p-6">
                <div class="text-center py-12">
                    <div class="mx-auto h-12 w-12 text-gray-400">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Aucun résultat</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Utilisez les critères de recherche ci-dessus pour trouver des factures.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
