<?php

use Livewire\Component;
use App\Models\Quiz;
use App\Models\Answer;

new class extends Component
{
    public $layout = 'app';

    public Quiz $quiz;
    public array $selectedAnswers = [];
    public bool $submitted = false;
    public int $score = 0;

    public function mount(Quiz $quiz): void
    {
        $this->quiz = $quiz->load('questions.answers');
    }

    public function selectAnswer(int $questionId, int $answerId): void
    {
        if ($this->submitted) {
            return;
        }
        $this->selectedAnswers[$questionId] = $answerId;
    }

    public function submit(): void
    {
        if ($this->submitted) {
            return;
        }

        $this->validate([
            'selectedAnswers' => ['required', 'array', function ($attr, $value, $fail) {
                $questionIds = $this->quiz->questions->pluck('id')->toArray();
                foreach ($questionIds as $qId) {
                    if (!isset($value[$qId])) {
                        $fail('Please answer all questions before submitting.');
                        return;
                    }
                }
            }],
        ]);

        $score = 0;
        foreach ($this->selectedAnswers as $questionId => $answerId) {
            $answer = Answer::find($answerId);
            if ($answer && $answer->is_correct) {
                $score++;
            }
        }

        $this->score = $score;
        $this->submitted = true;
    }

    public function restart(): void
    {
        $this->selectedAnswers = [];
        $this->submitted = false;
        $this->score = 0;
    }

    public function getScoreClassProperty(): string
    {
        $total = $this->quiz->questions->count();
        if ($total === 0) return 'ok';
        $pct = $this->score / $total;
        if ($pct >= 0.7) return 'good';
        if ($pct >= 0.4) return 'ok';
        return 'bad';
    }

    public function getScoreLabelProperty(): string
    {
        $total = $this->quiz->questions->count();
        if ($total === 0) return 'N/A';
        $pct = $this->score / $total;
        if ($pct >= 0.8) return 'NEURAL LINK OPTIMAL';
        if ($pct >= 0.6) return 'SYNC ESTABLISHED';
        if ($pct >= 0.4) return 'PARTIAL UPLINK';
        return 'CONNECTION FAILED';
    }
};
?>

