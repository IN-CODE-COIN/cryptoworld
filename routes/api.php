<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\UserController;


Route::get('/', function () {
    return response()->json(['message' => 'API de usuarios']);
});

Route::apiResource('users', UserController::class);