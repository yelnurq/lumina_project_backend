<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FeedbackController;
use App\Http\Middleware\TokenCheck;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DocumentController;

Route::post("/login", [AuthController::class, "login"]);
Route::post("/register", [AuthController::class, "register"]);
Route::post("/logout", [AuthController::class, "logout"])->middleware(TokenCheck::class);

Route::delete('/users/{id}', [AuthController::class, 'delete']);

Route::post("/feedbacks", [FeedbackController::class, "store"]);
Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/blogs/{slug}', [BlogController::class, 'show']);

Route::get('/documents', [DocumentController::class, 'index']);
Route::get('/documents/{slug}', [DocumentController::class, 'show']);
Route::post('/documents', [DocumentController::class, 'store']);
Route::put('/documents/{slug}', [DocumentController::class, 'update']);
Route::delete('/documents/{slug}', [DocumentController::class, 'destroy']);

Route::middleware(TokenCheck::class)->group(function(){
    Route::get("/feedbacks", [FeedbackController::class, "index"]);
    
    Route::post('/blogs', [BlogController::class, 'store']);
    Route::put('/blogs/{slug}', [BlogController::class, 'update']);
    Route::delete('/blogs/{slug}', [BlogController::class, 'destroy']);
});

// Авторизация / LOGIN, REGISTER
//             'email' => 'required|email',
//             'password' => 'required|string',

// Выход / LOGOUT
//             'bearer token',

// Фидбэк / GET запрос админа
//             'full_name',
//             'title',
//             'text',


// Фидбэк / POST запрос обычных юзеров
            // 'full_name' => 'required|string|max:255',
            // 'title' => 'nullable|string|max:255',
            // 'text' => 'required|string',

            
// Блоги / POST, UPDATE запрос админа / Delete через {SLUG}
            // 'title' => 'required|string|max:255',
            // 'description' => 'required|string|max:500',
            // 'text' => 'required|string',
            // 'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:12008',

// Блоги / GET запрос 
//             'title',
//             'description',
//             'text',
//             'image',
//             'slug', SLUG для URl