<div class="cp-container animate-in">

    {{-- Header --}}
    <div style="margin-bottom:2rem; display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
        <div>
            <p class="cp-subheading">// QUIZ SESSION</p>
            <h1 class="cp-heading" style="font-size:1.5rem; margin-top:0.3rem;">
                {{ strtoupper($quiz->title) }}
            </h1>
        </div>
        <div style="display:flex; align-items:center; gap:1rem;">
            <span style="font-size:0.7rem; color:#555; letter-spacing:0.1em;">
                {{ $quiz->questions->count() }} QUESTIONS
            </span>
            @if(!$submitted)
                <div class="cp-tag">ACTIVE</div>
            @else
                <div class="cp-tag" style="border-color:var(--green); color:var(--green);">COMPLETED</div>
            @endif
        </div>
    </div>

    {{-- Progress indicator --}}
    @if(!$submitted)
        <div style="margin-bottom:1.5rem;">
            <div style="display:flex; justify-content:space-between; font-size:0.65rem; color:#555; letter-spacing:0.1em; margin-bottom:0.4rem;">
                <span>PROGRESS</span>
                <span>{{ count($selectedAnswers) }}/{{ $quiz->questions->count() }}</span>
            </div>
            <div class="cp-progress">
                <div class="cp-progress-bar" style="width:{{ $quiz->questions->count() > 0 ? (count($selectedAnswers) / $quiz->questions->count() * 100) : 0 }}%;"></div>
            </div>
        </div>
    @endif

    {{-- Score display (after submit) --}}
    @if($submitted)
        <div class="cp-card" style="margin-bottom:2rem; text-align:center; padding:2.5rem;">
            <div class="cp-subheading" style="margin-bottom:1rem;">NEURAL EVALUATION COMPLETE</div>
            <div class="cp-score {{ $this->scoreClass }}">
                {{ $score }}/{{ $quiz->questions->count() }}
            </div>
            <div style="margin-top:0.75rem; font-family:'Orbitron',monospace; font-size:0.8rem; letter-spacing:0.15em; color:#888;">
                {{ $this->scoreLabel }}
            </div>
            <div style="margin-top:2rem; display:flex; gap:1rem; justify-content:center; flex-wrap:wrap;">
                <button wire:click="restart" class="cp-btn">
                    <span>RETRY</span>
                </button>
                <a href="{{ route('quizzes.index') }}" class="cp-btn cp-btn-magenta">
                    <span>BACK TO ARCHIVE</span>
                </a>
            </div>
        </div>
    @endif

    {{-- Validation error --}}
    @error('selectedAnswers')
        <div class="cp-error" style="margin-bottom:1rem; font-size:0.8rem;">{{ $message }}</div>
    @enderror

    {{-- Questions --}}
    <div style="display:flex; flex-direction:column; gap:1.5rem;">
        @foreach($quiz->questions as $qi => $question)
            @php
                $selectedId = $selectedAnswers[$question->id] ?? null;
            @endphp
            <div class="cp-card" wire:key="q-{{ $question->id }}">
                <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1rem;">
                    <span style="font-family:'Orbitron',monospace; font-size:0.65rem; color:var(--cyan); opacity:0.6;">
                        Q.{{ str_pad($qi + 1, 2, '0', STR_PAD_LEFT) }}
                    </span>
                    @if($submitted && isset($selectedAnswers[$question->id]))
                        @php
                            $pickedAnswer = $question->answers->firstWhere('id', $selectedAnswers[$question->id]);
                        @endphp
                        @if($pickedAnswer?->is_correct)
                            <span style="font-size:0.65rem; color:var(--green); letter-spacing:0.1em;">✓ CORRECT</span>
                        @else
                            <span style="font-size:0.65rem; color:var(--red); letter-spacing:0.1em;">✗ WRONG</span>
                        @endif
                    @endif
                </div>

                <p style="font-size:1rem; color:#e0e0ff; margin-bottom:1rem; line-height:1.5;">
                    {{ $question->text }}
                </p>

                <div style="display:flex; flex-direction:column; gap:0.5rem;">
                    @foreach($question->answers as $answer)
                        @php
                            $isSelected = $selectedId === $answer->id;
                            $classes = 'cp-answer';
                            if ($submitted) {
                                if ($answer->is_correct) $classes .= ' correct';
                                elseif ($isSelected && !$answer->is_correct) $classes .= ' wrong';
                            } elseif ($isSelected) {
                                $classes .= ' selected';
                            }
                        @endphp
                        <div
                            class="{{ $classes }}"
                            wire:key="a-{{ $answer->id }}"
                            @if(!$submitted) wire:click="selectAnswer({{ $question->id }}, {{ $answer->id }})" @endif
                        >
                            <div class="cp-radio"></div>
                            <span style="font-size:0.9rem;">{{ $answer->text }}</span>
                            @if($submitted && $answer->is_correct)
                                <span style="margin-left:auto; font-size:0.65rem; color:var(--green); letter-spacing:0.1em;">CORRECT</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    {{-- Submit button --}}
    @if(!$submitted)
        <div style="margin-top:2rem; display:flex; justify-content:flex-end;">
            <button
                wire:click="submit"
                class="cp-btn cp-btn-green"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>SUBMIT &amp; EVALUATE</span>
                <span wire:loading class="cp-loading">PROCESSING</span>
            </button>
        </div>
    @endif

    <div style="margin-top:1.5rem;">
        <a href="{{ route('quizzes.index') }}" style="font-size:0.75rem; color:#444; letter-spacing:0.1em; transition:color 0.2s;" onmouseover="this.style.color='var(--cyan)'" onmouseout="this.style.color='#444'">
            ‹ BACK TO ARCHIVE
        </a>
    </div>

</div>
