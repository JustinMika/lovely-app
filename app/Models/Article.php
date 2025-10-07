<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
	use HasFactory;

	protected $fillable = [
		'designation',
		'description',
		'actif',
	];

	protected $casts = [
		'actif' => 'boolean',
	];

	/**
	 * Relation avec les lots
	 */
	public function lots(): HasMany
	{
		return $this->hasMany(Lot::class);
	}

	/**
	 * Relation avec les lignes de vente
	 */
	public function ligneVentes(): HasMany
	{
		return $this->hasMany(LigneVente::class);
	}

	/**
	 * Scope pour les articles actifs
	 */
	public function scopeActive($query)
	{
		return $query->where('actif', true);
	}

	/**
	 * Scope pour les articles inactifs
	 */
	public function scopeInactive($query)
	{
		return $query->where('actif', false);
	}

	/**
	 * Obtenir le stock total disponible pour cet article
	 */
	public function getStockTotalAttribute(): int
	{
		return $this->lots()->sum('quantite_restante');
	}

	/**
	 * Vérifier si l'article a du stock disponible
	 */
	public function hasStock(): bool
	{
		return $this->stock_total > 0;
	}
}
