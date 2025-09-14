<?php

namespace App\Livewire;

use App\Models\Vente;
use App\Models\Client;
use App\Models\Article;
use App\Models\Lot;
use App\Models\LigneVente;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class VenteManager extends Component
{
    use WithPagination, LivewireAlert;

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

    // Propriétés pour les lignes de vente
    public $lignes = [];
    public $selectedArticle = null;
    public $selectedLot = null;
    public $quantite = 1;
    public $prix_unitaire = 0;
    public $remise_ligne = 0;

    // États du composant
    public $showModal = false;
    public $editMode = false;
    public $showDetailModal = false;
    public $selectedVente = null;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'total' => 'required|numeric|min:0',
            'remise_totale' => 'nullable|numeric|min:0|lte:total',
            'montant_paye' => 'required|numeric|min:0',
            'lignes' => 'required|array|min:1',
            'lignes.*.article_id' => 'required|exists:articles,id',
            'lignes.*.lot_id' => 'required|exists:lots,id',
            'lignes.*.quantite' => 'required|integer|min:1',
            'lignes.*.prix_unitaire' => 'required|numeric|min:0',
            'lignes.*.remise_ligne' => 'nullable|numeric|min:0',
        ];
    }

    protected $messages = [
        'client_id.required' => 'Veuillez sélectionner un client.',
        'client_id.exists' => 'Le client sélectionné n\'existe pas.',
        'total.required' => 'Le montant total est obligatoire.',
        'total.min' => 'Le montant total doit être positif.',
        'remise_totale.lte' => 'La remise ne peut pas dépasser le montant total.',
        'montant_paye.required' => 'Le montant payé est obligatoire.',
        'montant_paye.min' => 'Le montant payé doit être positif.',
        'lignes.required' => 'Veuillez ajouter au moins un article à la vente.',
        'lignes.min' => 'Veuillez ajouter au moins un article à la vente.',
        'lignes.*.article_id.required' => 'Veuillez sélectionner un article.',
        'lignes.*.lot_id.required' => 'Veuillez sélectionner un lot.',
        'lignes.*.quantite.required' => 'La quantité est obligatoire.',
        'lignes.*.quantite.min' => 'La quantité doit être d\'au moins 1.',
        'lignes.*.prix_unitaire.required' => 'Le prix unitaire est obligatoire.',
        'lignes.*.prix_unitaire.min' => 'Le prix unitaire doit être positif.',
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
        $this->venteId = null;
        $this->client_id = null;
        $this->total = 0;
        $this->remise_totale = 0;
        $this->montant_paye = 0;
        $this->lignes = [];
        $this->selectedArticle = null;
        $this->selectedLot = null;
        $this->quantite = 1;
        $this->prix_unitaire = 0;
        $this->remise_ligne = 0;
    }

    public function updatedSelectedArticle()
    {
        if ($this->selectedArticle) {
            // Réinitialiser le lot sélectionné
            $this->selectedLot = null;
            $this->prix_unitaire = 0;
        }
    }

    public function updatedSelectedLot()
    {
        if ($this->selectedLot) {
            $lot = Lot::find($this->selectedLot);
            if ($lot) {
                $this->prix_unitaire = $lot->prix_vente;
            }
        }
    }

    public function ajouterLigne()
    {
        // Validation des champs de la ligne
        $this->validate([
            'selectedArticle' => 'required|exists:articles,id',
            'selectedLot' => 'required|exists:lots,id',
            'quantite' => 'required|integer|min:1',
            'prix_unitaire' => 'required|numeric|min:0',
            'remise_ligne' => 'nullable|numeric|min:0',
        ], [
            'selectedArticle.required' => 'Veuillez sélectionner un article.',
            'selectedLot.required' => 'Veuillez sélectionner un lot.',
            'quantite.required' => 'La quantité est obligatoire.',
            'quantite.min' => 'La quantité doit être d\'au moins 1.',
            'prix_unitaire.required' => 'Le prix unitaire est obligatoire.',
            'prix_unitaire.min' => 'Le prix unitaire doit être positif.',
        ]);

        // Vérifier le stock disponible
        $lot = Lot::find($this->selectedLot);
        if (!$lot || $lot->quantite_restante < $this->quantite) {
            $this->alert('error', 'Stock insuffisant pour ce lot.');
            return;
        }

        // Vérifier si l'article/lot n'est pas déjà dans la liste
        $existingIndex = collect($this->lignes)->search(function ($ligne) {
            return $ligne['article_id'] == $this->selectedArticle && $ligne['lot_id'] == $this->selectedLot;
        });

        if ($existingIndex !== false) {
            // Mettre à jour la ligne existante
            $this->lignes[$existingIndex]['quantite'] += $this->quantite;
        } else {
            // Ajouter une nouvelle ligne
            $article = Article::find($this->selectedArticle);
            $this->lignes[] = [
                'article_id' => $this->selectedArticle,
                'article_nom' => $article->designation,
                'lot_id' => $this->selectedLot,
                'lot_numero' => $lot->numero_lot,
                'quantite' => $this->quantite,
                'prix_unitaire' => $this->prix_unitaire,
                'prix_achat' => $lot->prix_achat,
                'remise_ligne' => $this->remise_ligne ?? 0,
            ];
        }

        // Calculer le total
        $this->calculerTotal();

        // Réinitialiser les champs
        $this->selectedArticle = null;
        $this->selectedLot = null;
        $this->quantite = 1;
        $this->prix_unitaire = 0;
        $this->remise_ligne = 0;
    }

    public function supprimerLigne($index)
    {
        unset($this->lignes[$index]);
        $this->lignes = array_values($this->lignes);
        $this->calculerTotal();
    }

    public function calculerTotal()
    {
        $this->total = collect($this->lignes)->sum(function ($ligne) {
            return ($ligne['prix_unitaire'] * $ligne['quantite']) - ($ligne['remise_ligne'] ?? 0);
        });
    }

    public function save()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                if ($this->editMode) {
                    $vente = Vente::findOrFail($this->venteId);

                    // Restaurer le stock des anciennes lignes
                    foreach ($vente->ligneVentes as $ancienneLigne) {
                        $lot = $ancienneLigne->lot;
                        $lot->quantite_restante += $ancienneLigne->quantite;
                        $lot->save();
                    }

                    // Supprimer les anciennes lignes
                    $vente->ligneVentes()->delete();
                } else {
                    $vente = new Vente();
                }

                // Sauvegarder la vente
                $vente->fill([
                    'utilisateur_id' => auth()->id(),
                    'client_id' => $this->client_id,
                    'total' => $this->total,
                    'remise_totale' => $this->remise_totale ?? 0,
                    'montant_paye' => $this->montant_paye,
                ]);
                $vente->save();

                // Sauvegarder les lignes de vente
                foreach ($this->lignes as $ligne) {
                    // Vérifier et réduire le stock
                    $lot = Lot::find($ligne['lot_id']);
                    if (!$lot->hasStock($ligne['quantite'])) {
                        throw new \Exception("Stock insuffisant pour l'article {$ligne['article_nom']}");
                    }

                    $lot->reduireStock($ligne['quantite']);

                    // Créer la ligne de vente
                    LigneVente::create([
                        'vente_id' => $vente->id,
                        'article_id' => $ligne['article_id'],
                        'lot_id' => $ligne['lot_id'],
                        'quantite' => $ligne['quantite'],
                        'prix_unitaire' => $ligne['prix_unitaire'],
                        'prix_achat' => $ligne['prix_achat'],
                        'remise_ligne' => $ligne['remise_ligne'] ?? 0,
                    ]);
                }
            });

            $this->alert('success', $this->editMode ? 'Vente modifiée avec succès!' : 'Nouvelle vente créée avec succès!');
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

        // Charger les lignes
        $this->lignes = $vente->ligneVentes->map(function ($ligne) {
            return [
                'article_id' => $ligne->article_id,
                'article_nom' => $ligne->article->designation,
                'lot_id' => $ligne->lot_id,
                'lot_numero' => $ligne->lot->numero_lot,
                'quantite' => $ligne->quantite,
                'prix_unitaire' => $ligne->prix_unitaire,
                'prix_achat' => $ligne->prix_achat,
                'remise_ligne' => $ligne->remise_ligne,
            ];
        })->toArray();

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

                // Restaurer le stock
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

    public function getLotsForArticle()
    {
        if (!$this->selectedArticle) {
            return collect();
        }

        return Lot::where('article_id', $this->selectedArticle)
            ->where('quantite_restante', '>', 0)
            ->with(['ville'])
            ->orderBy('date_arrivee', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.vente-manager', [
            'ventes' => $this->getVentes(),
            'clients' => $this->getClients(),
            'articles' => $this->getArticles(),
            'lotsDisponibles' => $this->getLotsForArticle(),
        ]);
    }
}
