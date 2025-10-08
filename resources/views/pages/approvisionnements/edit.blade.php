@extends('layouts.app')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">
        Modifier l'Approvisionnement
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
            <li>
                <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300" 
                   href="{{ route('approvisionnements.show', $approvisionnement) }}">Détails /</a>
            </li>
            <li class="font-medium text-gray-800 dark:text-white/90">Modifier</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<form action="{{ route('approvisionnements.update', $approvisionnement) }}" method="POST">
    @csrf
    @method('PUT')
    
    <!-- Informations générales -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-6">
            Informations générales
        </h3>
        
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label for="date_approvisionnement" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Date d'approvisionnement *
                </label>
                <input type="date" id="date_approvisionnement" name="date_approvisionnement" 
                       value="{{ old('date_approvisionnement', $approvisionnement->date_approvisionnement) }}"
                       class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('date_approvisionnement') border-red-500 @enderror" 
                       required>
                @error('date_approvisionnement')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="fournisseur" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Fournisseur *
                </label>
                <input type="text" id="fournisseur" name="fournisseur" 
                       value="{{ old('fournisseur', $approvisionnement->fournisseur) }}"
                       placeholder="Nom du fournisseur"
                       class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('fournisseur') border-red-500 @enderror" 
                       required>
                @error('fournisseur')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="reference_facture" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Référence facture
                </label>
                <input type="text" id="reference_facture" name="reference_facture" 
                       value="{{ old('reference_facture', $approvisionnement->reference_facture) }}"
                       placeholder="Numéro de facture"
                       class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('reference_facture') border-red-500 @enderror">
                @error('reference_facture')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="notes" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Notes
                </label>
                <textarea id="notes" name="notes" rows="3"
                          placeholder="Notes sur l'approvisionnement"
                          class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('notes') border-red-500 @enderror">{{ old('notes', $approvisionnement->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Lots associés (lecture seule) -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-6">
            Lots associés
        </h3>
        
        <div class="bg-blue-50 dark:bg-blue-500/15 border border-blue-200 dark:border-blue-500/30 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <svg class="h-5 w-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h4 class="text-sm font-medium text-blue-800 dark:text-blue-300">Information</h4>
                    <p class="text-sm text-blue-700 dark:text-blue-400 mt-1">
                        Les lots ne peuvent pas être modifiés depuis cette page. Utilisez la page de détail de chaque lot pour les modifier individuellement.
                    </p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Article</th>
                        <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Lot</th>
                        <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Quantité</th>
                        <th class="pb-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Prix</th>
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
                        </td>
                        <td class="py-4">
                            <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 dark:bg-blue-500/15 dark:text-blue-400">
                                {{ $lot->numero_lot }}
                            </span>
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
                                Achat: {{ currency($lot->prix_achat, 2) }}
                            </div>
                            <div class="text-sm text-gray-800 dark:text-white/90">
                                Vente: {{ currency($lot->prix_vente, 2) }}
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
                            @else
                            <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 dark:bg-green-500/15 dark:text-green-400">
                                Stock OK
                            </span>
                            @endif
                        </td>
                        <td class="py-4 text-right">
                            <a href="{{ route('lots.edit', $lot) }}" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-700">
                                Modifier le lot
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex gap-4">
        <button type="submit" class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-6 py-3 text-sm font-medium text-white transition">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Mettre à jour
        </button>
        <a href="{{ route('approvisionnements.show', $approvisionnement) }}" class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-6 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
            Annuler
        </a>
    </div>
</form>
@endsection
