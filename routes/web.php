<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\UserController;

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


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('country.countries');
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::controller(CountryController::class)->middleware('auth')->prefix('country')->group(function () { 
    Route::get('export/{form}', 'export')->name('country.export')->middleware('role:ADMIN');
    Route::get('list', 'countries')->name('country.list');
    Route::get('{country}/cities', 'citiesList')->name('country.cities.view');
    Route::get('get/{country}/cities', 'citiesByCountry')->name('country.cities.list');
});

Route::controller(CityController::class)->middleware('auth')->prefix('city')->group(function () {
    Route::get('export/{form}', 'export')->name('city.export')->middleware('role:ADMIN'); 
    Route::get('{city}/users', 'usersList')->name('city.users.view');
    Route::get('get/{city}/users', 'usersByCity')->name('city.users.list');
});

Route::controller(UserController::class)->middleware(['auth', 'role:ADMIN'])->prefix('user')->group(function () {
    Route::get('export/{form}', 'export')->name('user.export');  
});

Route::middleware(['auth', 'role:ADMIN'])->group(function () {
    Route::resource('country', CountryController::class);
    Route::resource('city', CityController::class);
    Route::resource('user', UserController::class);
});

require __DIR__.'/auth.php';
