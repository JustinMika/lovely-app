@extends('layouts.app')

@section('content')
    <!-- Breadcrumb Start -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">
            Créer un Nouvel Article
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
                    Informations de l'Article
                </h2>
            </div>
            <div class="p-4 sm:p-6 dark:border-gray-800">
                <form action="{{ route('articles.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="designation"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Désignation <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="designation" name="designation" value="{{ old('designation') }}"
                                placeholder="Entrez la désignation de l'article"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('designation') border-red-500 @enderror" />
                            @error('designation')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Description
                            </label>
                            <textarea id="description" name="description" rows="4" placeholder="Entrez la description de l'article"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Statut
                            </label>
                            <div class="flex items-center">
                                <input type="hidden" name="actif" value="0">
                                <input type="checkbox" id="actif" name="actif" value="1"
                                    {{ old('actif', true) ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500/10 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-brand-500" />
                                <label for="actif" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Article actif
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Décochez cette case pour désactiver l'article
                            </p>
                            @error('actif')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Button -->
                        <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                            <a href="{{ route('articles.index') }}"
                                class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                                Annuler
                            </a>
                            <button type="submit"
                                class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition">
                                Créer l'Article
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
            // Form validation
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const designationField = form.querySelector('[name="designation"]');

                    if (!designationField.value.trim()) {
                        e.preventDefault();
                        designationField.classList.add('border-red-500');
                        designationField.classList.remove('border-gray-300');

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Erreur de validation',
                                text: 'La désignation est obligatoire.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            alert('La désignation est obligatoire.');
                        }
                    } else {
                        designationField.classList.remove('border-red-500');
                        designationField.classList.add('border-gray-300');
                    }
                });
            }
        });
    </script>
@endpush
