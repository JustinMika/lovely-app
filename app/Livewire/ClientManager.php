<?php

namespace App\Livewire;

use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class ClientManager extends Component
{
    use WithPagination, LivewireAlert;

    // Propriétés de recherche et tri
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;

    // Propriétés du formulaire
    public $clientId;
    public $nom;
    public $telephone;
    public $email;

    // États du composant
    public $showModal = false;
    public $editMode = false;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'nom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20|unique:clients,telephone,' . $this->clientId,
            'email' => 'nullable|email|max:255|unique:clients,email,' . $this->clientId,
        ];
    }

    protected $messages = [
        'nom.required' => 'Le nom du client est obligatoire.',
        'nom.max' => 'Le nom ne peut pas dépasser 255 caractères.',
        'telephone.required' => 'Le numéro de téléphone est obligatoire.',
        'telephone.max' => 'Le numéro de téléphone ne peut pas dépasser 20 caractères.',
        'telephone.unique' => 'Ce numéro de téléphone est déjà utilisé par un autre client.',
        'email.email' => 'L\'adresse email doit être valide.',
        'email.max' => 'L\'adresse email ne peut pas dépasser 255 caractères.',
        'email.unique' => 'Cette adresse email est déjà utilisée par un autre client.',
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
        $this->clientId = null;
        $this->nom = '';
        $this->telephone = '';
        $this->email = '';
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->editMode) {
                $client = Client::findOrFail($this->clientId);
                $client->update([
                    'nom' => $this->nom,
                    'telephone' => $this->telephone,
                    'email' => $this->email,
                ]);

                $this->alert('success', 'Client modifié avec succès!');
            } else {
                Client::create([
                    'nom' => $this->nom,
                    'telephone' => $this->telephone,
                    'email' => $this->email,
                ]);

                $this->alert('success', 'Nouveau client créé avec succès!');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            $this->alert('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    public function edit($clientId)
    {
        $client = Client::findOrFail($clientId);

        $this->clientId = $client->id;
        $this->nom = $client->nom;
        $this->telephone = $client->telephone;
        $this->email = $client->email;

        $this->editMode = true;
        $this->showModal = true;
    }

    #[On('confirmDelete')]
    public function delete($clientId)
    {
        try {
            $client = Client::findOrFail($clientId);

            // Vérifier si le client a des ventes associées
            if ($client->ventes()->count() > 0) {
                $this->alert('error', 'Impossible de supprimer ce client car il a des ventes associées.');
                return;
            }

            $client->delete();
            $this->alert('success', 'Client supprimé avec succès!');
        } catch (\Exception $e) {
            $this->alert('error', 'Une erreur est survenue lors de la suppression.');
        }
    }

    public function confirmDelete($clientId)
    {
        $this->confirm('Êtes-vous sûr de vouloir supprimer ce client?', [
            'confirmButtonText' => 'Oui, supprimer',
            'cancelButtonText' => 'Annuler',
            'onConfirmed' => 'confirmDelete',
            'data' => ['clientId' => $clientId]
        ]);
    }

    public function exportPdf()
    {
        return redirect()->route('clients.export.pdf', [
            'search' => $this->search,
            'sortField' => $this->sortField,
            'sortDirection' => $this->sortDirection,
        ]);
    }

    public function getClients()
    {
        return Client::withCount('ventes')
            ->withSum('ventes', 'total')
            ->when($this->search, function (Builder $query) {
                $query->where(function (Builder $q) {
                    $q->where('nom', 'like', '%' . $this->search . '%')
                        ->orWhere('telephone', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.client-manager', [
            'clients' => $this->getClients(),
        ]);
    }
}
