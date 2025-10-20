<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        try {
            $user = User::create([
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Пользователь успешно зарегистрирован!',
                'user' => $user,
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Ошибка регистрации: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Не удалось зарегистрировать пользователя. Попробуйте позже.',
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Пользователь с таким email не найден.',
            ], 404);
        }

        if (!Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Неверный пароль.',
            ], 401);
        }

        try {
            $token = Str::random(60);
            Token::create([
                'user_id' => $user->id,
                'token' => $token,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Авторизация успешна.',
                'token' => $token,
                'user' => $user,
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Ошибка входа: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Не удалось выполнить вход. Попробуйте позже.',
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $token = $request->bearerToken();

            if (!$token) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Токен не предоставлен.',
                ], 400);
            }

            $tokenRecord = Token::where('token', $token)->first();

            if (!$tokenRecord) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Недействительный токен.',
                ], 401);
            }

            $tokenRecord->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Вы успешно вышли из системы.',
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Ошибка выхода: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Произошла ошибка при выходе. Попробуйте позже.',
            ], 500);
        }
    }
}
