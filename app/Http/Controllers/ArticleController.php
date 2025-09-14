<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
	/**
	 * Display a listing of the articles.
	 */
	public function index(): View
	{
		// TODO: Implement article retrieval logic
		$articles = collect(); // Placeholder for articles

		$metrics = [
			'total_articles' => 1245,
			'total_value' => 89450,
			'out_of_stock' => 23,
			'new_this_month' => 156
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
	public function store(Request $request)
	{
		// TODO: Implement article creation logic
		$validated = $request->validate([
			'name' => 'required|string|max:255',
			'description' => 'nullable|string',
			'price' => 'required|numeric|min:0',
			'stock_quantity' => 'required|integer|min:0',
			'category_id' => 'nullable|exists:categories,id',
		]);

		// Article::create($validated);

		return redirect()->route('articles.index')
			->with('success', 'Article créé avec succès.');
	}

	/**
	 * Display the specified article.
	 */
	public function show(string $id): View
	{
		// TODO: Implement article retrieval logic
		// $article = Article::findOrFail($id);

		return view('pages.articles.show', compact('id'));
	}

	/**
	 * Show the form for editing the specified article.
	 */
	public function edit(string $id): View
	{
		// TODO: Implement article retrieval logic
		// $article = Article::findOrFail($id);

		return view('pages.articles.edit', compact('id'));
	}

	/**
	 * Update the specified article in storage.
	 */
	public function update(Request $request, string $id)
	{
		// TODO: Implement article update logic
		$validated = $request->validate([
			'name' => 'required|string|max:255',
			'description' => 'nullable|string',
			'price' => 'required|numeric|min:0',
			'stock_quantity' => 'required|integer|min:0',
			'category_id' => 'nullable|exists:categories,id',
		]);

		// $article = Article::findOrFail($id);
		// $article->update($validated);

		return redirect()->route('articles.index')
			->with('success', 'Article mis à jour avec succès.');
	}

	/**
	 * Remove the specified article from storage.
	 */
	public function destroy(string $id)
	{
		// TODO: Implement article deletion logic
		// $article = Article::findOrFail($id);
		// $article->delete();

		return redirect()->route('articles.index')
			->with('success', 'Article supprimé avec succès.');
	}
}
