@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
    <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
        <!-- Breadcrumb Start -->
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Gestion des Utilisateurs
            </h2>
            <nav>
                <ol class="flex items-center gap-2">
                    <li>
                        <a class="font-medium text-gray-600 hover:text-brand-500 dark:text-gray-400 dark:hover:text-brand-400"
                            href="{{ route('dashboard') }}">Tableau de bord</a>
                    </li>
                    <li class="text-gray-400 dark:text-gray-600">/</li>
                    <li class="font-medium text-brand-500 dark:text-brand-400">Utilisateurs</li>
                </ol>
            </nav>
        </div>
        <!-- Breadcrumb End -->

        <!-- User Manager Component -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-theme-xs dark:border-gray-800 dark:bg-white/[0.03]">
            @livewire('user-manager')
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
