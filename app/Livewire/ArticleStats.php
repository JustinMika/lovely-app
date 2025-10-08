<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\Lot;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ArticleStats extends Component
{
	public $totalArticles = 0;
	public $stockFaible = 0;
	public $totalCategories = 0;
	public $valeurStock = 0;
	public $croissanceArticles = 0;
	public $croissanceStock = 0;

	public function mount()
	{
		$this->calculateStats();
	}

	public function calculateStats()
	{
		// 1. Total Articles
		$this->totalArticles = Article::where('actif', true)->count();

		// Croissance articles (comparaison avec le mois dernier)
		$articlesLastMonth = Article::where('actif', true)
			->where('created_at', '<=', now()->subMonth())
			->count();

		if ($articlesLastMonth > 0) {
			$this->croissanceArticles = round((($this->totalArticles - $articlesLastMonth) / $articlesLastMonth) * 100, 1);
		}

		// 2. Stock Faible (articles avec stock total < seuil d'alerte)
		$this->stockFaible = Article::active()
			->whereHas('lots', function ($query) {
				$query->whereRaw('quantite_restante <= seuil_alerte');
			})->count();

		// 3. Total Catégories (estimation basée sur les mots-clés des désignations)
		$designations = Article::active()->pluck('designation');
		$categories = collect();

		foreach ($designations as $designation) {
			// Extraire le premier mot comme catégorie
			$firstWord = strtolower(explode(' ', trim($designation))[0]);
			if (strlen($firstWord) > 2) {
				$categories->push($firstWord);
			}
		}

		$this->totalCategories = $categories->unique()->count() ?: 1;

		// 4. Valeur Stock (somme des prix d'achat * quantités restantes)
		$valeurStock = DB::table('lots')
			->join('articles', 'lots.article_id', '=', 'articles.id')
			->where('articles.actif', true)
			->sum(DB::raw('lots.prix_achat * lots.quantite_restante'));

		$this->valeurStock = round($valeurStock, 2);

		// Croissance valeur stock (comparaison avec le mois dernier)
		$valeurStockLastMonth = DB::table('lots')
			->join('articles', 'lots.article_id', '=', 'articles.id')
			->where('articles.actif', true)
			->where('lots.created_at', '<=', now()->subMonth())
			->sum(DB::raw('lots.prix_achat * lots.quantite_restante'));

		if ($valeurStockLastMonth > 0) {
			$this->croissanceStock = round((($this->valeurStock - $valeurStockLastMonth) / $valeurStockLastMonth) * 100, 1);
		}
	}

	public function refreshStats()
	{
		$this->calculateStats();
		session()->flash('message', 'Statistiques mises à jour avec succès !');
	}

	public function render()
	{
		return view('livewire.article-stats');
	}
}
