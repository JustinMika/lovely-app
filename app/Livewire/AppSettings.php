<?php

namespace App\Livewire;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class AppSettings extends Component
{
	use WithFileUploads;

	// Propriétés du formulaire
	public $app_name;
	public $app_description;
	public $company_name;
	public $company_address;
	public $company_phone;
	public $company_email;
	public $app_logo;
	public $currency_symbol;
	public $currency_position;
	public $low_stock_threshold;
	public $enable_stock_alerts;

	// Upload de logo
	public $new_logo;

	protected $rules = [
		'app_name' => 'required|string|max:255',
		'app_description' => 'nullable|string|max:1000',
		'company_name' => 'nullable|string|max:255',
		'company_address' => 'nullable|string|max:1000',
		'company_phone' => 'nullable|string|max:20',
		'company_email' => 'nullable|email|max:255',
		'currency_symbol' => 'required|string|max:10',
		'currency_position' => 'required|in:before,after',
		'low_stock_threshold' => 'required|integer|min:1|max:1000',
		'enable_stock_alerts' => 'boolean',
		'new_logo' => 'nullable|image|max:2048',
	];

	protected $messages = [
		'app_name.required' => 'Le nom de l\'application est obligatoire.',
		'company_email.email' => 'L\'email doit être valide.',
		'currency_symbol.required' => 'Le symbole de devise est obligatoire.',
		'low_stock_threshold.required' => 'Le seuil de stock faible est obligatoire.',
		'low_stock_threshold.min' => 'Le seuil doit être au minimum de 1.',
		'new_logo.image' => 'Le fichier doit être une image.',
		'new_logo.max' => 'L\'image ne peut pas dépasser 2MB.',
	];

	public function mount()
	{
		$this->loadSettings();
	}

	public function loadSettings()
	{
		$settings = AppSetting::getInstance();

		$this->app_name = $settings->app_name;
		$this->app_description = $settings->app_description;
		$this->company_name = $settings->company_name;
		$this->company_address = $settings->company_address;
		$this->company_phone = $settings->company_phone;
		$this->company_email = $settings->company_email;
		$this->app_logo = $settings->app_logo;
		$this->currency_symbol = $settings->currency_symbol;
		$this->currency_position = $settings->currency_position;
		$this->low_stock_threshold = $settings->low_stock_threshold;
		$this->enable_stock_alerts = $settings->enable_stock_alerts;
	}

	public function save()
	{
		Log::info('AppSettings::save() appelée');
		$this->validate();

		try {
			$settings = AppSetting::getInstance();

			// Gérer l'upload du logo
			if ($this->new_logo) {
				// Supprimer l'ancien logo s'il existe
				if ($this->app_logo && Storage::disk('public')->exists($this->app_logo)) {
					Storage::disk('public')->delete($this->app_logo);
				}

				// Sauvegarder le nouveau logo
				$logoPath = $this->new_logo->store('logos', 'public');
				$this->app_logo = $logoPath;
				$this->new_logo = null;
			}

			// Mettre à jour les paramètres
			$settings->update([
				'app_name' => $this->app_name,
				'app_description' => $this->app_description,
				'company_name' => $this->company_name,
				'company_address' => $this->company_address,
				'company_phone' => $this->company_phone,
				'company_email' => $this->company_email,
				'app_logo' => $this->app_logo,
				'currency_symbol' => $this->currency_symbol,
				'currency_position' => $this->currency_position,
				'low_stock_threshold' => $this->low_stock_threshold,
				'enable_stock_alerts' => $this->enable_stock_alerts,
			]);

			session()->flash('success', 'Paramètres sauvegardés avec succès!');
		} catch (\Exception $e) {
			session()->flash('error', 'Erreur lors de la sauvegarde: ' . $e->getMessage());
		}
	}

	public function removeLogo()
	{
		try {
			if ($this->app_logo && Storage::disk('public')->exists($this->app_logo)) {
				Storage::disk('public')->delete($this->app_logo);
			}

			$settings = AppSetting::getInstance();
			$settings->update(['app_logo' => null]);
			$this->app_logo = null;

			session()->flash('success', 'Logo supprimé avec succès!');
		} catch (\Exception $e) {
			session()->flash('error', 'Erreur lors de la suppression du logo: ' . $e->getMessage());
		}
	}

	public function resetToDefaults()
	{
		try {
			$settings = AppSetting::getInstance();
			$settings->update([
				'app_name' => 'Lovely App',
				'app_description' => 'Système de gestion commerciale',
				'company_name' => 'Mon Entreprise',
				'company_address' => 'Nk, Goma',
				'company_phone' => '0855555555',
				'company_email' => 'admin@lovely-app.com',
				'app_logo' => null,
				'currency_symbol' => 'FC',
				'currency_position' => 'after',
				'low_stock_threshold' => 10,
				'enable_stock_alerts' => true,
			]);

			$this->loadSettings();
			session()->flash('success', 'Paramètres restaurés aux valeurs par défaut!');
		} catch (\Exception $e) {
			session()->flash('error', 'Erreur lors de la restauration: ' . $e->getMessage());
		}
	}

	public function testButton()
	{
		Log::info('AppSettings::testButton() appelée');
		session()->flash('success', 'Test réussi ! Livewire fonctionne.');
	}

	public function render()
	{
		return view('livewire.app-settings');
	}
}
