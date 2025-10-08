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
		Route::get('articles/export/pdf', [App\Http\Controllers\ArticleExportController::class, 'exportPdf'])->name('articles.export.pdf');
		Route::get('articles/export/excel', [App\Http\Controllers\ArticleExportController::class, 'exportExcel'])->name('articles.export.excel');
	});

	// Stock & Approvisionnement - Admin/Gérant/Caissier
	Route::prefix('stock')->name('stock.')->middleware(['role:Admin,Gérant,Caissier'])->controller(App\Http\Controllers\StockController::class)->group(function () {
		Route::get('/', 'view')->name('index');
		Route::get('/alerts', 'alerts')->name('alerts');
		Route::get('/movements', 'movements')->name('movements');
		Route::put('/update-quantity/{articleId}', 'updateQuantity')->name('updateQuantity');
	});

	// Gestion des lots - Admin/Gérant only
	Route::middleware(['role:Admin,Gérant'])->group(function () {
		Route::resource('lots', App\Http\Controllers\LotController::class);
		Route::get('lots/export/pdf', [App\Http\Controllers\LotExportController::class, 'exportPdf'])->name('lots.export.pdf');
	});

	// Gestion des approvisionnements - Admin/Gérant only
	Route::middleware(['role:Admin,Gérant'])->group(function () {
		Route::resource('approvisionnements', App\Http\Controllers\ApprovisionnementController::class);
		Route::get('approvisionnements-stats', [App\Http\Controllers\ApprovisionnementController::class, 'stats'])->name('approvisionnements.stats');
	});

	// Ventes - All authenticated users
	Route::resource('sales', App\Http\Controllers\SaleController::class);
	Route::prefix('sales')->name('sales.')->group(function () {
		Route::get('/{sale}/invoice', [App\Http\Controllers\SaleController::class, 'invoice'])->name('invoice');
		Route::get('/{sale}/invoice/pdf', [App\Http\Controllers\SaleController::class, 'invoicePdf'])->name('invoice.pdf');
		Route::get('/invoices', function () {
			return view('pages.sales.invoices');
		})->name('invoices');
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

	// Clients - All authenticated users
	Route::get('clients/history', [App\Http\Controllers\ClientController::class, 'history'])->name('clients.history');
	Route::get('clients/export/pdf', [App\Http\Controllers\ClientExportController::class, 'exportPdf'])->name('clients.export.pdf');
	Route::resource('clients', App\Http\Controllers\ClientController::class);

	// Example SweetAlert2 + Livewire Demo
	Route::get('/examples/sweetalert', [App\Http\Controllers\ExampleLivewireController::class, 'showAlert'])->name('examples.sweetalert');

	// Rapports - Admin/Gérant only
	Route::prefix('reports')->name('reports.')->middleware(['role:Admin,Gérant'])->group(function () {
		Route::get('/sales', [App\Http\Controllers\GlobalReportController::class, 'sales'])->name('sales');
		Route::get('/financial', function () {
			return view('pages.reports.financial');
		})->name('financial');
		Route::get('/stock', function () {
			return view('pages.reports.stock');
		})->name('stock');
		Route::get('/exports', function () {
			return view('pages.reports.exports');
		})->name('exports');
		Route::get('/global/pdf', [App\Http\Controllers\GlobalReportController::class, 'exportPdf'])->name('global.pdf');
	});

	// Utilisateurs & Sécurité - Admin only
	Route::prefix('users')->name('users.')->middleware(['role:Admin'])->group(function () {
		Route::get('/', function () {
			return view('pages.users.index');
		})->name('index');
		Route::get('/create', function () {
			return view('pages.users.create');
		})->name('create');
		Route::get('/export/pdf', [App\Http\Controllers\UserExportController::class, 'exportPdf'])->name('export.pdf');
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
		Route::get('/cities/export/pdf', [App\Http\Controllers\VilleExportController::class, 'exportPdf'])->name('cities.export.pdf');
	});

	// Routes pour les ventes avec middleware de rôles
	Route::middleware(['auth', 'role:Admin,Gérant,Caissier,Vendeur'])->group(function () {
		Route::get('/ventes', function () {
			return view('pages.ventes.index');
		})->name('ventes.index');

		// Routes pour les factures
		Route::get('/factures/{vente}/generer', [App\Http\Controllers\FactureController::class, 'genererFacture'])->name('factures.generer');
		Route::get('/factures/{vente}/imprimer', [App\Http\Controllers\FactureController::class, 'imprimerFacture'])->name('factures.imprimer');
		Route::get('/factures/{vente}/telecharger', [App\Http\Controllers\FactureController::class, 'telechargerFacture'])->name('factures.telecharger');

		Route::get('/ventes/export/pdf', [App\Http\Controllers\VenteExportController::class, 'exportPdf'])->name('ventes.export.pdf');
	});

	// Routes d'export pour les ventes
	Route::get('ventes/export/pdf', [App\Http\Controllers\VenteExportController::class, 'exportPdf'])->name('ventes.export.pdf');
});
