@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
    <!-- Breadcrumb Start -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Paramètres Généraux
        </h2>
        <nav>
            <ol class="flex items-center gap-2">
                <li>
                    <a class="font-medium" href="{{ route('dashboard') }}">Tableau de bord /</a>
                </li>
                <li class="font-medium text-primary">Paramètres</li>
            </ol>
        </nav>
    </div>
    <!-- Breadcrumb End -->

    <!-- Content Area -->
    <div class="rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
        <div class="max-w-full overflow-x-auto">
            <h4 class="text-xl font-semibold text-black dark:text-white mb-6">
                Configuration de la Boutique
            </h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="rounded-sm border border-stroke bg-white p-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                    <h5 class="text-lg font-semibold text-black dark:text-white mb-4">
                        Informations Générales
                    </h5>
                    <div class="space-y-4">
                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">Nom de la Boutique</label>
                            <input type="text" value="Lovely Boutique" class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                        </div>
                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">Devise</label>
                            <select class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                                <option value="EUR">Euro (€)</option>
                                <option value="USD">Dollar ($)</option>
                                <option value="GBP">Livre (£)</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="rounded-sm border border-stroke bg-white p-6 shadow-default dark:border-strokedark dark:bg-boxdark">
                    <h5 class="text-lg font-semibold text-black dark:text-white mb-4">
                        Paramètres de Vente
                    </h5>
                    <div class="space-y-4">
                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">TVA par Défaut (%)</label>
                            <input type="number" value="20" class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                        </div>
                        <div>
                            <label class="mb-2.5 block text-black dark:text-white">Seuil Stock Faible</label>
                            <input type="number" value="10" class="w-full rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 font-medium outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button class="inline-flex items-center justify-center rounded-md bg-primary py-2 px-6 text-center font-medium text-white hover:bg-opacity-90">
                    Sauvegarder
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
