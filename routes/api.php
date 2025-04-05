<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CoverLetterController;
use App\Http\Controllers\Api\LanguagesController;
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

Route::get('/ping', function() {
	return response()->json(['message' => 'pong']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')
	->get('/resume/{hash}', [ResumeController::class, 'get']); // Open
Route::middleware('auth:sanctum')
	->get('/cover-letter/{hash}', [CoverLetterController::class, 'get']); // Open

Route::middleware('auth:sanctum')
	->post('/repositories', [LanguagesController::class, 'addRepositories']);
Route::get('/languages/stats', [LanguagesController::class, 'stats'])
	->middleware('throttle:50,1'); // Open