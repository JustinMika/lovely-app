<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Lot;

class Ville extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
    ];

    /**
     * Relation avec les lots
     */
    public function lots(): HasMany
    {
        return $this->hasMany(Lot::class);
    }
}
