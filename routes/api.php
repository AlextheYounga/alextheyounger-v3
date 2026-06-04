<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CoverLetterController;
use App\Http\Controllers\Api\CodingLanguageController;
use App\Http\Controllers\Api\BookImageController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ProjectImageController;
use App\Http\Controllers\Api\ResumeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// User::first()->createToken('default');

Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/resume/{hash}', [ResumeController::class, 'get'])->middleware('throttle:50,1'); // Open

Route::get('/cover-letter/{hash}', [CoverLetterController::class, 'get'])->middleware(
    'throttle:50,1',
); // Open

Route::get('/projects', [ProjectController::class, 'index'])->middleware('throttle:50,1'); // Open
Route::get('/projects/{project}', [ProjectController::class, 'show'])->middleware('throttle:50,1'); // Open

Route::get('/books/{book}/image', [BookImageController::class, 'show'])
    ->name('api.books.image')
    ->middleware('throttle:50,1');
Route::get('/projects/{project}/image', [ProjectImageController::class, 'show'])
    ->name('api.projects.image')
    ->middleware('throttle:50,1');

Route::middleware('auth:sanctum')->post('/languages', [CodingLanguageController::class, 'store']);

Route::get('/languages', [CodingLanguageController::class, 'index'])->middleware('throttle:50,1'); // Open

Route::get('/languages/stats', [CodingLanguageController::class, 'stats'])->middleware(
    'throttle:50,1',
); // Open
