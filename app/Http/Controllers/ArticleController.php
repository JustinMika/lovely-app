<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ArticleController extends Controller
{
	/**
	 * Display a listing of the articles.
	 */
	public function index(): View
	{
		$articles = Article::latest()->paginate(10);

		$metrics = [
			'total_articles' => Article::count(),
			'active_articles' => Article::where('actif', true)->count(),
			'inactive_articles' => Article::where('actif', false)->count(),
			'recent_articles' => Article::where('created_at', '>=', now()->subMonth())->count()
		];

		return view('pages.articles.index', compact('articles', 'metrics'));
	}

	/**
	 * Show the form for creating a new article.
	 */
	public function create(): View
	{
		return view('pages.articles.create');
	}

	/**
	 * Store a newly created article in storage.
	 */
	public function store(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'designation' => 'required|string|max:255',
			'description' => 'nullable|string',
			'actif' => 'boolean',
		]);

		// Set default value for actif if not provided
		$validated['actif'] = $request->has('actif') ? (bool) $request->actif : true;

		Article::create($validated);

		return redirect()->route('articles.index')
			->with('success', 'Article créé avec succès.');
	}

	/**
	 * Display the specified article.
	 */
	public function show(Article $article): View
	{
		// Load relationships if needed
		$article->load(['lots']);

		return view('pages.articles.show', compact('article'));
	}

	/**
	 * Show the form for editing the specified article.
	 */
	public function edit(Article $article): View
	{
		return view('pages.articles.edit', compact('article'));
	}

	/**
	 * Update the specified article in storage.
	 */
	public function update(Request $request, Article $article): RedirectResponse
	{
		$validated = $request->validate([
			'designation' => 'required|string|max:255|unique:articles,designation,' . $article->id,
			'description' => 'nullable|string',
			'actif' => 'boolean',
		]);

		// Set default value for actif if not provided
		$validated['actif'] = $request->has('actif') ? (bool) $request->actif : false;

		$article->update($validated);

		return redirect()->route('articles.index')
			->with('success', 'Article mis à jour avec succès.');
	}

	/**
	 * Remove the specified article from storage.
	 */
	public function destroy(Article $article): RedirectResponse
	{
		// Check if article has associated lots
		if ($article->lots()->count() > 0) {
			return redirect()->route('articles.index')
				->with('error', 'Impossible de supprimer cet article car il a des lots associés.');
		}

		// Check if article has associated sales
		if ($article->ligneVentes()->count() > 0) {
			return redirect()->route('articles.index')
				->with('error', 'Impossible de supprimer cet article car il a des ventes associées.');
		}

		$article->delete();

		return redirect()->route('articles.index')
			->with('success', 'Article supprimé avec succès.');
	}
}
