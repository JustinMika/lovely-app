<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppSettingsSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 */
	public function run(): void
	{
		AppSetting::create([
			'app_name' => 'Lovely App',
			'app_description' => 'Système de gestion commerciale moderne et intuitif',
			'company_name' => 'Mon Entreprise',
			'company_address' => 'Kinshasa, République Démocratique du Congo',
			'company_phone' => '+243 123 456 789',
			'company_email' => 'contact@monentreprise.cd',
			'currency_symbol' => 'FC',
			'currency_position' => 'after',
			'low_stock_threshold' => 10,
			'enable_stock_alerts' => true,
		]);
	}
}
