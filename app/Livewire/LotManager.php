<?php

namespace App\Livewire;

use App\Models\Lot;
use App\Models\Article;
use App\Models\Ville;
use App\Models\Approvisionnement;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class LotManager extends Component
{
	use WithPagination;

	// Propriétés de recherche et tri
	public $search = '';
	public $sortField = 'created_at';
	public $sortDirection = 'desc';
	public $perPage = 10;

	// Propriétés du formulaire
	public $lotId;
	public $approvisionnement_id;
	public $article_id;
	public $ville_id;
	public $numero_lot;
	public $quantite_initiale;
	public $quantite_restante;
	public $prix_achat;
	public $prix_vente;
	public $date_arrivee;

	// Propriétés pour l'approvisionnement
	public $date_approvisionnement;
	public $fournisseur;

	// États du composant
	public $showModal = false;
	public $editMode = false;

	protected $paginationTheme = 'tailwind';

	protected function rules()
	{
		return [
			'article_id' => 'required|exists:articles,id',
			'ville_id' => 'required|exists:villes,id',
			'numero_lot' => 'required|string|max:50|unique:lots,numero_lot,' . $this->lotId,
			'quantite_initiale' => 'required|integer|min:1',
			'quantite_restante' => 'required|integer|min:0|lte:quantite_initiale',
			'prix_achat' => 'required|numeric|min:0',
			'prix_vente' => 'required|numeric|min:0|gte:prix_achat',
			'date_arrivee' => 'required|date|before_or_equal:today',
			'date_approvisionnement' => 'required|date|before_or_equal:today',
			'fournisseur' => 'required|string|max:255',
		];
	}

	protected $messages = [
		'article_id.required' => 'Veuillez sélectionner un article.',
		'article_id.exists' => 'L\'article sélectionné n\'existe pas.',
		'ville_id.required' => 'Veuillez sélectionner une ville.',
		'ville_id.exists' => 'La ville sélectionnée n\'existe pas.',
		'numero_lot.required' => 'Le numéro de lot est obligatoire.',
		'numero_lot.unique' => 'Ce numéro de lot existe déjà.',
		'quantite_initiale.required' => 'La quantité initiale est obligatoire.',
		'quantite_initiale.min' => 'La quantité initiale doit être d\'au moins 1.',
		'quantite_restante.required' => 'La quantité restante est obligatoire.',
		'quantite_restante.lte' => 'La quantité restante ne peut pas dépasser la quantité initiale.',
		'prix_achat.required' => 'Le prix d\'achat est obligatoire.',
		'prix_achat.min' => 'Le prix d\'achat doit être positif.',
		'prix_vente.required' => 'Le prix de vente est obligatoire.',
		'prix_vente.gte' => 'Le prix de vente doit être supérieur ou égal au prix d\'achat.',
		'date_arrivee.required' => 'La date d\'arrivée est obligatoire.',
		'date_arrivee.before_or_equal' => 'La date d\'arrivée ne peut pas être dans le futur.',
		'date_approvisionnement.required' => 'La date d\'approvisionnement est obligatoire.',
		'date_approvisionnement.before_or_equal' => 'La date d\'approvisionnement ne peut pas être dans le futur.',
		'fournisseur.required' => 'Le nom du fournisseur est obligatoire.',
	];

	public function mount()
	{
		$this->date_arrivee = now()->format('Y-m-d');
		$this->date_approvisionnement = now()->format('Y-m-d');
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
		$this->lotId = null;
		$this->approvisionnement_id = null;
		$this->article_id = null;
		$this->ville_id = null;
		$this->numero_lot = '';
		$this->quantite_initiale = null;
		$this->quantite_restante = null;
		$this->prix_achat = null;
		$this->prix_vente = null;
		$this->date_arrivee = now()->format('Y-m-d');
		$this->date_approvisionnement = now()->format('Y-m-d');
		$this->fournisseur = '';
	}

	public function save()
	{
		$this->validate();

		try {
			// Créer ou récupérer l'approvisionnement
			if (!$this->editMode) {
				$approvisionnement = Approvisionnement::create([
					'date' => $this->date_approvisionnement,
					'fournisseur' => $this->fournisseur,
					'utilisateur_id' => auth()->id(),
				]);
				$this->approvisionnement_id = $approvisionnement->id;
			}

			if ($this->editMode) {
				$lot = Lot::findOrFail($this->lotId);
				$lot->update([
					'article_id' => $this->article_id,
					'ville_id' => $this->ville_id,
					'numero_lot' => $this->numero_lot,
					'quantite_initiale' => $this->quantite_initiale,
					'quantite_restante' => $this->quantite_restante,
					'prix_achat' => $this->prix_achat,
					'prix_vente' => $this->prix_vente,
					'date_arrivee' => $this->date_arrivee,
				]);

				// Mettre à jour l'approvisionnement si nécessaire
				$lot->approvisionnement->update([
					'date' => $this->date_approvisionnement,
					'fournisseur' => $this->fournisseur,
				]);

				$this->alert('success', 'Lot modifié avec succès!');
			} else {
				Lot::create([
					'approvisionnement_id' => $this->approvisionnement_id,
					'article_id' => $this->article_id,
					'ville_id' => $this->ville_id,
					'numero_lot' => $this->numero_lot,
					'quantite_initiale' => $this->quantite_initiale,
					'quantite_restante' => $this->quantite_initiale, // Au début, quantité restante = quantité initiale
					'prix_achat' => $this->prix_achat,
					'prix_vente' => $this->prix_vente,
					'date_arrivee' => $this->date_arrivee,
				]);

				$this->alert('success', 'Nouveau lot créé avec succès!');
			}

			$this->closeModal();
		} catch (\Exception $e) {
			$this->alert('error', 'Une erreur est survenue: ' . $e->getMessage());
		}
	}

	public function edit($lotId)
	{
		$lot = Lot::with('approvisionnement')->findOrFail($lotId);

		$this->lotId = $lot->id;
		$this->approvisionnement_id = $lot->approvisionnement_id;
		$this->article_id = $lot->article_id;
		$this->ville_id = $lot->ville_id;
		$this->numero_lot = $lot->numero_lot;
		$this->quantite_initiale = $lot->quantite_initiale;
		$this->quantite_restante = $lot->quantite_restante;
		$this->prix_achat = $lot->prix_achat;
		$this->prix_vente = $lot->prix_vente;
		$this->date_arrivee = $lot->date_arrivee->format('Y-m-d');
		$this->date_approvisionnement = $lot->approvisionnement->date->format('Y-m-d');
		$this->fournisseur = $lot->approvisionnement->fournisseur;

		$this->editMode = true;
		$this->showModal = true;
	}

	#[On('confirmDelete')]
	public function delete($lotId)
	{
		try {
			$lot = Lot::findOrFail($lotId);

			// Vérifier si le lot a des ventes associées
			if ($lot->ligneVentes()->count() > 0) {
				$this->alert('error', 'Impossible de supprimer ce lot car il a des ventes associées.');
				return;
			}

			$lot->delete();
			$this->alert('success', 'Lot supprimé avec succès!');
		} catch (\Exception $e) {
			$this->alert('error', 'Une erreur est survenue lors de la suppression.');
		}
	}

	public function confirmDelete($lotId)
	{
		$this->confirm('Êtes-vous sûr de vouloir supprimer ce lot?', [
			'confirmButtonText' => 'Oui, supprimer',
			'cancelButtonText' => 'Annuler',
			'onConfirmed' => 'confirmDelete',
			'data' => ['lotId' => $lotId]
		]);
	}

	public function exportPdf()
	{
		return redirect()->route('lots.export.pdf', [
			'search' => $this->search,
			'sortField' => $this->sortField,
			'sortDirection' => $this->sortDirection,
		]);
	}

	public function getLots()
	{
		return Lot::with(['article', 'ville', 'approvisionnement.utilisateur'])
			->when($this->search, function (Builder $query) {
				$query->where(function (Builder $q) {
					$q->where('numero_lot', 'like', '%' . $this->search . '%')
						->orWhereHas('article', function (Builder $articleQuery) {
							$articleQuery->where('designation', 'like', '%' . $this->search . '%');
						})
						->orWhereHas('ville', function (Builder $villeQuery) {
							$villeQuery->where('nom', 'like', '%' . $this->search . '%');
						})
						->orWhereHas('approvisionnement', function (Builder $approvQuery) {
							$approvQuery->where('fournisseur', 'like', '%' . $this->search . '%');
						});
				});
			})
			->orderBy($this->sortField, $this->sortDirection)
			->paginate($this->perPage);
	}

	public function getArticles()
	{
		return Article::where('actif', true)->orderBy('designation')->get();
	}

	public function getVilles()
	{
		return Ville::orderBy('nom')->get();
	}

	public function render()
	{
		return view('livewire.lot-manager', [
			'lots' => $this->getLots(),
			'articles' => $this->getArticles(),
			'villes' => $this->getVilles(),
		]);
	}
}
