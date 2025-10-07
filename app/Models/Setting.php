<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'description'
    ];

    protected $casts = [
        'value' => 'json'
    ];

    /**
     * Récupérer une valeur de paramètre avec cache
     */
    public static function get($key, $default = null)
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Définir une valeur de paramètre
     */
    public static function set($key, $value, $type = 'string', $group = 'general', $description = null)
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'description' => $description
            ]
        );

        // Vider le cache
        Cache::forget("setting.{$key}");

        return $setting;
    }

    /**
     * Récupérer tous les paramètres d'un groupe
     */
    public static function getGroup($group)
    {
        return Cache::remember("settings.group.{$group}", 3600, function () use ($group) {
            return static::where('group', $group)->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Vider tout le cache des paramètres
     */
    public static function clearCache()
    {
        $settings = static::all();
        foreach ($settings as $setting) {
            Cache::forget("setting.{$setting->key}");
        }
        
        $groups = static::distinct('group')->pluck('group');
        foreach ($groups as $group) {
            Cache::forget("settings.group.{$group}");
        }
    }

    /**
     * Initialiser les paramètres par défaut
     */
    public static function initializeDefaults()
    {
        $defaults = [
            // Informations générales
            'app_name' => [
                'value' => 'Lovely App',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Nom de l\'application'
            ],
            'app_description' => [
                'value' => 'Système de gestion commerciale',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Description de l\'application'
            ],
            'company_name' => [
                'value' => 'Mon Entreprise',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Nom de l\'entreprise'
            ],
            'company_address' => [
                'value' => '',
                'type' => 'text',
                'group' => 'general',
                'description' => 'Adresse de l\'entreprise'
            ],
            'company_phone' => [
                'value' => '',
                'type' => 'string',
                'group' => 'general',
                'description' => 'Téléphone de l\'entreprise'
            ],
            'company_email' => [
                'value' => '',
                'type' => 'email',
                'group' => 'general',
                'description' => 'Email de l\'entreprise'
            ],
            'app_logo' => [
                'value' => null,
                'type' => 'file',
                'group' => 'general',
                'description' => 'Logo de l\'application'
            ],
            
            // Paramètres de devise
            'currency_symbol' => [
                'value' => 'FCFA',
                'type' => 'string',
                'group' => 'currency',
                'description' => 'Symbole de la devise'
            ],
            'currency_position' => [
                'value' => 'after',
                'type' => 'select',
                'group' => 'currency',
                'description' => 'Position du symbole de devise'
            ],
            
            // Paramètres de stock
            'low_stock_threshold' => [
                'value' => 10,
                'type' => 'number',
                'group' => 'stock',
                'description' => 'Seuil d\'alerte stock faible'
            ],
            'enable_stock_alerts' => [
                'value' => true,
                'type' => 'boolean',
                'group' => 'stock',
                'description' => 'Activer les alertes de stock'
            ],
            
            // Paramètres de vente
            'default_tax_rate' => [
                'value' => 0,
                'type' => 'number',
                'group' => 'sales',
                'description' => 'Taux de taxe par défaut (%)'
            ],
            'enable_discounts' => [
                'value' => true,
                'type' => 'boolean',
                'group' => 'sales',
                'description' => 'Activer les remises'
            ]
        ];

        foreach ($defaults as $key => $config) {
            if (!static::where('key', $key)->exists()) {
                static::create([
                    'key' => $key,
                    'value' => $config['value'],
                    'type' => $config['type'],
                    'group' => $config['group'],
                    'description' => $config['description']
                ]);
            }
        }
    }
}
