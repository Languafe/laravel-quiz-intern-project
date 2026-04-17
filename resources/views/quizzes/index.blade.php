@extends('layouts.app')

@section('title', 'All Quizzes')

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:2rem;">
    <div>
        <h1 class="neon">All Quizzes</h1>
        <p style="color:var(--muted); margin-top:.3rem;">Pick a quiz and test your knowledge.</p>
    </div>
    <a href="{{ route('quizzes.create') }}" class="btn btn-primary">
        <span>+</span> Create Quiz
    </a>
</div>

@if($quizzes->isEmpty())
    <div class="card card-glow" style="text-align:center; padding:4rem 2rem;">
        <div style="font-size:3.5rem; margin-bottom:1rem; animation: float 3s ease-in-out infinite;">🧠</div>
        <h2 style="color:var(--cyan); font-family:'Space Grotesk',sans-serif; margin-bottom:.5rem;">No quizzes yet</h2>
        <p style="color:var(--muted); margin-bottom:1.5rem;">Be the first to create one!</p>
        <a href="{{ route('quizzes.create') }}" class="btn btn-primary">Create the first quiz</a>
    </div>
@else
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px,1fr)); gap:1.25rem;">
        @foreach($quizzes as $quiz)
        <a href="{{ route('quizzes.show', $quiz) }}" style="text-decoration:none;">
            <div class="card" style="height:100%; display:flex; flex-direction:column; justify-content:space-between; gap:1rem;">
                <div>
                    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:.5rem; margin-bottom:.75rem;">
                        <h3 style="font-family:'Space Grotesk',sans-serif; font-size:1.05rem; font-weight:600; color:#e0eaff; line-height:1.35;">
                            {{ $quiz->title }}
                        </h3>
                        <span class="badge badge-cyan" style="flex-shrink:0;">
                            {{ $quiz->questions_count }} Q
                        </span>
                    </div>
                    @if($quiz->description)
                        <p style="color:var(--muted); font-size:.875rem; line-height:1.5;">
                            {{ Str::limit($quiz->description, 100) }}
                        </p>
                    @endif
                </div>
                <div style="display:flex; align-items:center; gap:.5rem; padding-top:.75rem; border-top:1px solid var(--border);">
                    <span style="color:var(--cyan); font-size:.85rem; font-weight:600;">Take quiz →</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
@endif
@endsection
