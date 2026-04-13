<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('quizzes.index');
});

Route::livewire('/quizzes', 'pages::quizzes.index')->name('quizzes.index');
Route::livewire('/quizzes/create', 'pages::quizzes.create')->name('quizzes.create');
Route::livewire('/quizzes/{quiz}', 'pages::quizzes.show')->name('quizzes.show');
