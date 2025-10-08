<div class="space-y-6">
    <!-- En-tête avec filtres -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tableau de Bord</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400">Vue d'ensemble des performances et statistiques</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
                <!-- Sélecteur de période -->
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Période:</label>
                    <select wire:model.live="dateRange"
                        class="border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="7">7 derniers jours</option>
                        <option value="30">30 derniers jours</option>
                        <option value="90">90 derniers jours</option>
                        <option value="365">1 an</option>
                    </select>
                </div>

                <!-- Bouton Export -->
                <button wire:click="exportGlobalReport"
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Rapport Global
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiques générales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Articles -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Articles</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ number_format($generalStats['total_articles']) }}</p>
                    <p class="text-xs text-green-600 dark:text-green-400">{{ $generalStats['articles_actifs'] }} actifs
                    </p>
                </div>
            </div>
        </div>

        <!-- Clients -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Clients</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ number_format($generalStats['total_clients']) }}</p>
                </div>
            </div>
        </div>

        <!-- Stock -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Lots en Stock</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ number_format($generalStats['lots_en_stock']) }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">/ {{ $generalStats['total_lots'] }} total</p>
                </div>
            </div>
        </div>

        <!-- Ventes -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Ventes ({{ $dateRange }}j)</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ number_format($generalStats['total_ventes']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques financières -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chiffre d'affaires -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Performance Financière</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Chiffre d'affaires</span>
                    <span class="text-lg font-semibold text-green-600 dark:text-green-400">
                        {{ currency($financialStats['chiffre_affaires']) }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Montant encaissé</span>
                    <span class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                        {{ currency($financialStats['montant_encaisse']) }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Bénéfice total</span>
                    <span class="text-lg font-semibold text-purple-600 dark:text-purple-400">
                        {{ currency($financialStats['benefice_total']) }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Panier moyen</span>
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ currency($financialStats['panier_moyen']) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Valeur du stock -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Valeur du Stock</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Prix d'achat</span>
                    <span class="text-lg font-semibold text-red-600 dark:text-red-400">
                        {{ currency($financialStats['valeur_stock_achat']) }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Prix de vente</span>
                    <span class="text-lg font-semibold text-green-600 dark:text-green-400">
                        {{ currency($financialStats['valeur_stock_vente']) }}
                    </span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-gray-200 dark:border-gray-700">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Marge potentielle</span>
                    <span class="text-lg font-semibold text-purple-600 dark:text-purple-400">
                        {{ currency($financialStats['valeur_stock_vente'] - $financialStats['valeur_stock_achat']) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Alertes stock -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Alertes Stock</h3>
            <div class="space-y-3">
                @if ($stockAlerts['stock_faible']->count() > 0)
                    <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400 mr-2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                            <span class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                {{ $stockAlerts['stock_faible']->count() }} lot(s) en stock faible
                            </span>
                        </div>
                    </div>
                @endif

                @if ($stockAlerts['rupture_stock']->count() > 0)
                    <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-red-600 dark:text-red-400 mr-2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-red-800 dark:text-red-200">
                                {{ $stockAlerts['rupture_stock']->count() }} article(s) en rupture
                            </span>
                        </div>
                    </div>
                @endif

                @if ($stockAlerts['stock_faible']->count() == 0 && $stockAlerts['rupture_stock']->count() == 0)
                    <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400 mr-2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-green-800 dark:text-green-200">
                                Aucune alerte stock
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Top performers -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Articles -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Articles ({{ $dateRange }}
                jours)</h3>
            <div class="space-y-3">
                @forelse($topArticles as $index => $article)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center">
                            <span
                                class="w-6 h-6 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center text-xs font-semibold mr-3">
                                {{ $index + 1 }}
                            </span>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $article->designation }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $article->quantite_vendue }}
                                    unités vendues</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-green-600 dark:text-green-400">
                                {{ currency($article->chiffre_affaires) }}
                            </p>
                            <p class="text-xs text-purple-600 dark:text-purple-400">
                                +{{ currency($article->benefice) }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 dark:text-gray-400 py-4">Aucune vente sur cette période</p>
                @endforelse
            </div>
        </div>

        <!-- Top Clients -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Clients ({{ $dateRange }}
                jours)</h3>
            <div class="space-y-3">
                @forelse($topClients as $index => $client)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center">
                            <span
                                class="w-6 h-6 bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-400 rounded-full flex items-center justify-center text-xs font-semibold mr-3">
                                {{ $index + 1 }}
                            </span>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $client->nom }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $client->ventes_count }}
                                    vente(s)</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-green-600 dark:text-green-400">
                                {{ currency($client->ventes_sum_total) }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 dark:text-gray-400 py-4">Aucune vente sur cette période</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Top Villes -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Répartition par Ville</h3>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            @forelse($topVilles as $ville)
                <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $ville->lots_count }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $ville->nom }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500">lot(s)</p>
                </div>
            @empty
                <div class="col-span-5 text-center text-gray-500 dark:text-gray-400 py-4">
                    Aucune ville avec des lots
                </div>
            @endforelse
        </div>
    </div>

    <!-- Détails des alertes stock -->
    @if ($stockAlerts['stock_faible']->count() > 0 || $stockAlerts['rupture_stock']->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Détails des Alertes Stock</h3>

            @if ($stockAlerts['stock_faible']->count() > 0)
                <div class="mb-6">
                    <h4 class="text-md font-medium text-yellow-600 dark:text-yellow-400 mb-3">Stock Faible (≤ 10
                        unités)</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Article</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Lot</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Ville</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Stock</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($stockAlerts['stock_faible'] as $lot)
                                    <tr>
                                        <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">
                                            {{ $lot->article->designation }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $lot->numero_lot }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $lot->ville->nom }}</td>
                                        <td class="px-4 py-2">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                {{ $lot->quantite_restante }} / {{ $lot->quantite_initiale }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if ($stockAlerts['rupture_stock']->count() > 0)
                <div>
                    <h4 class="text-md font-medium text-red-600 dark:text-red-400 mb-3">Rupture de Stock</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach ($stockAlerts['rupture_stock'] as $article)
                            <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                <p class="text-sm font-medium text-red-800 dark:text-red-200">
                                    {{ $article->designation }}</p>
                                <p class="text-xs text-red-600 dark:text-red-400">Aucun stock disponible</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
