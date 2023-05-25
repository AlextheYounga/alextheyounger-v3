<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Language;

class PagesController extends Controller
{
    public function home()
    {
        $languages = Language::getLanguagesWithWidths();
        return Inertia::render('Home', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'languages' => $languages,
        ]);
    }

    public function resume()
    {
        return Inertia::render('Resume');
    }
}
