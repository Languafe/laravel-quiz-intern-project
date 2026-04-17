<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Quiz;
use Illuminate\Http\Request;

class AttemptController extends Controller
{
    public function store(Request $request, Quiz $quiz)
    {
        $quiz->load('questions.answers');

        $request->validate([
            'answers' => 'required|array',
        ]);

        $score = 0;
        $results = [];

        foreach ($quiz->questions as $question) {
            $selectedAnswerId = $request->answers[$question->id] ?? null;
            $correctAnswer = $question->answers->firstWhere('is_correct', true);
            $selectedAnswer = $selectedAnswerId
                ? $question->answers->find($selectedAnswerId)
                : null;

            $isCorrect = $selectedAnswer && $selectedAnswer->is_correct;

            if ($isCorrect) {
                $score++;
            }

            $results[] = [
                'question_text' => $question->text,
                'selected_text' => $selectedAnswer?->text,
                'correct_text' => $correctAnswer?->text,
                'is_correct' => $isCorrect,
                'answered' => $selectedAnswer !== null,
            ];
        }

        session()->flash('quiz_result', [
            'quiz_title' => $quiz->title,
            'score' => $score,
            'total' => $quiz->questions->count(),
            'results' => $results,
        ]);

        return redirect()->route('quizzes.result', $quiz);
    }

    public function result(Quiz $quiz)
    {
        $result = session('quiz_result');

        if (!$result) {
            return redirect()->route('quizzes.show', $quiz);
        }

        return view('quizzes.result', compact('quiz', 'result'));
    }
}
