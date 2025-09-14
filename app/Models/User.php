<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'name',
		'email',
		'password',
		'role_id',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
		'password' => 'hashed',
	];

	/**
	 * Relation avec le rôle
	 */
	public function role(): BelongsTo
	{
		return $this->belongsTo(Role::class);
	}

	/**
	 * Relation avec les ventes
	 */
	public function ventes(): HasMany
	{
		return $this->hasMany(Vente::class, 'utilisateur_id');
	}

	/**
	 * Relation avec les approvisionnements
	 */
	public function approvisionnements(): HasMany
	{
		return $this->hasMany(Approvisionnement::class, 'utilisateur_id');
	}

	/**
	 * Check if user has a specific role
	 */
	public function hasRole(string $roleName): bool
	{
		return $this->role && $this->role->nom === $roleName;
	}

	/**
	 * Check if user has any of the specified roles
	 */
	public function hasAnyRole(array $roles): bool
	{
		return $this->role && in_array($this->role->nom, $roles);
	}

	/**
	 * Check if user is admin
	 */
	public function isAdmin(): bool
	{
		return $this->hasRole('Admin');
	}

	/**
	 * Check if user is manager
	 */
	public function isManager(): bool
	{
		return $this->hasRole('Gérant');
	}

	/**
	 * Check if user can manage articles
	 */
	public function canManageArticles(): bool
	{
		return $this->hasAnyRole(['Admin', 'Gérant']);
	}

	/**
	 * Check if user can manage stock
	 */
	public function canManageStock(): bool
	{
		return $this->hasAnyRole(['Admin', 'Gérant', 'Caissier']);
	}
}
