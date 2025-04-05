<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
	public function sanityCheck(Request $request) {
		return response()->json(['user' => $request->user()], 200);
	}
}
