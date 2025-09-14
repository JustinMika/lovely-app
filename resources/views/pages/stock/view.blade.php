@extends('layouts.app')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">
        Consultation du Stock
    </h2>
    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300" 
                   href="{{ route('dashboard') }}">Tableau de bord /</a>
            </li>
            <li class="font-medium text-gray-800 dark:text-white/90">Stock</li>
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
                    <path d="M35 10a5 5 0 0 0-5-5H10a5 5 0 0 0-5 5v20a5 5 0 0 0 5 5h20a5 5 0 0 0 5-5V10ZM30 7.5a2.5 2.5 0 0 1 2.5 2.5v20a2.5 2.5 0 0 1-2.5 2.5H10A2.5 2.5 0 0 1 7.5 30V10A2.5 2.5 0 0 1 10 7.5h20Z"/>
                    <path d="M15 15h10v2.5H15V15ZM15 20h10v2.5H15V20ZM15 25h7.5v2.5H15V25Z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    Articles
                </h3>
                <span class="block text-theme-xs text-gray-500 dark:text-gray-400">
                    En stock
                </span>
            </div>
        </div>

        <div class="flex items-end justify-between">
            <div>
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    1,245
                </h4>
            </div>
            <span class="flex items-center gap-1 rounded-full bg-success-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">
                <svg class="fill-current" width="13" height="12" viewBox="0 0 13 12">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.06462 1.62393C6.20193 1.47072 6.40135 1.37432 6.62329 1.37432C6.6236 1.37432 6.62391 1.37432 6.62422 1.37432C6.81631 1.37415 7.00845 1.44731 7.15505 1.5938L10.1551 4.5918C10.4481 4.88459 10.4483 5.35946 10.1555 5.65246C9.86273 5.94546 9.38785 5.94562 9.09486 5.65283L7.37329 3.93247L7.37329 10.125C7.37329 10.5392 7.03751 10.875 6.62329 10.875C6.20908 10.875 5.87329 10.5392 5.87329 10.125L5.87329 3.93578L4.15516 5.65281C3.86218 5.94561 3.3873 5.94546 3.0945 5.65248C2.8017 5.35949 2.80185 4.88462 3.09484 4.59182L6.06462 1.62393Z"/>
                </svg>
                +5.2%
            </span>
        </div>
    </div>
    <!-- Metric Item End -->

    <!-- Metric Item Start -->
    <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="mb-6 flex items-center gap-3">
            <div class="h-10 w-10">
                <svg class="fill-error-600 dark:fill-error-400" width="40" height="40" viewBox="0 0 40 40">
                    <path d="M20 5C11.716 5 5 11.716 5 20s6.716 15 15 15 15-6.716 15-15S28.284 5 20 5Zm0 27.5c-6.893 0-12.5-5.607-12.5-12.5S13.107 7.5 20 7.5 32.5 13.107 32.5 20 26.893 32.5 20 32.5Z"/>
                    <path d="M20 12.5c-1.381 0-2.5 1.119-2.5 2.5v7.5c0 1.381 1.119 2.5 2.5 2.5s2.5-1.119 2.5-2.5V15c0-1.381-1.119-2.5-2.5-2.5ZM20 27.5c-1.381 0-2.5 1.119-2.5 2.5s1.119 2.5 2.5 2.5 2.5-1.119 2.5-2.5-1.119-2.5-2.5-2.5Z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    Alertes
                </h3>
                <span class="block text-theme-xs text-gray-500 dark:text-gray-400">
                    Stock faible
                </span>
            </div>
        </div>

        <div class="flex items-end justify-between">
            <div>
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    23
                </h4>
            </div>
            <span class="flex items-center gap-1 rounded-full bg-error-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500">
                <svg class="fill-current" width="12" height="12" viewBox="0 0 12 12">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5.31462 10.3761C5.45194 10.5293 5.65136 10.6257 5.87329 10.6257C5.8736 10.6257 5.8739 10.6257 5.87421 10.6257C6.0663 10.6259 6.25845 10.5527 6.40505 10.4062L9.40514 7.4082C9.69814 7.11541 9.69831 6.64054 9.40552 6.34754C9.11273 6.05454 8.63785 6.05438 8.34486 6.34717L6.62329 8.06753L6.62329 1.875C6.62329 1.46079 6.28751 1.125 5.87329 1.125C5.45908 1.125 5.12329 1.46079 5.12329 1.875L5.12329 8.06422L3.40516 6.34719C3.11218 6.05439 2.6373 6.05454 2.3445 6.34752C2.0517 6.64051 2.05185 7.11538 2.34484 7.40818L5.31462 10.3761Z"/>
                </svg>
                +12
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
                    <path d="M27.5 17.5c0 1.381-1.119 2.5-2.5 2.5h-7.5V12.5c0-1.381 1.119-2.5 2.5-2.5s2.5 1.119 2.5 2.5V15h2.5c1.381 0 2.5 1.119 2.5 2.5ZM12.5 22.5c0-1.381 1.119-2.5 2.5-2.5h7.5v7.5c0 1.381-1.119 2.5-2.5 2.5s-2.5-1.119-2.5-2.5V25H15c-1.381 0-2.5-1.119-2.5-2.5Z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    Valeur
                </h3>
                <span class="block text-theme-xs text-gray-500 dark:text-gray-400">
                    Total stock
                </span>
            </div>
        </div>

        <div class="flex items-end justify-between">
            <div>
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    €125,430
                </h4>
            </div>
            <span class="flex items-center gap-1 rounded-full bg-success-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">
                <svg class="fill-current" width="13" height="12" viewBox="0 0 13 12">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.06462 1.62393C6.20193 1.47072 6.40135 1.37432 6.62329 1.37432C6.6236 1.37432 6.62391 1.37432 6.62422 1.37432C6.81631 1.37415 7.00845 1.44731 7.15505 1.5938L10.1551 4.5918C10.4481 4.88459 10.4483 5.35946 10.1555 5.65246C9.86273 5.94546 9.38785 5.94562 9.09486 5.65283L7.37329 3.93247L7.37329 10.125C7.37329 10.5392 7.03751 10.875 6.62329 10.875C6.20908 10.875 5.87329 10.5392 5.87329 10.125L5.87329 3.93578L4.15516 5.65281C3.86218 5.94561 3.3873 5.94546 3.0945 5.65248C2.8017 5.35949 2.80185 4.88462 3.09484 4.59182L6.06462 1.62393Z"/>
                </svg>
                +8.1%
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
                    <path d="M20 12.5c-1.036 0-1.875.839-1.875 1.875v8.75c0 1.036.839 1.875 1.875 1.875s1.875-.839 1.875-1.875v-8.75c0-1.036-.839-1.875-1.875-1.875ZM20 27.5c-1.036 0-1.875.839-1.875 1.875S18.964 31.25 20 31.25s1.875-.839 1.875-1.875S21.036 27.5 20 27.5Z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-gray-800 dark:text-white/90">
                    Mouvements
                </h3>
                <span class="block text-theme-xs text-gray-500 dark:text-gray-400">
                    Aujourd'hui
                </span>
            </div>
        </div>

        <div class="flex items-end justify-between">
            <div>
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    45
                </h4>
            </div>
            <span class="flex items-center gap-1 rounded-full bg-warning-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-500">
                <svg class="fill-current" width="13" height="12" viewBox="0 0 13 12">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.06462 1.62393C6.20193 1.47072 6.40135 1.37432 6.62329 1.37432C6.6236 1.37432 6.62391 1.37432 6.62422 1.37432C6.81631 1.37415 7.00845 1.44731 7.15505 1.5938L10.1551 4.5918C10.4481 4.88459 10.4483 5.35946 10.1555 5.65246C9.86273 5.94546 9.38785 5.94562 9.09486 5.65283L7.37329 3.93247L7.37329 10.125C7.37329 10.5392 7.03751 10.875 6.62329 10.875C6.20908 10.875 5.87329 10.5392 5.87329 10.125L5.87329 3.93578L4.15516 5.65281C3.86218 5.94561 3.3873 5.94546 3.0945 5.65248C2.8017 5.35949 2.80185 4.88462 3.09484 4.59182L6.06462 1.62393Z"/>
                </svg>
                +15
            </span>
        </div>
    </div>
    <!-- Metric Item End -->
</div>

<!-- Main Content -->
<div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            État du Stock
        </h3>
        <div class="flex gap-3">
            <a href="{{ route('stock.alerts') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-theme-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-white/[0.03] dark:text-gray-300 dark:hover:bg-white/[0.05]">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                Alertes
            </a>
            <a href="{{ route('stock.movements') }}" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-theme-sm font-medium text-white hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Mouvements
            </a>
        </div>
    </div>

    <div class="text-center py-12">
        <div class="mx-auto mb-4 h-16 w-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
        </div>
        <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-2">
            Interface en développement
        </h4>
        <p class="text-gray-500 dark:text-gray-400">
            La consultation détaillée du stock sera bientôt disponible.
        </p>
    </div>
</div>
@endsection
