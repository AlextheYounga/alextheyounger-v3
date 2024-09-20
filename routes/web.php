<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\LanguageBarController;
use App\Http\Controllers\PasswordController;
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

Route::get('/', [PagesController::class, 'home'])->name('home');
Route::redirect('/resume', '/');
Route::get('/books', [PagesController::class, 'readingList'])->name('pages.books');
Route::get('/projects', [PagesController::class, 'projects'])->name('pages.projects');
Route::get('/starfield', [PagesController::class, 'starfield'])->name('pages.starfield');

Route::prefix('secure-passwords')->group(function () {
    Route::get('/', [PasswordController::class, 'encrypt'])->name('passwords.encrypt');
	Route::get('/decrypt/{uuid}', [PasswordController::class, 'get'])->name('passwords.get');
    Route::post('/store', [PasswordController::class, 'store'])->name('passwords.store'); // Ajax
	Route::get('/destroy/{uuid}', [PasswordController::class, 'destroy'])->name('passwords.destroy'); // Ajax
});

// Ajax
Route::get('/languages/setup', [LanguageBarController::class, 'setup'])->name('languages.setup');