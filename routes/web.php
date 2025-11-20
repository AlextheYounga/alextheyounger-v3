<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ClipboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProposalController;

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
Route::get('/books', [PagesController::class, 'readingList'])->name('pages.books');
Route::get('/projects', [PagesController::class, 'projects'])->name('pages.projects');
Route::get('/starfield', [PagesController::class, 'starfield'])->name('pages.starfield');

Route::prefix('secure-passwords')->group(function () {
    Route::get('/', [PasswordController::class, 'encrypt'])->name('passwords.encrypt');
    Route::get('/decrypt/{uuid}', [PasswordController::class, 'get'])->name('passwords.get');
    Route::post('/store', [PasswordController::class, 'store'])
        ->name('passwords.store')
        ->middleware('throttle:50,1'); // Ajax
    Route::get('/destroy/{uuid}', [PasswordController::class, 'destroy'])->name('passwords.destroy'); // Ajax
});

Route::prefix('clipboard')->group(function () {
    Route::get('/', [ClipboardController::class, 'index'])->name('note.index');
    Route::get('/{note_id}', [ClipboardController::class, 'get'])->name('note.get');
    Route::post('/store', [ClipboardController::class, 'store'])
        ->name('note.store')
        ->middleware('throttle:50,1'); // Ajax
});

Route::group(['prefix' => 'proposals'], function () {
    Route::get('/{hash}', [ProposalController::class, 'show'])->name('proposals.show');
    Route::post('/{hash}/sign', [ProposalController::class, 'sign'])->name('proposals.sign');
});
