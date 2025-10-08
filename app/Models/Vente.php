<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vente extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id',
        'client_id',
        'total',
        'remise_totale',
        'montant_paye',
        'statut',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'remise_totale' => 'decimal:2',
        'montant_paye' => 'decimal:2',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    /**
     * Relation avec le client
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relation avec les lignes de vente
     */
    public function ligneVentes(): HasMany
    {
        return $this->hasMany(LigneVente::class);
    }

    /**
     * Calculer le bénéfice total de la vente
     */
    public function getBeneficeAttribute(): float
    {
        return $this->ligneVentes->sum(function ($ligne) {
            return ($ligne->prix_unitaire - $ligne->prix_achat) * $ligne->quantite;
        });
    }

    /**
     * Calculer le montant restant à payer
     */
    public function getMontantRestantAttribute(): float
    {
        return $this->total - $this->remise_totale - $this->montant_paye;
    }
}
