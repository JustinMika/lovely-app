@extends('layouts.app')

@section('content')
    <!-- Breadcrumb Start -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">
            Gestion des Articles
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                        href="{{ route('dashboard') }}">Tableau de bord /</a>
                </li>
                <li class="font-medium text-gray-800 dark:text-white/90">Articles</li>
            </ol>
        </nav>
    </div>
    <!-- Breadcrumb End -->

    <!-- Statistiques dynamiques -->
    <livewire:article-stats />

    <!-- Composant Livewire -->
    <div class="container-fluid px-4 py-6">
        <livewire:article-manager />
    </div>
@endsection

@push('scripts')
    <script>
        // Configuration SweetAlert2 pour le dark mode
        document.addEventListener('DOMContentLoaded', function() {
            // Vérifier si le dark mode est activé
            const isDarkMode = document.documentElement.classList.contains('dark');

            if (isDarkMode) {
                // Configuration globale pour SweetAlert2 en mode sombre
                window.Swal = window.Swal.mixin({
                    customClass: {
                        popup: 'dark:bg-gray-800 dark:text-white',
                        title: 'dark:text-white',
                        content: 'dark:text-gray-300',
                        confirmButton: 'bg-blue-600 hover:bg-blue-700',
                        cancelButton: 'bg-gray-600 hover:bg-gray-700'
                    }
                });
            }
        });
    </script>
@endpush
