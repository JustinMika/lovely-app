<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lot extends Model
{
	use HasFactory;

	protected $fillable = [
		'approvisionnement_id',
		'article_id',
		'ville_id',
		'numero_lot',
		'quantite_initiale',
		'quantite_restante',
		'seuil_alerte',
		'prix_achat',
		'prix_vente',
		'date_arrivee',
		'date_expiration',
	];

	protected $casts = [
		'date_arrivee' => 'date',
		'date_expiration' => 'date',
		'prix_achat' => 'decimal:2',
		'prix_vente' => 'decimal:2',
	];

	/**
	 * Relation avec l'approvisionnement
	 */
	public function approvisionnement(): BelongsTo
	{
		return $this->belongsTo(Approvisionnement::class);
	}

	/**
	 * Relation avec l'article
	 */
	public function article(): BelongsTo
	{
		return $this->belongsTo(Article::class);
	}

	/**
	 * Relation avec la ville
	 */
	public function ville(): BelongsTo
	{
		return $this->belongsTo(Ville::class);
	}

	/**
	 * Relation avec les lignes de vente
	 */
	public function ligneVentes(): HasMany
	{
		return $this->hasMany(LigneVente::class);
	}

	/**
	 * Vérifier si le lot a suffisamment de stock
	 */
	public function hasStock(int $quantite): bool
	{
		return $this->quantite_restante >= $quantite;
	}

	/**
	 * Réduire le stock du lot
	 */
	public function reduireStock(int $quantite): void
	{
		if ($this->hasStock($quantite)) {
			$this->quantite_restante -= $quantite;
			$this->save();
		} else {
			throw new \Exception("Stock insuffisant. Disponible: {$this->quantite_restante}, Demandé: {$quantite}");
		}
	}

	/**
	 * Vérifier si le lot est en alerte de stock
	 */
	public function isStockAlert(): bool
	{
		return $this->quantite_restante <= $this->seuil_alerte;
	}

	/**
	 * Vérifier si le lot est expiré
	 */
	public function isExpired(): bool
	{
		return $this->date_expiration && $this->date_expiration < now();
	}

	/**
	 * Vérifier si le lot expire bientôt (dans les 30 jours)
	 */
	public function isExpiringSoon(): bool
	{
		return $this->date_expiration && $this->date_expiration <= now()->addDays(30);
	}

	/**
	 * Scope pour les lots en alerte de stock
	 */
	public function scopeStockAlert($query)
	{
		return $query->whereRaw('quantite_restante <= seuil_alerte');
	}

	/**
	 * Scope pour les lots expirés
	 */
	public function scopeExpired($query)
	{
		return $query->where('date_expiration', '<', now());
	}

	/**
	 * Scope pour les lots qui expirent bientôt
	 */
	public function scopeExpiringSoon($query, $days = 30)
	{
		return $query->where('date_expiration', '<=', now()->addDays($days))
			->where('date_expiration', '>', now());
	}
}
