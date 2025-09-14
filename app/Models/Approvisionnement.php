<?php

namespace App\Models;

use App\Models\Lot;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Approvisionnement extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'fournisseur',
        'utilisateur_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    /**
     * Relation avec les lots
     */
    public function lots(): HasMany
    {
        return $this->hasMany(Lot::class);
    }
}
