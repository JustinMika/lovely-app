@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
    <!-- Breadcrumb Start -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Rapports de Ventes
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium" href="{{ route('dashboard') }}">Tableau de bord /</a>
                </li>
                <li class="font-medium text-primary">Rapports</li>
            </ol>
        </nav>
    </div>
    <!-- Breadcrumb End -->

    <!-- Content Area -->
    <div class="rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
        <div class="max-w-full overflow-x-auto">
            <h4 class="text-xl font-semibold text-black dark:text-white mb-6">
                Analyse des Ventes
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="rounded-sm border border-stroke bg-white p-4 shadow-default dark:border-strokedark dark:bg-boxdark">
                    <div class="text-center">
                        <h4 class="text-title-md font-bold text-meta-3">
                            €245,680
                        </h4>
                        <span class="text-sm font-medium">CA ce Mois</span>
                        <div class="text-meta-3 text-sm mt-1">+12.5%</div>
                    </div>
                </div>
                
                <div class="rounded-sm border border-stroke bg-white p-4 shadow-default dark:border-strokedark dark:bg-boxdark">
                    <div class="text-center">
                        <h4 class="text-title-md font-bold text-black dark:text-white">
                            2,847
                        </h4>
                        <span class="text-sm font-medium">Ventes Totales</span>
                        <div class="text-meta-5 text-sm mt-1">+8.2%</div>
                    </div>
                </div>
                
                <div class="rounded-sm border border-stroke bg-white p-4 shadow-default dark:border-strokedark dark:bg-boxdark">
                    <div class="text-center">
                        <h4 class="text-title-md font-bold text-meta-6">
                            €86.30
                        </h4>
                        <span class="text-sm font-medium">Panier Moyen</span>
                        <div class="text-meta-3 text-sm mt-1">+3.8%</div>
                    </div>
                </div>
            </div>

            <p class="text-gray-600 dark:text-gray-400 text-center py-8">
                Interface de rapports de ventes en cours de développement...
            </p>
        </div>
    </div>
</div>
@endsection
