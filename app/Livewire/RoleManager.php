<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class RoleManager extends Component
{
	use WithPagination;

	// Propriétés de recherche et tri
	public $search = '';
	public $sortField = 'nom';
	public $sortDirection = 'asc';
	public $perPage = 10;

	// Propriétés du formulaire
	public $roleId;
	public $nom;
	public $description;

	// États du composant
	public $showModal = false;
	public $editMode = false;
	public $showDetailModal = false;
	public $selectedRole = null;

	protected $paginationTheme = 'tailwind';

	protected function rules()
	{
		return [
			'nom' => 'required|string|max:255|unique:roles,nom,' . $this->roleId,
			'description' => 'nullable|string|max:500',
		];
	}

	protected $messages = [
		'nom.required' => 'Le nom du rôle est obligatoire.',
		'nom.unique' => 'Ce nom de rôle existe déjà.',
		'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
		'description.max' => 'La description ne peut pas dépasser 500 caractères.',
	];

	public function updatedSearch()
	{
		$this->resetPage();
	}

	public function updatedPerPage()
	{
		$this->resetPage();
	}

	public function sortBy($field)
	{
		if ($this->sortField === $field) {
			$this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
		} else {
			$this->sortField = $field;
			$this->sortDirection = 'asc';
		}
		$this->resetPage();
	}

	public function openModal()
	{
		$this->resetForm();
		$this->showModal = true;
		$this->editMode = false;
	}

	public function closeModal()
	{
		$this->showModal = false;
		$this->resetForm();
		$this->resetValidation();
	}

	public function resetForm()
	{
		$this->roleId = null;
		$this->nom = '';
		$this->description = '';
	}

	public function save()
	{
		$this->validate();

		try {
			if ($this->editMode) {
				$role = Role::findOrFail($this->roleId);
				$role->update([
					'nom' => $this->nom,
					'description' => $this->description,
				]);

				$this->alert('success', 'Rôle modifié avec succès!');
			} else {
				Role::create([
					'nom' => $this->nom,
					'description' => $this->description,
				]);

				$this->alert('success', 'Nouveau rôle créé avec succès!');
			}

			$this->closeModal();
		} catch (\Exception $e) {
			$this->alert('error', 'Une erreur est survenue lors de la sauvegarde.');
		}
	}

	public function edit($roleId)
	{
		$role = Role::findOrFail($roleId);

		$this->roleId = $role->id;
		$this->nom = $role->nom;
		$this->description = $role->description;

		$this->editMode = true;
		$this->showModal = true;
	}

	public function showDetail($roleId)
	{
		$this->selectedRole = Role::withCount('users')->findOrFail($roleId);
		$this->showDetailModal = true;
	}

	public function closeDetailModal()
	{
		$this->showDetailModal = false;
		$this->selectedRole = null;
	}

	#[On('confirmDelete')]
	public function delete($roleId)
	{
		try {
			$role = Role::findOrFail($roleId);

			// Vérifier s'il y a des utilisateurs associés
			if ($role->users()->count() > 0) {
				$this->alert('error', 'Impossible de supprimer ce rôle car il est assigné à des utilisateurs.');
				return;
			}

			// Empêcher la suppression des rôles système
			if (in_array($role->nom, ['Admin', 'Gérant', 'Caissier', 'Vendeur'])) {
				$this->alert('error', 'Impossible de supprimer un rôle système.');
				return;
			}

			$role->delete();
			$this->alert('success', 'Rôle supprimé avec succès!');
		} catch (\Exception $e) {
			$this->alert('error', 'Une erreur est survenue lors de la suppression.');
		}
	}

	public function confirmDelete($roleId)
	{
		$role = Role::find($roleId);

		if (!$role) {
			$this->alert('error', 'Rôle introuvable.');
			return;
		}

		// Vérifier s'il y a des utilisateurs associés
		if ($role->users()->count() > 0) {
			$this->alert('error', 'Impossible de supprimer ce rôle car il est assigné à ' . $role->users()->count() . ' utilisateur(s).');
			return;
		}

		$this->confirm('Êtes-vous sûr de vouloir supprimer ce rôle?', [
			'confirmButtonText' => 'Oui, supprimer',
			'cancelButtonText' => 'Annuler',
			'onConfirmed' => 'confirmDelete',
			'data' => ['roleId' => $roleId]
		]);
	}

	public function getRoles()
	{
		return Role::withCount('users')
			->when($this->search, function (Builder $query) {
				$query->where(function (Builder $q) {
					$q->where('nom', 'like', '%' . $this->search . '%')
						->orWhere('description', 'like', '%' . $this->search . '%');
				});
			})
			->orderBy($this->sortField, $this->sortDirection)
			->paginate($this->perPage);
	}

	public function render()
	{
		return view('livewire.role-manager', [
			'roles' => $this->getRoles(),
		]);
	}
}
