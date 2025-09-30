<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;  
use App\Http\Controllers\ExamController;  // We'll create this

Route::get('/', function () {
    return view('welcome');
});

// We'll create this controller next

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/verify-otp', [AuthController::class, 'showVerifyForm'])->name('verify.form');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify-otp');
Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard')->middleware('auth');




// Admin routes
Route::get('/admin/dashboard', [ExamController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/exams/create', [ExamController::class, 'create'])->name('exams.create');
Route::post('/admin/exams', [ExamController::class, 'store'])->name('exams.store');

// User exam access route (via UUID link)
Route::get('/exam/{uuid}', [AuthController::class, 'showRegisterFormWithExam'])->name('exam.register');  // Modified register