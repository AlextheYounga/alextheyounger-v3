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

	public function decrypt($uuid)
	{
		$password = Password::where('uuid', $uuid)->first();
		Password::where('uuid', $uuid)->delete();

		return Inertia::render('Passwords/PasswordDecrypt', [
            'encryptedData' => $password->data,			
        ]);
	}

	public function encrypt()
	{
        return Inertia::render('Passwords/PasswordEncrypt');
	}	
}
