@extends('layouts.app')

@section('title', $quiz->title)

@section('content')
<div style="max-width:680px; margin:0 auto;">

    <div style="margin-bottom:2rem; animation: fade-in-up .4s ease both;">
        <a href="{{ route('quizzes.index') }}" style="color:var(--muted); text-decoration:none; font-size:.85rem; display:inline-flex; align-items:center; gap:.4rem; margin-bottom:1rem;">
            ← Back to quizzes
        </a>
        <h1 class="neon">{{ $quiz->title }}</h1>
        @if($quiz->description)
            <p style="color:var(--muted); margin-top:.5rem; line-height:1.6;">{{ $quiz->description }}</p>
        @endif
        <div style="display:flex; gap:.75rem; margin-top:.75rem;">
            <span class="badge badge-cyan">{{ $quiz->questions->count() }} questions</span>
            <span class="badge badge-green">1 pt each</span>
        </div>
    </div>

    <form method="POST" action="{{ route('quizzes.attempt', $quiz) }}" id="quiz-form">
        @csrf

        @foreach($quiz->questions as $i => $question)
        <div class="card" style="margin-bottom:1.25rem;" id="question-{{ $question->id }}">
            <div style="display:flex; gap:.75rem; align-items:flex-start; margin-bottom:1rem;">
                <span style="background:rgba(0,245,255,.1); border:1px solid rgba(0,245,255,.3); color:var(--cyan);
                             border-radius:50%; width:28px; height:28px; display:flex; align-items:center; justify-content:center;
                             font-size:.8rem; font-weight:700; flex-shrink:0; font-family:'Space Grotesk',sans-serif;">
                    {{ $i + 1 }}
                </span>
                <p style="color:#e0eaff; font-size:1rem; line-height:1.5; padding-top:.2rem;">{{ $question->text }}</p>
            </div>

            <div style="display:flex; flex-direction:column; gap:.6rem;">
                @foreach($question->answers->shuffle() as $answer)
                <div class="answer-option">
                    <input type="radio"
                           name="answers[{{ $question->id }}]"
                           value="{{ $answer->id }}"
                           id="answer-{{ $answer->id }}">
                    <label for="answer-{{ $answer->id }}">
                        <span class="radio-dot"></span>
                        {{ $answer->text }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <div style="position:sticky; bottom:1.5rem; padding:1rem; background:rgba(6,6,9,.85);
                    backdrop-filter:blur(12px); border:1px solid var(--border); border-radius:12px;
                    display:flex; align-items:center; justify-content:space-between; gap:1rem;
                    box-shadow: 0 0 30px rgba(0,0,0,.5);">
            <div id="progress-label" style="color:var(--muted); font-size:.875rem;">
                <span id="answered-count">0</span> / {{ $quiz->questions->count() }} answered
            </div>
            <button type="submit" class="btn btn-success" style="font-size:1rem; padding:.75rem 2rem;">
                Submit Answers ⚡
            </button>
        </div>
    </form>
</div>

<script>
(function () {
    const total = {{ $quiz->questions->count() }};
    const label = document.getElementById('answered-count');
    const form = document.getElementById('quiz-form');

    function updateProgress() {
        const answered = new Set(
            [...form.querySelectorAll('input[type="radio"]:checked')]
                .map(r => r.name)
        ).size;
        label.textContent = answered;
        label.style.color = answered === total ? 'var(--green)' : 'var(--muted)';
    }

    form.addEventListener('change', updateProgress);
})();
</script>
@endsection
