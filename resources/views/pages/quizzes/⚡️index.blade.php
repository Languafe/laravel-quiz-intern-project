<?php

use Livewire\Component;
use App\Models\Quiz;

new class extends Component
{
    public $layout = 'app';

    public function getQuizzesProperty()
    {
        return Quiz::withCount('questions')->latest()->get();
    }
};
?>

<div class="cp-container animate-in">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:2rem;">
        <div>
            <p class="cp-subheading">// NEURAL DATABASE</p>
            <h1 class="cp-heading" style="font-size:1.8rem; margin-top:0.3rem;">QUIZ ARCHIVE</h1>
        </div>
        <a href="{{ route('quizzes.create') }}" class="cp-btn">
            <span>+ NEW QUIZ</span>
        </a>
    </div>

    @if($this->quizzes->isEmpty())
        <div class="cp-card" style="text-align:center; padding:3rem;">
            <p style="font-size:2rem; margin-bottom:1rem; opacity:0.3;">[ EMPTY ]</p>
            <p style="color:#888; font-size:0.85rem; margin-bottom:1.5rem;">No quiz data found in the neural network.</p>
            <a href="{{ route('quizzes.create') }}" class="cp-btn">
                <span>INITIALIZE FIRST QUIZ</span>
            </a>
        </div>
    @else
        <div class="cp-progress" style="margin-bottom:1.5rem;">
            <div class="cp-progress-bar" style="width:100%;"></div>
        </div>
        <div style="display:flex; flex-direction:column; gap:0.75rem;">
            @foreach($this->quizzes as $quiz)
                <a href="{{ route('quizzes.show', $quiz) }}" style="display:block;">
                    <div class="cp-quiz-item">
                        <div style="display:flex; align-items:center; gap:1rem;">
                            <span style="color:rgba(0,245,255,0.4); font-size:0.75rem; font-family:'Orbitron',monospace;">
                                {{ str_pad($loop->index + 1, 3, '0', STR_PAD_LEFT) }}
                            </span>
                            <div>
                                <div style="font-size:0.95rem; color:#e0e0ff; margin-bottom:0.2rem;">
                                    {{ $quiz->title }}
                                </div>
                                <div style="font-size:0.7rem; color:#666;">
                                    {{ $quiz->questions_count }} {{ $quiz->questions_count === 1 ? 'question' : 'questions' }} loaded
                                </div>
                            </div>
                        </div>
                        <div style="display:flex; align-items:center; gap:1rem;">
                            <span class="cp-tag">TAKE QUIZ</span>
                            <span style="color:var(--cyan); font-size:1.2rem;">›</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div style="margin-top:1.5rem; text-align:right; font-size:0.7rem; color:#444; letter-spacing:0.1em;">
            {{ $this->quizzes->count() }} RECORDS FOUND // SYSTEM NOMINAL
        </div>
    @endif
</div>
