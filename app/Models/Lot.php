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
        'prix_achat',
        'prix_vente',
        'date_arrivee',
    ];

    protected $casts = [
        'date_arrivee' => 'date',
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
        }
    }
}
