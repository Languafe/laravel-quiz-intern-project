<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::withCount('questions')->latest()->get();

        return view('quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        return view('quizzes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'questions' => 'required|array|min:1|max:10',
            'questions.*.text' => 'required|string|max:500',
            'questions.*.correct_answer' => 'required',
            'questions.*.answers' => 'required|array|min:2|max:10',
            'questions.*.answers.*.text' => 'required|string|max:500',
        ]);

        $quiz = Quiz::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        foreach ($request->questions as $order => $questionData) {
            $question = $quiz->questions()->create([
                'text' => $questionData['text'],
                'order' => $order,
            ]);

            $correctIndex = $questionData['correct_answer'];

            foreach ($questionData['answers'] as $answerIndex => $answerData) {
                $question->answers()->create([
                    'text' => $answerData['text'],
                    'is_correct' => (string) $answerIndex === (string) $correctIndex,
                ]);
            }
        }

        return redirect()->route('quizzes.show', $quiz)
            ->with('success', 'Quiz created successfully!');
    }

    public function show(Quiz $quiz)
    {
        $quiz->load('questions.answers');

        return view('quizzes.show', compact('quiz'));
    }
}
