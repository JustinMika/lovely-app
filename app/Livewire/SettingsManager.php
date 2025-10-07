<?php

namespace App\Livewire;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class SettingsManager extends Component
{
    use WithFileUploads;

    // Propriétés du formulaire
    public $app_name;
    public $company_name;
    public $currency_symbol;
    public $low_stock_threshold;
    public $enable_stock_alerts;

    protected $rules = [
        'app_name' => 'required|string|max:255',
        'company_name' => 'nullable|string|max:255',
        'currency_symbol' => 'required|string|max:10',
        'low_stock_threshold' => 'required|integer|min:1|max:1000',
        'enable_stock_alerts' => 'boolean',
    ];

    public function mount()
    {
        Log::info('SettingsManager::mount() appelée');
        $this->loadSettings();
    }

    public function loadSettings()
    {
        Log::info('SettingsManager::loadSettings() appelée');
        $settings = AppSetting::getInstance();
        
        $this->app_name = $settings->app_name;
        $this->company_name = $settings->company_name;
        $this->currency_symbol = $settings->currency_symbol;
        $this->low_stock_threshold = $settings->low_stock_threshold;
        $this->enable_stock_alerts = $settings->enable_stock_alerts;
    }

    public function testButton()
    {
        Log::info('SettingsManager::testButton() appelée');
        session()->flash('success', '🎉 TEST RÉUSSI ! Livewire fonctionne parfaitement.');
    }

    public function save()
    {
        Log::info('SettingsManager::save() appelée');
        $this->validate();

        try {
            $settings = AppSetting::getInstance();
            $settings->update([
                'app_name' => $this->app_name,
                'company_name' => $this->company_name,
                'currency_symbol' => $this->currency_symbol,
                'low_stock_threshold' => $this->low_stock_threshold,
                'enable_stock_alerts' => $this->enable_stock_alerts,
            ]);

            session()->flash('success', '✅ Paramètres sauvegardés avec succès!');
            Log::info('SettingsManager::save() terminée avec succès');
        } catch (\Exception $e) {
            Log::error('SettingsManager::save() erreur: ' . $e->getMessage());
            session()->flash('error', '❌ Erreur lors de la sauvegarde: ' . $e->getMessage());
        }
    }

    public function render()
    {
        Log::info('SettingsManager::render() appelée');
        return view('livewire.settings-manager');
    }
}
