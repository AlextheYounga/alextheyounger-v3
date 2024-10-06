<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Password;

class PasswordController extends Controller
{
    public function store(Request $request)
    {
        $password = new Password();
        $password->data = $request->encryptedData;
        $password->save();
		return response()->json($password->uuid);
    }

	public function destroy($uuid)
	{
		Password::where('uuid', $uuid)->first()->delete();
		return response()->json(['success' => true]);
	}

	public function get($uuid)
	{
		$password = Password::where('uuid', $uuid)->first();

		if (!$password) {
			return response('Data not found.', 404);
		}
		
		return Inertia::render('Passwords/PasswordDecrypt', [
            'encryptedData' => $password->data,			
        ]);
	}

	public function encrypt()
	{
        return Inertia::render('Passwords/PasswordEncrypt');
	}	
}
