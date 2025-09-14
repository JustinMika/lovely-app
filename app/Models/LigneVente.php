<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LigneVente extends Model
{
    use HasFactory;

    protected $fillable = [
        'vente_id',
        'article_id',
        'lot_id',
        'quantite',
        'prix_unitaire',
        'prix_achat',
        'remise_ligne',
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'prix_achat' => 'decimal:2',
        'remise_ligne' => 'decimal:2',
    ];

    /**
     * Relation avec la vente
     */
    public function vente(): BelongsTo
    {
        return $this->belongsTo(Vente::class);
    }

    /**
     * Relation avec l'article
     */
    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    /**
     * Relation avec le lot
     */
    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }

    /**
     * Calculer le sous-total de la ligne
     */
    public function getSousTotalAttribute(): float
    {
        return ($this->prix_unitaire * $this->quantite) - $this->remise_ligne;
    }

    /**
     * Calculer le bénéfice de la ligne
     */
    public function getBeneficeAttribute(): float
    {
        return ($this->prix_unitaire - $this->prix_achat) * $this->quantite;
    }
}
