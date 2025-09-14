@extends('layouts.app')

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
                    <a class="font-medium" href="{{ route('dashboard') }}">Tableau de bord /</a>
                </li>
                <li class="font-medium text-primary">Utilisateurs</li>
            </ol>
        </nav>
    </div>
    <!-- Breadcrumb End -->

    <!-- Content Area -->
    <div class="rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
        <div class="max-w-full overflow-x-auto">
            <div class="flex justify-between items-center mb-6">
                <h4 class="text-xl font-semibold text-black dark:text-white">
                    Administration des Utilisateurs
                </h4>
                <a href="{{ route('users.create') }}" class="inline-flex items-center justify-center rounded-md bg-primary py-2 px-4 text-center font-medium text-white hover:bg-opacity-90">
                    Nouvel Utilisateur
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="rounded-sm border border-stroke bg-white p-4 shadow-default dark:border-strokedark dark:bg-boxdark">
                    <div class="text-center">
                        <h4 class="text-title-md font-bold text-black dark:text-white">
                            12
                        </h4>
                        <span class="text-sm font-medium">Utilisateurs Actifs</span>
                    </div>
                </div>
                
                <div class="rounded-sm border border-stroke bg-white p-4 shadow-default dark:border-strokedark dark:bg-boxdark">
                    <div class="text-center">
                        <h4 class="text-title-md font-bold text-meta-3">
                            5
                        </h4>
                        <span class="text-sm font-medium">Administrateurs</span>
                    </div>
                </div>
                
                <div class="rounded-sm border border-stroke bg-white p-4 shadow-default dark:border-strokedark dark:bg-boxdark">
                    <div class="text-center">
                        <h4 class="text-title-md font-bold text-meta-5">
                            7
                        </h4>
                        <span class="text-sm font-medium">Employés</span>
                    </div>
                </div>
                
                <div class="rounded-sm border border-stroke bg-white p-4 shadow-default dark:border-strokedark dark:bg-boxdark">
                    <div class="text-center">
                        <h4 class="text-title-md font-bold text-meta-6">
                            3
                        </h4>
                        <span class="text-sm font-medium">Rôles Définis</span>
                    </div>
                </div>
            </div>

            <p class="text-gray-600 dark:text-gray-400 text-center py-8">
                Interface de gestion des utilisateurs en cours de développement...
            </p>
        </div>
    </div>
</div>
@endsection
