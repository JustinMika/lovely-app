<div class="space-y-6">
    @if ($currentView === 'list')
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestion des Ventes</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Gérez vos ventes, factures et paiements
                </p>
            </div>
            <button wire:click="switchToCreate"
                class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nouvelle Vente
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Revenue Today -->
            <div
                class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/20">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Revenus aujourd'hui</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($metrics['revenue_today'], 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Sales -->
            <div
                class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/20">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total ventes</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($metrics['total_sales'], 0, ',', ' ') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue -->
            <div
                class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-900/20">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Revenus ce mois</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($metrics['revenue_this_month'], 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                </div>
            </div>

            <!-- Average Order -->
            <div
                class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="flex items-center">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-lg bg-orange-100 dark:bg-orange-900/20">
                        <svg class="h-6 w-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Panier moyen</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($metrics['average_order'], 0, ',', ' ') }} FCFA
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Table -->
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-4 sm:p-6 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ventes récentes</h3>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher..."
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-64 rounded-lg border border-gray-300 bg-transparent pl-10 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                        <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('id')">
                                <div class="flex items-center gap-1">
                                    N° Vente
                                    @if ($sortField === 'id')
                                        <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3">Client</th>
                            <th class="px-6 py-3 cursor-pointer" wire:click="sortBy('created_at')">
                                <div class="flex items-center gap-1">
                                    Date
                                    @if ($sortField === 'created_at')
                                        <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-right cursor-pointer" wire:click="sortBy('total')">
                                <div class="flex items-center gap-1 justify-end">
                                    Total
                                    @if ($sortField === 'total')
                                        <svg class="w-3 h-3 {{ $sortDirection === 'asc' ? '' : 'rotate-180' }}"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" />
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-3 text-right">Payé</th>
                            <th class="px-6 py-3">Statut</th>
                            <th class="px-6 py-3">Vendeur</th>
                            <th class="px-6 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700"
                                wire:key="sale-{{ $sale->id }}">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-gray-900 dark:text-white font-medium">{{ $sale->client->nom }}
                                        {{ $sale->client->prenom }}</div>
                                    @if ($sale->client->telephone)
                                        <div class="text-gray-500 dark:text-gray-400 text-xs">
                                            {{ $sale->client->telephone }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                    {{ $sale->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-right font-medium text-gray-900 dark:text-white">
                                    {{ number_format($sale->total, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="px-6 py-4 text-right text-green-600 dark:text-green-400">
                                    {{ number_format($sale->montant_paye, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="px-6 py-4">
                                    @if ($sale->restant_a_payer <= 0)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                            Payé
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                            Impayé
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                    {{ $sale->utilisateur->name }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('sales.show', $sale) }}"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                            title="Voir">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        <button wire:click="switchToEdit({{ $sale->id }})"
                                            class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300"
                                            title="Modifier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <a href="{{ route('sales.invoice', $sale) }}"
                                            class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                            title="Facture">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </a>
                                        <button wire:click="confirmDelete({{ $sale->id }})"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                            title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Aucune vente
                                        </h3>
                                        <p class="text-gray-500 dark:text-gray-400 mb-4">Commencez par créer votre
                                            première
                                            vente.</p>
                                        <button wire:click="switchToCreate"
                                            class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-white transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Nouvelle Vente
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($sales->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">
                    {{ $sales->links() }}
                </div>
            @endif
        </div>
    @else
        <!-- Form View -->
        <div class="space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $isEditing ? 'Modifier la vente #' . $editingSaleId : 'Nouvelle Vente' }}
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $isEditing ? 'Modifiez les informations de cette vente' : 'Créez une nouvelle vente' }}
                    </p>
                </div>
                <button wire:click="switchToList"
                    class="bg-gray-500 shadow-theme-xs hover:bg-gray-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour à la liste
                </button>
            </div>

            <form wire:submit="saveSale" class="space-y-8">
                {{ $this->form }}
            </form>
        </div>

        <!-- Client Modal -->
        @if ($showClientModal)
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 w-full max-w-md dark:bg-gray-800">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Nouveau Client</h3>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nom
                                    *</label>
                                <input type="text" wire:model="newClientData.nom"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                @error('newClientData.nom')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Prénom
                                    *</label>
                                <input type="text" wire:model="newClientData.prenom"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                @error('newClientData.prenom')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Téléphone</label>
                            <input type="text" wire:model="newClientData.telephone"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('newClientData.telephone')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input type="email" wire:model="newClientData.email"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @error('newClientData.email')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Adresse</label>
                            <textarea wire:model="newClientData.adresse" rows="3"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea>
                            @error('newClientData.adresse')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 mt-6">
                        <button type="button" wire:click="$set('showClientModal', false)"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition">
                            Annuler
                        </button>
                        <button type="button" wire:click="createClient"
                            class="bg-brand-500 hover:bg-brand-600 text-white px-4 py-2 rounded-lg font-medium transition">
                            Créer
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <!-- Loading indicator -->
    <div wire:loading class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 flex items-center gap-3">
            <svg class="animate-spin h-5 w-5 text-brand-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                    stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span class="text-gray-700">Chargement...</span>
        </div>
    </div>

    @script
        <script>
            // Événements SweetAlert pour Filament Forms
            document.addEventListener('livewire:init', () => {
                Livewire.on('confirm-delete', () => {
                    Swal.fire({
                        title: 'Êtes-vous sûr ?',
                        text: "Cette action supprimera définitivement la vente et restaurera le stock.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Oui, supprimer',
                        cancelButtonText: 'Annuler'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Livewire.dispatch('delete-confirmed');
                        }
                    });
                });

                Livewire.on('sale-deleted', (event) => {
                    Swal.fire({
                        title: event.type === 'success' ? 'Supprimé !' : 'Erreur !',
                        text: event.message,
                        icon: event.type,
                        timer: 3000,
                        showConfirmButton: false
                    });
                });

                Livewire.on('sale-saved', (event) => {
                    Swal.fire({
                        title: 'Succès !',
                        text: event.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                });

                Livewire.on('show-error', (event) => {
                    Swal.fire({
                        title: 'Erreur !',
                        text: event.message,
                        icon: 'error',
                        confirmButtonColor: '#ef4444'
                    });
                });

                Livewire.on('client-created', (event) => {
                    Swal.fire({
                        title: 'Succès !',
                        text: event.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
            });
        </script>
    @endscript
</div>
