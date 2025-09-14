<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
	/**
	 * Show the login form
	 */
	public function showLogin(): View
	{
		return view('auth.login');
	}

	/**
	 * Handle login request
	 */
	public function login(Request $request): RedirectResponse
	{
		$credentials = $request->validate([
			'email' => 'required|email',
			'password' => 'required',
		]);

		if (Auth::attempt($credentials, $request->boolean('remember'))) {
			$request->session()->regenerate();

			return redirect()->intended('/dashboard');
		}

		throw ValidationException::withMessages([
			'email' => ['Les informations d\'identification fournies ne correspondent pas à nos enregistrements.'],
		]);
	}

	/**
	 * Show the registration form
	 */
	public function showRegister(): View
	{
		$roles = Role::all();
		return view('auth.register', compact('roles'));
	}

	/**
	 * Handle registration request
	 */
	public function register(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:users',
			'password' => 'required|string|min:8|confirmed',
			'role_id' => 'required|exists:roles,id',
		]);

		$user = User::create([
			'name' => $validated['name'],
			'email' => $validated['email'],
			'password' => Hash::make($validated['password']),
			'role_id' => $validated['role_id'],
		]);

		Auth::login($user);

		return redirect('/dashboard');
	}

	/**
	 * Handle logout request
	 */
	public function logout(Request $request): RedirectResponse
	{
		Auth::logout();

		$request->session()->invalidate();
		$request->session()->regenerateToken();

		return redirect('/');
	}
}
