<div class="rounded-2xl border border-gray-200 bg-white px-6 pb-5 pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
            Exemples SweetAlert2 avec Livewire
        </h2>
        <p class="text-gray-600 dark:text-gray-400">
            Testez les différents types d'alertes SweetAlert2 intégrées avec Livewire
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Alert Success -->
        <button wire:click="showSuccess" 
                class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition-colors duration-200">
            <i class="fas fa-check-circle mr-2"></i>
            Alerte Succès
        </button>

        <!-- Alert Error -->
        <button wire:click="showError" 
                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200">
            <i class="fas fa-times-circle mr-2"></i>
            Alerte Erreur
        </button>

        <!-- Alert Warning -->
        <button wire:click="showWarning" 
                class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-colors duration-200">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Alerte Attention
        </button>

        <!-- Alert Confirm -->
        <button wire:click="showConfirm" 
                class="px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-colors duration-200">
            <i class="fas fa-question-circle mr-2"></i>
            Confirmation
        </button>

        <!-- Toast Notification -->
        <button wire:click="showToast" 
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200">
            <i class="fas fa-bell mr-2"></i>
            Toast Notification
        </button>
    </div>

    <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
        <h3 class="font-semibold text-gray-900 dark:text-white mb-2">
            Comment utiliser dans vos composants :
        </h3>
        <pre class="text-sm text-gray-600 dark:text-gray-400 overflow-x-auto"><code>use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

public function save()
{
    // Votre logique de sauvegarde...
    
    LivewireAlert::title('Succès!')
        ->text('Les données ont été sauvegardées.')
        ->success()
        ->show();
}</code></pre>
    </div>
</div>
