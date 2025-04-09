<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\PerimeterController;
use App\Http\Controllers\API\AuthController;


Route::get('/', function () {
    return view('pages.index');
})->name('index');

Route::resource('class', ClassroomController::class);
Route::resource('student', StudentController::class);
Route::resource('perimeter', PerimeterController::class);
Route::resource('teacher', TeacherController::class);
Route::get('reset-password/{token}', [AuthController::class, 'verifyToken'])->name('reset-password');

