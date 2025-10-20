<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FeedbackController;
use App\Http\Middleware\TokenCheck;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;




Route::post("/login", [AuthController::class, "login"]);
Route::post("/register", [AuthController::class, "register"]);
Route::post("/logout", [AuthController::class, "logout"])->middleware(TokenCheck::class);


Route::middleware(TokenCheck::class)->group(function(){

    Route::get("/feedbacks", [FeedbackController::class, "index"]);
    Route::post("/feedbacks", [FeedbackController::class, "store"]);
    
    Route::get('/blogs', [BlogController::class, 'index']);
    Route::get('/blogs/{slug}', [BlogController::class, 'show']);
    Route::post('/blogs', [BlogController::class, 'store']);
    Route::put('/blogs/{slug}', [BlogController::class, 'update']);
    Route::delete('/blogs/{slug}', [BlogController::class, 'destroy']);

});