<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::orderBy('created_at', 'desc')->get();

        if ($blogs->isEmpty()) {
            return response()->json(['message' => 'Пока нет статей.'], 404);
        }

        return response()->json($blogs);
    }

    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)->first();

        if (!$blog) {
            return response()->json(['message' => 'Статья не найдена.'], 404);
        }

        return response()->json($blog);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'text' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:12008',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('blogs', 'public');
            $validated['image'] = $path;
        }

        $blog = Blog::create($validated);

        if (!$blog) {
            return response()->json(['message' => 'Ошибка при создании статьи.'], 500);
        }

        return response()->json([
            'message' => 'Статья успешно создана!',
            'data' => $blog
        ], 201);
    }

    public function update(Request $request, $slug)
    {
        $blog = Blog::where('slug', $slug)->first();

        if (!$blog) {
            return response()->json(['message' => 'Статья не найдена.'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|max:500',
            'text' => 'sometimes|required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:12008',
        ]);

        if ($request->hasFile('image')) {
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }
            $path = $request->file('image')->store('blogs', 'public');
            $validated['image'] = $path;
        }

        $blog->update($validated);

        return response()->json([
            'message' => 'Статья успешно обновлена!',
            'data' => $blog
        ]);
    }

    public function destroy($slug)
    {
        $blog = Blog::where('slug', $slug)->first();

        if (!$blog) {
            return response()->json(['message' => 'Статья не найдена.'], 404);
        }

        if ($blog->image && Storage::disk('public')->exists($blog->image)) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();

        return response()->json(['message' => 'Статья успешно удалена.']);
    }
}
