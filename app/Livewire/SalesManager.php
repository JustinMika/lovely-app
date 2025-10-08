<?php

namespace App\Livewire;

use App\Models\Vente;
use App\Models\LigneVente;
use App\Models\Client;
use App\Models\Article;
use App\Models\Lot;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class SalesManager extends Component
{
	use WithPagination;

	// View modes
	public $currentView = 'list'; // 'list', 'create', 'edit'
	public $editingSaleId = null;

	// List view properties
	public $search = '';
	public $perPage = 15;
	public $sortField = 'created_at';
	public $sortDirection = 'desc';

	// Form properties
	public $clientId = '';
	public $remiseTotale = 0;
	public $montantPaye = 0;
	public $items = [];
	public $showPaymentModal = false;
	public $clients = [];
	public $lots = [];
	public $isEditing = false;

	// Add article form properties
	public $selectedLotId = null;
	public $selectedQuantity = 1;
	public $selectedRemise = 0;

	// Search properties
	public $clientOptions = [];
	public $lotOptions = [];

	// Modal states
	public $showDeleteModal = false;
	public $saleToDelete = null;
	public $showClientModal = false;
	public $newClientData = [
		'nom' => '',
		'prenom' => '',
		'telephone' => '',
		'email' => '',
		'adresse' => ''
	];

	protected $queryString = [
		'search' => ['except' => ''],
		'page' => ['except' => 1],
	];

	protected function rules()
	{
		return [
			'clientId' => 'required|exists:clients,id',
			'items' => 'required|array|min:1',
			'items.*.lot_id' => 'required|exists:lots,id',
			'items.*.quantite' => 'required|integer|min:1',
			'items.*.prix_unitaire' => 'required|numeric|min:0',
			'items.*.remise_ligne' => 'nullable|numeric|min:0',
			'remiseTotale' => 'nullable|numeric|min:0',
			'montantPaye' => 'nullable|numeric|min:0',
		];
	}

	public function mount($view = 'list', $saleId = null)
	{
		$this->currentView = $view;

		$this->loadFormData();
		$this->loadClientOptions();
		$this->loadLotOptions();

		if ($this->currentView === 'create') {
			$this->resetForm();
		} elseif ($this->currentView === 'edit' && $saleId) {
			$this->isEditing = true;
			$this->editingSaleId = $saleId;
			$this->loadSaleData();
		}
	}

	public function switchToCreate()
	{
		$this->currentView = 'create';
		$this->isEditing = false;
		$this->editingSaleId = null;
		$this->resetForm();
		$this->loadFormData();
		$this->addItem();
	}

	public function switchToEdit($saleId)
	{
		$this->currentView = 'edit';
		$this->isEditing = true;
		$this->editingSaleId = $saleId;
		$this->resetForm();
		$this->loadFormData();
		$this->loadSaleData();
	}

	public function switchToList()
	{
		$this->currentView = 'list';
		$this->isEditing = false;
		$this->editingSaleId = null;
		$this->resetForm();
	}

	public function resetForm()
	{
		$this->clientId = '';
		$this->remiseTotale = 0;
		$this->montantPaye = 0;
		$this->items = [];
		$this->showClientModal = false;
		$this->newClientData = [
			'nom' => '',
			'prenom' => '',
			'telephone' => '',
			'email' => '',
			'adresse' => ''
		];
	}

	public function loadFormData()
	{
		$this->clients = Client::all();
		$this->lots = Lot::with('article')->where('quantite_restante', '>', 0)->get();
	}

	public function loadClientOptions()
	{
		$this->clients = Client::all();
	}

	public function loadLotOptions()
	{
		$this->lots = Lot::with('article')->where('quantite_restante', '>', 0)->get();
	}

	public function searchClients($search)
	{
		if (empty($search)) {
			$this->loadClientOptions();
			return;
		}

		$this->clientOptions = Client::where('nom', 'like', "%{$search}%")
			->orWhere('prenom', 'like', "%{$search}%")
			->orWhere('telephone', 'like', "%{$search}%")
			->limit(50)
			->get()
			->mapWithKeys(function ($client) {
				return [
					$client->id => "{$client->nom} {$client->prenom}" .
						($client->telephone ? " - {$client->telephone}" : '')
				];
			})
			->toArray();
	}

	public function searchLots($search)
	{
		if (empty($search)) {
			$this->loadLotOptions();
			return;
		}

		$this->lotOptions = Lot::with('article')
			->where(function ($query) use ($search) {
				$query->whereHas('article', function ($q) use ($search) {
					$q->where('designation', 'like', "%{$search}%");
				})
					->orWhere('numero_lot', 'like', "%{$search}%");
			})
			->where('quantite_restante', '>', 0)
			->limit(50)
			->get()
			->mapWithKeys(function ($lot) {
				return [
					$lot->id => "{$lot->article->designation} - {$lot->numero_lot} " .
						"({$lot->quantite_restante} en stock - " .
						currency($lot->prix_vente) . ")"
				];
			})
			->toArray();
	}

	public function loadSaleData()
	{
		$sale = Vente::with(['ligneVentes.lot.article'])->findOrFail($this->editingSaleId);

		$this->clientId = $sale->client_id;
		$this->remiseTotale = $sale->remise_totale;
		$this->montantPaye = $sale->montant_paye;

		$this->items = [];
		foreach ($sale->ligneVentes as $ligne) {
			$this->items[] = [
				'lot_id' => $ligne->lot_id,
				'article_designation' => $ligne->lot->article->designation,
				'quantite' => $ligne->quantite,
				'prix_unitaire' => $ligne->prix_unitaire,
				'remise_ligne' => $ligne->remise_ligne,
			];
		}
	}


	public function removeItem($index)
	{
		unset($this->items[$index]);
		$this->items = array_values($this->items);
	}

	public function updatedItems($value, $key)
	{
		if (str_ends_with($key, '.lot_id')) {
			$index = explode('.', $key)[0];
			$lotId = $value;

			if ($lotId) {
				$lot = Lot::find($lotId);
				if ($lot && isset($lot->prix_vente)) {
					$this->items[$index]['prix_unitaire'] = $lot->prix_vente;
				}
			}
		}
	}

	public function createClient()
	{
		try {
			$this->validate([
				'newClientData.nom' => 'required|string|max:255',
				'newClientData.prenom' => 'required|string|max:255',
				'newClientData.telephone' => 'nullable|string|max:20',
				'newClientData.email' => 'nullable|email|max:255',
				'newClientData.adresse' => 'nullable|string|max:500',
			]);

			$client = Client::create($this->newClientData);
			$this->clientId = $client->id;
			$this->clients = Client::orderBy('nom')->get();

			$this->showClientModal = false;
			$this->newClientData = [
				'nom' => '',
				'prenom' => '',
				'telephone' => '',
				'email' => '',
				'adresse' => ''
			];

			$this->loadClientOptions();

			// Notification de succès
			session()->flash('success', 'Client "' . $client->nom . ' ' . $client->prenom . '" créé avec succès et sélectionné!');
			$this->dispatch('client-created');
		} catch (\Exception $e) {
			session()->flash('error', 'Erreur lors de la création du client: ' . $e->getMessage());
		}
	}

	public function validateStock()
	{
		foreach ($this->items as $index => $item) {
			if (empty($item['lot_id'])) continue;

			$lot = Lot::find($item['lot_id']);
			if (!$lot || $lot->quantite_restante < $item['quantite']) {
				$this->addError(
					"items.{$index}.quantite",
					"Stock insuffisant. Disponible: " . ($lot->quantite_restante ?? 0)
				);
				return false;
			}
		}
		return true;
	}

	public function getTotal()
	{
		$total = 0;
		foreach ($this->items as $item) {
			$total += $this->getSubTotal($item);
		}
		return $total - $this->remiseTotale;
	}

	public function getSubTotal($item)
	{
		$sousTotal = ($item['prix_unitaire'] * $item['quantite']) - ($item['remise_ligne'] ?? 0);
		return max(0, $sousTotal);
	}

	public function getResteAPayer()
	{
		return max(0, $this->getTotal() - $this->montantPaye);
	}

	public function getStatutVente()
	{
		$total = $this->getTotal();
		$paye = $this->montantPaye;

		if ($paye >= $total) {
			return 'payée';
		} elseif ($paye > 0) {
			return 'partiellement_payée';
		} else {
			return 'impayée';
		}
	}

	public function openPaymentModal()
	{
		$this->showPaymentModal = true;
	}

	public function closePaymentModal()
	{
		$this->showPaymentModal = false;
	}

	public function addArticleToCart()
	{
		try {
			$this->validate([
				'selectedLotId' => 'required|exists:lots,id',
				'selectedQuantity' => 'required|integer|min:1',
				'selectedRemise' => 'nullable|numeric|min:0|max:100',
			]);

			$lot = Lot::with('article')->find($this->selectedLotId);

			if ($lot->quantite_restante < $this->selectedQuantity) {
				LivewireAlert::title('Stock insuffisant')
					->text('Stock disponible: ' . $lot->quantite_restante)
					->error()
					->show();
				return;
			}

			$existingItemIndex = null;
			foreach ($this->items as $index => $item) {
				if ($item['lot_id'] == $this->selectedLotId) {
					$existingItemIndex = $index;
					break;
				}
			}

			if ($existingItemIndex !== null) {
				$this->items[$existingItemIndex]['quantite'] += $this->selectedQuantity;
				LivewireAlert::title('Quantité mise à jour')
					->text('"' . $lot->article->designation . '"')
					->success()
					->show();
			} else {
				$this->items[] = [
					'lot_id' => $lot->id,
					'article_designation' => $lot->article->designation,
					'quantite' => $this->selectedQuantity,
					'prix_unitaire' => $lot->prix_vente,
					'remise_ligne' => $this->selectedRemise,
				];
				LivewireAlert::title('Article ajouté au panier')
					->text('"' . $lot->article->designation . '"')
					->success()
					->show();
			}

			// Reset form
			$this->selectedLotId = null;
			$this->selectedQuantity = 1;
			$this->selectedRemise = 0;
		} catch (\Exception $e) {
			LivewireAlert::title('Erreur')
				->text($e->getMessage())
				->error()
				->show();
		}
	}

	public function saveSale()
	{
		$this->validate();

		if (count($this->items) === 0) {
			LivewireAlert::title('Panier vide')
				->text('Veuillez ajouter au moins un article au panier')
				->warning()
				->show();
			return;
		}

		try {
			DB::beginTransaction();

			// Create or update sale
			$total = $this->getTotal();

			$saleData = [
				'client_id' => $this->clientId,
				'utilisateur_id' => Auth::id(),
				'remise_totale' => $this->remiseTotale ?? 0,
				'montant_paye' => $total, // Paiement automatique du montant total
				'total' => $total,
				'statut' => 'payée' // Statut automatiquement "payée"
			];

			if ($this->isEditing) {
				$sale = Vente::findOrFail($this->editingSaleId);

				// Restore stock for old line items before deleting
				foreach ($sale->ligneVentes as $ligne) {
					$lot = Lot::find($ligne->lot_id);
					if ($lot) {
						$lot->quantite_restante += $ligne->quantite;
						$lot->save();
					}
				}

				// Delete existing line items
				$sale->ligneVentes()->delete();

				// Update sale data
				$sale->update($saleData);
			} else {
				$sale = Vente::create($saleData);
			}

			// Create line items
			foreach ($this->items as $item) {
				if (!empty($item['lot_id']) && !empty($item['quantite'])) {
					$lot = Lot::find($item['lot_id']);

					if ($lot) {
						LigneVente::create([
							'vente_id' => $sale->id,
							'article_id' => $lot->article_id,
							'lot_id' => $item['lot_id'],
							'quantite' => $item['quantite'],
							'prix_unitaire' => $item['prix_unitaire'] ?? 0,
							'prix_achat' => $lot->prix_achat,
							'remise_ligne' => $item['remise_ligne'] ?? 0,
							'sous_total' => ($item['quantite'] * $item['prix_unitaire']) - ($item['remise_ligne'] ?? 0)
						]);

						// Update lot quantity
						$lot->quantite_restante -= $item['quantite'];
						$lot->save();
					}
				}
			}

			DB::commit();

			if ($this->isEditing) {
				LivewireAlert::title('Vente mise à jour')
					->text('Vente #' . $sale->id . ' mise à jour avec succès!')
					->success()
					->show();
				$this->switchToList();
			} else {
				LivewireAlert::title('Vente créée avec succès!')
					->text('Vente #' . $sale->id . ' enregistrée. Vous pouvez créer une nouvelle vente.')
					->success()
					->show();
				// Réinitialiser le formulaire pour une nouvelle vente
				$this->resetForm();
				$this->loadFormData();
			}
		} catch (\Exception $e) {
			DB::rollBack();
			LivewireAlert::title('Erreur d\'enregistrement')
				->text($e->getMessage())
				->error()
				->show();
		}
	}

	public function updatingSearch()
	{
		$this->resetPage();
	}

	public function sortBy($field)
	{
		if ($this->sortField === $field) {
			$this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
		} else {
			$this->sortDirection = 'asc';
		}
		$this->sortField = $field;
	}

	public function confirmDelete($saleId)
	{
		$this->saleToDelete = $saleId;
		$this->dispatch('confirm-delete');
	}

	#[On('delete-confirmed')]
	public function deleteSale()
	{
		if (!$this->saleToDelete) {
			return;
		}

		try {
			$sale = Vente::findOrFail($this->saleToDelete);

			DB::transaction(function () use ($sale) {
				// Restaurer le stock pour chaque ligne de vente
				foreach ($sale->ligneVentes as $ligne) {
					$lot = $ligne->lot;
					$lot->quantite_restante += $ligne->quantite;
					$lot->save();
				}

				// Supprimer les lignes de vente puis la vente
				$sale->ligneVentes()->delete();
				$sale->delete();
			});

			$this->dispatch('sale-deleted', [
				'message' => 'Vente supprimée avec succès',
				'type' => 'success'
			]);

			$this->saleToDelete = null;
			$this->showDeleteModal = false;
		} catch (\Exception $e) {
			$this->dispatch('sale-deleted', [
				'message' => 'Erreur lors de la suppression: ' . $e->getMessage(),
				'type' => 'error'
			]);
		}
	}


	public function getMetrics()
	{
		return [
			'total_sales' => Vente::count(),
			'revenue_today' => Vente::whereDate('created_at', today())->sum('total'),
			'revenue_this_month' => Vente::whereMonth('created_at', now()->month)->sum('total'),
			'average_order' => Vente::avg('total') ?? 0
		];
	}

	public function render()
	{
		if ($this->currentView === 'list') {
			$sales = Vente::with(['client', 'utilisateur', 'ligneVentes'])
				->when($this->search, function ($query) {
					$query->whereHas('client', function ($q) {
						$q->where('nom', 'like', '%' . $this->search . '%')
							->orWhere('prenom', 'like', '%' . $this->search . '%')
							->orWhere('telephone', 'like', '%' . $this->search . '%');
					})
						->orWhere('id', 'like', '%' . $this->search . '%');
				})
				->orderBy($this->sortField, $this->sortDirection)
				->paginate($this->perPage);

			$metrics = $this->getMetrics();

			return view('livewire.sales-manager', compact('sales', 'metrics'));
		}

		return view('livewire.sales-manager', [
			'clients' => $this->clients,
			'lots' => $this->lots
		]);
	}
}
