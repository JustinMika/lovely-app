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

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-4 2xl:gap-7.5 mb-6">
        <!-- Card Item Start -->
        <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-6 flex items-center gap-3">
                <div class="h-10 w-10">
                    <svg class="fill-blue-600 dark:fill-blue-400" width="40" height="40" viewBox="0 0 40 40">
                        <path
                            d="M35 10a5 5 0 0 0-5-5H10a5 5 0 0 0-5 5v20a5 5 0 0 0 5 5h20a5 5 0 0 0 5-5V10ZM30 7.5a2.5 2.5 0 0 1 2.5 2.5v20a2.5 2.5 0 0 1-2.5 2.5H10A2.5 2.5 0 0 1 7.5 30V10A2.5 2.5 0 0 1 10 7.5h20Z" />
                        <path d="M15 15h10v2.5H15V15ZM15 20h10v2.5H15V20ZM15 25h7.5v2.5H15V25Z" />
                    </svg>
                </div>
                <div class="mt-4 flex items-end justify-between">
                    <div>
                        <h4 class="text-title-md font-bold text-gray-800 dark:text-white/90">
                            245
                        </h4>
                        <span class="text-theme-sm font-medium text-gray-500 dark:text-gray-400">Total Articles</span>
                    </div>
                    <span class="flex items-center gap-1 text-theme-sm font-medium text-green-600 dark:text-green-400">
                        +5.2%
                        <svg class="fill-current" width="10" height="11" viewBox="0 0 10 11">
                            <path
                                d="M4.35716 2.47737L0.908974 5.82987L5.0443e-07 4.94612L5 0.0848689L10 4.94612L9.09103 5.82987L5.64284 2.47737L5.64284 10.0849L4.35716 10.0849L4.35716 2.47737Z" />
                        </svg>
                    </span>
                </div>
            </div>
        </div>
        <!-- Card Item End -->

        <!-- Card Item Start -->
        <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/20">
                <svg class="fill-red-600 dark:fill-red-400" width="22" height="22" viewBox="0 0 22 22">
                    <path d="M16.1 11.8H5.9a1.1 1.1 0 0 0 0 2.2h10.2a1.1 1.1 0 0 0 0-2.2Z" />
                    <path
                        d="M11 1.1C5.4 1.1 1.1 5.4 1.1 11S5.4 20.9 11 20.9 20.9 16.6 20.9 11 16.6 1.1 11 1.1ZM11 19.7c-4.8 0-8.7-3.9-8.7-8.7S6.2 2.3 11 2.3s8.7 3.9 8.7 8.7-3.9 8.7-8.7 8.7Z" />
                </svg>
            </div>
            <div class="mt-4 flex items-end justify-between">
                <div>
                    <h4 class="text-title-md font-bold text-gray-800 dark:text-white/90">
                        12
                    </h4>
                    <span class="text-theme-sm font-medium text-gray-500 dark:text-gray-400">Stock Faible</span>
                </div>
                <span class="flex items-center gap-1 text-theme-sm font-medium text-red-600 dark:text-red-400">
                    Urgent
                    <svg class="fill-current" width="10" height="11" viewBox="0 0 10 11">
                        <path d="M5 0.5L6.5 3.5H9L7 5.5L8 8.5L5 7L2 8.5L3 5.5L1 3.5H3.5L5 0.5Z" />
                    </svg>
                </span>
            </div>
        </div>
        <!-- Card Item End -->

        <!-- Card Item Start -->
        <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900/20">
                <svg class="fill-purple-600 dark:fill-purple-400" width="22" height="22" viewBox="0 0 22 22">
                    <path
                        d="M11 1.1C5.4 1.1 1.1 5.4 1.1 11S5.4 20.9 11 20.9 20.9 16.6 20.9 11 16.6 1.1 11 1.1ZM11 19.7c-4.8 0-8.7-3.9-8.7-8.7S6.2 2.3 11 2.3s8.7 3.9 8.7 8.7-3.9 8.7-8.7 8.7Z" />
                    <path
                        d="M11 6.6c-.6 0-1.1.5-1.1 1.1v3.3c0 .6.5 1.1 1.1 1.1s1.1-.5 1.1-1.1V7.7c0-.6-.5-1.1-1.1-1.1ZM11 13.8c-.6 0-1.1.5-1.1 1.1s.5 1.1 1.1 1.1 1.1-.5 1.1-1.1-.5-1.1-1.1-1.1Z" />
                </svg>
            </div>
            <div class="mt-4 flex items-end justify-between">
                <div>
                    <h4 class="text-title-md font-bold text-gray-800 dark:text-white/90">
                        18
                    </h4>
                    <span class="text-theme-sm font-medium text-gray-500 dark:text-gray-400">Catégories</span>
                </div>
                <span class="flex items-center gap-1 text-theme-sm font-medium text-purple-600 dark:text-purple-400">
                    +2 nouvelles
                </span>
            </div>
        </div>
        <!-- Card Item End -->

        <!-- Card Item Start -->
        <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/20">
                <svg class="fill-green-600 dark:fill-green-400" width="22" height="22" viewBox="0 0 22 22">
                    <path
                        d="M11 1.1C5.4 1.1 1.1 5.4 1.1 11S5.4 20.9 11 20.9 20.9 16.6 20.9 11 16.6 1.1 11 1.1ZM11 19.7c-4.8 0-8.7-3.9-8.7-8.7S6.2 2.3 11 2.3s8.7 3.9 8.7 8.7-3.9 8.7-8.7 8.7Z" />
                    <path
                        d="M15.4 8.4L10.2 13.6c-.2.2-.4.2-.6 0L6.6 10.6c-.2-.2-.2-.4 0-.6s.4-.2.6 0l2.7 2.7 4.9-4.9c.2-.2.4-.2.6 0s.2.4 0 .6Z" />
                </svg>
            </div>
            <div class="mt-4 flex items-end justify-between">
                <div>
                    <h4 class="text-title-md font-bold text-gray-800 dark:text-white/90">
                        €125,430
                    </h4>
                    <span class="text-theme-sm font-medium text-gray-500 dark:text-gray-400">Valeur Stock</span>
                </div>
                <span class="flex items-center gap-1 text-theme-sm font-medium text-green-600 dark:text-green-400">
                    +8.1%
                    <svg class="fill-current" width="10" height="11" viewBox="0 0 10 11">
                        <path
                            d="M4.35716 2.47737L0.908974 5.82987L5.0443e-07 4.94612L5 0.0848689L10 4.94612L9.09103 5.82987L5.64284 2.47737L5.64284 10.0849L4.35716 10.0849L4.35716 2.47737Z" />
                    </svg>
                </span>
            </div>
        </div>
        <!-- Card Item End -->
    </div>

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
