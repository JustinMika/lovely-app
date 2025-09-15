@extends('layouts.app')

@section('title', 'Gestion des Ventes')

@section('content')
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
        <!-- Breadcrumb Start -->
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Gestion des Ventes
            </h2>
            <nav>
                <ol class="flex items-center gap-2">
                    <li>
                        <a class="font-medium text-gray-600 hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-400"
                            href="{{ route('dashboard') }}">Tableau de bord</a>
                    </li>
                    <li class="text-gray-400 dark:text-gray-600">/</li>
                    <li class="font-medium text-brand-500 dark:text-brand-400">Ventes</li>
                </ol>
            </nav>
        </div>
        <!-- Breadcrumb End -->

        <!-- Vente Manager Component -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
            @livewire('vente-manager')
        </div>
    </div>

    <script>
        // Configuration SweetAlert2 pour le mode sombre
        document.addEventListener('DOMContentLoaded', function() {
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                    '(prefers-color-scheme: dark)').matches)) {
                Swal = Swal.mixin({
                    customClass: {
                        popup: 'bg-gray-800 text-white',
                        title: 'text-white',
                        content: 'text-gray-300',
                        confirmButton: 'bg-brand-500 hover:bg-brand-600',
                        cancelButton: 'bg-gray-600 hover:bg-gray-700'
                    }
                });
            }
        });

        // Écouter l'événement d'impression de facture
        document.addEventListener('livewire:init', () => {
            Livewire.on('imprimer-facture', (event) => {
                window.open(`/sales/${event.venteId}/invoice`, '_blank');
            });
        });
    </script>
@endsection

@push('scripts')
@endpush
