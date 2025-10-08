@extends('layouts.app')

@section('title', 'Modifier le Lot')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb -->
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                    <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L9 5.414V17a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1V5.414l2.293 2.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    Tableau de bord
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <a href="{{ route('lots.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Lots</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Modifier</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Modifier le Lot</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Modifiez les informations du lot {{ $lot->numero_lot }}</p>
        </div>
    </div>

    <!-- Form -->
    <div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <form action="{{ route('lots.update', $lot->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Approvisionnement -->
                <div>
                    <label for="approvisionnement_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Approvisionnement <span class="text-error-500">*</span>
                    </label>
                    <select name="approvisionnement_id" id="approvisionnement_id" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                        <option value="">Sélectionner un approvisionnement</option>
                        @foreach($approvisionnements as $approvisionnement)
                            <option value="{{ $approvisionnement->id }}" {{ old('approvisionnement_id', $lot->approvisionnement_id) == $approvisionnement->id ? 'selected' : '' }}>
                                {{ $approvisionnement->fournisseur }} - {{ $approvisionnement->date->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                    @error('approvisionnement_id')
                        <p class="mt-1 text-sm text-theme-xs text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Article -->
                <div>
                    <label for="article_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Article <span class="text-error-500">*</span>
                    </label>
                    <select name="article_id" id="article_id" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                        <option value="">Sélectionner un article</option>
                        @foreach($articles as $article)
                            <option value="{{ $article->id }}" {{ old('article_id', $lot->article_id) == $article->id ? 'selected' : '' }}>
                                {{ $article->designation }}
                            </option>
                        @endforeach
                    </select>
                    @error('article_id')
                        <p class="mt-1 text-sm text-theme-xs text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ville -->
                <div>
                    <label for="ville_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Ville <span class="text-error-500">*</span>
                    </label>
                    <select name="ville_id" id="ville_id" required
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                        <option value="">Sélectionner une ville</option>
                        @foreach($villes as $ville)
                            <option value="{{ $ville->id }}" {{ old('ville_id', $lot->ville_id) == $ville->id ? 'selected' : '' }}>
                                {{ $ville->nom }}
                            </option>
                        @endforeach
                    </select>
                    @error('ville_id')
                        <p class="mt-1 text-sm text-theme-xs text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Numéro de Lot -->
                <div>
                    <label for="numero_lot" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Numéro de Lot <span class="text-error-500">*</span>
                    </label>
                    <input type="text" name="numero_lot" id="numero_lot" required
                        value="{{ old('numero_lot', $lot->numero_lot) }}"
                        placeholder="Ex: LOT-2024-001"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                    @error('numero_lot')
                        <p class="mt-1 text-sm text-theme-xs text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quantité Initiale -->
                <div>
                    <label for="quantite_initiale" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Quantité Initiale <span class="text-error-500">*</span>
                    </label>
                    <input type="number" name="quantite_initiale" id="quantite_initiale" required min="1"
                        value="{{ old('quantite_initiale', $lot->quantite_initiale) }}"
                        placeholder="Ex: 100"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                    @error('quantite_initiale')
                        <p class="mt-1 text-sm text-theme-xs text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prix d'Achat -->
                <div>
                    <label for="prix_achat" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Prix d'Achat Unitaire ({{ app_setting('currency_symbol', 'FC') }}) <span class="text-error-500">*</span>
                    </label>
                    <input type="number" name="prix_achat" id="prix_achat" required min="0" step="0.01"
                        value="{{ old('prix_achat', $lot->prix_achat) }}"
                        placeholder="Ex: 1500"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                    @error('prix_achat')
                        <p class="mt-1 text-sm text-theme-xs text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prix de Vente -->
                <div>
                    <label for="prix_vente" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Prix de Vente Unitaire ({{ app_setting('currency_symbol', 'FC') }}) <span class="text-error-500">*</span>
                    </label>
                    <input type="number" name="prix_vente" id="prix_vente" required min="0" step="0.01"
                        value="{{ old('prix_vente', $lot->prix_vente) }}"
                        placeholder="Ex: 2000"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                    @error('prix_vente')
                        <p class="mt-1 text-sm text-theme-xs text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Seuil d'Alerte -->
                <div>
                    <label for="seuil_alerte" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Seuil d'Alerte <span class="text-error-500">*</span>
                    </label>
                    <input type="number" name="seuil_alerte" id="seuil_alerte" required min="0"
                        value="{{ old('seuil_alerte', $lot->seuil_alerte ?? 10) }}"
                        placeholder="Ex: 10"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                    @error('seuil_alerte')
                        <p class="mt-1 text-sm text-theme-xs text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date d'Arrivée -->
                <div>
                    <label for="date_arrivee" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Date d'Arrivée <span class="text-error-500">*</span>
                    </label>
                    <input type="date" name="date_arrivee" id="date_arrivee" required
                        value="{{ old('date_arrivee', $lot->date_arrivee->format('Y-m-d')) }}"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                    @error('date_arrivee')
                        <p class="mt-1 text-sm text-theme-xs text-error-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date d'Expiration -->
                <div>
                    <label for="date_expiration" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Date d'Expiration
                    </label>
                    <input type="date" name="date_expiration" id="date_expiration"
                        value="{{ old('date_expiration', $lot->date_expiration?->format('Y-m-d')) }}"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                    @error('date_expiration')
                        <p class="mt-1 text-sm text-theme-xs text-error-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Current Stock Info -->
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <div class="rounded-lg bg-blue-100 p-2 dark:bg-blue-800/50">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-blue-800 dark:text-blue-200">Information Stock Actuel</h4>
                        <p class="text-sm text-blue-600 dark:text-blue-300 mt-1">
                            Quantité restante actuelle: <strong>{{ $lot->quantite_restante }}</strong> unités<br>
                            Quantité vendue: <strong>{{ $lot->quantite_initiale - $lot->quantite_restante }}</strong> unités
                        </p>
                        <p class="text-xs text-blue-500 dark:text-blue-400 mt-2">
                            <strong>Note:</strong> La modification de la quantité initiale n'affectera pas la quantité restante actuelle.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <button type="submit" class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Mettre à jour le lot
                </button>
                
                <a href="{{ route('lots.index') }}" class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Auto-calculate margin when prices change
    document.addEventListener('DOMContentLoaded', function() {
        const prixAchat = document.getElementById('prix_achat');
        const prixVente = document.getElementById('prix_vente');
        
        function updateMarginInfo() {
            const achat = parseFloat(prixAchat.value) || 0;
            const vente = parseFloat(prixVente.value) || 0;
            
            if (achat > 0 && vente > 0) {
                const marge = vente - achat;
                const pourcentage = ((marge / achat) * 100).toFixed(1);
                
                // You can add margin display logic here if needed
                console.log(`Marge: ${marge} {{ app_setting('currency_symbol', 'FC') }} (${pourcentage}%)`);
            }
        }
        
        prixAchat.addEventListener('input', updateMarginInfo);
        prixVente.addEventListener('input', updateMarginInfo);
    });
</script>
@endsection
