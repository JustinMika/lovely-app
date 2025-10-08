<?php

use App\Models\AppSetting;

if (!function_exists('currency')) {
	/**
	 * Formater un montant avec la devise configurée
	 *
	 * @param float|int $amount Le montant à formater
	 * @param int $decimals Nombre de décimales (défaut: 0)
	 * @return string Le montant formaté avec la devise
	 */
	function currency($amount, $decimals = 0)
	{
		$settings = AppSetting::getInstance();

		// Récupérer le symbole et la position de la devise
		$symbol = $settings->currency_symbol ?? 'USD';
		$position = $settings->currency_position ?? 'after';

		// Formater le montant
		$formattedAmount = number_format($amount, $decimals, ',', ' ');

		// Retourner le montant avec la devise selon la position
		return $position === 'before'
			? $symbol . ' ' . $formattedAmount
			: $formattedAmount . ' ' . $symbol;
	}
}

if (!function_exists('app_setting')) {
	/**
	 * Récupérer une valeur de paramètre de l'application
	 *
	 * @param string $key La clé du paramètre
	 * @param mixed $default La valeur par défaut
	 * @return mixed
	 */
	function app_setting($key, $default = null)
	{
		$settings = AppSetting::getInstance();
		return $settings->$key ?? $default;
	}
}
