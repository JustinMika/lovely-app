@extends('layouts.app')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">
        Statistiques des Approvisionnements
    </h2>
    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300" 
                   href="{{ route('dashboard') }}">Tableau de bord /</a>
            </li>
            <li>
                <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300" 
                   href="{{ route('approvisionnements.index') }}">Approvisionnements /</a>
            </li>
            <li class="font-medium text-gray-800 dark:text-white/90">Statistiques</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<!-- Statistiques mensuelles -->
<div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] mb-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-6">
        Évolution mensuelle {{ now()->year }}
    </h3>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Mois</th>
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Nb Approvisionnements</th>
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Valeur totale</th>
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Moyenne</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @php
                    $months = [
                        1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
                        5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
                        9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
                    ];
                @endphp
                @for($i = 1; $i <= 12; $i++)
                    @php
                        $monthData = $monthlyStats->where('month', $i)->first();
                        $count = $monthData ? $monthData->count : 0;
                        $value = $monthData ? $monthData->value : 0;
                        $average = $count > 0 ? $value / $count : 0;
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                        <td class="py-4">
                            <div class="font-medium text-gray-800 dark:text-white/90">
                                {{ $months[$i] }}
                            </div>
                        </td>
                        <td class="py-4">
                            <div class="text-sm text-gray-800 dark:text-white/90">
                                {{ number_format($count) }}
                            </div>
                        </td>
                        <td class="py-4">
                            <div class="text-sm font-medium text-gray-800 dark:text-white/90">
                                €{{ number_format($value, 2) }}
                            </div>
                        </td>
                        <td class="py-4">
                            <div class="text-sm text-gray-800 dark:text-white/90">
                                €{{ number_format($average, 2) }}
                            </div>
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>

<!-- Top fournisseurs -->
<div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] mb-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-6">
        Top Fournisseurs
    </h3>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Rang</th>
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Fournisseur</th>
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Nb Approvisionnements</th>
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Valeur totale</th>
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Moyenne</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($topSuppliers as $index => $supplier)
                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                    <td class="py-4">
                        <div class="flex items-center">
                            @if($index == 0)
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-yellow-100 text-yellow-800 text-xs font-medium dark:bg-yellow-500/15 dark:text-yellow-400">
                                1
                            </span>
                            @elseif($index == 1)
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-100 text-gray-800 text-xs font-medium dark:bg-gray-500/15 dark:text-gray-400">
                                2
                            </span>
                            @elseif($index == 2)
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-orange-100 text-orange-800 text-xs font-medium dark:bg-orange-500/15 dark:text-orange-400">
                                3
                            </span>
                            @else
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-800 text-xs font-medium dark:bg-blue-500/15 dark:text-blue-400">
                                {{ $index + 1 }}
                            </span>
                            @endif
                        </div>
                    </td>
                    <td class="py-4">
                        <div class="font-medium text-gray-800 dark:text-white/90">
                            {{ $supplier->fournisseur }}
                        </div>
                    </td>
                    <td class="py-4">
                        <div class="text-sm text-gray-800 dark:text-white/90">
                            {{ number_format($supplier->count) }}
                        </div>
                    </td>
                    <td class="py-4">
                        <div class="text-sm font-medium text-gray-800 dark:text-white/90">
                            €{{ number_format($supplier->total_value, 2) }}
                        </div>
                    </td>
                    <td class="py-4">
                        <div class="text-sm text-gray-800 dark:text-white/90">
                            €{{ number_format($supplier->total_value / $supplier->count, 2) }}
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 text-center">
                        <div class="text-gray-500 dark:text-gray-400">
                            Aucune donnée disponible
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Approvisionnements récents -->
<div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-6">
        Approvisionnements Récents
    </h3>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Date</th>
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Fournisseur</th>
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Référence</th>
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Nb Lots</th>
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Valeur</th>
                    <th class="pb-3 text-right text-sm font-medium text-gray-500 dark:text-gray-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($recentApprovisionnements as $approvisionnement)
                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                    <td class="py-4">
                        <div class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ \Carbon\Carbon::parse($approvisionnement->date_approvisionnement)->format('d/m/Y') }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($approvisionnement->created_at)->diffForHumans() }}
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
                            {{ $approvisionnement->lots->count() }} lots
                        </div>
                    </td>
                    <td class="py-4">
                        <div class="text-sm font-medium text-gray-800 dark:text-white/90">
                            €{{ number_format($approvisionnement->lots->sum(function($lot) { return $lot->quantite_initiale * $lot->prix_achat; }), 2) }}
                        </div>
                    </td>
                    <td class="py-4 text-right">
                        <a href="{{ route('approvisionnements.show', $approvisionnement) }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-300 dark:hover:bg-white/[0.05]">
                            Voir
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center">
                        <div class="text-gray-500 dark:text-gray-400">
                            Aucun approvisionnement récent
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($recentApprovisionnements->count() > 0)
    <div class="mt-6 text-center">
        <a href="{{ route('approvisionnements.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-300 dark:hover:bg-white/[0.05]">
            Voir tous les approvisionnements
            <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
    @endif
</div>
@endsection
