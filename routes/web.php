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

Route::get('/', function () {
	return view('welcome');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

// Gestion des Articles
Route::resource('articles', App\Http\Controllers\ArticleController::class);
Route::get('articles/categories', function () {
	return view('pages.articles.categories');
})->name('articles.categories');

// Stock & Approvisionnement
Route::prefix('stock')->name('stock.')->controller(App\Http\Controllers\StockController::class)->group(function () {
	Route::get('/lots', function () {
		return view('pages.stock.lots');
	})->name('lots');
	Route::get('/view', 'view')->name('view');
	Route::get('/alerts', 'alerts')->name('alerts');
	Route::get('/movements', 'movements')->name('movements');
	Route::put('/update-quantity/{articleId}', 'updateQuantity')->name('updateQuantity');
});

// Ventes
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

// Clients
Route::resource('clients', App\Http\Controllers\ClientController::class);
Route::get('clients/history', function () {
	return view('pages.clients.history');
})->name('clients.history');

// Example SweetAlert2 + Livewire Demo
Route::get('/examples/sweetalert', [App\Http\Controllers\ExampleLivewireController::class, 'showAlert'])->name('examples.sweetalert');

// Rapports
Route::prefix('reports')->name('reports.')->group(function () {
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

// Utilisateurs & Sécurité
Route::prefix('users')->name('users.')->group(function () {
	Route::get('/', function () {
		return view('pages.users.index');
	})->name('index');
	Route::get('/create', function () {
		return view('pages.users.create');
	})->name('create');
	Route::get('/roles', function () {
		return view('pages.users.roles');
	})->name('roles');
	Route::get('/permissions', function () {
		return view('pages.users.permissions');
	})->name('permissions');
});

// Paramètres
Route::prefix('settings')->name('settings.')->group(function () {
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
