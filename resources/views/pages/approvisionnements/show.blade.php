@extends('layouts.app')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">
        Détails de l'Approvisionnement
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
            <li class="font-medium text-gray-800 dark:text-white/90">Détails</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<!-- Informations générales -->
<div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] mb-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            Informations générales
        </h3>
        <div class="flex gap-3">
            <a href="{{ route('approvisionnements.edit', $approvisionnement) }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-theme-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-300 dark:hover:bg-white/[0.05]">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Modifier
            </a>
            <form action="{{ route('approvisionnements.destroy', $approvisionnement) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet approvisionnement ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-2 text-theme-sm font-medium text-white hover:bg-red-700">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Date d'approvisionnement
            </label>
            <div class="text-sm font-medium text-gray-800 dark:text-white/90">
                {{ \Carbon\Carbon::parse($approvisionnement->date_approvisionnement)->format('d/m/Y') }}
            </div>
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Fournisseur
            </label>
            <div class="text-sm font-medium text-gray-800 dark:text-white/90">
                {{ $approvisionnement->fournisseur }}
            </div>
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Référence facture
            </label>
            <div class="text-sm font-medium text-gray-800 dark:text-white/90">
                @if($approvisionnement->reference_facture)
                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 dark:bg-blue-500/15 dark:text-blue-400">
                        {{ $approvisionnement->reference_facture }}
                    </span>
                @else
                    <span class="text-gray-500 dark:text-gray-400">-</span>
                @endif
            </div>
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Responsable
            </label>
            <div class="text-sm font-medium text-gray-800 dark:text-white/90">
                {{ $approvisionnement->user->name }}
            </div>
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Valeur totale
            </label>
            <div class="text-sm font-medium text-gray-800 dark:text-white/90">
                €{{ number_format($totalValue, 2) }}
            </div>
        </div>

        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Quantité vendue
            </label>
            <div class="text-sm font-medium text-gray-800 dark:text-white/90">
                {{ number_format($totalSold) }} articles
            </div>
        </div>

        @if($approvisionnement->notes)
        <div class="md:col-span-2 lg:col-span-3">
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Notes
            </label>
            <div class="text-sm text-gray-800 dark:text-white/90 bg-gray-50 dark:bg-gray-800/50 rounded-lg p-3">
                {{ $approvisionnement->notes }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Lots associés -->
<div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-6">
        Lots associés ({{ $approvisionnement->lots->count() }})
    </h3>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Article</th>
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Lot</th>
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Ville</th>
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Quantité</th>
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Prix</th>
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Valeur</th>
                    <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Statut</th>
                    <th class="pb-3 text-right text-sm font-medium text-gray-500 dark:text-gray-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($approvisionnement->lots as $lot)
                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                    <td class="py-4">
                        <div class="font-medium text-gray-800 dark:text-white/90">
                            {{ $lot->article->designation }}
                        </div>
                        @if($lot->article->description)
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ Str::limit($lot->article->description, 50) }}
                        </div>
                        @endif
                    </td>
                    <td class="py-4">
                        <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 dark:bg-blue-500/15 dark:text-blue-400">
                            {{ $lot->numero_lot }}
                        </span>
                    </td>
                    <td class="py-4">
                        <div class="text-sm text-gray-800 dark:text-white/90">
                            {{ $lot->ville->nom }}
                        </div>
                    </td>
                    <td class="py-4">
                        <div class="text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ number_format($lot->quantite_restante) }} / {{ number_format($lot->quantite_initiale) }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $lot->unite }}
                        </div>
                    </td>
                    <td class="py-4">
                        <div class="text-sm text-gray-800 dark:text-white/90">
                            Achat: €{{ number_format($lot->prix_achat, 2) }}
                        </div>
                        <div class="text-sm text-gray-800 dark:text-white/90">
                            Vente: €{{ number_format($lot->prix_vente, 2) }}
                        </div>
                    </td>
                    <td class="py-4">
                        <div class="text-sm font-medium text-gray-800 dark:text-white/90">
                            €{{ number_format($lot->quantite_initiale * $lot->prix_achat, 2) }}
                        </div>
                    </td>
                    <td class="py-4">
                        @if($lot->quantite_restante <= 0)
                        <span class="inline-flex items-center rounded-full bg-gray-50 px-2 py-1 text-xs font-medium text-gray-700 dark:bg-gray-500/15 dark:text-gray-400">
                            Épuisé
                        </span>
                        @elseif($lot->quantite_restante <= $lot->seuil_alerte)
                        <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 dark:bg-red-500/15 dark:text-red-400">
                            Stock faible
                        </span>
                        @elseif($lot->quantite_restante <= ($lot->quantite_initiale * 0.3))
                        <span class="inline-flex items-center rounded-full bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400">
                            Stock moyen
                        </span>
                        @else
                        <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 dark:bg-green-500/15 dark:text-green-400">
                            Stock OK
                        </span>
                        @endif

                        @if($lot->date_expiration && \Carbon\Carbon::parse($lot->date_expiration)->isPast())
                        <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 dark:bg-red-500/15 dark:text-red-400 mt-1">
                            Expiré
                        </span>
                        @elseif($lot->date_expiration && \Carbon\Carbon::parse($lot->date_expiration)->diffInDays() <= 30)
                        <span class="inline-flex items-center rounded-full bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400 mt-1">
                            Expire bientôt
                        </span>
                        @endif
                    </td>
                    <td class="py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('lots.show', $lot) }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-300 dark:hover:bg-white/[0.05]">
                                Voir
                            </a>
                            <a href="{{ route('lots.edit', $lot) }}" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-700">
                                Modifier
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($approvisionnement->lots->where('ligneVentes')->count() > 0)
    <!-- Historique des ventes -->
    <div class="mt-8">
        <h4 class="text-md font-semibold text-gray-800 dark:text-white/90 mb-4">
            Historique des ventes
        </h4>
        
        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-800 dark:text-white/90">
                        {{ number_format($totalSold) }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Articles vendus
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-800 dark:text-white/90">
                        €{{ number_format($approvisionnement->lots->sum(function($lot) { 
                            return ($lot->quantite_initiale - $lot->quantite_restante) * $lot->prix_vente; 
                        }), 2) }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Chiffre d'affaires
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                        €{{ number_format($approvisionnement->lots->sum(function($lot) { 
                            $sold = $lot->quantite_initiale - $lot->quantite_restante;
                            return $sold * ($lot->prix_vente - $lot->prix_achat); 
                        }), 2) }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Bénéfice brut
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
