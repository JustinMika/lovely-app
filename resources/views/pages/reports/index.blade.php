@extends('layouts.app')

@section('title', 'Rapports et Tableaux de Bord')

@section('content')
    <div class="container-fluid px-4 py-6">
        <livewire:dashboard-reports />
    </div>
@endsection

@push('scripts')
    <script>
        // Configuration pour les graphiques et interactions
        document.addEventListener('DOMContentLoaded', function() {
            // Vérifier si le dark mode est activé
            const isDarkMode = document.documentElement.classList.contains('dark');

            // Configuration globale pour les couleurs selon le thème
            const colors = {
                primary: isDarkMode ? '#3B82F6' : '#1D4ED8',
                success: isDarkMode ? '#10B981' : '#059669',
                warning: isDarkMode ? '#F59E0B' : '#D97706',
                danger: isDarkMode ? '#EF4444' : '#DC2626',
                text: isDarkMode ? '#F9FAFB' : '#111827',
                background: isDarkMode ? '#1F2937' : '#FFFFFF'
            };

            // Écouter les événements Livewire pour rafraîchir les données
            window.addEventListener('refreshCharts', function() {
                console.log('Rafraîchissement des données du tableau de bord...');
                // Ici on pourrait ajouter du code pour rafraîchir des graphiques Chart.js si nécessaire
            });

            // Animation des cartes statistiques au chargement
            const statCards = document.querySelectorAll('.bg-white.dark\\:bg-gray-800.rounded-lg.shadow-lg');
            statCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease-out';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
@endpush
