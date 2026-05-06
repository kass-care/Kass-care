<?php

namespace App\Http\Controllers;

use App\Models\SmartPhrase;
use Illuminate\Http\Request;

class SmartPhraseController extends Controller
{
    public function index()
    {
        $phrases = SmartPhrase::where('user_id', auth()->id())
            ->orderBy('category')
            ->orderBy('shortcut')
            ->get();

        return view('provider.smart_phrases.index', compact('phrases'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => ['nullable', 'string', 'max:255'],
            'shortcut' => ['required', 'string', 'max:50'],
            'content' => ['required', 'string'],
        ]);

        SmartPhrase::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'shortcut' => $validated['shortcut'],
            ],
            [
                'category' => $validated['category'] ?? null,
                'content' => $validated['content'],
            ]
        );

        return back()->with('success', 'Smart phrase saved.');
    }

    public function destroy($id)
    {
        $phrase = SmartPhrase::where('user_id', auth()->id())
            ->findOrFail($id);

        $phrase->delete();

        return back()->with('success', 'Smart phrase deleted.');
    }
}
