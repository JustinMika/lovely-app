<div class="space-y-6-">
    <!-- Messages flash -->
    @if (session()->has('success'))
        <div class="rounded-lg bg-green-50 p-4 dark:bg-green-900/20">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="rounded-lg bg-red-50 p-4 dark:bg-red-900/20">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800 dark:text-red-200">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Paramètres Généraux</h2>
            <p class="text-gray-600 dark:text-gray-400">Configurez les paramètres de base de votre application</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button type="button" wire:click="testMethod"
                class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-yellow-500 px-4 py-3 text-sm font-medium text-white ring-1 ring-yellow-300 transition hover:bg-yellow-600">
                Test Livewire
            </button>
            <button type="button" wire:click="resetToDefaults" wire:loading.attr="disabled"
                class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03] disabled:opacity-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove
                    wire:target="resetToDefaults">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                    </path>
                </svg>
                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24" wire:loading
                    wire:target="resetToDefaults">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <span wire:loading.remove wire:target="resetToDefaults">Restaurer par défaut</span>
                <span wire:loading wire:target="resetToDefaults">Restauration...</span>
            </button>
            <button type="button" wire:click="save" wire:loading.attr="disabled"
                class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 focus:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition focus:outline-hidden disabled:opacity-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" wire:loading.remove
                    wire:target="save">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24" wire:loading wire:target="save">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <span wire:loading.remove wire:target="save">Sauvegarder</span>
                <span wire:loading wire:target="save">Sauvegarde...</span>
            </button>
        </div>
    </div>

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Informations générales -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <h3 class="text-lg font-medium text-gray-800 dark:text-white">
                    Informations générales
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Configurez les informations de base de votre application
                </p>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="app_name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nom de l'application <span class="text-error-500">*</span>
                        </label>
                        <input type="text" wire:model="app_name" id="app_name"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('app_name') border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 @enderror"
                            placeholder="Ex: Lovely App">
                        @error('app_name')
                            <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="company_name"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nom de l'entreprise <span class="text-error-500">*</span>
                        </label>
                        <input type="text" wire:model="company_name" id="company_name"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('company_name') border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 @enderror"
                            placeholder="Ex: Mon Entreprise SARL">
                        @error('company_name')
                            <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="app_description"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Description de l'application
                    </label>
                    <textarea wire:model="app_description" id="app_description" rows="3"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('app_description') border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 @enderror"
                        placeholder="Description courte de votre application..."></textarea>
                    @error('app_description')
                        <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="company_address"
                        class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Adresse de l'entreprise
                    </label>
                    <textarea wire:model="company_address" id="company_address" rows="3"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('company_address') border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 @enderror"
                        placeholder="Adresse complète de votre entreprise..."></textarea>
                    @error('company_address')
                        <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="company_phone"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Téléphone
                        </label>
                        <input type="tel" wire:model="company_phone" id="company_phone"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('company_phone') border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 @enderror"
                            placeholder="Ex: +243 123 456 789">
                        @error('company_phone')
                            <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="company_email"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Email
                        </label>
                        <input type="email" wire:model="company_email" id="company_email"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('company_email') border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 @enderror"
                            placeholder="contact@monentreprise.com">
                        @error('company_email')
                            <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Logo de l'application -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <h3 class="text-lg font-medium text-gray-800 dark:text-white">
                    Logo de l'application
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Téléchargez le logo de votre entreprise (format: JPG, PNG - max 2MB)
                </p>
            </div>
            <div class="p-6">
                <div class="flex items-start gap-6">
                    <!-- Aperçu du logo actuel -->
                    <div class="flex-shrink-0">
                        @if ($current_logo)
                            <div class="relative">
                                <img src="{{ asset('storage/' . $current_logo) }}" alt="Logo actuel"
                                    class="h-20 w-20 object-contain rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                                <button type="button" wire:click="removeLogo"
                                    class="absolute -top-2 -right-2 h-6 w-6 rounded-full bg-red-500 text-white hover:bg-red-600 flex items-center justify-center">
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        @else
                            <div
                                class="h-20 w-20 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center bg-gray-50 dark:bg-gray-800">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Upload du nouveau logo -->
                    <div class="flex-1">
                        <label for="new_logo"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            {{ $current_logo ? 'Remplacer le logo' : 'Télécharger un logo' }}
                        </label>
                        <input type="file" wire:model="new_logo" id="new_logo" accept="image/*"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('new_logo') border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 @enderror">
                        @error('new_logo')
                            <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                        @enderror

                        @if ($new_logo)
                            <div class="mt-2">
                                <p class="text-sm text-green-600 dark:text-green-400">
                                    Nouveau logo sélectionné: {{ $new_logo->getClientOriginalName() }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Paramètres de devise -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <h3 class="text-lg font-medium text-gray-800 dark:text-white">
                    Paramètres de devise
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Configurez l'affichage des montants dans l'application
                </p>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="currency_symbol"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Symbole de devise <span class="text-error-500">*</span>
                        </label>
                        <input type="text" wire:model="currency_symbol" id="currency_symbol"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('currency_symbol') border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 @enderror"
                            placeholder="Ex: FCFA, €, $">
                        @error('currency_symbol')
                            <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="currency_position"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Position du symbole <span class="text-error-500">*</span>
                        </label>
                        <select wire:model="currency_position" id="currency_position"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('currency_position') border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 @enderror">
                            <option value="before">Avant le montant (FC100)</option>
                            <option value="after">Après le montant (100 FC)</option>
                        </select>
                        @error('currency_position')
                            <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Paramètres de stock -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <h3 class="text-lg font-medium text-gray-800 dark:text-white">
                    Paramètres de stock
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Configurez les alertes et seuils de stock
                </p>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="low_stock_threshold"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Seuil d'alerte stock faible <span class="text-error-500">*</span>
                        </label>
                        <input type="number" wire:model="low_stock_threshold" id="low_stock_threshold"
                            min="1"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('low_stock_threshold') border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 @enderror"
                            placeholder="Ex: 10">
                        @error('low_stock_threshold')
                            <p class="text-xs text-error-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="enable_stock_alerts"
                                class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded dark:border-gray-600 dark:bg-gray-700">
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-400">
                                Activer les alertes de stock
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

<script>
    // Debug Livewire
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Livewire loaded:', typeof Livewire !== 'undefined');

        // Écouter les événements Livewire
        if (typeof Livewire !== 'undefined') {
            Livewire.on('livewire:load', () => {
                console.log('Livewire component loaded');
            });

            Livewire.on('livewire:update', () => {
                console.log('Livewire component updated');
            });
        }
    });
</script>
