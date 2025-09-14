@extends('layouts.app')

@section('title', 'Gestion des Ventes')

@section('content')
    <div class="container-fluid px-4 py-6">
        <livewire:vente-manager />
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
