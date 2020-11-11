<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function() {
    Route::resource('item-type', \App\Http\Controllers\ItemTypeController::class)->names('item-type');
    Route::resource('customer', \App\Http\Controllers\CustomerController::class)->names('customer');
    Route::resource('supplier', \App\Http\Controllers\SupplierController::class)->names('supplier');
    Route::resource('item', \App\Http\Controllers\ItemController::class)->names('item');
    Route::prefix('order')->group(function() {
        Route::get('/', [\App\Http\Controllers\OrderController::class, 'index'])->name('order.index');
        Route::get('create', [\App\Http\Controllers\OrderController::class, 'create'])->name('order.create');
        Route::post('store', [\App\Http\Controllers\OrderController::class, 'store'])->name('order.store');
        Route::get('/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('order.show');
        Route::get('/{order}/print', [\App\Http\Controllers\OrderController::class, 'print'])->name('order.print');
    });
    Route::prefix('transaction')->group(function() {
        Route::get('/', [\App\Http\Controllers\TransactionController::class, 'index'])->name('transaction.index');
        Route::get('create', [\App\Http\Controllers\TransactionController::class, 'create'])->name('transaction.create');
        Route::post('store', [\App\Http\Controllers\TransactionController::class, 'store'])->name('transaction.store');
        Route::get('/ajax-get-item/{id}', [\App\Http\Controllers\TransactionController::class, 'ajaxGetItem'])->name('transaction.ajax-get-item');
        Route::get('/{transaction}', [\App\Http\Controllers\TransactionController::class, 'show'])->name('transaction.show');
        Route::get('/{transaction}/print', [\App\Http\Controllers\TransactionController::class, 'print'])->name('transaction.print');
    });
    Route::prefix('report')->group(function() {
        Route::get('/item-stock', [\App\Http\Controllers\ReportController::class, 'itemStock'])->name('report.item-stock');
    });
});
