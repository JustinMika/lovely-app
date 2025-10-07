<div class="space-y-6">
    <!-- En-tête avec actions -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Gestion des Rôles</h2>
            <p class="text-gray-600 dark:text-gray-400">Gérez les rôles utilisateur et leurs permissions</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button wire:click="openModal"
                class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 focus:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition focus:outline-hidden">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nouveau Rôle
            </button>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-4">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="Rechercher par nom ou description..."
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
            </div>
            <div class="flex gap-2">
                <select wire:model.live="perPage"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                    <option value="10">10 par page</option>
                    <option value="25">25 par page</option>
                    <option value="50">50 par page</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table des rôles -->
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
            <h3 class="text-lg font-medium text-gray-800 dark:text-white">
                Liste des Rôles ({{ $roles->total() }})
            </h3>
        </div>

        @if ($roles->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <button wire:click="sortBy('nom')"
                                    class="flex items-center gap-1 text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                                    Nom du Rôle
                                    @if ($sortField === 'nom')
                                        @if ($sortDirection === 'asc')
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 15l7-7 7 7"></path>
                                            </svg>
                                        @else
                                            <svg class="h-3 w-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        @endif
                                    @endif
                                </button>
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Description
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Utilisateurs
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($roles as $role)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div
                                                class="h-10 w-10 rounded-full bg-brand-100 dark:bg-brand-900/20 flex items-center justify-center">
                                                <svg class="h-5 w-5 text-brand-600 dark:text-brand-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                                    </path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-800 dark:text-white">
                                                {{ $role->nom }}
                                            </div>
                                            @if (in_array($role->nom, ['Admin', 'Gérant', 'Caissier', 'Vendeur']))
                                                <div class="text-xs text-brand-600 dark:text-brand-400">
                                                    Rôle système
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-800 dark:text-white">
                                        {{ $role->description ?: 'Aucune description' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400">
                                        {{ $role->users_count }} utilisateur{{ $role->users_count > 1 ? 's' : '' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <button wire:click="showDetail({{ $role->id }})"
                                            class="text-brand-600 hover:text-brand-900 dark:text-brand-400 dark:hover:text-brand-300">
                                            Voir
                                        </button>
                                        <button wire:click="edit({{ $role->id }})"
                                            class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300">
                                            Modifier
                                        </button>
                                        @if (!in_array($role->nom, ['Admin', 'Gérant', 'Caissier', 'Vendeur']) && $role->users_count == 0)
                                            <button wire:click="confirmDelete({{ $role->id }})"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                Supprimer
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($roles->hasPages())
                <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-800">
                    {{ $roles->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <div
                    class="mx-auto mb-4 h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                </div>
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-2">
                    Aucun rôle trouvé
                </h4>
                <p class="text-gray-500 dark:text-gray-400 mb-4">
                    Commencez par créer votre premier rôle personnalisé.
                </p>
                <button wire:click="openModal"
                    class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-white transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Créer un rôle
                </button>
            </div>
        @endif
    </div>

    <!-- Modal de création/édition -->
    @if ($showModal)
        <div class="fixed inset-0 flex items-center justify-center p-5 overflow-y-auto modal z-99999"
            aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="relative w-full max-w-[507px] rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-1">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                <div
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                    id="modal-title">
                                    {{ $editMode ? 'Modifier le rôle' : 'Nouveau rôle' }}
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label for="nom"
                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Nom du rôle <span class="text-error-500">*</span>
                                        </label>
                                        <input type="text" wire:model="nom" id="nom"
                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('nom') border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 @enderror"
                                            placeholder="Ex: Superviseur">
                                        @error('nom')
                                            <p class="text-theme-xs text-error-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="description"
                                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Description
                                        </label>
                                        <textarea wire:model="description" id="description" rows="3"
                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('description') border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 @enderror"
                                            placeholder="Description du rôle et de ses responsabilités..."></textarea>
                                        @error('description')
                                            <p class="text-theme-xs text-error-500 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="save" type="button"
                            class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 focus:bg-brand-600 w-full inline-flex justify-center rounded-lg px-4 py-2 text-sm font-medium text-white transition focus:outline-hidden sm:ml-3 sm:w-auto">
                            {{ $editMode ? 'Mettre à jour' : 'Créer' }}
                        </button>
                        <button wire:click="closeModal" type="button"
                            class="shadow-theme-xs mt-3 w-full inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03] sm:mt-0 sm:w-auto">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal de détails -->
    @if ($showDetailModal && $selectedRole)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeDetailModal">
                </div>

                <div
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                    Détails du rôle : {{ $selectedRole->nom }}
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-400">Description</label>
                                        <p class="mt-1 text-sm text-gray-800 dark:text-white">
                                            {{ $selectedRole->description ?: 'Aucune description' }}
                                        </p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-400">Statistiques</label>
                                        <div class="mt-2 grid grid-cols-2 gap-4">
                                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                                <div class="text-2xl font-bold text-gray-800 dark:text-white">
                                                    {{ $selectedRole->users_count }}</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">Utilisateurs
                                                </div>
                                            </div>
                                            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                                <div class="text-2xl font-bold text-gray-800 dark:text-white">
                                                    {{ in_array($selectedRole->nom, ['Admin', 'Gérant', 'Caissier', 'Vendeur']) ? 'Oui' : 'Non' }}
                                                </div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">Rôle système
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="closeDetailModal" type="button"
                            class="shadow-theme-xs w-full inline-flex justify-center rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03] sm:w-auto">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
