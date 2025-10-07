<?php

namespace App\Livewire;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class GeneralSettingsNew extends Component
{
    use WithFileUploads;

    // Paramètres généraux
    public $app_name = '';
    public $app_description = '';
    public $company_name = '';
    public $company_address = '';
    public $company_phone = '';
    public $company_email = '';
    public $current_logo = '';

    // Paramètres de devise
    public $currency_symbol = 'FCFA';
    public $currency_position = 'after';

    // Paramètres de stock
    public $low_stock_threshold = 10;
    public $enable_stock_alerts = true;

    // Upload de logo
    public $new_logo;

    protected function rules()
    {
        return [
            'app_name' => 'required|string|max:255',
            'app_description' => 'nullable|string|max:500',
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string|max:1000',
            'company_phone' => 'nullable|string|max:20',
            'company_email' => 'nullable|email|max:255',
            'currency_symbol' => 'required|string|max:10',
            'currency_position' => 'required|in:before,after',
            'low_stock_threshold' => 'required|integer|min:1|max:1000',
            'enable_stock_alerts' => 'boolean',
            'new_logo' => 'nullable|image|max:2048', // 2MB max
        ];
    }

    public function mount()
    {
        Log::info('GeneralSettingsNew::mount() called');
        $this->loadSettings();
    }

    public function loadSettings()
    {
        Log::info('GeneralSettingsNew::loadSettings() called');
        
        $this->app_name = Setting::get('app_name', 'Lovely App');
        $this->app_description = Setting::get('app_description', '');
        $this->company_name = Setting::get('company_name', '');
        $this->company_address = Setting::get('company_address', '');
        $this->company_phone = Setting::get('company_phone', '');
        $this->company_email = Setting::get('company_email', '');
        $this->current_logo = Setting::get('app_logo');

        $this->currency_symbol = Setting::get('currency_symbol', 'FCFA');
        $this->currency_position = Setting::get('currency_position', 'after');

        $this->low_stock_threshold = Setting::get('low_stock_threshold', 10);
        $this->enable_stock_alerts = Setting::get('enable_stock_alerts', true);
    }

    public function testMethod()
    {
        Log::info('GeneralSettingsNew::testMethod() called');
        session()->flash('success', 'Test Livewire réussi dans le nouveau composant !');
    }

    public function save()
    {
        Log::info('GeneralSettingsNew::save() called');
        
        try {
            $this->validate();

            // Sauvegarder les paramètres généraux
            Setting::set('app_name', $this->app_name, 'string', 'general', 'Nom de l\'application');
            Setting::set('app_description', $this->app_description, 'text', 'general', 'Description de l\'application');
            Setting::set('company_name', $this->company_name, 'string', 'general', 'Nom de l\'entreprise');
            Setting::set('company_address', $this->company_address, 'text', 'general', 'Adresse de l\'entreprise');
            Setting::set('company_phone', $this->company_phone, 'string', 'general', 'Téléphone de l\'entreprise');
            Setting::set('company_email', $this->company_email, 'email', 'general', 'Email de l\'entreprise');

            // Sauvegarder les paramètres de devise
            Setting::set('currency_symbol', $this->currency_symbol, 'string', 'currency', 'Symbole de la devise');
            Setting::set('currency_position', $this->currency_position, 'select', 'currency', 'Position du symbole de devise');

            // Sauvegarder les paramètres de stock
            Setting::set('low_stock_threshold', $this->low_stock_threshold, 'number', 'stock', 'Seuil d\'alerte stock faible');
            Setting::set('enable_stock_alerts', $this->enable_stock_alerts, 'boolean', 'stock', 'Activer les alertes de stock');

            session()->flash('success', 'Paramètres sauvegardés avec succès!');
            Log::info('GeneralSettingsNew::save() completed successfully');
        } catch (\Exception $e) {
            Log::error('GeneralSettingsNew::save() error: ' . $e->getMessage());
            session()->flash('error', 'Une erreur est survenue lors de la sauvegarde: ' . $e->getMessage());
        }
    }

    public function resetToDefaults()
    {
        Log::info('GeneralSettingsNew::resetToDefaults() called');
        
        try {
            // Réinitialiser aux valeurs par défaut
            $this->app_name = 'Lovely App';
            $this->app_description = '';
            $this->company_name = '';
            $this->company_address = '';
            $this->company_phone = '';
            $this->company_email = '';
            $this->currency_symbol = 'FCFA';
            $this->currency_position = 'after';
            $this->low_stock_threshold = 10;
            $this->enable_stock_alerts = true;

            session()->flash('success', 'Paramètres restaurés aux valeurs par défaut!');
            Log::info('GeneralSettingsNew::resetToDefaults() completed successfully');
        } catch (\Exception $e) {
            Log::error('GeneralSettingsNew::resetToDefaults() error: ' . $e->getMessage());
            session()->flash('error', 'Erreur lors de la restauration: ' . $e->getMessage());
        }
    }

    public function render()
    {
        Log::info('GeneralSettingsNew::render() called');
        return view('livewire.general-settings-new');
    }
}
