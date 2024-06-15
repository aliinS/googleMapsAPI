<?php

use App\Http\Controllers\MarkerController;
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

// Display a list of markers
Route::get('/markers', [MarkerController::class, 'index'])->name('markers.index');

// Display the form to create a new marker
Route::get('/markers/create', [MarkerController::class, 'create'])->name('markers.create');

// Store a new marker in the database
Route::post('/markers', [MarkerController::class, 'store'])->name('markers.store');

// Display the form to edit an existing marker
Route::get('/markers/{marker}/edit', [MarkerController::class, 'edit'])->name('markers.edit');

// Update an existing marker in the database
Route::put('/markers/{marker}', [MarkerController::class, 'update'])->name('markers.update');

// Delete an existing marker from the database
Route::delete('/markers/{marker}', [MarkerController::class, 'destroy'])->name('markers.destroy');
