<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CountryController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\CityController;

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

Route::get('/', [CountryController::class, 'index'])->name('countries.index');
Route::get('/states', [StateController::class, 'index'])->name('states.index');
Route::get('/cities', [CityController::class, 'index'])->name('cities.index');
Route::resource('countries', CountryController::class)->except(['index', 'create', 'edit', 'show']);
Route::resource('states', StateController::class)->except(['create', 'edit', 'show']);
Route::resource('cities', CityController::class)->except(['create', 'edit', 'show']);

Route::get('/states-by-country/{country}', [CityController::class, 'getStates']);