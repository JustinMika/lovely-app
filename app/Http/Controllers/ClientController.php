<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
	/**
	 * Display a listing of the clients.
	 */
	public function index(): View
	{
		// TODO: Implement client retrieval logic
		$clients = collect(); // Placeholder for clients

		$metrics = [
			'total_clients' => 856,
			'new_this_month' => 42,
			'active_clients' => 734,
			'total_revenue' => 245680
		];

		return view('pages.clients.index', compact('clients', 'metrics'));
	}

	/**
	 * Show the form for creating a new client.
	 */
	public function create(): View
	{
		return view('pages.clients.create');
	}

	/**
	 * Store a newly created client in storage.
	 */
	public function store(Request $request)
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|email|unique:clients,email',
			'phone' => 'nullable|string|max:20',
			'address' => 'nullable|string',
			'company' => 'nullable|string|max:255',
			'status' => 'required|in:active,inactive',
		]);

		// TODO: Implement client creation logic
		// Client::create($validated);

		return redirect()->route('clients.index')
			->with('success', 'Client créé avec succès.');
	}

	/**
	 * Display the specified client.
	 */
	public function show(string $id): View
	{
		// TODO: Implement client retrieval logic
		// $client = Client::findOrFail($id);

		return view('pages.clients.show', compact('id'));
	}

	/**
	 * Show the form for editing the specified client.
	 */
	public function edit(string $id): View
	{
		// TODO: Implement client retrieval logic
		// $client = Client::findOrFail($id);

		return view('pages.clients.edit', compact('id'));
	}

	/**
	 * Update the specified client in storage.
	 */
	public function update(Request $request, string $id)
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|email|unique:clients,email,' . $id,
			'phone' => 'nullable|string|max:20',
			'address' => 'nullable|string',
			'company' => 'nullable|string|max:255',
			'status' => 'required|in:active,inactive',
		]);

		// TODO: Implement client update logic
		// $client = Client::findOrFail($id);
		// $client->update($validated);

		return redirect()->route('clients.index')
			->with('success', 'Client mis à jour avec succès.');
	}

	/**
	 * Remove the specified client from storage.
	 */
	public function destroy(string $id)
	{
		// TODO: Implement client deletion logic
		// $client = Client::findOrFail($id);
		// $client->delete();

		return redirect()->route('clients.index')
			->with('success', 'Client supprimé avec succès.');
	}
}
