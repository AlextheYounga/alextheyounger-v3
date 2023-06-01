<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PagesController;
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

Route::get('/', [PagesController::class, 'home']);

Route::get('/books', [
    PagesController::class, 'readingList'
])->name('pages.books');

Route::get('/projects', [
    PagesController::class, 'projects'
])->name('pages.projects');

Route::get('/terrain', [
    PagesController::class, 'terrainPlayground'
])->name('pages.terrain-playground');