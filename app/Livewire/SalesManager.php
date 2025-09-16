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
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Group;

class SalesManager extends Component implements HasForms
{
	use WithPagination, InteractsWithForms;

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
	public $clients = [];
	public $lots = [];
	public $isEditing = false;

	// Filament Forms data
	public ?array $data = [];

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

	protected $rules = [
		'clientId' => 'required|exists:clients,id',
		'items' => 'required|array|min:1',
		'items.*.lot_id' => 'required|exists:lots,id',
		'items.*.quantite' => 'required|integer|min:1',
		'items.*.prix_unitaire' => 'required|numeric|min:0',
		'items.*.remise_ligne' => 'nullable|numeric|min:0',
		'remiseTotale' => 'nullable|numeric|min:0',
		'montantPaye' => 'nullable|numeric|min:0',
		'newClientData.nom' => 'required|string|max:255',
		'newClientData.prenom' => 'required|string|max:255',
		'newClientData.telephone' => 'nullable|string|max:20',
		'newClientData.email' => 'nullable|email|max:255',
		'newClientData.adresse' => 'nullable|string|max:500',
	];

	public function mount($view = 'list', $saleId = null)
	{
		$this->loadFormData();
		$this->loadClientOptions();
		$this->loadLotOptions();

		if ($this->currentView === 'create') {
			$this->addItem();
		}

		// Initialize Filament form data
		$this->data = [
			'clientId' => $this->clientId,
			'items' => $this->items,
			'remiseTotale' => $this->remiseTotale,
			'montantPaye' => $this->montantPaye,
		];
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
		$this->loadClientOptions();
		$this->loadLotOptions();
	}

	public function loadClientOptions()
	{
		$this->clientOptions = Client::all()
			->mapWithKeys(function ($client) {
				return [
					$client->id => "{$client->nom} {$client->prenom}" .
						($client->telephone ? " - {$client->telephone}" : '')
				];
			})
			->toArray();
	}

