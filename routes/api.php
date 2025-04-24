<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CoverLetterController;
use App\Http\Controllers\Api\CodingLanguageController;
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

/*
TODO: Investigate proper authentication for these routes. Issue currently is resume Vue app has no server-side code. 
Random hash is being used as a psuedo-authentication method. Adding throttle to these routes for now to prevent abuse.
*/

Route::get('/resume/{hash}', [ResumeController::class, 'get'])
	->middleware('throttle:50,1'); // Open
Route::get('/cover-letter/{hash}', [CoverLetterController::class, 'get'])
	->middleware('throttle:50,1'); // Open

Route::middleware('auth:sanctum')
	->post('/repositories', [CodingLanguageController::class, 'addRepositories']);

Route::get('/languages/stats', [CodingLanguageController::class, 'stats'])
	->middleware('throttle:50,1'); // Open