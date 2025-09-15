<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManager extends Component
{
    use WithPagination;

    // Propriétés de recherche et tri
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $roleFilter = '';

    // Propriétés du formulaire
    public $userId;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $role_id;

    // États du composant
    public $showModal = false;
    public $editMode = false;
    public $showDetailModal = false;
    public $selectedUser = null;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->userId),
            ],
            'role_id' => 'required|exists:roles,id',
        ];

        if (!$this->editMode || !empty($this->password)) {
            $rules['password'] = 'required|string|min:8|confirmed';
            $rules['password_confirmation'] = 'required';
        }

        return $rules;
    }

    protected $messages = [
        'name.required' => 'Le nom est obligatoire.',
        'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
        'email.required' => 'L\'email est obligatoire.',
        'email.email' => 'L\'email doit être une adresse valide.',
        'email.unique' => 'Cette adresse email est déjà utilisée.',
        'email.max' => 'L\'email ne peut pas dépasser 255 caractères.',
        'password.required' => 'Le mot de passe est obligatoire.',
        'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        'password_confirmation.required' => 'La confirmation du mot de passe est obligatoire.',
        'role_id.required' => 'Veuillez sélectionner un rôle.',
        'role_id.exists' => 'Le rôle sélectionné n\'existe pas.',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedRoleFilter()
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
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role_id = null;
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->editMode) {
                $user = User::findOrFail($this->userId);

                // Vérifier si l'utilisateur peut être modifié
                if ($user->id === auth()->id() && $this->role_id != $user->role_id) {
                    $this->alert('error', 'Vous ne pouvez pas modifier votre propre rôle.');
                    return;
                }

                $user->update([
                    'name' => $this->name,
                    'email' => $this->email,
                    'role_id' => $this->role_id,
                ]);

                // Mettre à jour le mot de passe seulement s'il est fourni
                if (!empty($this->password)) {
                    $user->update(['password' => Hash::make($this->password)]);
                }

                $this->alert('success', 'Utilisateur modifié avec succès!');
            } else {
                User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                    'role_id' => $this->role_id,
                ]);

                $this->alert('success', 'Nouvel utilisateur créé avec succès!');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            $this->alert('error', 'Une erreur est survenue lors de la sauvegarde.');
        }
    }

    public function edit($userId)
    {
        $user = User::with('role')->findOrFail($userId);

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->role_id;
        $this->password = '';
        $this->password_confirmation = '';

        $this->editMode = true;
        $this->showModal = true;
    }

    public function showDetail($userId)
    {
        $this->selectedUser = User::with(['role', 'ventes', 'approvisionnements'])
            ->withCount(['ventes', 'approvisionnements'])
            ->findOrFail($userId);
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedUser = null;
    }

    #[On('confirmDelete')]
    public function delete($userId)
    {
        try {
            $user = User::findOrFail($userId);

            // Vérifier si l'utilisateur essaie de se supprimer lui-même
            if ($user->id === auth()->id()) {
                $this->alert('error', 'Vous ne pouvez pas supprimer votre propre compte.');
                return;
            }

            // Vérifier s'il y a des contraintes (ventes ou approvisionnements)
            if ($user->ventes()->count() > 0 || $user->approvisionnements()->count() > 0) {
                $this->alert('error', 'Impossible de supprimer cet utilisateur car il a des ventes ou des approvisionnements associés.');
                return;
            }

            $user->delete();
            $this->alert('success', 'Utilisateur supprimé avec succès!');
        } catch (\Exception $e) {
            $this->alert('error', 'Une erreur est survenue lors de la suppression.');
        }
    }

    public function confirmDelete($userId)
    {
        $user = User::find($userId);

        if ($user && $user->id === auth()->id()) {
            $this->alert('error', 'Vous ne pouvez pas supprimer votre propre compte.');
            return;
        }

        $this->confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?', [
            'confirmButtonText' => 'Oui, supprimer',
            'cancelButtonText' => 'Annuler',
            'onConfirmed' => 'confirmDelete',
            'data' => ['userId' => $userId]
        ]);
    }

    public function toggleStatus($userId)
    {
        try {
            $user = User::findOrFail($userId);

            if ($user->id === auth()->id()) {
                $this->alert('error', 'Vous ne pouvez pas désactiver votre propre compte.');
                return;
            }

            // Basculer le statut (nous pourrions ajouter un champ 'active' au modèle User)
            // Pour l'instant, nous utilisons une approche simple
            $this->alert('info', 'Fonctionnalité de désactivation à implémenter.');
        } catch (\Exception $e) {
            $this->alert('error', 'Une erreur est survenue.');
        }
    }

    public function exportPdf()
    {
        return redirect()->route('users.export.pdf', [
            'search' => $this->search,
            'roleFilter' => $this->roleFilter,
            'sortField' => $this->sortField,
            'sortDirection' => $this->sortDirection,
        ]);
    }

    public function getUsers()
    {
        return User::with(['role'])
            ->withCount(['ventes', 'approvisionnements'])
            ->when($this->search, function (Builder $query) {
                $query->where(function (Builder $q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhereHas('role', function (Builder $roleQuery) {
                            $roleQuery->where('nom', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->roleFilter, function (Builder $query) {
                $query->where('role_id', $this->roleFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function getRoles()
    {
        return Role::orderBy('nom')->get();
    }

    public function render()
    {
        return view('livewire.user-manager', [
            'users' => $this->getUsers(),
            'roles' => $this->getRoles(),
        ]);
    }
}
