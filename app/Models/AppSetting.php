<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
	use HasFactory;

	protected $fillable = [
		'app_name',
		'app_description',
		'company_name',
		'company_address',
		'company_phone',
		'company_email',
		'app_logo',
		'currency_symbol',
		'currency_position',
		'low_stock_threshold',
		'enable_stock_alerts',
	];

	protected $casts = [
		'enable_stock_alerts' => 'boolean',
		'low_stock_threshold' => 'integer',
	];

	/**
	 * Récupérer ou créer l'instance unique des paramètres
	 */
	public static function getInstance()
	{
		$settings = static::first();

		if (!$settings) {
			$settings = static::create([
				'app_name' => 'Lovely App',
				'app_description' => 'Système de gestion commerciale',
				'company_name' => 'Mon Entreprise',
				'currency_symbol' => 'FC',
				'currency_position' => 'after',
				'low_stock_threshold' => 10,
				'enable_stock_alerts' => true,
			]);
		}

		return $settings;
	}
}
