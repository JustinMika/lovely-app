@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
        <!-- Breadcrumb Start -->
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Créer un Lot
            </h2>
            <nav>
                <ol class="flex items-center gap-2">
                    <li>
                        <a class="font-medium text-gray-600 hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-400"
                            href="{{ route('dashboard') }}">Tableau de bord</a>
                    </li>
                    <li class="text-gray-400 dark:text-gray-600">/</li>
                    <li>
                        <a class="font-medium text-gray-600 hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-400"
                            href="{{ route('lots.index') }}">Lots</a>
                    </li>
                    <li class="text-gray-400 dark:text-gray-600">/</li>
                    <li class="font-medium text-brand-500 dark:text-brand-400">Créer un Lot</li>
                </ol>
            </nav>
        </div>
        <!-- Breadcrumb End -->

        <!-- Content Area -->
        <div
            class="rounded-2xl border border-gray-200 bg-white p-4 shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03] sm:p-6">
            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-4 dark:border-gray-800">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        Informations du Lot
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Créez un nouveau lot de stock pour un article.
                    </p>
                </div>

                <form action="{{ route('lots.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Article -->
                        <div>
                            <label for="article_id"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Article <span class="text-error-500">*</span>
                            </label>
                            <select id="article_id" name="article_id" required
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                <option value="">Sélectionner un article</option>
                                @foreach (\App\Models\Article::where('actif', true)->orderBy('designation')->get() as $article)
                                    <option value="{{ $article->id }}"
                                        {{ old('article_id') == $article->id ? 'selected' : '' }}>
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
                            <select id="ville_id" name="ville_id" required
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                <option value="">Sélectionner une ville</option>
                                @foreach (\App\Models\Ville::orderBy('nom')->get() as $ville)
                                    <option value="{{ $ville->id }}"
                                        {{ old('ville_id') == $ville->id ? 'selected' : '' }}>
                                        {{ $ville->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ville_id')
                                <p class="mt-1 text-sm text-theme-xs text-error-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Numéro de lot -->
                        <div>
                            <label for="numero_lot"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Numéro de Lot <span class="text-error-500">*</span>
                            </label>
                            <input type="text" id="numero_lot" name="numero_lot" value="{{ old('numero_lot') }}"
                                required
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                placeholder="Ex: LOT-2024-001">
                            @error('numero_lot')
                                <p class="mt-1 text-sm text-theme-xs text-error-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quantité initiale -->
                        <div>
                            <label for="quantite_initiale"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Quantité Initiale <span class="text-error-500">*</span>
                            </label>
                            <input type="number" id="quantite_initiale" name="quantite_initiale"
                                value="{{ old('quantite_initiale') }}" required min="1"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                placeholder="Ex: 100">
                            @error('quantite_initiale')
                                <p class="mt-1 text-sm text-theme-xs text-error-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Prix d'achat -->
                        <div>
                            <label for="prix_achat"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Prix d'Achat <span class="text-error-500">*</span>
                            </label>
                            <input type="number" id="prix_achat" name="prix_achat" value="{{ old('prix_achat') }}"
                                required min="0" step="0.01"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                placeholder="Ex: 15.50">
                            @error('prix_achat')
                                <p class="mt-1 text-sm text-theme-xs text-error-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Prix de vente -->
                        <div>
                            <label for="prix_vente"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Prix de Vente <span class="text-error-500">*</span>
                            </label>
                            <input type="number" id="prix_vente" name="prix_vente" value="{{ old('prix_vente') }}"
                                required min="0" step="0.01"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                placeholder="Ex: 25.00">
                            @error('prix_vente')
                                <p class="mt-1 text-sm text-theme-xs text-error-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Seuil d'alerte -->
                        <div>
                            <label for="seuil_alerte"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Seuil d'Alerte <span class="text-error-500">*</span>
                            </label>
                            <input type="number" id="seuil_alerte" name="seuil_alerte"
                                value="{{ old('seuil_alerte', 10) }}" required min="0"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                placeholder="Ex: 10">
                            @error('seuil_alerte')
                                <p class="mt-1 text-sm text-theme-xs text-error-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date d'arrivée -->
                        <div>
                            <label for="date_arrivee"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Date d'Arrivée <span class="text-error-500">*</span>
                            </label>
                            <input type="date" id="date_arrivee" name="date_arrivee"
                                value="{{ old('date_arrivee', date('Y-m-d')) }}" required
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                            @error('date_arrivee')
                                <p class="mt-1 text-sm text-theme-xs text-error-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date d'expiration -->
                        <div>
                            <label for="date_expiration"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Date d'Expiration
                            </label>
                            <input type="date" id="date_expiration" name="date_expiration"
                                value="{{ old('date_expiration') }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                            @error('date_expiration')
                                <p class="mt-1 text-sm text-theme-xs text-error-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Approvisionnement (optionnel) -->
                        <div>
                            <label for="approvisionnement_id"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Approvisionnement <span class="text-error-500">*</span>
                            </label>
                            <select id="approvisionnement_id" name="approvisionnement_id" required
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                                <option value="">Sélectionner un approvisionnement</option>
                                @foreach (\App\Models\Approvisionnement::orderBy('created_at', 'desc')->limit(20)->get() as $appro)
                                    <option value="{{ $appro->id }}"
                                        {{ old('approvisionnement_id') == $appro->id ? 'selected' : '' }}>
                                        Appro #{{ $appro->id }} - {{ $appro->created_at->format('d/m/Y') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('approvisionnement_id')
                                <p class="mt-1 text-sm text-theme-xs text-error-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6 dark:border-gray-800">
                        <a href="{{ route('lots.index') }}"
                            class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                            Annuler
                        </a>
                        <button type="submit"
                            class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Créer le Lot
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Auto-remplir la quantité restante avec la quantité initiale
        document.getElementById('quantite_initiale').addEventListener('input', function() {
            // La quantité restante sera automatiquement égale à la quantité initiale lors de la création
        });

        // Calculer automatiquement un prix de vente suggéré (marge de 30%)
        document.getElementById('prix_achat').addEventListener('input', function() {
            const prixAchat = parseFloat(this.value);
            if (prixAchat > 0) {
                const prixVenteSuggere = (prixAchat * 1.3).toFixed(2);
                const prixVenteInput = document.getElementById('prix_vente');
                if (!prixVenteInput.value) {
                    prixVenteInput.value = prixVenteSuggere;
                }
            }
        });
    </script>
@endsection
