<?php

namespace App\Livewire;

use App\Models\Ville;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class VilleManager extends Component
{
	use WithPagination;

	// Propriétés de recherche et tri
	public $search = '';
	public $sortField = 'nom';
	public $sortDirection = 'asc';
	public $perPage = 10;

	// Propriétés du formulaire
	public $villeId;
	public $nom;

	// États du composant
	public $showModal = false;
	public $editMode = false;
	public $showDetailModal = false;
	public $selectedVille = null;

	protected $paginationTheme = 'tailwind';

	protected function rules()
	{
		return [
			'nom' => [
				'required',
				'string',
				'max:255',
				'unique:villes,nom,' . $this->villeId,
			],
		];
	}

	protected $messages = [
		'nom.required' => 'Le nom de la ville est obligatoire.',
		'nom.string' => 'Le nom de la ville doit être une chaîne de caractères.',
		'nom.max' => 'Le nom de la ville ne peut pas dépasser 255 caractères.',
		'nom.unique' => 'Cette ville existe déjà.',
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
		$this->villeId = null;
		$this->nom = '';
	}

	public function save()
	{
		$this->validate();

		try {
			if ($this->editMode) {
				$ville = Ville::findOrFail($this->villeId);
				$ville->update([
					'nom' => $this->nom,
				]);

				$this->alert('success', 'Ville modifiée avec succès!');
			} else {
				Ville::create([
					'nom' => $this->nom,
				]);

				$this->alert('success', 'Nouvelle ville créée avec succès!');
			}

			$this->closeModal();
		} catch (\Exception $e) {
			$this->alert('error', 'Une erreur est survenue lors de la sauvegarde.');
		}
	}

	public function edit($villeId)
	{
		$ville = Ville::findOrFail($villeId);

		$this->villeId = $ville->id;
		$this->nom = $ville->nom;

		$this->editMode = true;
		$this->showModal = true;
	}

	public function showDetail($villeId)
	{
		$this->selectedVille = Ville::with(['lots.article', 'lots.approvisionnement'])
			->withCount(['lots'])
			->findOrFail($villeId);
		$this->showDetailModal = true;
	}

	public function closeDetailModal()
	{
		$this->showDetailModal = false;
		$this->selectedVille = null;
	}

	#[On('confirmDelete')]
	public function delete($villeId)
	{
		try {
			$ville = Ville::findOrFail($villeId);

			// Vérifier s'il y a des lots associés
			if ($ville->lots()->count() > 0) {
				$this->alert('error', 'Impossible de supprimer cette ville car elle a des lots associés.');
				return;
			}

			$ville->delete();
			$this->alert('success', 'Ville supprimée avec succès!');
		} catch (\Exception $e) {
			$this->alert('error', 'Une erreur est survenue lors de la suppression.');
		}
	}

	public function confirmDelete($villeId)
	{
		$this->confirm('Êtes-vous sûr de vouloir supprimer cette ville?', [
			'confirmButtonText' => 'Oui, supprimer',
			'cancelButtonText' => 'Annuler',
			'onConfirmed' => 'confirmDelete',
			'data' => ['villeId' => $villeId]
		]);
	}

	public function exportPdf()
	{
		return redirect()->route('villes.export.pdf', [
			'search' => $this->search,
			'sortField' => $this->sortField,
			'sortDirection' => $this->sortDirection,
		]);
	}

	public function getVilles()
	{
		return Ville::withCount(['lots'])
			->when($this->search, function (Builder $query) {
				$query->where('nom', 'like', '%' . $this->search . '%');
			})
			->orderBy($this->sortField, $this->sortDirection)
			->paginate($this->perPage);
	}

	public function render()
	{
		return view('livewire.ville-manager', [
			'villes' => $this->getVilles(),
		]);
	}
}
