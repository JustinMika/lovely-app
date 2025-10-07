<?php

namespace App\Helpers;

use App\Models\Setting;

class SettingsHelper
{
    /**
     * Récupérer une valeur de paramètre
     */
    public static function get($key, $default = null)
    {
        return Setting::get($key, $default);
    }

    /**
     * Définir une valeur de paramètre
     */
    public static function set($key, $value, $type = 'string', $group = 'general', $description = null)
    {
        return Setting::set($key, $value, $type, $group, $description);
    }

    /**
     * Formater un montant avec la devise configurée
     */
    public static function formatCurrency($amount)
    {
        $symbol = self::get('currency_symbol', 'FCFA');
        $position = self::get('currency_position', 'after');
        
        $formattedAmount = number_format($amount, 0, ',', ' ');
        
        return $position === 'before' 
            ? $symbol . ' ' . $formattedAmount
            : $formattedAmount . ' ' . $symbol;
    }

    /**
     * Récupérer le logo de l'application
     */
    public static function getLogo()
    {
        $logo = self::get('app_logo');
        return $logo ? asset('storage/' . $logo) : null;
    }

    /**
     * Récupérer le nom de l'application
     */
    public static function getAppName()
    {
        return self::get('app_name', 'Lovely App');
    }

    /**
     * Récupérer le nom de l'entreprise
     */
    public static function getCompanyName()
    {
        return self::get('company_name', 'Mon Entreprise');
    }

    /**
     * Vérifier si les alertes de stock sont activées
     */
    public static function isStockAlertsEnabled()
    {
        return self::get('enable_stock_alerts', true);
    }

    /**
     * Récupérer le seuil de stock faible
     */
    public static function getLowStockThreshold()
    {
        return self::get('low_stock_threshold', 10);
    }

    /**
     * Vérifier si les remises sont activées
     */
    public static function isDiscountsEnabled()
    {
        return self::get('enable_discounts', true);
    }

    /**
     * Récupérer le taux de taxe par défaut
     */
    public static function getDefaultTaxRate()
    {
        return self::get('default_tax_rate', 0);
    }

    /**
     * Récupérer toutes les informations de l'entreprise
     */
    public static function getCompanyInfo()
    {
        return [
            'name' => self::get('company_name', 'Mon Entreprise'),
            'address' => self::get('company_address', ''),
            'phone' => self::get('company_phone', ''),
            'email' => self::get('company_email', ''),
            'logo' => self::getLogo(),
        ];
    }
}
