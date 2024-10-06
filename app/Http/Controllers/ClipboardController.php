<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use Inertia\Inertia;

class ClipboardController extends Controller
{
	public function index()
	{
        return Inertia::render('Clipboard/NoteCreate');
	}	

	public function get($noteId)
	{
		$note = Note::where('note_id', $noteId)->first();

		if (!$note) {
			return response('Note not found.', 404);
		}

		return Inertia::render('Clipboard/NoteRead', [
			'noteId' => $noteId,
			'note' => $note->data,			
		]);
	}

    public function store(Request $request)
    {
        $note = new Note();
		$note->note_id = $request->noteId;
        $note->data = $request->note;
        $note->save();
		return response()->json($note->note_id);
    }
}
