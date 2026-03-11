<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CareNote;

class CareNoteController extends Controller
{
    public function index()
    {
        $notes = CareNote::latest()->get();

        return view('care-notes.index', [
            'notes' => $notes
        ]);
    }
}
