<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Authentication Routes
Route::middleware('guest')->group(function () {
	Route::get('/', [App\Http\Controllers\AuthController::class, 'showLogin']);
	Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
	Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
	Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegister'])->name('register');
	Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
});

Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Protected Routes - Require Authentication
Route::middleware(['auth'])->group(function () {
	Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

	// Gestion des Articles - Admin/Gérant only
	Route::middleware(['role:Admin,Gérant'])->group(function () {
		Route::resource('articles', App\Http\Controllers\ArticleController::class);
		Route::get('articles/categories', function () {
			return view('pages.articles.categories');
		})->name('articles.categories');
	});

	// Stock & Approvisionnement - Admin/Gérant/Caissier
	Route::prefix('stock')->name('stock.')->middleware(['role:Admin,Gérant,Caissier'])->controller(App\Http\Controllers\StockController::class)->group(function () {
		Route::get('/', 'index')->name('index');
		Route::get('/alerts', 'alerts')->name('alerts');
		Route::get('/movements', 'movements')->name('movements');
		Route::put('/update-quantity/{articleId}', 'updateQuantity')->name('updateQuantity');
	});

	// Gestion des lots - Admin/Gérant only
	Route::middleware(['role:Admin,Gérant'])->group(function () {
		Route::resource('lots', App\Http\Controllers\LotController::class);
	});

	// Ventes - All authenticated users
	Route::resource('sales', App\Http\Controllers\SaleController::class);
	Route::prefix('sales')->name('sales.')->group(function () {
		Route::get('/{id}/invoice', [App\Http\Controllers\SaleController::class, 'invoice'])->name('invoice');
		Route::get('/invoices', function () {
			return view('pages.sales.invoices');
		})->name('invoices');
		Route::get('/payments', function () {
			return view('pages.sales.payments');
		})->name('payments');
	});

	// Factures - All authenticated users
	Route::prefix('invoices')->name('invoices.')->group(function () {
		Route::get('/', function () {
			return view('pages.sales.invoices');
		})->name('index');
		Route::get('/search', function () {
			return view('pages.sales.search');
		})->name('search');
	});

	// Paiements - All authenticated users
	Route::prefix('payments')->name('payments.')->group(function () {
		Route::get('/', function () {
			return view('pages.sales.payments');
		})->name('index');
	});

	// Clients - All authenticated users
	Route::resource('clients', App\Http\Controllers\ClientController::class);
	Route::get('clients/history', function () {
		return view('pages.clients.history');
	})->name('clients.history');

	// Example SweetAlert2 + Livewire Demo
	Route::get('/examples/sweetalert', [App\Http\Controllers\ExampleLivewireController::class, 'showAlert'])->name('examples.sweetalert');

	// Rapports - Admin/Gérant only
	Route::prefix('reports')->name('reports.')->middleware(['role:Admin,Gérant'])->group(function () {
		Route::get('/sales', function () {
			return view('pages.reports.sales');
		})->name('sales');
		Route::get('/financial', function () {
			return view('pages.reports.financial');
		})->name('financial');
		Route::get('/stock', function () {
			return view('pages.reports.stock');
		})->name('stock');
		Route::get('/exports', function () {
			return view('pages.reports.exports');
		})->name('exports');
	});

	// Utilisateurs & Sécurité - Admin only
	Route::prefix('users')->name('users.')->middleware(['role:Admin'])->group(function () {
		Route::get('/', function () {
			return view('pages.users.index');
		})->name('index');
		Route::get('/create', function () {
			return view('pages.users.create');
		})->name('create');
	});

	// Rôles & Permissions - Admin only
	Route::prefix('roles')->name('roles.')->middleware(['role:Admin'])->group(function () {
		Route::get('/', function () {
			return view('pages.users.roles');
		})->name('index');
		Route::get('/permissions', function () {
			return view('pages.users.permissions');
		})->name('permissions');
	});

	// Paramètres - Admin/Gérant only
	Route::prefix('settings')->name('settings.')->middleware(['role:Admin,Gérant'])->group(function () {
		Route::get('/general', function () {
			return view('pages.settings.general');
		})->name('general');
		Route::get('/billing', function () {
			return view('pages.settings.billing');
		})->name('billing');
		Route::get('/cities', function () {
			return view('pages.settings.cities');
		})->name('cities');
	});
});
