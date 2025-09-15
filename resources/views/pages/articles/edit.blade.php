@extends('layouts.app')

@section('content')
    <!-- Breadcrumb Start -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">
            Modifier l'Article
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                        href="{{ route('dashboard') }}">Tableau de bord /</a>
                </li>
                <li>
                    <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                        href="{{ route('articles.index') }}">Articles /</a>
                </li>
                <li class="font-medium text-gray-800 dark:text-white/90">Modifier</li>
            </ol>
        </nav>
    </div>
    <!-- Breadcrumb End -->

    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="mb-6">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Modifier l'article</h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Modifiez les informations de l'article "{{ $article->designation }}"
            </p>
        </div>

        <form action="{{ route('articles.update', $article) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Designation -->
                <div class="lg:col-span-2">
                    <label for="designation" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Désignation <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="designation" name="designation"
                        value="{{ old('designation', $article->designation) }}"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        placeholder="Nom de l'article" required>
                    @error('designation')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="lg:col-span-2">
                    <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="4"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                        placeholder="Description de l'article (optionnel)">{{ old('description', $article->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Statut actif -->
                <div class="lg:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" id="actif" name="actif" value="1"
                            {{ old('actif', $article->actif) ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800">
                        <label for="actif" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-400">
                            Article actif
                        </label>
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Les articles inactifs ne seront pas disponibles pour les ventes
                    </p>
                    @error('actif')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Informations supplémentaires -->
            <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800/50">
                <h4 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Informations</h4>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Créé le</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $article->created_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Modifié le</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $article->updated_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Lots associés</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $article->lots->count() }} lot(s)
                        </p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('articles.show', $article) }}"
                    class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                    Annuler
                </a>
                <button type="submit"
                    class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Mettre à jour l'article
                </button>
            </div>
        </form>
    </div>

    @if ($article->lots->count() > 0)
        <!-- Warning about lots -->
        <div class="mt-6 rounded-2xl border border-amber-200 bg-amber-50 p-6 dark:border-amber-800 dark:bg-amber-900/20">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-amber-800 dark:text-amber-200">
                        Attention
                    </h4>
                    <p class="mt-1 text-sm text-amber-700 dark:text-amber-300">
                        Cet article a {{ $article->lots->count() }} lot(s) associé(s).
                        Si vous désactivez cet article, il ne sera plus disponible pour les nouvelles ventes.
                    </p>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        // Auto-save draft functionality (optional)
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const inputs = form.querySelectorAll('input, textarea, select');

            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    // Save to localStorage for draft functionality
                    const formData = new FormData(form);
                    const data = Object.fromEntries(formData);
                    localStorage.setItem('article_edit_draft_{{ $article->id }}', JSON.stringify(
                        data));
                });
            });

            // Clear draft on successful submit
            form.addEventListener('submit', function() {
                localStorage.removeItem('article_edit_draft_{{ $article->id }}');
            });
        });
    </script>
@endpush
