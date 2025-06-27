<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LoginController;

Route::post('/users/login', [LoginController::class, 'login']);
Route::get('/users', [UserController::class, 'getUser']);
Route::post('/users/create', [UserController::class, 'addUser']);
Route::patch('/users/update/{id}', [UserController::class, 'updateUser']);
Route::delete('/users/delete/{id}', [UserController::class, 'deleteUser']);
Route::get('/departments', [UserController::class, 'getDepartments']);
Route::get('/positions', [UserController::class, 'getPositions']);