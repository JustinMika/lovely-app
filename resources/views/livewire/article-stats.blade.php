<div>
    <!-- En-tête avec bouton de rafraîchissement -->
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Statistiques des Articles</h3>
        <button wire:click="refreshStats" wire:loading.attr="disabled"
            class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
            <svg wire:loading.remove wire:target="refreshStats" class="w-4 h-4" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                </path>
            </svg>
            <svg wire:loading wire:target="refreshStats" class="animate-spin w-4 h-4" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span wire:loading.remove wire:target="refreshStats">Actualiser</span>
            <span wire:loading wire:target="refreshStats">Actualisation...</span>
        </button>
    </div>

    @if (session()->has('message'))
        <div
            class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg dark:bg-green-900/20 dark:border-green-700 dark:text-green-400">
            {{ session('message') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-4 2xl:gap-7.5 mb-6">
        <!-- Total Articles -->
        <div
            class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center gap-3 mb-6">
                <div
                    class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/20">
                    <svg class="fill-blue-600 dark:fill-blue-400" width="22" height="22" viewBox="0 0 22 22">
                        <path d="M16.1 11.8H5.9a1.1 1.1 0 0 0 0 2.2h10.2a1.1 1.1 0 0 0 0-2.2Z" />
                        <path
                            d="M11 1.1C5.4 1.1 1.1 5.4 1.1 11S5.4 20.9 11 20.9 20.9 16.6 20.9 11 16.6 1.1 11 1.1ZM11 19.7c-4.8 0-8.7-3.9-8.7-8.7S6.2 2.3 11 2.3s8.7 3.9 8.7 8.7-3.9 8.7-8.7 8.7Z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-end justify-between">
                <div>
                    <h4 class="text-title-md font-bold text-gray-800 dark:text-white/90">
                        {{ number_format($totalArticles) }}
                    </h4>
                    <span class="text-theme-sm font-medium text-gray-500 dark:text-gray-400">Total Articles</span>
                </div>
                @if ($croissanceArticles != 0)
                    <span
                        class="flex items-center gap-1 text-theme-sm font-medium {{ $croissanceArticles > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ $croissanceArticles > 0 ? '+' : '' }}{{ $croissanceArticles }}%
                        <svg class="fill-current {{ $croissanceArticles > 0 ? '' : 'rotate-180' }}" width="10"
                            height="11" viewBox="0 0 10 11">
                            <path
                                d="M4.35716 2.47737L0.908974 5.82987L5.0443e-07 4.94612L5 0.0848689L10 4.94612L9.09103 5.82987L5.64284 2.47737L5.64284 10.0849L4.35716 10.0849L4.35716 2.47737Z" />
                        </svg>
                    </span>
                @endif
            </div>
        </div>

        <!-- Stock Faible -->
        <div
            class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center gap-3 mb-6">
                <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/20">
                    <svg class="fill-red-600 dark:fill-red-400" width="22" height="22" viewBox="0 0 22 22">
                        <path d="M16.1 11.8H5.9a1.1 1.1 0 0 0 0 2.2h10.2a1.1 1.1 0 0 0 0-2.2Z" />
                        <path
                            d="M11 1.1C5.4 1.1 1.1 5.4 1.1 11S5.4 20.9 11 20.9 20.9 16.6 20.9 11 16.6 1.1 11 1.1ZM11 19.7c-4.8 0-8.7-3.9-8.7-8.7S6.2 2.3 11 2.3s8.7 3.9 8.7 8.7-3.9 8.7-8.7 8.7Z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-end justify-between">
                <div>
                    <h4 class="text-title-md font-bold text-gray-800 dark:text-white/90">
                        {{ $stockFaible }}
                    </h4>
                    <span class="text-theme-sm font-medium text-gray-500 dark:text-gray-400">Stock Faible</span>
                </div>
                @if ($stockFaible > 0)
                    <span class="flex items-center gap-1 text-theme-sm font-medium text-red-600 dark:text-red-400">
                        Urgent
                        <svg class="fill-current" width="10" height="11" viewBox="0 0 10 11">
                            <path d="M5 0.5L6.5 3.5H9L7 5.5L8 8.5L5 7L2 8.5L3 5.5L1 3.5H3.5L5 0.5Z" />
                        </svg>
                    </span>
                @else
                    <span class="flex items-center gap-1 text-theme-sm font-medium text-green-600 dark:text-green-400">
                        OK
                    </span>
                @endif
            </div>
        </div>

        <!-- Catégories -->
        <div
            class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center gap-3 mb-6">
                <div
                    class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900/20">
                    <svg class="fill-purple-600 dark:fill-purple-400" width="22" height="22" viewBox="0 0 22 22">
                        <path
                            d="M11 1.1C5.4 1.1 1.1 5.4 1.1 11S5.4 20.9 11 20.9 20.9 16.6 20.9 11 16.6 1.1 11 1.1ZM11 19.7c-4.8 0-8.7-3.9-8.7-8.7S6.2 2.3 11 2.3s8.7 3.9 8.7 8.7-3.9 8.7-8.7 8.7Z" />
                        <path
                            d="M11 6.6c-.6 0-1.1.5-1.1 1.1v3.3c0 .6.5 1.1 1.1 1.1s1.1-.5 1.1-1.1V7.7c0-.6-.5-1.1-1.1-1.1ZM11 13.8c-.6 0-1.1.5-1.1 1.1s.5 1.1 1.1 1.1 1.1-.5 1.1-1.1-.5-1.1-1.1-1.1Z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-end justify-between">
                <div>
                    <h4 class="text-title-md font-bold text-gray-800 dark:text-white/90">
                        {{ $totalCategories }}
                    </h4>
                    <span class="text-theme-sm font-medium text-gray-500 dark:text-gray-400">Catégories</span>
                </div>
                <span class="flex items-center gap-1 text-theme-sm font-medium text-purple-600 dark:text-purple-400">
                    Actives
                </span>
            </div>
        </div>

        <!-- Valeur Stock -->
        <div
            class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center gap-3 mb-6">
                <div
                    class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/20">
                    <svg class="fill-green-600 dark:fill-green-400" width="22" height="22" viewBox="0 0 22 22">
                        <path
                            d="M11 1.1C5.4 1.1 1.1 5.4 1.1 11S5.4 20.9 11 20.9 20.9 16.6 20.9 11 16.6 1.1 11 1.1ZM11 19.7c-4.8 0-8.7-3.9-8.7-8.7S6.2 2.3 11 2.3s8.7 3.9 8.7 8.7-3.9 8.7-8.7 8.7Z" />
                        <path
                            d="M15.4 8.4L10.2 13.6c-.2.2-.4.2-.6 0L6.6 10.6c-.2-.2-.2-.4 0-.6s.4-.2.6 0l2.7 2.7 4.9-4.9c.2-.2.4-.2.6 0s.2.4 0 .6Z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-end justify-between">
                <div>
                    <h4 class="text-title-md font-bold text-gray-800 dark:text-white/90">
                        {{ currency($valeurStock, 2) }}
                    </h4>
                    <span class="text-theme-sm font-medium text-gray-500 dark:text-gray-400">Valeur Stock</span>
                </div>
                @if ($croissanceStock != 0)
                    <span
                        class="flex items-center gap-1 text-theme-sm font-medium {{ $croissanceStock > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ $croissanceStock > 0 ? '+' : '' }}{{ $croissanceStock }}%
                        <svg class="fill-current {{ $croissanceStock > 0 ? '' : 'rotate-180' }}" width="10"
                            height="11" viewBox="0 0 10 11">
                            <path
                                d="M4.35716 2.47737L0.908974 5.82987L5.0443e-07 4.94612L5 0.0848689L10 4.94612L9.09103 5.82987L5.64284 2.47737L5.64284 10.0849L4.35716 10.0849L4.35716 2.47737Z" />
                        </svg>
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
