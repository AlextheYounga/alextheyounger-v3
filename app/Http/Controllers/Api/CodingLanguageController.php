<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Repository;
use App\Models\CodingLanguage;
use Database\Seeders\CodingLanguageSeeder;
use App\Http\Controllers\Controller;

class CodingLanguageController extends Controller
{
    public function addRepositories(Request $request) {
		try {
			$repositories = $request->collect('repositories');
			
			// Update repositories
			foreach($repositories as $repository) {
				Repository::updateOrCreate([
					'name' => $repository['name'],
				],
				[
					'path' => $repository['path'],
					'size' => $repository['totalSize'],
					'languages' => $repository['languages'],
				]);
			}

			// Update languages
			$CodingLanguageSeeder = new CodingLanguageSeeder();
			$CodingLanguageSeeder->run();
	
			// Process the data as needed
			return response()->json(['success' => true], 201);
		} catch (ValidationException $e) {
			return response()->json([
				'success' => false,
				'errors' => $e->errors(),
			], 422);
		}
	}

	public function stats()
    {
        $languages = CodingLanguage::active()
            ->orderBy('width', 'desc')
            ->get();

        // Repository size
        $repoCount = Repository::all()->count();
        $bytes = Repository::getTotalSize();
		$digits = strlen((string) $bytes);
		
		switch (true) {
			case ($digits >= 4 && $digits < 7):
				$divisor = pow(10, 3);
				$scale = 'KB';
				break;
			case ($digits >= 7 && $digits < 10):
				$divisor = pow(10, 6);
				$scale = 'MB';
				break;
			case ($digits > 10):
				$divisor = pow(10, 9);
				$scale = 'GB';
				break;
			default:
				$divisor = pow(10, 9);
				$scale = 'GB';
		}

        $displaySize = $bytes ? round($bytes / $divisor, 2) : 0;

        $data = [
            'languages' => $languages,
            'repoStats' => [
                'count' => $repoCount,
                'size' => $displaySize,
				'scale' => $scale
            ]
        ];

        return response()->json($data);
    }
}
