<?php

use Livewire\Component;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;

new class extends Component
{
    public $layout = 'app';

    public string $title = '';
    public array $questions = [];

    public function mount(): void
    {
        $this->addQuestion();
    }

    public function addQuestion(): void
    {
        if (count($this->questions) >= 10) {
            return;
        }
        $this->questions[] = [
            'text' => '',
            'answers' => [
                ['text' => '', 'is_correct' => true],
                ['text' => '', 'is_correct' => false],
            ],
        ];
    }

    public function removeQuestion(int $index): void
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
    }

    public function addAnswer(int $qIndex): void
    {
        if (count($this->questions[$qIndex]['answers']) >= 10) {
            return;
        }
        $this->questions[$qIndex]['answers'][] = ['text' => '', 'is_correct' => false];
    }

    public function removeAnswer(int $qIndex, int $aIndex): void
    {
        $answers = $this->questions[$qIndex]['answers'];
        if (count($answers) <= 2) {
            return;
        }
        $wasCorrect = $answers[$aIndex]['is_correct'];
        unset($answers[$aIndex]);
        $answers = array_values($answers);
        if ($wasCorrect && count($answers) > 0) {
            $answers[0]['is_correct'] = true;
        }
        $this->questions[$qIndex]['answers'] = $answers;
    }

    public function setCorrect(int $qIndex, int $aIndex): void
    {
        foreach ($this->questions[$qIndex]['answers'] as $i => $_) {
            $this->questions[$qIndex]['answers'][$i]['is_correct'] = ($i === $aIndex);
        }
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255|min:3',
            'questions' => 'required|array|min:1|max:10',
            'questions.*.text' => 'required|string|max:500|min:3',
            'questions.*.answers' => 'required|array|min:2|max:10',
            'questions.*.answers.*.text' => 'required|string|max:255|min:1',
        ]);

        foreach ($this->questions as $qi => $question) {
            $hasCorrect = collect($question['answers'])->contains('is_correct', true);
            if (!$hasCorrect) {
                $this->addError("questions.{$qi}.text", 'Mark one answer as correct.');
                return;
            }
        }

        $quiz = Quiz::create(['title' => $this->title]);

        foreach ($this->questions as $questionData) {
            $question = Question::create([
                'quiz_id' => $quiz->id,
                'text' => $questionData['text'],
            ]);
            foreach ($questionData['answers'] as $answerData) {
                Answer::create([
                    'question_id' => $question->id,
                    'text' => $answerData['text'],
                    'is_correct' => $answerData['is_correct'],
                ]);
            }
        }

        $this->redirect(route('quizzes.show', $quiz));
    }
};
?>

