<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resume;

class ResumeController extends Controller
{
    public function get($hash)
	{
		try {
			$resume = Resume::where('hash', $hash)->firstOrFail();
		} catch (\Exception $e) {
			return response()->json(['error' => 'Resume not found'], 404);
		}

		return response()->json($resume);
	}
}