	public function loadLotOptions()
	{
		$this->lotOptions = Lot::with('article')
			->where('quantite_restante', '>', 0)
			->get()
			->mapWithKeys(function ($lot) {
				return [
					$lot->id => "{$lot->article->designation} - {$lot->numero_lot} " .
						"({$lot->quantite_restante} en stock - " .
						number_format($lot->prix_vente, 0, ',', ' ') . " FCFA)"
				];
			})
			->toArray();
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
			->whereHas('article', function ($query) use ($search) {
				$query->where('designation', 'like', "%{$search}%");
			})
			->orWhere('numero_lot', 'like', "%{$search}%")
			->where('quantite_restante', '>', 0)
			->limit(50)
			->get()
			->mapWithKeys(function ($lot) {
				return [
					$lot->id => "{$lot->article->designation} - {$lot->numero_lot} " .
						"({$lot->quantite_restante} en stock - " .
						number_format($lot->prix_vente, 0, ',', ' ') . " FCFA)"
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
				'quantite' => $ligne->quantite,
				'prix_unitaire' => $ligne->prix_unitaire,
				'remise_ligne' => $ligne->remise_ligne,
			];
		}
	}

	public function addItem()
	{
		$this->items[] = [
			'lot_id' => '',
			'quantite' => 1,
			'prix_unitaire' => 0,
			'remise_ligne' => 0,
		];
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

		$this->dispatch('client-created', [
			'message' => 'Client créé avec succès'
		]);
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

	public function saveSale()
	{
		// Sync Filament form data with component properties
		$this->clientId = $this->data['clientId'] ?? '';
		$this->items = $this->data['items'] ?? [];
		$this->remiseTotale = $this->data['remiseTotale'] ?? 0;
		$this->montantPaye = $this->data['montantPaye'] ?? 0;

		$this->validate();

		try {
			DB::beginTransaction();

			// Create or update sale
			$saleData = [
				'client_id' => $this->clientId,
				'user_id' => Auth::id(),
				'remise_totale' => $this->remiseTotale ?? 0,
				'montant_paye' => $this->montantPaye ?? 0,
				'total' => $this->getTotal(),
				'statut' => 'en_cours'
			];

			if ($this->isEditing) {
				$sale = Vente::findOrFail($this->editingSaleId);
				$sale->update($saleData);
				// Delete existing line items
				$sale->ligneVentes()->delete();
			} else {
				$sale = Vente::create($saleData);
			}

			// Create line items
			foreach ($this->items as $item) {
				if (!empty($item['lot_id']) && !empty($item['quantite'])) {
					LigneVente::create([
						'vente_id' => $sale->id,
						'lot_id' => $item['lot_id'],
						'quantite' => $item['quantite'],
						'prix_unitaire' => $item['prix_unitaire'] ?? 0,
						'remise_ligne' => $item['remise_ligne'] ?? 0,
						'sous_total' => ($item['quantite'] * $item['prix_unitaire']) - ($item['remise_ligne'] ?? 0)
					]);

					// Update lot quantity
					$lot = Lot::find($item['lot_id']);
					if ($lot) {
						$lot->quantite_restante -= $item['quantite'];
						$lot->save();
					}
				}
			}

			DB::commit();

			$this->dispatch('sale-saved', [
				'message' => $this->isEditing ? 'Vente mise à jour avec succès!' : 'Vente créée avec succès!',
				'type' => 'success'
			]);

			$this->switchToList();
		} catch (\Exception $e) {
			DB::rollBack();
			$this->dispatch('sale-saved', [
				'message' => 'Erreur lors de l\'enregistrement: ' . $e->getMessage(),
				'type' => 'error'
			]);
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

	public function form(Form $form): Form
	{
		return $form
			->schema([
				Grid::make(2)
					->schema([
						// Section Client avec TailwindCSS
						Section::make('Client')
							->schema([
								Grid::make(1)
									->schema([
										Group::make()
											->schema([
												TextInput::make('client_search')
													->label('Rechercher un client')
													->placeholder('Tapez le nom, prénom ou téléphone...')
													->live(debounce: 300)
													->afterStateUpdated(function ($state) {
														$this->searchClients($state);
													})
													->extraAttributes([
														'class' => 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30'
													]),

												Select::make('clientId')
													->label('Sélectionner le client')
													->options($this->clientOptions ?? [])
													->placeholder('Choisissez un client...')
													->required()
													->live()
													->afterStateUpdated(function ($state) {
														$this->clientId = $state;
													})
													->extraAttributes([
														'class' => 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30'
													]),
											])
									])
							])
							->columnSpan(1),

						// Section Articles
						Section::make('Articles')
							->schema([
								Repeater::make('items')
									->schema([
										Grid::make(6)
											->schema([
												Group::make()
													->schema([
														TextInput::make('lot_search')
															->label('Rechercher un lot')
															->placeholder('Tapez le nom de l\'article ou numéro de lot...')
															->live(debounce: 300)
															->afterStateUpdated(function ($state) {
																$this->searchLots($state);
															})
															->extraAttributes([
																'class' => 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30'
															]),

														Select::make('lot_id')
															->label('Sélectionner le lot')
															->options($this->lotOptions ?? [])
															->placeholder('Choisissez un lot...')
															->live()
															->afterStateUpdated(function ($state, $set) {
																if ($state) {
																	$lot = Lot::find($state);
																	if ($lot) {
																		$set('prix_unitaire', $lot->prix_vente);
																	}
																}
															})
															->extraAttributes([
																'class' => 'dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30'
															]),
													])
													->columnSpan(2),

												TextInput::make('quantite')
													->label('Quantité')
													->numeric()
													->minValue(1)
													->required()
													->live()
													->columnSpan(1),

												TextInput::make('prix_unitaire')
													->label('Prix unitaire')
													->numeric()
													->minValue(0)
													->required()
													->live()
													->columnSpan(1),

												TextInput::make('remise_ligne')
													->label('Remise ligne')
													->numeric()
													->minValue(0)
													->default(0)
													->live()
													->columnSpan(1),
											])
									])
									->addActionLabel('Ajouter un article')
									->defaultItems(1)
									->minItems(1)
									->collapsible()
									->itemLabel(function (array $state): ?string {
										if (isset($state['lot_id'])) {
											$lot = Lot::with('article')->find($state['lot_id']);
											return $lot?->article?->nom ?? 'Article inconnu';
										}
										return 'Nouvel article';
									}),
							])
							->columnSpan(1),
					]),

				// Section Totaux
				Grid::make(3)
					->schema([
						TextInput::make('remiseTotale')
							->label('Remise totale')
							->numeric()
							->minValue(0)
							->default(0)
							->live(),

						TextInput::make('montantPaye')
							->label('Montant payé')
							->numeric()
							->minValue(0)
							->required()
							->live(),
					])
			])
			->statePath('data');
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

		return view('livewire.sales-manager');
	}
}
