<?php

namespace App\Livewire;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class GeneralSettings extends Component
{
	use WithFileUploads;

	// Paramètres généraux
	public $app_name;
	public $app_description;
	public $company_name;
	public $company_address;
	public $company_phone;
	public $company_email;
	public $app_logo;
	public $current_logo;

	// Paramètres de devise
	public $currency_symbol;
	public $currency_position;

	// Paramètres de stock
	public $low_stock_threshold;
	public $enable_stock_alerts;


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

	protected $messages = [
		'app_name.required' => 'Le nom de l\'application est obligatoire.',
		'company_name.required' => 'Le nom de l\'entreprise est obligatoire.',
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
		// Initialiser les paramètres par défaut s'ils n'existent pas
		Setting::initializeDefaults();

		// Charger les paramètres
		$this->app_name = Setting::get('app_name', 'Lovely App');
		$this->app_description = Setting::get('app_description', 'Système de gestion commerciale');
		$this->company_name = Setting::get('company_name', 'Mon Entreprise');
		$this->company_address = Setting::get('company_address', '');
		$this->company_phone = Setting::get('company_phone', '');
		$this->company_email = Setting::get('company_email', '');
		$this->current_logo = Setting::get('app_logo');

		$this->currency_symbol = Setting::get('currency_symbol', 'FC');
		$this->currency_position = Setting::get('currency_position', 'after');

		$this->low_stock_threshold = Setting::get('low_stock_threshold', 10);
		$this->enable_stock_alerts = Setting::get('enable_stock_alerts', true);
	}

	public function save()
	{
		Log::info('GeneralSettings::save() called');
		$this->validate();

		try {
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

			// Gérer l'upload du logo
			if ($this->new_logo) {
				$this->uploadLogo();
			}

			session()->flash('success', 'Paramètres sauvegardés avec succès!');
		} catch (\Exception $e) {
			session()->flash('error', 'Une erreur est survenue lors de la sauvegarde: ' . $e->getMessage());
		}
	}

	public function uploadLogo()
	{
		try {
			// Supprimer l'ancien logo s'il existe
			if ($this->current_logo && Storage::disk('public')->exists($this->current_logo)) {
				Storage::disk('public')->delete($this->current_logo);
			}

			// Sauvegarder le nouveau logo
			$logoPath = $this->new_logo->store('logos', 'public');

			// Mettre à jour le paramètre
			Setting::set('app_logo', $logoPath, 'file', 'general', 'Logo de l\'application');

			$this->current_logo = $logoPath;
			$this->new_logo = null;

			session()->flash('success', 'Logo mis à jour avec succès!');
		} catch (\Exception $e) {
			session()->flash('error', 'Erreur lors de l\'upload du logo: ' . $e->getMessage());
		}
	}

	public function removeLogo()
	{
		try {
			if ($this->current_logo && Storage::disk('public')->exists($this->current_logo)) {
				Storage::disk('public')->delete($this->current_logo);
			}

			Setting::set('app_logo', null, 'file', 'general', 'Logo de l\'application');
			$this->current_logo = null;

			session()->flash('success', 'Logo supprimé avec succès!');
		} catch (\Exception $e) {
			session()->flash('error', 'Erreur lors de la suppression du logo: ' . $e->getMessage());
		}
	}

	public function resetToDefaults()
	{
		Log::info('GeneralSettings::resetToDefaults() called');
		try {
			// Supprimer tous les paramètres existants
			Setting::truncate();

			// Réinitialiser les paramètres par défaut
			Setting::initializeDefaults();

			// Recharger les paramètres
			$this->loadSettings();

			session()->flash('success', 'Paramètres restaurés aux valeurs par défaut!');
		} catch (\Exception $e) {
			session()->flash('error', 'Erreur lors de la restauration: ' . $e->getMessage());
		}
	}

	public function testMethod()
	{
		Log::info('GeneralSettings::testMethod() called');
		session()->flash('success', 'Test Livewire réussi !');
	}

	public function render()
	{
		return view('livewire.general-settings');
	}
}
