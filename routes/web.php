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
    return redirect('/admin');
});

// Routes pour les factures PDF
Route::get('/vente/{vente}/invoice/pdf', [App\Http\Controllers\InvoiceController::class, 'generateInvoice'])
    ->name('vente.invoice.pdf')
    ->middleware('auth');

// Route pour fiche client PDF
Route::get('/client/{client}/report/pdf', [App\Http\Controllers\InvoiceController::class, 'generateClientReport'])
    ->name('client.report.pdf')
    ->middleware('auth');

// require __DIR__.'/auth.php';