<div class="cp-container animate-in">
    <div style="margin-bottom:2rem;">
        <p class="cp-subheading">// QUIZ COMPILER</p>
        <h1 class="cp-heading" style="font-size:1.8rem; margin-top:0.3rem;">CREATE QUIZ</h1>
    </div>

    <form wire:submit="save">

        {{-- Quiz Title --}}
        <div class="cp-card" style="margin-bottom:1.5rem;">
            <label class="cp-subheading" style="display:block; margin-bottom:0.75rem;">QUIZ TITLE</label>
            <input
                type="text"
                wire:model="title"
                class="cp-input"
                placeholder="Enter quiz designation..."
                maxlength="255"
            >
            @error('title')
                <div class="cp-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Questions --}}
        <div style="display:flex; flex-direction:column; gap:1.2rem;">
            @foreach($questions as $qi => $question)
                <div class="cp-question-block animate-in" wire:key="question-{{ $qi }}">
                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem;">
                        <span class="cp-subheading">
                            QUESTION {{ str_pad($qi + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        @if(count($questions) > 1)
                            <button type="button" wire:click="removeQuestion({{ $qi }})" class="cp-btn cp-btn-red">
                                <span>REMOVE</span>
                            </button>
                        @endif
                    </div>

                    <input
                        type="text"
                        wire:model="questions.{{ $qi }}.text"
                        class="cp-input"
                        placeholder="Enter question text..."
                        maxlength="500"
                        style="margin-bottom:0.75rem;"
                    >
                    @error("questions.{$qi}.text")
                        <div class="cp-error">{{ $message }}</div>
                    @enderror

                    <div style="margin-top:0.75rem; margin-bottom:0.5rem; font-size:0.7rem; color:#555; letter-spacing:0.1em;">
                        ANSWERS — CLICK CIRCLE TO MARK CORRECT
                    </div>

                    <div style="display:flex; flex-direction:column; gap:0.5rem;">
                        @foreach($question['answers'] as $ai => $answer)
                            <div class="cp-answer-row animate-in" wire:key="answer-{{ $qi }}-{{ $ai }}">
                                <button
                                    type="button"
                                    wire:click="setCorrect({{ $qi }}, {{ $ai }})"
                                    class="cp-correct-toggle {{ $answer['is_correct'] ? 'active' : '' }}"
                                    title="Mark as correct answer"
                                ></button>
                                <input
                                    type="text"
                                    wire:model="questions.{{ $qi }}.answers.{{ $ai }}.text"
                                    class="cp-input"
                                    placeholder="{{ $answer['is_correct'] ? 'Correct answer...' : 'Wrong answer...' }}"
                                    maxlength="255"
                                    style="{{ $answer['is_correct'] ? 'border-color:rgba(57,255,20,0.4); box-shadow:0 0 8px rgba(57,255,20,0.1);' : '' }}"
                                >
                                @error("questions.{$qi}.answers.{$ai}.text")
                                    <span style="color:var(--red); font-size:0.7rem; white-space:nowrap;">required</span>
                                @enderror
                                @if(count($question['answers']) > 2)
                                    <button
                                        type="button"
                                        wire:click="removeAnswer({{ $qi }}, {{ $ai }})"
                                        class="cp-btn cp-btn-red"
                                        style="padding:0.3rem 0.6rem; font-size:0.6rem; white-space:nowrap;"
                                    >
                                        <span>×</span>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if(count($question['answers']) < 10)
                        <button
                            type="button"
                            wire:click="addAnswer({{ $qi }})"
                            style="margin-top:0.75rem; font-size:0.7rem; color:#555; background:none; border:1px dashed #333; padding:0.4rem 0.8rem; cursor:pointer; letter-spacing:0.1em; transition:all 0.2s; width:100%;"
                            onmouseover="this.style.borderColor='rgba(0,245,255,0.4)'; this.style.color='var(--cyan)';"
                            onmouseout="this.style.borderColor='#333'; this.style.color='#555';"
                        >
                            + ADD ANSWER
                        </button>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Add Question --}}
        @if(count($questions) < 10)
            <div style="margin-top:1rem;">
                <button
                    type="button"
                    wire:click="addQuestion"
                    style="font-size:0.75rem; color:#555; background:none; border:1px dashed rgba(255,0,170,0.3); padding:0.8rem; cursor:pointer; letter-spacing:0.1em; transition:all 0.2s; width:100%; font-family:'Share Tech Mono',monospace;"
                    onmouseover="this.style.borderColor='var(--magenta)'; this.style.color='var(--magenta)'; this.style.boxShadow='0 0 10px rgba(255,0,170,0.1)';"
                    onmouseout="this.style.borderColor='rgba(255,0,170,0.3)'; this.style.color='#555'; this.style.boxShadow='none';"
                >
                    + ADD QUESTION ({{ count($questions) }}/10)
                </button>
            </div>
        @endif

        {{-- Save --}}
        <div style="margin-top:2rem; display:flex; justify-content:flex-end;">
            <button type="submit" class="cp-btn cp-btn-green" wire:loading.attr="disabled">
                <span wire:loading.remove>COMPILE &amp; SAVE QUIZ</span>
                <span wire:loading class="cp-loading">SAVING</span>
            </button>
        </div>

    </form>
</div>
