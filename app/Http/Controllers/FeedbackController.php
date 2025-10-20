<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $data = Feedback::orderBy('created_at', 'desc')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'full_name' => $item->full_name,
                'title' => $item->title,
                'text' => $item->text,
                'created_at' => $item->created_at->format('Y-m-d H:i'),
            ];
        });

        if ($data->isEmpty()) {
            return response()->json(['message' => 'Пока нет отзывов.'], 404);
        }

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'text' => 'required|string',
        ]);

        $feedback = Feedback::create($validated);

        if (!$feedback) {
            return response()->json(['message' => 'Ошибка при отправке сообщения. Попробуйте позже.'], 500);
        }

        return response()->json([
            'message' => 'Ваше сообщение успешно отправлено!',
            'data' => $feedback
        ], 201);
    }
    
    public function destroy($id)
    {
        $feedback = Feedback::find($id);

        if (!$feedback) {
            return response()->json(['message' => 'Отзыв не найден.'], 404);
        }

        $feedback->delete();

        return response()->json(['message' => 'Отзыв успешно удалён.']);
    }
}
