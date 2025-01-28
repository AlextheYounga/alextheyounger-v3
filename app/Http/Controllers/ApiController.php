<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Repository;
use Database\Seeders\LanguageSeeder;

class ApiController extends Controller
{

	public function sanityCheck(Request $request) {
		return response()->json(['user' => $request->user()], 200);
	}

    public function addRepositories(Request $request) {
		try {
			$repositories = $request->collect('repositories');
			
			// Update repositories
			foreach($repositories as $repository) {
				$record = Repository::updateOrCreate([
					'name' => $repository['name'],
				],
				[
					'path' => $repository['path'],
					'size' => (float) $repository['totalSize'],
					'languages' => $repository['languages'],
				]);
			}

			// Update languages
			$languageSeeder = new LanguageSeeder();
			$languageSeeder->run();
	
			// Process the data as needed
			return response()->json(['success' => true], 201);
		} catch (ValidationException $e) {
			return response()->json([
				'success' => false,
				'errors' => $e->errors(),
			], 422);
		}
	}
}
