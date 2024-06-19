<?php

use App\Http\Controllers\QuoteController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [QuoteController::class, 'index'])->name('quotes.index');
Route::get('/quotes', [QuoteController::class, 'index']);
Route::get('/quotes/create', [QuoteController::class, 'create'])->name('quotes.create');
Route::post('/quotes', [QuoteController::class, 'store'])->name('quotes.store');
Route::get('/quotes/{quote}/edit', [QuoteController::class, 'edit'])->name('quotes.edit');
Route::patch('/quotes/{quote}', [QuoteController::class, 'update'])->name('quotes.update');
Route::delete('/quotes/{quote}', [QuoteController::class, 'destory'])->name('quotes.destory');
