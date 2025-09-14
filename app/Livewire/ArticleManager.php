<?php

namespace App\Livewire;

use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ArticleManager extends Component
{
	use WithPagination, LivewireAlert;

	// Propriétés pour le formulaire
	public $designation = '';
	public $description = '';
	public $actif = true;

	// Propriétés pour la gestion
	public $articleId = null;
	public $showModal = false;
	public $isEditing = false;
	public $search = '';
	public $sortField = 'designation';
	public $sortDirection = 'asc';
	public $perPage = 10;

	protected $paginationTheme = 'tailwind';

	protected $rules = [
		'designation' => 'required|string|max:255|unique:articles,designation',
		'description' => 'nullable|string|max:1000',
		'actif' => 'boolean',
	];

	protected $messages = [
		'designation.required' => 'La désignation est obligatoire.',
		'designation.unique' => 'Cette désignation existe déjà.',
		'designation.max' => 'La désignation ne peut pas dépasser 255 caractères.',
		'description.max' => 'La description ne peut pas dépasser 1000 caractères.',
	];

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

	public function openModal()
	{
		$this->resetForm();
		$this->showModal = true;
		$this->isEditing = false;
	}

	public function closeModal()
	{
		$this->showModal = false;
		$this->resetForm();
		$this->resetValidation();
	}

	public function resetForm()
	{
		$this->designation = '';
		$this->description = '';
		$this->actif = true;
		$this->articleId = null;
	}

	public function store()
	{
		$this->validate();

		try {
			Article::create([
				'designation' => $this->designation,
				'description' => $this->description,
				'actif' => $this->actif,
			]);

			$this->closeModal();
			$this->alert('success', 'Article créé avec succès !');
		} catch (\Exception $e) {
			$this->alert('error', 'Erreur lors de la création de l\'article.');
		}
	}

	public function edit($id)
	{
		$article = Article::findOrFail($id);

		$this->articleId = $article->id;
		$this->designation = $article->designation;
		$this->description = $article->description;
		$this->actif = $article->actif;

		$this->isEditing = true;
		$this->showModal = true;
	}

	public function update()
	{
		$rules = $this->rules;
		$rules['designation'] = 'required|string|max:255|unique:articles,designation,' . $this->articleId;

		$this->validate($rules);

		try {
			$article = Article::findOrFail($this->articleId);
			$article->update([
				'designation' => $this->designation,
				'description' => $this->description,
				'actif' => $this->actif,
			]);

			$this->closeModal();
			$this->alert('success', 'Article modifié avec succès !');
		} catch (\Exception $e) {
			$this->alert('error', 'Erreur lors de la modification de l\'article.');
		}
	}

	public function confirmDelete($id)
	{
		$this->confirm('Êtes-vous sûr de vouloir supprimer cet article ?', [
			'confirmButtonText' => 'Oui, supprimer',
			'cancelButtonText' => 'Annuler',
			'onConfirmed' => 'delete',
			'data' => ['id' => $id]
		]);
	}

	public function delete($data)
	{
		try {
			$article = Article::findOrFail($data['id']);

			// Vérifier s'il y a des lots associés
			if ($article->lots()->count() > 0) {
				$this->alert('error', 'Impossible de supprimer cet article car il a des lots associés.');
				return;
			}

			$article->delete();
			$this->alert('success', 'Article supprimé avec succès !');
		} catch (\Exception $e) {
			$this->alert('error', 'Erreur lors de la suppression de l\'article.');
		}
	}

	public function toggleStatus($id)
	{
		try {
			$article = Article::findOrFail($id);
			$article->update(['actif' => !$article->actif]);

			$status = $article->actif ? 'activé' : 'désactivé';
			$this->alert('success', "Article {$status} avec succès !");
		} catch (\Exception $e) {
			$this->alert('error', 'Erreur lors du changement de statut.');
		}
	}

	public function exportPdf()
	{
		return redirect()->route('articles.export.pdf', [
			'search' => $this->search,
			'sortField' => $this->sortField,
			'sortDirection' => $this->sortDirection
		]);
	}

	public function exportExcel()
	{
		return redirect()->route('articles.export.excel', [
			'search' => $this->search,
			'sortField' => $this->sortField,
			'sortDirection' => $this->sortDirection
		]);
	}

	public function render()
	{
		$articles = Article::query()
			->when($this->search, function ($query) {
				$query->where('designation', 'like', '%' . $this->search . '%')
					->orWhere('description', 'like', '%' . $this->search . '%');
			})
			->orderBy($this->sortField, $this->sortDirection)
			->paginate($this->perPage);

		return view('livewire.article-manager', compact('articles'));
	}
}
