<?php

namespace App\Livewire;

use App\Models\Vente;
use App\Models\Client;
use App\Models\Article;
use App\Models\Lot;
use App\Models\LigneVente;
use App\Services\CartService;
use App\Services\StockFifoService;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class VenteManager extends Component
{
    use WithPagination, LivewireAlert;

    protected $cartService;
    protected $stockFifoService;

    // Propriétés de recherche et tri
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;

    // Propriétés du formulaire vente
    public $venteId;
    public $client_id;
    public $total = 0;
    public $remise_totale = 0;
    public $montant_paye = 0;

    // Propriétés pour le panier
    public $selectedArticle = null;
    public $quantite = 1;
    public $remise_ligne = 0;
    public $contenuPanier = [];

    // États du composant
    public $showModal = false;
    public $editMode = false;
    public $showDetailModal = false;
    public $selectedVente = null;
    public $showCartModal = false;

    protected $paginationTheme = 'tailwind';

    public function boot(CartService $cartService, StockFifoService $stockFifoService)
    {
        $this->cartService = $cartService;
        $this->stockFifoService = $stockFifoService;
    }

    protected function rules()
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'montant_paye' => 'required|numeric|min:0',
        ];
    }

    protected $messages = [
        'client_id.required' => 'Veuillez sélectionner un client.',
        'client_id.exists' => 'Le client sélectionné n\'existe pas.',
        'montant_paye.required' => 'Le montant payé est obligatoire.',
        'montant_paye.min' => 'Le montant payé doit être positif.',
    ];

    public function mount()
    {
        $this->resetForm();
        $this->rafraichirPanier();
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
        $this->viderPanier();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
        $this->viderPanier();
    }

    public function resetForm()
    {
        $this->venteId = null;
        $this->client_id = null;
        $this->total = 0;
        $this->remise_totale = 0;
        $this->montant_paye = 0;
        $this->selectedArticle = null;
        $this->quantite = 1;
        $this->remise_ligne = 0;
    }

    public function openCartModal()
    {
        $this->showCartModal = true;
        $this->rafraichirPanier();
    }

    public function closeCartModal()
    {
        $this->showCartModal = false;
    }

    public function ajouterAuPanier()
    {
        $this->validate([
            'selectedArticle' => 'required|exists:articles,id',
            'quantite' => 'required|integer|min:1',
            'remise_ligne' => 'nullable|numeric|min:0|max:100',
        ], [
            'selectedArticle.required' => 'Veuillez sélectionner un article.',
            'quantite.required' => 'La quantité est obligatoire.',
            'quantite.min' => 'La quantité doit être d\'au moins 1.',
            'remise_ligne.max' => 'La remise ne peut pas dépasser 100%.',
        ]);

        try {
            $result = $this->cartService->ajouterArticle(
                $this->selectedArticle,
                $this->quantite,
                $this->remise_ligne ?? 0
            );

            $this->alert('success', $result['message']);
            $this->rafraichirPanier();

            // Réinitialiser les champs
            $this->selectedArticle = null;
            $this->quantite = 1;
            $this->remise_ligne = 0;
        } catch (\Exception $e) {
            $this->alert('error', $e->getMessage());
        }
    }

    public function mettreAJourQuantitePanier($articleId, $nouvelleQuantite)
    {
        try {
            $result = $this->cartService->mettreAJourQuantite($articleId, $nouvelleQuantite);
            $this->alert('success', $result['message']);
            $this->rafraichirPanier();
        } catch (\Exception $e) {
            $this->alert('error', $e->getMessage());
        }
    }

    public function retirerDuPanier($articleId)
    {
        try {
            $result = $this->cartService->retirerArticle($articleId);
            $this->alert('success', $result['message']);
            $this->rafraichirPanier();
        } catch (\Exception $e) {
            $this->alert('error', $e->getMessage());
        }
    }

    public function viderPanier()
    {
        try {
            $this->cartService->viderPanier();
            $this->rafraichirPanier();
        } catch (\Exception $e) {
            $this->alert('error', $e->getMessage());
        }
    }

    public function appliquerRemisePanier($articleId, $remise)
    {
        try {
            $result = $this->cartService->appliquerRemise($articleId, $remise);
            $this->alert('success', $result['message']);
            $this->rafraichirPanier();
        } catch (\Exception $e) {
            $this->alert('error', $e->getMessage());
        }
    }

    public function rafraichirPanier()
    {
        $contenu = $this->cartService->getContenuPanier();
        $this->contenuPanier = $contenu['items']->toArray();
        $this->total = $contenu['total'];
    }

    public function save()
    {
        // Valider le panier
        $validation = $this->cartService->validerPanier();
        if (!$validation['valide']) {
            foreach ($validation['erreurs'] as $erreur) {
                $this->alert('error', $erreur);
            }
            return;
        }

        $this->validate();

        try {
            DB::transaction(function () {
                // Créer la vente
                $vente = Vente::create([
                    'utilisateur_id' => auth()->id(),
                    'client_id' => $this->client_id,
                    'total' => $this->total,
                    'remise_totale' => $this->remise_totale ?? 0,
                    'montant_paye' => $this->montant_paye,
                ]);

                // Obtenir les informations de facturation du panier
                $infosFacturation = $this->cartService->getInfosFacturation();

                // Créer les lignes de vente et appliquer FIFO
                foreach ($infosFacturation['lignes_vente'] as $ligne) {
                    // Appliquer la déduction FIFO
                    $lotsUtilises = $this->stockFifoService->deduireStock(
                        $ligne['article_id'],
                        $ligne['quantite']
                    );

                    $this->stockFifoService->appliquerDeduction($lotsUtilises);

                    // Créer les lignes de vente pour chaque lot utilisé
                    foreach ($lotsUtilises as $lotUtilise) {
                        LigneVente::create([
                            'vente_id' => $vente->id,
                            'article_id' => $ligne['article_id'],
                            'lot_id' => $lotUtilise['lot_id'],
                            'quantite' => $lotUtilise['quantite_deduite'],
                            'prix_unitaire' => $lotUtilise['prix_vente'],
                            'prix_achat' => $lotUtilise['prix_achat'],
                            'remise_ligne' => ($ligne['remise_pourcentage'] * $lotUtilise['prix_vente'] * $lotUtilise['quantite_deduite']) / 100,
                        ]);
                    }
                }

                // Vider le panier après succès
                $this->cartService->viderPanier();

                // Rediriger vers l'impression de facture
                $this->dispatch('imprimer-facture', venteId: $vente->id);
            });

            $this->alert('success', 'Vente créée avec succès! Impression de la facture...');
            $this->closeModal();
        } catch (\Exception $e) {
            $this->alert('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    public function edit($venteId)
    {
        $vente = Vente::with(['ligneVentes.article', 'ligneVentes.lot'])->findOrFail($venteId);

        $this->venteId = $vente->id;
        $this->client_id = $vente->client_id;
        $this->total = $vente->total;
        $this->remise_totale = $vente->remise_totale;
        $this->montant_paye = $vente->montant_paye;

        $this->editMode = true;
        $this->showModal = true;
    }

    public function showDetail($venteId)
    {
        $this->selectedVente = Vente::with(['client', 'utilisateur', 'ligneVentes.article', 'ligneVentes.lot'])->findOrFail($venteId);
        $this->showDetailModal = true;
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedVente = null;
    }

    #[On('confirmDelete')]
    public function delete($venteId)
    {
        try {
            DB::transaction(function () use ($venteId) {
                $vente = Vente::with('ligneVentes')->findOrFail($venteId);

                // Restaurer le stock avec FIFO inverse
                foreach ($vente->ligneVentes as $ligne) {
                    $lot = $ligne->lot;
                    $lot->quantite_restante += $ligne->quantite;
                    $lot->save();
                }

                // Supprimer les lignes puis la vente
                $vente->ligneVentes()->delete();
                $vente->delete();
            });

            $this->alert('success', 'Vente supprimée avec succès!');
        } catch (\Exception $e) {
            $this->alert('error', 'Une erreur est survenue lors de la suppression.');
        }
    }

    public function confirmDelete($venteId)
    {
        $this->confirm('Êtes-vous sûr de vouloir supprimer cette vente?', [
            'confirmButtonText' => 'Oui, supprimer',
            'cancelButtonText' => 'Annuler',
            'onConfirmed' => 'confirmDelete',
            'data' => ['venteId' => $venteId]
        ]);
    }

    public function exportPdf()
    {
        return redirect()->route('ventes.export.pdf', [
            'search' => $this->search,
            'sortField' => $this->sortField,
            'sortDirection' => $this->sortDirection,
        ]);
    }

    public function getVentes()
    {
        return Vente::with(['client', 'utilisateur'])
            ->withCount('ligneVentes')
            ->when($this->search, function (Builder $query) {
                $query->where(function (Builder $q) {
                    $q->whereHas('client', function (Builder $clientQuery) {
                        $clientQuery->where('nom', 'like', '%' . $this->search . '%');
                    })
                        ->orWhereHas('utilisateur', function (Builder $userQuery) {
                            $userQuery->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhere('id', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function getClients()
    {
        return Client::orderBy('nom')->get();
    }

    public function getArticles()
    {
        return Article::where('actif', true)->orderBy('designation')->get();
    }

    public function getStockDisponible($articleId)
    {
        return $this->stockFifoService->getStockDisponible($articleId);
    }

    public function render()
    {
        return view('livewire.vente-manager', [
            'ventes' => $this->getVentes(),
            'clients' => $this->getClients(),
            'articles' => $this->getArticles(),
        ]);
    }
}
