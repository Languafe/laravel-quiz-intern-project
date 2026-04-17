@extends('layouts.app')

@section('title', 'Your Result')

@section('content')
<div style="max-width:680px; margin:0 auto;">

    @php
        $score = $result['score'];
        $total = $result['total'];
        $pct   = $total > 0 ? round(($score / $total) * 100) : 0;
        $color = $pct >= 80 ? 'var(--green)' : ($pct >= 50 ? 'var(--cyan)' : 'var(--pink)');
        $emoji = $pct >= 80 ? '🏆' : ($pct >= 50 ? '👍' : '💡');
    @endphp

    {{-- Score hero --}}
    <div class="card card-glow" style="text-align:center; padding:3rem 2rem; margin-bottom:1.5rem;
         border-color:{{ $color }}; box-shadow:0 0 40px {{ $color }}22; animation: scale-in .5s ease both;">

        <div style="font-size:4rem; margin-bottom:.5rem; animation: float 3s ease-in-out infinite;">{{ $emoji }}</div>

        <h1 style="font-family:'Space Grotesk',sans-serif; font-size:1.2rem; color:var(--muted);
                   text-transform:uppercase; letter-spacing:.12em; margin-bottom:1rem;">
            {{ $result['quiz_title'] }}
        </h1>

        <div style="display:inline-flex; align-items:baseline; gap:.4rem; margin-bottom:.5rem;">
            <span id="score-display" style="font-family:'Space Grotesk',sans-serif; font-size:5rem; font-weight:700;
                  color:{{ $color }}; text-shadow:0 0 30px {{ $color }}, 0 0 60px {{ $color }}44; line-height:1;">0</span>
            <span style="font-family:'Space Grotesk',sans-serif; font-size:2rem; color:var(--muted);">/ {{ $total }}</span>
        </div>

        <div style="color:var(--muted); font-size:1rem; margin-bottom:1.5rem;">
            <span style="color:{{ $color }}; font-weight:600;">{{ $pct }}%</span> correct
        </div>

        <div style="display:inline-flex; width:min(300px,80%); height:8px; background:rgba(255,255,255,.06);
                    border-radius:999px; overflow:hidden; margin-bottom:2rem;">
            <div id="progress-bar" style="width:0%; height:100%; background:{{ $color }};
                 border-radius:999px; box-shadow:0 0 10px {{ $color }};
                 transition:width 1s cubic-bezier(.4,0,.2,1) .3s;"></div>
        </div>

        <div style="display:flex; gap:1rem; justify-content:center; flex-wrap:wrap;">
            <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-primary">Try Again</a>
            <a href="{{ route('quizzes.index') }}" class="btn btn-secondary">All Quizzes</a>
        </div>
    </div>

    {{-- Question breakdown --}}
    <h2 class="section-title" style="margin-top:2rem;">Question Breakdown</h2>

    @foreach($result['results'] as $i => $item)
    @php
        $rowColor = !$item['answered'] ? 'var(--pink)' : ($item['is_correct'] ? 'var(--green)' : 'var(--pink)');
        $icon = !$item['answered'] ? '—' : ($item['is_correct'] ? '✓' : '✗');
    @endphp
    <div class="card" style="margin-bottom:.9rem; border-color:{{ $rowColor }}33;
         animation: fade-in-up .4s ease {{ $i * 0.07 }}s both;">
        <div style="display:flex; gap:.75rem; align-items:flex-start;">
            <span style="width:28px; height:28px; border-radius:50%; border:2px solid {{ $rowColor }};
                         color:{{ $rowColor }}; display:flex; align-items:center; justify-content:center;
                         font-size:.9rem; font-weight:700; flex-shrink:0; font-family:'Space Grotesk',sans-serif;
                         box-shadow:0 0 10px {{ $rowColor }}44;">
                {{ $icon }}
            </span>
            <div style="flex:1; min-width:0;">
                <p style="color:#e0eaff; font-size:.95rem; margin-bottom:.6rem; line-height:1.45;">
                    {{ $item['question_text'] }}
                </p>

                @if(!$item['answered'])
                    <p style="color:var(--pink); font-size:.83rem;">You did not answer this question.</p>
                    <p style="color:var(--muted); font-size:.83rem; margin-top:.2rem;">
                        Correct answer: <span style="color:var(--green);">{{ $item['correct_text'] }}</span>
                    </p>
                @elseif($item['is_correct'])
                    <p style="color:var(--green); font-size:.83rem;">
                        ✓ {{ $item['selected_text'] }}
                    </p>
                @else
                    <p style="color:var(--pink); font-size:.83rem; margin-bottom:.2rem;">
                        ✗ Your answer: {{ $item['selected_text'] }}
                    </p>
                    <p style="color:var(--muted); font-size:.83rem;">
                        Correct answer: <span style="color:var(--green);">{{ $item['correct_text'] }}</span>
                    </p>
                @endif
            </div>
        </div>
    </div>
    @endforeach

    <div style="margin-top:1.5rem; display:flex; gap:1rem; flex-wrap:wrap;">
        <a href="{{ route('quizzes.show', $quiz) }}" class="btn btn-primary">Try Again</a>
        <a href="{{ route('quizzes.index') }}" class="btn btn-secondary">All Quizzes</a>
    </div>
</div>

<script>
(function () {
    const finalScore = {{ $score }};
    const pct = {{ $pct }};
    const el = document.getElementById('score-display');
    const bar = document.getElementById('progress-bar');
    let current = 0;

    requestAnimationFrame(function tick() {
        if (current < finalScore) {
            current++;
            el.textContent = current;
            setTimeout(() => requestAnimationFrame(tick), 80);
        }
    });

    // Trigger progress bar animation
    setTimeout(() => { bar.style.width = pct + '%'; }, 100);
})();
</script>
@endsection
