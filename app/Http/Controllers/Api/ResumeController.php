<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resume;

class ResumeController extends Controller
{
    public function get($hash)
    {
        try {
            $record = Resume::where('hash', $hash)->firstOrFail();
            $resume = $record->toArray();
            $resume['projects'] = $record
                ->projects()
                ->get()
                ->map(function ($project) {
                    return $project->resumeFormat();
                });

            return response()->json($resume);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Resume not found'], 404);
        }
    }
}
