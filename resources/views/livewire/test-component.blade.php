<div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
    <h3 class="text-lg font-bold mb-4">Test Livewire</h3>
    
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif
    
    <p class="mb-4">{{ $message }}</p>
    
    <button wire:click="increment" 
            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
        Cliquer ici ({{ $counter }})
    </button>
</div>
