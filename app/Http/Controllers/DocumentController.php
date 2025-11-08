<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::all();

        if ($documents->isEmpty()) {
            return response()->json(['message' => 'Пока нет документов.'], 404);
        }

        return response()->json($documents);
    }

    public function show($slug)
    {
        $document = Document::where('slug', $slug)->first();

        if (!$document) {
            return response()->json(['message' => 'Документ не найдена.'], 404);
        }

        return response()->json($document);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'document' => 'nullable|file|mimes:pdf,doc,docx,txt|max:12008',
        ]);

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('documents', 'public');
            $validated['document'] = $path;
        }

        $document = Document::create($validated);

        if (!$document) {
            return response()->json(['message' => 'Ошибка при создании документа.'], 500);
        }

        return response()->json([
            'message' => 'Документ успешно создан!',
            'data' => $document
        ], 201);
    }

    public function update(Request $request, $slug)
    {
        $document = Document::where('slug', $slug)->first();

        if (!$document) {
            return response()->json(['message' => 'Документ не найден.'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'document' => 'nullable|file|mimes:pdf,doc,docx,txt|max:12008',
        ]);

        if ($request->hasFile('document')) {
            if ($document->document && Storage::disk('public')->exists($document->document)) {
                Storage::disk('public')->delete($document->document);
            }
            $path = $request->file('document')->store('documents', 'public');
            $validated['document'] = $path;
        }

        $document->update($validated);

        return response()->json([
            'message' => 'Документ успешно обновлен!',
            'data' => $document
        ]);
    }

    public function destroy($slug)
    {
        $document = Document::where('slug', $slug)->first();

        if (!$document) {
            return response()->json(['message' => 'Документ не найден.'], 404);
        }

        if ($document->document && Storage::disk('public')->exists($document->document)) {
            Storage::disk('public')->delete($document->document);
        }

        $document->delete();

        return response()->json(['message' => 'Документ успешно удален.']);
    }
}
