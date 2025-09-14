@extends('layouts.app')

@section('content')
<!-- Breadcrumb Start -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">
        Gestion des Ventes
    </h2>
    <nav>
        <ol class="flex items-center gap-2">
            <li>
                <a class="font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300" 
                   href="{{ route('dashboard') }}">Tableau de bord /</a>
            </li>
            <li class="font-medium text-gray-800 dark:text-white/90">Ventes</li>
        </ol>
    </nav>
</div>
<!-- Breadcrumb End -->

<!-- Stats Cards -->
<div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-4 2xl:gap-7.5 mb-6">
    <!-- Card Item Start -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/20">
            <svg class="fill-green-600 dark:fill-green-400" width="22" height="22" viewBox="0 0 22 22">
                <path d="M11 1.54199C5.04902 1.54199 0.25 6.34101 0.25 12.292C0.25 18.243 5.04902 23.042 11 23.042C16.951 23.042 21.75 18.243 21.75 12.292C21.75 6.34101 16.951 1.54199 11 1.54199ZM11 21.542C5.87745 21.542 1.75 17.4146 1.75 12.292C1.75 7.16945 5.87745 3.04199 11 3.04199C16.1226 3.04199 20.25 7.16945 20.25 12.292C20.25 17.4146 16.1226 21.542 11 21.542Z"/>
                <path d="M15.125 9.79199C15.539 9.79199 15.875 9.45599 15.875 9.04199C15.875 8.62799 15.539 8.29199 15.125 8.29199H11.75V6.91699C11.75 6.50299 11.414 6.16699 11 6.16699C10.586 6.16699 10.25 6.50299 10.25 6.91699V8.29199H6.875C6.461 8.29199 6.125 8.62799 6.125 9.04199C6.125 9.45599 6.461 9.79199 6.875 9.79199H10.25V15.667C10.25 16.081 10.586 16.417 11 16.417C11.414 16.417 11.75 16.081 11.75 15.667V9.79199H15.125Z"/>
            </svg>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-gray-800 dark:text-white/90">
                    €15,240
                </h4>
                <span class="text-theme-sm font-medium text-gray-500 dark:text-gray-400">Ventes Aujourd'hui</span>
            </div>
            <span class="flex items-center gap-1 text-theme-sm font-medium text-green-600 dark:text-green-400">
                +12.5%
                <svg class="fill-current" width="10" height="11" viewBox="0 0 10 11">
                    <path d="M4.35716 2.47737L0.908974 5.82987L5.0443e-07 4.94612L5 0.0848689L10 4.94612L9.09103 5.82987L5.64284 2.47737L5.64284 10.0849L4.35716 10.0849L4.35716 2.47737Z"/>
                </svg>
            </span>
        </div>
    </div>
    <!-- Card Item End -->

    <!-- Card Item Start -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/20">
            <svg class="fill-blue-600 dark:fill-blue-400" width="22" height="22" viewBox="0 0 22 22">
                <path d="M21.1 6.11a1.4 1.4 0 0 0-1.13-.83L12.23 4.7a1.4 1.4 0 0 0-.46 0L4.03 5.28a1.4 1.4 0 0 0-1.13.83 1.4 1.4 0 0 0 .08 1.46l1.24 1.76v7.21a1.4 1.4 0 0 0 .7 1.21l6.3 3.64a1.4 1.4 0 0 0 1.4 0l6.3-3.64a1.4 1.4 0 0 0 .7-1.21V9.33l1.24-1.76a1.4 1.4 0 0 0 .08-1.46Z"/>
            </svg>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-gray-800 dark:text-white/90">
                    127
                </h4>
                <span class="text-theme-sm font-medium text-gray-500 dark:text-gray-400">Transactions</span>
            </div>
            <span class="flex items-center gap-1 text-theme-sm font-medium text-green-600 dark:text-green-400">
                +8.2%
                <svg class="fill-current" width="10" height="11" viewBox="0 0 10 11">
                    <path d="M4.35716 2.47737L0.908974 5.82987L5.0443e-07 4.94612L5 0.0848689L10 4.94612L9.09103 5.82987L5.64284 2.47737L5.64284 10.0849L4.35716 10.0849L4.35716 2.47737Z"/>
                </svg>
            </span>
        </div>
    </div>
    <!-- Card Item End -->

    <!-- Card Item Start -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900/20">
            <svg class="fill-purple-600 dark:fill-purple-400" width="22" height="22" viewBox="0 0 22 22">
                <path d="M11 1.1C5.4 1.1 1.1 5.4 1.1 11S5.4 20.9 11 20.9 20.9 16.6 20.9 11 16.6 1.1 11 1.1ZM11 19.7c-4.8 0-8.7-3.9-8.7-8.7S6.2 2.3 11 2.3s8.7 3.9 8.7 8.7-3.9 8.7-8.7 8.7Z"/>
                <path d="M11 5.5c-.4 0-.8.3-.8.8v4.4l3.1 1.8c.4.2.8.1 1-.3.2-.4.1-.8-.3-1L11 9.4V6.3c0-.4-.3-.8-.8-.8Z"/>
            </svg>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-gray-800 dark:text-white/90">
                    €120.50
                </h4>
                <span class="text-theme-sm font-medium text-gray-500 dark:text-gray-400">Panier Moyen</span>
            </div>
            <span class="flex items-center gap-1 text-theme-sm font-medium text-green-600 dark:text-green-400">
                +3.8%
                <svg class="fill-current" width="10" height="11" viewBox="0 0 10 11">
                    <path d="M4.35716 2.47737L0.908974 5.82987L5.0443e-07 4.94612L5 0.0848689L10 4.94612L9.09103 5.82987L5.64284 2.47737L5.64284 10.0849L4.35716 10.0849L4.35716 2.47737Z"/>
                </svg>
            </span>
        </div>
    </div>
    <!-- Card Item End -->

    <!-- Card Item Start -->
    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
        <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-900/20">
            <svg class="fill-orange-600 dark:fill-orange-400" width="22" height="22" viewBox="0 0 22 22">
                <path d="M16.1 11.8H5.9a1.1 1.1 0 0 0 0 2.2h10.2a1.1 1.1 0 0 0 0-2.2Z"/>
                <path d="M11 1.1C5.4 1.1 1.1 5.4 1.1 11S5.4 20.9 11 20.9 20.9 16.6 20.9 11 16.6 1.1 11 1.1ZM11 19.7c-4.8 0-8.7-3.9-8.7-8.7S6.2 2.3 11 2.3s8.7 3.9 8.7 8.7-3.9 8.7-8.7 8.7Z"/>
            </svg>
        </div>
        <div class="mt-4 flex items-end justify-between">
            <div>
                <h4 class="text-title-md font-bold text-gray-800 dark:text-white/90">
                    45
                </h4>
                <span class="text-theme-sm font-medium text-gray-500 dark:text-gray-400">Commandes en Attente</span>
            </div>
            <span class="flex items-center gap-1 text-theme-sm font-medium text-orange-600 dark:text-orange-400">
                -2.1%
                <svg class="fill-current rotate-180" width="10" height="11" viewBox="0 0 10 11">
                    <path d="M4.35716 2.47737L0.908974 5.82987L5.0443e-07 4.94612L5 0.0848689L10 4.94612L9.09103 5.82987L5.64284 2.47737L5.64284 10.0849L4.35716 10.0849L4.35716 2.47737Z"/>
                </svg>
            </span>
        </div>
    </div>
    <!-- Card Item End -->
</div>

<!-- Main Content -->
<div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-theme-xs dark:border-gray-800 dark:bg-gray-900">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
        <h3 class="text-title-md2 font-bold text-gray-800 dark:text-white/90">
            Tableau de Bord des Ventes
        </h3>
        <div class="flex gap-3">
            <button class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-theme-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exporter
            </button>
            <a href="{{ route('sales.create') }}" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-theme-sm font-medium text-white hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Nouvelle Vente
            </a>
        </div>
    </div>

    <!-- Content placeholder -->
    <div class="flex flex-col items-center justify-center py-16">
        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800">
            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
        </div>
        <h4 class="mb-2 text-lg font-semibold text-gray-800 dark:text-white/90">Interface en développement</h4>
        <p class="text-center text-gray-500 dark:text-gray-400">
            Le module de gestion des ventes sera bientôt disponible.<br>
            Fonctionnalités prévues : création de ventes, gestion des factures, suivi des paiements.
        </p>
    </div>
</div>
@endsection
