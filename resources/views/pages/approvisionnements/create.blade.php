@extends('layouts.app')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">
        Nouvel Approvisionnement
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
            <li class="font-medium text-gray-800 dark:text-white/90">Nouveau</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<form action="{{ route('approvisionnements.store') }}" method="POST" id="approvisionnementForm">
    @csrf
    
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
                       value="{{ old('date_approvisionnement', now()->format('Y-m-d')) }}"
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
                       value="{{ old('fournisseur') }}"
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
                       value="{{ old('reference_facture') }}"
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
                          class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Lots -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] mb-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Lots d'articles
            </h3>
            <button type="button" id="addLot" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Ajouter un lot
            </button>
        </div>

        <div id="lotsContainer">
            <!-- Template de lot sera ajouté ici par JavaScript -->
        </div>
    </div>

    <!-- Actions -->
    <div class="flex gap-4">
        <button type="submit" class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-6 py-3 text-sm font-medium text-white transition">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Créer l'approvisionnement
        </button>
        <a href="{{ route('approvisionnements.index') }}" class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-6 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
            Annuler
        </a>
    </div>
</form>

<!-- Template de lot (caché) -->
<template id="lotTemplate">
    <div class="lot-item border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-4">
        <div class="flex items-center justify-between mb-4">
            <h4 class="font-medium text-gray-800 dark:text-white/90">Lot #<span class="lot-number">1</span></h4>
            <button type="button" class="remove-lot text-red-600 hover:text-red-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
        
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Article *</label>
                <select name="lots[INDEX][article_id]" class="article-select dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" required>
                    <option value="">Sélectionner un article</option>
                    @foreach($articles as $article)
                    <option value="{{ $article->id }}">{{ $article->designation }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Ville *</label>
                <select name="lots[INDEX][ville_id]" class="ville-select dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" required>
                    <option value="">Sélectionner une ville</option>
                    @foreach($villes as $ville)
                    <option value="{{ $ville->id }}">{{ $ville->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Numéro de lot *</label>
                <input type="text" name="lots[INDEX][numero_lot]" placeholder="LOT-001" class="numero-lot dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Quantité *</label>
                <input type="number" name="lots[INDEX][quantite_initiale]" min="1" placeholder="100" class="quantite dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Unité *</label>
                <input type="text" name="lots[INDEX][unite]" placeholder="pièces" class="unite dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Prix d'achat *</label>
                <input type="number" name="lots[INDEX][prix_achat]" step="0.01" min="0" placeholder="10.00" class="prix-achat dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Prix de vente *</label>
                <input type="number" name="lots[INDEX][prix_vente]" step="0.01" min="0" placeholder="15.00" class="prix-vente dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required>
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Date d'arrivée</label>
                <input type="date" name="lots[INDEX][date_arrivee]" class="date-arrivee dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Date d'expiration</label>
                <input type="date" name="lots[INDEX][date_expiration]" class="date-expiration dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Seuil d'alerte *</label>
                <input type="number" name="lots[INDEX][seuil_alerte]" min="0" placeholder="10" class="seuil-alerte dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required>
            </div>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let lotIndex = 0;
    const lotsContainer = document.getElementById('lotsContainer');
    const addLotBtn = document.getElementById('addLot');
    const lotTemplate = document.getElementById('lotTemplate');

    // Ajouter le premier lot automatiquement
    addLot();

    addLotBtn.addEventListener('click', addLot);

    function addLot() {
        const template = lotTemplate.content.cloneNode(true);
        const lotItem = template.querySelector('.lot-item');
        
        // Remplacer INDEX par l'index actuel
        lotItem.innerHTML = lotItem.innerHTML.replace(/INDEX/g, lotIndex);
        
        // Mettre à jour le numéro de lot affiché
        lotItem.querySelector('.lot-number').textContent = lotIndex + 1;
        
        // Ajouter l'événement de suppression
        const removeBtn = lotItem.querySelector('.remove-lot');
        removeBtn.addEventListener('click', function() {
            if (lotsContainer.children.length > 1) {
                lotItem.remove();
                updateLotNumbers();
            }
        });

        // Calculer automatiquement la marge
        const prixAchatInput = lotItem.querySelector('.prix-achat');
        const prixVenteInput = lotItem.querySelector('.prix-vente');
        
        prixAchatInput.addEventListener('input', function() {
            if (this.value && !prixVenteInput.value) {
                const prixAchat = parseFloat(this.value);
                const prixVente = (prixAchat * 1.3).toFixed(2); // 30% de marge par défaut
                prixVenteInput.value = prixVente;
            }
        });

        lotsContainer.appendChild(lotItem);
        lotIndex++;
    }

    function updateLotNumbers() {
        const lots = lotsContainer.querySelectorAll('.lot-item');
        lots.forEach((lot, index) => {
            lot.querySelector('.lot-number').textContent = index + 1;
        });
    }
});
</script>
@endsection
