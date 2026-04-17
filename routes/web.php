<?php

use App\Http\Controllers\AttemptController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('quizzes.index');
});

Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
Route::get('/quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
Route::post('/quizzes', [QuizController::class, 'store'])->name('quizzes.store');
Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
Route::post('/quizzes/{quiz}/attempt', [AttemptController::class, 'store'])->name('quizzes.attempt');
Route::get('/quizzes/{quiz}/result', [AttemptController::class, 'result'])->name('quizzes.result');
