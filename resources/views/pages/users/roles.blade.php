@extends('layouts.app')

@section('title', 'Gestion des Rôles')

@section('content')
    <!-- Breadcrumb Start -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">
            Gestion des Rôles
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                        href="{{ route('dashboard') }}">Tableau de bord /</a>
                </li>
                <li class="font-medium text-gray-800 dark:text-white/90">Rôles</li>
            </ol>
        </nav>
    </div>
    <!-- Breadcrumb End -->

    <!-- Role Manager Component -->
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
        <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
            @livewire('role-manager')
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
    </script>
@endsection
