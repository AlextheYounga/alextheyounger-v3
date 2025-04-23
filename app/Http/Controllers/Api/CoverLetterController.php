<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CoverLetter;

class CoverLetterController extends Controller
{
    public function get($hash)
	{
		try {
			$coverLetter = CoverLetter::where('hash', $hash)->firstOrFail();
			return response()->json($coverLetter);
		} catch (\Exception $e) {
			return response()->json(['error' => 'Cover letter not found'], 404);
		}
	}
}
