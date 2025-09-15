@extends('layouts.app')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">
        Gestion des Approvisionnements
    </h2>
    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300" 
                   href="{{ route('dashboard') }}">Tableau de bord /</a>
            </li>
            <li class="font-medium text-gray-800 dark:text-white/90">Approvisionnements</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<!-- Stats Cards -->
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 xl:grid-cols-4 mb-6">
    <!-- Total Approvisionnements -->
    <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="mb-6 flex items-center gap-3">
            <div class="h-10 w-10">
                <svg class="fill-blue-600 dark:fill-blue-400" width="40" height="40" viewBox="0 0 40 40">
                    <path d="M35 10a5 5 0 0 0-5-5H10a5 5 0 0 0-5 5v20a5 5 0 0 0 5 5h20a5 5 0 0 0 5-5V10ZM30 7.5a2.5 2.5 0 0 1 2.5 2.5v20a2.5 2.5 0 0 1-2.5 2.5H10A2.5 2.5 0 0 1 7.5 30V10A2.5 2.5 0 0 1 10 7.5h20Z"/>
                    <path d="M15 15h10v2.5H15V15ZM15 20h10v2.5H15V20ZM15 25h7.5v2.5H15V25Z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    Total
                </h3>
                <span class="block text-theme-xs text-gray-500 dark:text-gray-400">
                    Approvisionnements
                </span>
            </div>
        </div>
        <div class="flex items-end justify-between">
            <div>
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    {{ number_format($stats['total']) }}
                </h4>
            </div>
        </div>
    </div>

    <!-- Ce mois -->
    <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="mb-6 flex items-center gap-3">
            <div class="h-10 w-10">
                <svg class="fill-success-600 dark:fill-success-400" width="40" height="40" viewBox="0 0 40 40">
                    <path d="M20 5C11.716 5 5 11.716 5 20s6.716 15 15 15 15-6.716 15-15S28.284 5 20 5Zm0 27.5c-6.893 0-12.5-5.607-12.5-12.5S13.107 7.5 20 7.5 32.5 13.107 32.5 20 26.893 32.5 20 32.5Z"/>
                    <path d="M27.5 17.5c0 1.381-1.119 2.5-2.5 2.5h-7.5V12.5c0-1.381 1.119-2.5 2.5-2.5s2.5 1.119 2.5 2.5V15h2.5c1.381 0 2.5 1.119 2.5 2.5ZM12.5 22.5c0-1.381 1.119-2.5 2.5-2.5h7.5v7.5c0 1.381-1.119 2.5-2.5 2.5s-2.5-1.119-2.5-2.5V25H15c-1.381 0-2.5-1.119-2.5-2.5Z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    Ce mois
                </h3>
                <span class="block text-theme-xs text-gray-500 dark:text-gray-400">
                    {{ now()->format('M Y') }}
                </span>
            </div>
        </div>
        <div class="flex items-end justify-between">
            <div>
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    {{ number_format($stats['this_month']) }}
                </h4>
            </div>
        </div>
    </div>

    <!-- Valeur totale -->
    <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="mb-6 flex items-center gap-3">
            <div class="h-10 w-10">
                <svg class="fill-warning-600 dark:fill-warning-400" width="40" height="40" viewBox="0 0 40 40">
                    <path d="M20 5C11.716 5 5 11.716 5 20s6.716 15 15 15 15-6.716 15-15S28.284 5 20 5Zm0 27.5c-6.893 0-12.5-5.607-12.5-12.5S13.107 7.5 20 7.5 32.5 13.107 32.5 20 26.893 32.5 20 32.5Z"/>
                    <path d="M20 12.5c-1.036 0-1.875.839-1.875 1.875v8.75c0 1.036.839 1.875 1.875 1.875s1.875-.839 1.875-1.875v-8.75c0-1.036-.839-1.875-1.875-1.875ZM20 27.5c-1.036 0-1.875.839-1.875 1.875S18.964 31.25 20 31.25s1.875-.839 1.875-1.875S21.036 27.5 20 27.5Z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    Valeur
                </h3>
                <span class="block text-theme-xs text-gray-500 dark:text-gray-400">
                    Total investie
                </span>
            </div>
        </div>
        <div class="flex items-end justify-between">
            <div>
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    €{{ number_format($stats['total_value'], 2) }}
                </h4>
            </div>
        </div>
    </div>

    <!-- Lots en attente -->
    <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="mb-6 flex items-center gap-3">
            <div class="h-10 w-10">
                <svg class="fill-error-600 dark:fill-error-400" width="40" height="40" viewBox="0 0 40 40">
                    <path d="M20 5C11.716 5 5 11.716 5 20s6.716 15 15 15 15-6.716 15-15S28.284 5 20 5Zm0 27.5c-6.893 0-12.5-5.607-12.5-12.5S13.107 7.5 20 7.5 32.5 13.107 32.5 20 26.893 32.5 20 32.5Z"/>
                    <path d="M20 12.5c-1.381 0-2.5 1.119-2.5 2.5v7.5c0 1.381 1.119 2.5 2.5 2.5s2.5-1.119 2.5-2.5V15c0-1.381-1.119-2.5-2.5-2.5ZM20 27.5c-1.381 0-2.5 1.119-2.5 2.5s1.119 2.5 2.5 2.5 2.5-1.119 2.5-2.5-1.119-2.5-2.5-2.5Z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    En attente
                </h3>
                <span class="block text-theme-xs text-gray-500 dark:text-gray-400">
                    Lots non reçus
                </span>
            </div>
        </div>
        <div class="flex items-end justify-between">
            <div>
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    {{ number_format($stats['pending_lots']) }}
                </h4>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            Liste des Approvisionnements
        </h3>
        <div class="flex gap-3">
            <a href="{{ route('approvisionnements.stats') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-theme-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-300 dark:hover:bg-white/[0.05]">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Statistiques
            </a>
            <a href="{{ route('approvisionnements.create') }}" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-theme-sm font-medium text-white hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Nouvel Approvisionnement
            </a>
        </div>
    </div>

    <!-- Approvisionnements Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Date</th>
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Fournisseur</th>
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Référence</th>
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Responsable</th>
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Nb Lots</th>
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Valeur</th>
                    <th class="pb-3 text-right text-sm font-medium text-gray-500 dark:text-gray-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($approvisionnements as $approvisionnement)
                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                    <td class="py-4">
                        <div class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ \Carbon\Carbon::parse($approvisionnement->date_approvisionnement)->format('d/m/Y') }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($approvisionnement->created_at)->format('H:i') }}
                        </div>
                    </td>
                    <td class="py-4">
                        <div class="font-medium text-gray-800 dark:text-white/90">
                            {{ $approvisionnement->fournisseur }}
                        </div>
                    </td>
                    <td class="py-4">
                        @if($approvisionnement->reference_facture)
                        <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 dark:bg-blue-500/15 dark:text-blue-400">
                            {{ $approvisionnement->reference_facture }}
                        </span>
                        @else
                        <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="py-4">
                        <div class="text-sm text-gray-800 dark:text-white/90">
                            {{ $approvisionnement->user->name }}
                        </div>
                    </td>
                    <td class="py-4">
                        <div class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ $approvisionnement->lots->count() }} lots
                        </div>
                    </td>
                    <td class="py-4">
                        <div class="text-sm font-medium text-gray-800 dark:text-white/90">
                            €{{ number_format($approvisionnement->lots->sum(function($lot) { return $lot->quantite_initiale * $lot->prix_achat; }), 2) }}
                        </div>
                    </td>
                    <td class="py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('approvisionnements.show', $approvisionnement) }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-300 dark:hover:bg-white/[0.05]">
                                Voir
                            </a>
                            <a href="{{ route('approvisionnements.edit', $approvisionnement) }}" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-700">
                                Modifier
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 text-center">
                        <div class="mx-auto mb-4 h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-2">
                            Aucun approvisionnement
                        </h4>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">
                            Commencez par créer votre premier approvisionnement.
                        </p>
                        <a href="{{ route('approvisionnements.create') }}" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Créer un approvisionnement
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($approvisionnements->hasPages())
    <!-- Pagination -->
    <div class="mt-6">
        {{ $approvisionnements->links() }}
    </div>
    @endif
</div>
@endsection
