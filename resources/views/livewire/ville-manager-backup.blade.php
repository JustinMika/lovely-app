<div>
    <!-- En-tête avec recherche et actions -->
    <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03] mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">🏙️ Gestion des Villes</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">Gérez les villes et paramètres de localisation</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Recherche -->
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Rechercher une ville..."
                        class="w-full sm:w-64 rounded-lg border border-gray-300 bg-white px-4 py-2 pl-10 text-sm focus:border-brand-500 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex gap-3">
                    <button wire:click="exportPdf"
                        class="flex items-center justify-center rounded-lg bg-red-500 px-4 py-3 text-sm font-medium text-white shadow-theme-xs hover:bg-red-600 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Export PDF
                    </button>

                    <button wire:click="openModal"
                        class="flex items-center justify-center rounded-lg bg-brand-500 px-4 py-3 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nouvelle Ville
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des villes -->
    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-6 pb-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <select wire:model.live="perPage"
                        class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm focus:border-brand-500 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="10">10 par page</option>
                        <option value="25">25 par page</option>
                        <option value="50">50 par page</option>
                    </select>
                </div>

                @if ($villes->total() > 0)
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Affichage de {{ $villes->firstItem() }} à {{ $villes->lastItem() }} sur
                        {{ $villes->total() }} résultats
                    </div>
                @endif
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sortBy('nom')"
                                class="flex items-center gap-2 font-medium text-gray-900 dark:text-white hover:text-brand-500 transition-colors">
                                Nom de la Ville
                                @if ($sortField === 'nom')
                                    <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <span class="font-medium text-gray-900 dark:text-white">Nombre de Lots</span>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sortBy('created_at')"
                                class="flex items-center gap-2 font-medium text-gray-900 dark:text-white hover:text-brand-500 transition-colors">
                                Date de Création
                                @if ($sortField === 'created_at')
                                    <svg class="w-4 h-4 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-right">
                            <span class="font-medium text-gray-900 dark:text-white">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($villes as $ville)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    🏙️ {{ $ville->nom }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full bg-brand-500/10 px-2.5 py-0.5 text-xs font-medium text-brand-500 dark:bg-brand-500/20 dark:text-brand-400">
                                    {{ $ville->lots_count }} lot(s)
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $ville->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="showDetail({{ $ville->id }})"
                                        class="rounded-lg p-2 text-brand-500 hover:bg-brand-500/10 transition-colors duration-200"
                                        title="Voir les détails">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button wire:click="edit({{ $ville->id }})"
                                        class="rounded-lg p-2 text-indigo-500 hover:bg-indigo-500/10 transition-colors duration-200"
                                        title="Modifier">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $ville->id }})"
                                        class="rounded-lg p-2 text-red-500 hover:bg-red-500/10 transition-colors duration-200"
                                        title="Supprimer">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="mb-4 h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                    <h3 class="mb-2 text-lg font-medium text-gray-900 dark:text-white">
                                        Aucune ville trouvée
                                    </h3>
                                    <p class="mb-4 text-gray-500 dark:text-gray-400">
                                        @if ($search)
                                            Aucune ville ne correspond à votre recherche "{{ $search }}".
                                        @else
                                            Commencez par ajouter votre première ville.
                                        @endif
                                    </p>
                                    @if ($search)
                                        <button wire:click="$set('search', '')"
                                            class="text-brand-500 hover:text-brand-600 font-medium">
                                            Effacer la recherche
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($villes->hasPages())
            <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-700">
                {{ $villes->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Création/Édition basé sur les templates -->
    @if ($showModal)
        <div class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto modal z-99999">
            <div class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]" wire:click="closeModal"></div>
            <div class="relative w-full max-w-[600px] rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-10">
                <!-- Bouton fermer -->
                <button wire:click="closeModal"
                    class="absolute right-3 top-3 z-999 flex h-9.5 w-9.5 items-center justify-center rounded-full bg-gray-100 text-gray-400 transition-colors hover:bg-gray-200 hover:text-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white sm:right-6 sm:top-6 sm:h-11 sm:w-11">
                    <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M6.04289 16.5413C5.65237 16.9318 5.65237 17.565 6.04289 17.9555C6.43342 18.346 7.06658 18.346 7.45711 17.9555L11.9987 13.4139L16.5408 17.956C16.9313 18.3466 17.5645 18.3466 17.955 17.956C18.3455 17.5655 18.3455 16.9323 17.955 16.5418L13.4129 11.9997L17.955 7.4576C18.3455 7.06707 18.3455 6.43391 17.955 6.04338C17.5645 5.65286 16.9313 5.65286 16.5408 6.04338L11.9987 10.5855L7.45711 6.0439C7.06658 5.65338 6.43342 5.65338 6.04289 6.0439C5.65237 6.43442 5.65237 7.06759 6.04289 7.45811L10.5845 11.9997L6.04289 16.5413Z"
                            fill="" />
                    </svg>
                </button>

                <div>
                    <h4 class="font-semibold text-gray-800 mb-7 text-title-sm dark:text-white/90">
                        {{ $editMode ? 'Modifier la ville' : 'Nouvelle ville' }}
                    </h4>

                    <form wire:submit="save">
                        <!-- Nom de la ville -->
                        <div class="mb-6">
                            <label for="nom" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nom de la ville *
                            </label>
                            <input wire:model="nom" type="text" id="nom"
                                class="block w-full rounded-lg border border-gray-300 px-3 py-3 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent dark:border-gray-600 @error('nom') border-red-500 @enderror"
                                placeholder="Entrez le nom de la ville">
                            @error('nom')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end w-full gap-3 mt-8">
                            <button type="button" wire:click="closeModal"
                                class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:w-auto">
                                Annuler
                            </button>
                            <button type="submit"
                                class="flex justify-center w-full px-4 py-3 text-sm font-medium text-white rounded-lg bg-brand-500 shadow-theme-xs hover:bg-brand-600 sm:w-auto">
                                {{ $editMode ? 'Modifier' : 'Créer' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Détails -->
    @if ($showDetailModal && $selectedVille)
        <div class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto modal z-99999">
            <div class="modal-close-btn fixed inset-0 h-full w-full bg-gray-400/50 backdrop-blur-[32px]" wire:click="closeDetailModal"></div>
            <div class="relative w-full max-w-[800px] rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-10">
                <!-- Bouton fermer -->
                <button wire:click="closeDetailModal"
                    class="absolute right-3 top-3 z-999 flex h-9.5 w-9.5 items-center justify-center rounded-full bg-gray-100 text-gray-400 transition-colors hover:bg-gray-200 hover:text-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white sm:right-6 sm:top-6 sm:h-11 sm:w-11">
                    <svg class="fill-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M6.04289 16.5413C5.65237 16.9318 5.65237 17.565 6.04289 17.9555C6.43342 18.346 7.06658 18.346 7.45711 17.9555L11.9987 13.4139L16.5408 17.956C16.9313 18.3466 17.5645 18.3466 17.955 17.956C18.3455 17.5655 18.3455 16.9323 17.955 16.5418L13.4129 11.9997L17.955 7.4576C18.3455 7.06707 18.3455 6.43391 17.955 6.04338C17.5645 5.65286 16.9313 5.65286 16.5408 6.04338L11.9987 10.5855L7.45711 6.0439C7.06658 5.65338 6.43342 5.65338 6.04289 6.0439C5.65237 6.43442 5.65237 7.06759 6.04289 7.45811L10.5845 11.9997L6.04289 16.5413Z"
                            fill="" />
                    </svg>
                </button>

                <div>
                    <h4 class="font-semibold text-gray-800 mb-7 text-title-sm dark:text-white/90">
                        Détails de la ville : {{ $selectedVille->nom }}
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                            <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                Informations générales
                            </h5>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Nom:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedVille->nom }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Créée le:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedVille->created_at->format('d/m/Y à H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                            <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                Statistiques
                            </h5>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Nombre de lots:</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $selectedVille->lots_count }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($selectedVille->lots->count() > 0)
                        <div class="mb-4">
                            <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                Lots associés ({{ $selectedVille->lots->count() }} lot(s))
                            </h5>
                            <div class="max-h-60 overflow-y-auto space-y-2">
                                @foreach ($selectedVille->lots->take(10) as $lot)
                                    <div class="flex items-center justify-between p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $lot->article->designation ?? 'Article supprimé' }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                Lot #{{ $lot->numero_lot }} - {{ $lot->quantite_restante }}/{{ $lot->quantite_initiale }} restant
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ number_format($lot->prix_vente, 0, ',', ' ') }} FCFA
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $lot->date_arrivee->format('d/m/Y') }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @if ($selectedVille->lots->count() > 10)
                                    <div class="text-center text-sm text-gray-500 dark:text-gray-400 py-2">
                                        ... et {{ $selectedVille->lots->count() - 10 }} autre(s) lot(s)
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center justify-end w-full gap-3 mt-8">
                        <button type="button" wire:click="closeDetailModal"
                            class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 sm:w-auto">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
