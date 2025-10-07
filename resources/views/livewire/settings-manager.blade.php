<div class="space-y-6">
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
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">🔧 Settings Manager (TEST)</h2>
            <p class="text-gray-600 dark:text-gray-400">Composant de test pour diagnostiquer Livewire</p>
        </div>
    </div>

    <!-- Formulaire de test -->
    <form wire:submit.prevent="save">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-800 dark:text-white">🔍 Test des paramètres de base</h3>
                <div class="flex flex-wrap gap-2">
                    <button type="button" wire:click="testButton"
                        class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-yellow-500 px-4 py-3 text-sm font-medium text-white ring-1 ring-yellow-300 transition hover:bg-yellow-600">
                        🧪 TEST LIVEWIRE
                    </button>
                    <button type="button" wire:click="save"
                        class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 focus:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition focus:outline-hidden">
                        💾 SAUVEGARDER
                    </button>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="app_name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Nom de l'application <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="app_name" id="app_name"
                        class="h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        placeholder="Ex: Mon Application">
                    @error('app_name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="company_name" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Nom de l'entreprise
                    </label>
                    <input type="text" wire:model="company_name" id="company_name"
                        class="h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        placeholder="Ex: Mon Entreprise">
                    @error('company_name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="currency_symbol"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Symbole de devise <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="currency_symbol" id="currency_symbol"
                            class="h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            placeholder="Ex: FCFA">
                        @error('currency_symbol')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="low_stock_threshold"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Seuil d'alerte stock <span class="text-red-500">*</span>
                        </label>
                        <input type="number" wire:model="low_stock_threshold" id="low_stock_threshold" min="1"
                            class="h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            placeholder="Ex: 10">
                        @error('low_stock_threshold')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
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
    </form>
</div>
