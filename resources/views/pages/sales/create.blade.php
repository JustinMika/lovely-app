@extends('layouts.app')

@section('title', 'Créer une Nouvelle Vente')

@section('content')
    <!-- Breadcrumb Start -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">
            Créer une Nouvelle Vente
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                        href="{{ route('dashboard') }}">Tableau de bord /</a>
                </li>
                <li>
                    <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                        href="{{ route('sales.index') }}">Ventes /</a>
                </li>
                <li class="font-medium text-gray-800 dark:text-white/90">Créer</li>
            </ol>
        </nav>
    </div>
    <!-- Breadcrumb End -->

    <!-- Content Start -->
    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-800">
                <h2 class="text-lg font-medium text-gray-800 dark:text-white">
                    Informations de la Vente
                </h2>
            </div>
            <div class="p-4 sm:p-6 dark:border-gray-800">
                <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
                    @csrf
                    <div class="space-y-6">
                        <!-- Client Selection -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="client_id"
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Client <span class="text-red-500">*</span>
                                </label>
                                <select id="client_id" name="client_id"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 @error('client_id') border-red-500 @enderror">
                                    <option value="">Sélectionner un client</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}"
                                            {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                            {{ $client->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('client_id')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="sale_date"
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Date de Vente <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="sale_date" name="sale_date"
                                    value="{{ old('sale_date', date('Y-m-d')) }}"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 @error('sale_date') border-red-500 @enderror" />
                                @error('sale_date')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Items Section -->
                        <div>
                            <div class="mb-4 flex items-center justify-between">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Articles <span class="text-red-500">*</span>
                                </label>
                                <button type="button" id="addItem"
                                    class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-white transition">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Ajouter un article
                                </button>
                            </div>

                            <div id="itemsContainer" class="space-y-4">
                                <!-- Items will be added here dynamically -->
                            </div>
                            @error('items')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Discount and Tax -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="discount"
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Remise (%)
                                </label>
                                <input type="number" id="discount" name="discount" value="{{ old('discount', 0) }}"
                                    min="0" max="100" step="0.01"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('discount') border-red-500 @enderror" />
                                @error('discount')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tax_rate"
                                    class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Taux de TVA (%)
                                </label>
                                <input type="number" id="tax_rate" name="tax_rate" value="{{ old('tax_rate', 0) }}"
                                    min="0" max="100" step="0.01"
                                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('tax_rate') border-red-500 @enderror" />
                                @error('tax_rate')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Total Display -->
                        <div
                            class="rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-medium text-gray-700 dark:text-gray-300">Total:</span>
                                <span id="totalAmount" class="text-xl font-bold text-gray-900 dark:text-white">0.00 €</span>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                            <a href="{{ route('sales.index') }}"
                                class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                                Annuler
                            </a>
                            <button type="submit"
                                class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                                Créer la Vente
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let itemIndex = 0;
            const itemsContainer = document.getElementById('itemsContainer');
            const addItemBtn = document.getElementById('addItem');
            const totalAmountEl = document.getElementById('totalAmount');

            // Articles data from backend
            const articles = @json($articles);
            console.log('Articles loaded:', articles);
            console.log('Articles count:', articles.length);

            function addItem() {
                console.log('Adding item with index:', itemIndex);

                const itemHtml = `
                    <div class="item-row rounded-lg border border-gray-200 p-4 dark:border-gray-700" data-index="${itemIndex}">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Article</label>
                                <select name="items[${itemIndex}][article_id]" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" required>
                                    <option value="">Sélectionner un article</option>
                                    ${articles.length > 0 ? articles.map(article => `<option value="${article.id}">${article.designation || article.nom || 'Article sans nom'}</option>`).join('') : '<option value="" disabled>Aucun article disponible</option>'}
                                </select>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Quantité</label>
                                <input type="number" name="items[${itemIndex}][quantity]" min="1" value="1" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90" required>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Prix unitaire (€)</label>
                                <input type="number" name="items[${itemIndex}][price]" min="0" step="0.01" placeholder="0.00" class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" required>
                            </div>
                            <div class="flex items-end">
                                <button type="button" class="remove-item shadow-theme-xs inline-flex h-11 w-full items-center justify-center gap-2 rounded-lg bg-red-500 px-4 py-3 text-sm font-medium text-white transition hover:bg-red-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                if (itemsContainer) {
                    itemsContainer.insertAdjacentHTML('beforeend', itemHtml);
                    itemIndex++;
                    updateTotal();
                    console.log('Item added successfully');
                } else {
                    console.error('Items container not found');
                }
            }

            function removeItem(button) {
                const itemRow = button.closest('.item-row');
                if (itemRow) {
                    itemRow.remove();
                    updateTotal();
                    console.log('Item removed');
                }
            }

            function updateTotal() {
                let total = 0;
                const items = document.querySelectorAll('.item-row');

                items.forEach(item => {
                    const quantityInput = item.querySelector('input[name*="[quantity]"]');
                    const priceInput = item.querySelector('input[name*="[price]"]');

                    const quantity = parseFloat(quantityInput?.value || 0);
                    const price = parseFloat(priceInput?.value || 0);
                    total += quantity * price;
                });

                const discountInput = document.getElementById('discount');
                const taxRateInput = document.getElementById('tax_rate');

                const discount = parseFloat(discountInput?.value || 0);
                const taxRate = parseFloat(taxRateInput?.value || 0);

                const discountAmount = total * (discount / 100);
                const subtotal = total - discountAmount;
                const taxAmount = subtotal * (taxRate / 100);
                const finalTotal = subtotal + taxAmount;

                if (totalAmountEl) {
                    totalAmountEl.textContent = finalTotal.toFixed(2) + ' €';
                }
            }

            // Event listeners
            if (addItemBtn) {
                addItemBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Add item button clicked');
                    addItem();
                });
            } else {
                console.error('Add item button not found');
            }

            if (itemsContainer) {
                itemsContainer.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-item')) {
                        e.preventDefault();
                        removeItem(e.target.closest('.remove-item'));
                    }
                });

                itemsContainer.addEventListener('input', updateTotal);
            }

            // Event listeners for discount and tax
            const discountInput = document.getElementById('discount');
            const taxRateInput = document.getElementById('tax_rate');

            if (discountInput) {
                discountInput.addEventListener('input', updateTotal);
            }

            if (taxRateInput) {
                taxRateInput.addEventListener('input', updateTotal);
            }

            // Debug: Check if elements exist
            console.log('Add button found:', !!addItemBtn);
            console.log('Items container found:', !!itemsContainer);
            console.log('Total element found:', !!totalAmountEl);

            // Add first item by default
            if (addItemBtn && itemsContainer) {
                addItem();
            } else {
                console.error('Required elements not found for initialization');
            }

            // Form validation
            const saleForm = document.getElementById('saleForm');
            if (saleForm) {
                saleForm.addEventListener('submit', function(e) {
                    const items = document.querySelectorAll('.item-row');
                    if (items.length === 0) {
                        e.preventDefault();
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Erreur',
                                text: 'Veuillez ajouter au moins un article à la vente.',
                                icon: 'error'
                            });
                        } else {
                            alert('Veuillez ajouter au moins un article à la vente.');
                        }
                        return false;
                    }

                    // Validate that all items have required fields
                    let isValid = true;
                    items.forEach((item, index) => {
                        const articleSelect = item.querySelector('select[name*="[article_id]"]');
                        const quantityInput = item.querySelector('input[name*="[quantity]"]');
                        const priceInput = item.querySelector('input[name*="[price]"]');

                        if (!articleSelect?.value || !quantityInput?.value || !priceInput?.value) {
                            isValid = false;
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Erreur',
                                text: 'Veuillez remplir tous les champs requis pour chaque article.',
                                icon: 'error'
                            });
                        } else {
                            alert('Veuillez remplir tous les champs requis pour chaque article.');
                        }
                        return false;
                    }
                });
            }

            console.log('Sales create form initialized');
        });
    </script>
@endpush
