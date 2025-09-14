@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6 xl:grid-cols-4 2xl:gap-7.5">
        <!-- Total Sales Card -->
        <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-brand-100 dark:bg-brand-900">
                <svg class="fill-brand-500 dark:fill-brand-400" width="22" height="16" viewBox="0 0 22 16" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M11 15.1156C4.19376 15.1156 0.825012 8.61876 0.687512 8.34376C0.584387 8.13751 0.584387 7.86251 0.687512 7.65626C0.825012 7.38126 4.19376 0.918762 11 0.918762C17.8063 0.918762 21.175 7.38126 21.3125 7.65626C21.4156 7.86251 21.4156 8.13751 21.3125 8.34376C21.175 8.61876 17.8063 15.1156 11 15.1156ZM2.26876 8.00001C3.02501 9.27189 5.98126 13.5688 11 13.5688C16.0188 13.5688 18.975 9.27189 19.7313 8.00001C18.975 6.72814 16.0188 2.43126 11 2.43126C5.98126 2.43126 3.02501 6.72814 2.26876 8.00001Z" />
                </svg>
            </div>
            <div class="mt-4 flex items-end justify-between">
                <div>
                    <h4 class="text-title-md font-bold text-black dark:text-white">
                        {{ number_format($metrics['total_sales']) }}
                    </h4>
                    <span class="text-sm font-medium">Total des ventes</span>
                </div>
                <span class="flex items-center gap-1 text-sm font-medium text-green-500">
                    {{ $metrics['revenue_growth'] }}%
                    <svg class="fill-green-500" width="10" height="11" viewBox="0 0 10 11" fill="none">
                        <path
                            d="M4.35716 2.47737L0.908974 5.82987L5.0443e-07 4.94612L5 0.0848689L10 4.94612L9.09103 5.82987L5.64284 2.47737L5.64284 10.0849L4.35716 10.0849L4.35716 2.47737Z" />
                    </svg>
                </span>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                <svg class="fill-green-500 dark:fill-green-400" width="20" height="22" viewBox="0 0 20 22"
                    fill="none">
                    <path
                        d="M11.7531 16.4312C10.3781 16.4312 9.27808 17.5312 9.27808 18.9062C9.27808 20.2812 10.3781 21.3812 11.7531 21.3812C13.1281 21.3812 14.2281 20.2812 14.2281 18.9062C14.2281 17.5656 13.1281 16.4312 11.7531 16.4312Z" />
                </svg>
            </div>
            <div class="mt-4 flex items-end justify-between">
                <div>
                    <h4 class="text-title-md font-bold text-black dark:text-white">
                        {{ number_format($metrics['total_revenue'], 0, ',', ' ') }} FCFA
                    </h4>
                    <span class="text-sm font-medium">Chiffre d'affaires</span>
                </div>
                <span class="flex items-center gap-1 text-sm font-medium text-green-500">
                    {{ $metrics['revenue_growth'] }}%
                </span>
            </div>
        </div>

        <!-- Total Clients Card -->
        <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                <svg class="fill-blue-500 dark:fill-blue-400" width="22" height="18" viewBox="0 0 22 18"
                    fill="none">
                    <path
                        d="M7.18418 8.03751C9.31543 8.03751 11.0686 6.35313 11.0686 4.25626C11.0686 2.15938 9.31543 0.475006 7.18418 0.475006C5.05293 0.475006 3.2998 2.15938 3.2998 4.25626C3.2998 6.35313 5.05293 8.03751 7.18418 8.03751Z" />
                </svg>
            </div>
            <div class="mt-4 flex items-end justify-between">
                <div>
                    <h4 class="text-title-md font-bold text-black dark:text-white">
                        {{ number_format($metrics['total_clients']) }}
                    </h4>
                    <span class="text-sm font-medium">Total clients</span>
                </div>
                <span class="flex items-center gap-1 text-sm font-medium text-green-500">
                    {{ $metrics['client_growth'] }}%
                </span>
            </div>
        </div>

        <!-- Stock Alerts Card -->
        <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex h-11.5 w-11.5 items-center justify-center rounded-full bg-red-100 dark:bg-red-900">
                <svg class="fill-red-500 dark:fill-red-400" width="22" height="22" viewBox="0 0 22 22"
                    fill="none">
                    <path
                        d="M21.25 10.3906H20.3125V6.59375C20.3125 6.21562 20.0031 5.90625 19.625 5.90625H17.875V4.8125C17.875 4.43437 17.5656 4.125 17.1875 4.125H4.8125C4.43437 4.125 4.125 4.43437 4.125 4.8125V5.90625H2.375C1.99687 5.90625 1.6875 6.21562 1.6875 6.59375V10.3906H0.75C0.371875 10.3906 0.0625 10.7 0.0625 11.0781V17.1875C0.0625 17.5656 0.371875 17.875 0.75 17.875H21.25C21.6281 17.875 21.9375 17.5656 21.9375 17.1875V11.0781C21.9375 10.7 21.6281 10.3906 21.25 10.3906Z" />
                </svg>
            </div>
            <div class="mt-4 flex items-end justify-between">
                <div>
                    <h4 class="text-title-md font-bold text-black dark:text-white">
                        {{ $metrics['low_stock_alerts'] }}
                    </h4>
                    <span class="text-sm font-medium">Alertes stock</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities Section -->
    <div class="mt-4 grid grid-cols-12 gap-4 md:mt-6 md:gap-6 2xl:mt-7.5 2xl:gap-7.5">
        <div class="col-span-12">
            <div
                class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-7.5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-7.5">
                <div class="mb-3 justify-between gap-4 sm:flex">
                    <div>
                        <h5 class="text-xl font-semibold text-black dark:text-white">
                            Activités récentes
                        </h5>
                    </div>
                </div>

                <div class="flex flex-col">
                    <div class="grid grid-cols-4 rounded-sm bg-gray-2 dark:bg-meta-4">
                        <div class="p-2.5 xl:p-5">
                            <h5 class="text-sm font-medium uppercase xsm:text-base">Type</h5>
                        </div>
                        <div class="p-2.5 text-center xl:p-5">
                            <h5 class="text-sm font-medium uppercase xsm:text-base">Description</h5>
                        </div>
                        <div class="p-2.5 text-center xl:p-5">
                            <h5 class="text-sm font-medium uppercase xsm:text-base">Montant</h5>
                        </div>
                        <div class="p-2.5 text-center xl:p-5">
                            <h5 class="text-sm font-medium uppercase xsm:text-base">Heure</h5>
                        </div>
                    </div>

                    @foreach ($recentActivities as $activity)
                        <div class="grid grid-cols-4 border-b border-stroke dark:border-strokedark">
                            <div class="flex items-center gap-3 p-2.5 xl:p-5">
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 bg-green-100 text-green-600 rounded-full text-sm font-medium dark:bg-green-900 dark:text-green-400">
                                    {{ strtoupper(substr($activity['type'], 0, 1)) }}
                                </span>
                                <p class="text-black dark:text-white">{{ ucfirst($activity['type']) }}</p>
                            </div>

                            <div class="flex items-center justify-center p-2.5 xl:p-5">
                                <p class="text-black dark:text-white">{{ $activity['description'] }}</p>
                            </div>

                            <div class="flex items-center justify-center p-2.5 xl:p-5">
                                <p class="text-meta-3">
                                    @if (isset($activity['amount']))
                                        {{ number_format($activity['amount'], 0, ',', ' ') }} FCFA
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>

                            <div class="flex items-center justify-center p-2.5 xl:p-5">
                                <p class="text-meta-3">{{ $activity['time'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
