<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\TokenCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




// Аутентификация для юзера
Route::post("/login", [AuthController::class, "login"]);
Route::post("/register", [AuthController::class, "register"]);
Route::post("/logout", [AuthController::class, "logout"])->middleware(TokenCheck::class);
