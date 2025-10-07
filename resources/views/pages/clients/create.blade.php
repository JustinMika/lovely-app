@extends('layouts.app')

@section('content')
    <!-- Breadcrumb Start -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">
            Créer un Nouveau Client
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                        href="{{ route('dashboard') }}">Tableau de bord /</a>
                </li>
                <li>
                    <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                        href="{{ route('clients.index') }}">Clients /</a>
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
                    Informations du Client
                </h2>
            </div>
            <div class="p-4 sm:p-6 dark:border-gray-800">
                <form action="{{ route('clients.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="nom" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Nom <span class="text-error-500">*</span>
                            </label>
                            <input type="text" id="nom" name="nom" value="{{ old('nom') }}"
                                placeholder="Entrez le nom du client"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('nom') border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 @enderror" />
                            @error('nom')
                                <p class="text-theme-xs text-error-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="telephone"
                                class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Téléphone
                            </label>
                            <input type="tel" id="telephone" name="telephone" value="{{ old('telephone') }}"
                                placeholder="Entrez le numéro de téléphone"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('telephone') border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 @enderror" />
                            @error('telephone')
                                <p class="text-theme-xs text-error-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Email
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                placeholder="Entrez l'adresse email"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent bg-none px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 @error('email') border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 @enderror" />
                            @error('email')
                                <p class="text-theme-xs text-error-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Button -->
                        <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                            <a href="{{ route('clients.index') }}"
                                class="shadow-theme-xs inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-medium text-gray-700 ring-1 ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                                Annuler
                            </a>
                            <button type="submit"
                                class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 focus:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition focus:outline-hidden">
                                Créer le Client
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
                    const nomField = form.querySelector('[name="nom"]');

                    if (!nomField.value.trim()) {
                        e.preventDefault();
                        nomField.classList.add('border-red-500');
                        nomField.classList.remove('border-gray-300');

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Erreur de validation',
                                text: 'Le nom est obligatoire.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            alert('Le nom est obligatoire.');
                        }
                    } else {
                        nomField.classList.remove('border-red-500');
                        nomField.classList.add('border-gray-300');
                    }
                });
            }
        });
    </script>
@endpush
