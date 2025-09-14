@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
            SweetAlert2 + Livewire Demo
        </h1>
        <p class="text-gray-600 dark:text-gray-400">
            Démonstration de l'intégration SweetAlert2 avec Livewire dans votre projet Laravel
        </p>
    </div>

    <!-- Livewire Component -->
    @livewire('example-alert')
</div>
@endsection
