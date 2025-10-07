<?php

namespace App\Livewire;

use App\Models\Ville;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
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
			'nom' => 'required|string|max:255|unique:villes,nom,' . $this->villeId,
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

	public function render()
	{
		return view('livewire.ville-manager', [
			'villes' => $this->getVilles(),
		]);
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

	public function testLivewire()
	{
		Log::info('VilleManager::testLivewire() appelée - TEST RÉUSSI !');
		$this->alert('success', 'Livewire fonctionne ! 🎉');
	}

	public function openModal()
	{
		$this->showModal = true;
		$this->editMode = false;
		$this->resetForm();
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
		// $this->showModal = true;
	}

	public function save()
	{
		Log::info('VilleManager::save() appelée - Début de la sauvegarde');
		Log::info('Données reçues: nom=' . $this->nom . ', editMode=' . ($this->editMode ? 'true' : 'false'));

		$this->validate();

		try {
			if ($this->editMode) {
				$ville = Ville::findOrFail($this->villeId);
				$ville->update([
					'nom' => $this->nom,
				]);

				LivewireAlert::title('Succès!')
					->text('Ville modifiée avec succès!')
					->success()
					->show();
			} else {
				Ville::create([
					'nom' => $this->nom,
				]);

				LivewireAlert::title('Succès!')
					->text('Nouvelle ville créée avec succès!')
					->success()
					->show();
			}

			$this->closeModal();
			$this->dispatch('close-modal');
		} catch (\Exception $e) {
			LivewireAlert::title('Erreur!')
				->text('Une erreur est survenue lors de la sauvegarde: ' . $e->getMessage())
				->error()
				->show();
		}
	}

	public function edit($villeId)
	{
		Log::info('VilleManager::edit() appelée avec villeId: ' . $villeId);

		$ville = Ville::findOrFail($villeId);
		Log::info('Ville trouvée: ' . $ville->nom . ' (ID: ' . $ville->id . ')');

		$this->villeId = $ville->id;
		$this->nom = $ville->nom;

		$this->editMode = true;

		Log::info('Données chargées: villeId=' . $this->villeId . ', nom=' . $this->nom . ', editMode=' . ($this->editMode ? 'true' : 'false'));
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

	#[On('delete')]
	public function delete($data)
	{
		try {
			$ville = Ville::findOrFail($data['villeId']);

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
		$this->dispatch('swal:confirm', [
			'title' => 'Confirmation',
			'text' => 'Êtes-vous sûr de vouloir supprimer cette ville?',
			'icon' => 'warning',
			'confirmButtonText' => 'Oui, supprimer',
			'cancelButtonText' => 'Annuler',
			'method' => 'delete',
			'params' => ['villeId' => $villeId]
		]);
	}

	public function exportPdf()
	{
		// dd("d;lfkd;lf");
		return redirect()->route('cities.export.pdf', [
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
}
