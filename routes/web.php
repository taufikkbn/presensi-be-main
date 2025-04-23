<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\PerimeterController;
use App\Http\Controllers\API\AuthController as APIAuthController;
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'loginView'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth:web'])->group(function () {
    Route::get('/', function () {
        return view('pages.index');
    })->name('index');

    Route::resource('class', ClassroomController::class);
    Route::resource('student', StudentController::class);
    Route::resource('perimeter', PerimeterController::class);
    Route::resource('teacher', TeacherController::class);
    Route::get('reset-password/{token}', [APIAuthController::class, 'verifyToken'])->name('reset-password');
});

