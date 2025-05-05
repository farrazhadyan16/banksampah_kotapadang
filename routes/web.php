<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SampahController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/profile', 'ProfileController@index')->name('profile');
Route::put('/profile', 'ProfileController@update')->name('profile.update');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/sampah', [SampahController::class, 'index'])->name('sampah.index');
Route::get('/sampah/edit/{id}', [SampahController::class, 'edit'])->name('sampah.edit');
Route::put('/sampah/update/{id}', [SampahController::class, 'update'])->name('sampah.update');
Route::delete('/sampah/{id}', [SampahController::class, 'destroy'])->name('sampah.destroy');