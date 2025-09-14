@extends('layouts.app')

@section('title', 'Gestion des Lots')

@section('content')
    <div class="container mx-auto px-4 py-6">
        @livewire('lot-manager')
    </div>
@endsection

@push('scripts')
    <script>
        // Configuration pour les alertes Livewire
        window.addEventListener('DOMContentLoaded', function() {
            // Configuration SweetAlert2 pour le thème sombre
            if (document.documentElement.classList.contains('dark')) {
                Swal.mixin({
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
