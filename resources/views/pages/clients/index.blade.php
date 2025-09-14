@extends('layouts.app')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">
        Gestion des Clients
    </h2>
    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300" 
                   href="{{ route('dashboard') }}">Tableau de bord /</a>
            </li>
            <li class="font-medium text-gray-800 dark:text-white/90">Clients</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<!-- Metric Group -->
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 xl:grid-cols-4 mb-6">
    <!-- Metric Item Start -->
    <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="mb-6 flex items-center gap-3">
            <div class="h-10 w-10">
                <svg class="fill-blue-600 dark:fill-blue-400" width="40" height="40" viewBox="0 0 40 40">
                    <path d="M20 5C11.716 5 5 11.716 5 20s6.716 15 15 15 15-6.716 15-15S28.284 5 20 5Zm0 27.5c-6.893 0-12.5-5.607-12.5-12.5S13.107 7.5 20 7.5 32.5 13.107 32.5 20 26.893 32.5 20 32.5Z"/>
                    <path d="M20 12.5c-2.761 0-5 2.239-5 5s2.239 5 5 5 5-2.239 5-5-2.239-5-5-5Zm0 7.5c-1.381 0-2.5-1.119-2.5-2.5s1.119-2.5 2.5-2.5 2.5 1.119 2.5 2.5-1.119 2.5-2.5 2.5Z"/>
                    <path d="M20 25c-4.142 0-7.5 3.358-7.5 7.5h2.5c0-2.761 2.239-5 5-5s5 2.239 5 5h2.5c0-4.142-3.358-7.5-7.5-7.5Z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    Clients
                </h3>
                <span class="block text-theme-xs text-gray-500 dark:text-gray-400">
                    Total actifs
                </span>
            </div>
        </div>

        <div class="flex items-end justify-between">
            <div>
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    1,847
                </h4>
            </div>
            <span class="flex items-center gap-1 rounded-full bg-success-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">
                <svg class="fill-current" width="13" height="12" viewBox="0 0 13 12">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.06462 1.62393C6.20193 1.47072 6.40135 1.37432 6.62329 1.37432C6.6236 1.37432 6.62391 1.37432 6.62422 1.37432C6.81631 1.37415 7.00845 1.44731 7.15505 1.5938L10.1551 4.5918C10.4481 4.88459 10.4483 5.35946 10.1555 5.65246C9.86273 5.94546 9.38785 5.94562 9.09486 5.65283L7.37329 3.93247L7.37329 10.125C7.37329 10.5392 7.03751 10.875 6.62329 10.875C6.20908 10.875 5.87329 10.5392 5.87329 10.125L5.87329 3.93578L4.15516 5.65281C3.86218 5.94561 3.3873 5.94546 3.0945 5.65248C2.8017 5.35949 2.80185 4.88462 3.09484 4.59182L6.06462 1.62393Z"/>
                </svg>
                +8.3%
            </span>
        </div>
    </div>
    <!-- Metric Item End -->

    <!-- Metric Item Start -->
    <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="mb-6 flex items-center gap-3">
            <div class="h-10 w-10">
                <svg class="fill-success-600 dark:fill-success-400" width="40" height="40" viewBox="0 0 40 40">
                    <path d="M20 5C11.716 5 5 11.716 5 20s6.716 15 15 15 15-6.716 15-15S28.284 5 20 5Zm0 27.5c-6.893 0-12.5-5.607-12.5-12.5S13.107 7.5 20 7.5 32.5 13.107 32.5 20 26.893 32.5 20 32.5Z"/>
                    <path d="M27.5 17.5c0 1.381-1.119 2.5-2.5 2.5h-7.5V12.5c0-1.381 1.119-2.5 2.5-2.5s2.5 1.119 2.5 2.5V15h2.5c1.381 0 2.5 1.119 2.5 2.5Z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    Nouveaux
                </h3>
                <span class="block text-theme-xs text-gray-500 dark:text-gray-400">
                    Ce mois
                </span>
            </div>
        </div>

        <div class="flex items-end justify-between">
            <div>
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    234
                </h4>
            </div>
            <span class="flex items-center gap-1 rounded-full bg-success-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">
                <svg class="fill-current" width="13" height="12" viewBox="0 0 13 12">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.06462 1.62393C6.20193 1.47072 6.40135 1.37432 6.62329 1.37432C6.6236 1.37432 6.62391 1.37432 6.62422 1.37432C6.81631 1.37415 7.00845 1.44731 7.15505 1.5938L10.1551 4.5918C10.4481 4.88459 10.4483 5.35946 10.1555 5.65246C9.86273 5.94546 9.38785 5.94562 9.09486 5.65283L7.37329 3.93247L7.37329 10.125C7.37329 10.5392 7.03751 10.875 6.62329 10.875C6.20908 10.875 5.87329 10.5392 5.87329 10.125L5.87329 3.93578L4.15516 5.65281C3.86218 5.94561 3.3873 5.94546 3.0945 5.65248C2.8017 5.35949 2.80185 4.88462 3.09484 4.59182L6.06462 1.62393Z"/>
                </svg>
                +15.2%
            </span>
        </div>
    </div>
    <!-- Metric Item End -->

    <!-- Metric Item Start -->
    <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="mb-6 flex items-center gap-3">
            <div class="h-10 w-10">
                <svg class="fill-warning-600 dark:fill-warning-400" width="40" height="40" viewBox="0 0 40 40">
                    <path d="M20 5C11.716 5 5 11.716 5 20s6.716 15 15 15 15-6.716 15-15S28.284 5 20 5Zm0 27.5c-6.893 0-12.5-5.607-12.5-12.5S13.107 7.5 20 7.5 32.5 13.107 32.5 20 26.893 32.5 20 32.5Z"/>
                    <path d="M20 12.5c-1.036 0-1.875.839-1.875 1.875v8.75c0 1.036.839 1.875 1.875 1.875s1.875-.839 1.875-1.875v-8.75c0-1.036-.839-1.875-1.875-1.875Z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    Chiffre
                </h3>
                <span class="block text-theme-xs text-gray-500 dark:text-gray-400">
                    d'Affaires
                </span>
            </div>
        </div>

        <div class="flex items-end justify-between">
            <div>
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    €89,340
                </h4>
            </div>
            <span class="flex items-center gap-1 rounded-full bg-warning-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-500">
                <svg class="fill-current" width="13" height="12" viewBox="0 0 13 12">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.06462 1.62393C6.20193 1.47072 6.40135 1.37432 6.62329 1.37432C6.6236 1.37432 6.62391 1.37432 6.62422 1.37432C6.81631 1.37415 7.00845 1.44731 7.15505 1.5938L10.1551 4.5918C10.4481 4.88459 10.4483 5.35946 10.1555 5.65246C9.86273 5.94546 9.38785 5.94562 9.09486 5.65283L7.37329 3.93247L7.37329 10.125C7.37329 10.5392 7.03751 10.875 6.62329 10.875C6.20908 10.875 5.87329 10.5392 5.87329 10.125L5.87329 3.93578L4.15516 5.65281C3.86218 5.94561 3.3873 5.94546 3.0945 5.65248C2.8017 5.35949 2.80185 4.88462 3.09484 4.59182L6.06462 1.62393Z"/>
                </svg>
                +6.7%
            </span>
        </div>
    </div>
    <!-- Metric Item End -->

    <!-- Metric Item Start -->
    <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="mb-6 flex items-center gap-3">
            <div class="h-10 w-10">
                <svg class="fill-purple-600 dark:fill-purple-400" width="40" height="40" viewBox="0 0 40 40">
                    <path d="M20 5C11.716 5 5 11.716 5 20s6.716 15 15 15 15-6.716 15-15S28.284 5 20 5Zm0 27.5c-6.893 0-12.5-5.607-12.5-12.5S13.107 7.5 20 7.5 32.5 13.107 32.5 20 26.893 32.5 20 32.5Z"/>
                    <path d="M25 15l-7.5 7.5L15 20l-2.5 2.5L20 30l12.5-12.5L25 15Z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    VIP
                </h3>
                <span class="block text-theme-xs text-gray-500 dark:text-gray-400">
                    Clients premium
                </span>
            </div>
        </div>

        <div class="flex items-end justify-between">
            <div>
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    12
                </h4>
            </div>
            <span class="flex items-center gap-1 rounded-full bg-purple-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-purple-600 dark:bg-purple-500/15 dark:text-purple-500">
                +2
            </span>
        </div>
    </div>
    <!-- Metric Item End -->
</div>

<!-- Main Content -->
<div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            Base de Données Clients
        </h3>
        <div class="flex gap-3">
            <a href="{{ route('clients.create') }}" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-theme-sm font-medium text-white hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Nouveau Client
            </a>
        </div>
    </div>

    <div class="text-center py-12">
        <div class="mx-auto mb-4 h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
            </svg>
        </div>
        <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-2">
            Interface en développement
        </h4>
        <p class="text-gray-500 dark:text-gray-400">
            La gestion des clients sera bientôt disponible.
        </p>
    </div>
</div>
@endsection
