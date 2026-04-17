@extends('layouts.app')

@section('title', 'Create Quiz')

@section('content')
<div style="max-width:700px; margin:0 auto;">
    <div style="margin-bottom:2rem;">
        <h1 class="neon">Create a Quiz</h1>
        <p style="color:var(--muted); margin-top:.3rem;">Add up to 10 questions, each with up to 10 answers. Mark exactly one answer as correct.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-error">
            <strong>Please fix these errors:</strong>
            <ul style="margin-top:.5rem; padding-left:1.2rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('quizzes.store') }}" id="quiz-form">
        @csrf

        <div class="card" style="margin-bottom:1.25rem;">
            <h2 class="section-title">Quiz Details</h2>
            <div class="form-group">
                <label class="form-label" for="title">Quiz Title *</label>
                <input type="text" name="title" id="title" class="form-input"
                       value="{{ old('title') }}" placeholder="e.g. World Geography Basics" required>
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <label class="form-label" for="description">Description (optional)</label>
                <textarea name="description" id="description" class="form-textarea"
                          placeholder="Brief description of what this quiz covers…">{{ old('description') }}</textarea>
            </div>
        </div>

        <div id="questions-container"></div>

        <div style="display:flex; gap:1rem; align-items:center; margin-bottom:2rem;">
            <button type="button" id="add-question-btn" class="btn btn-secondary">
                + Add Question
            </button>
            <span id="question-count-label" style="color:var(--muted); font-size:.85rem;"></span>
        </div>

        <div style="display:flex; gap:1rem;">
            <button type="submit" class="btn btn-success" style="font-size:1rem; padding:.8rem 2rem;">
                ⚡ Publish Quiz
            </button>
            <a href="{{ route('quizzes.index') }}" class="btn btn-danger">Cancel</a>
        </div>
    </form>
</div>

<script>
(function () {
    let questionCount = 0;
    const MAX_QUESTIONS = 10;
    const MAX_ANSWERS = 10;

    const container = document.getElementById('questions-container');
    const addBtn = document.getElementById('add-question-btn');
    const countLabel = document.getElementById('question-count-label');

    function updateCountLabel() {
        countLabel.textContent = questionCount + ' / ' + MAX_QUESTIONS + ' questions';
        addBtn.disabled = questionCount >= MAX_QUESTIONS;
        addBtn.style.opacity = questionCount >= MAX_QUESTIONS ? '0.4' : '1';
    }

    function createAnswerHTML(qi, ai, text, isCorrect) {
        return `
        <div class="answer-row" data-answer-index="${ai}" style="display:flex; gap:.6rem; align-items:center; margin-bottom:.6rem;">
            <label style="display:flex; align-items:center; gap:.5rem; cursor:pointer; color:var(--muted); font-size:.8rem; white-space:nowrap;">
                <input type="radio" name="questions[${qi}][correct_answer]" value="${ai}"
                       ${isCorrect ? 'checked' : ''} required
                       style="accent-color:var(--cyan); width:16px; height:16px; cursor:pointer;">
                Correct
            </label>
            <input type="text" name="questions[${qi}][answers][${ai}][text]" class="form-input"
                   value="${text || ''}" placeholder="Answer text…" required
                   style="flex:1;">
            <button type="button" class="btn btn-danger btn-sm remove-answer-btn" title="Remove answer">✕</button>
        </div>`;
    }

    function createQuestionHTML(qi) {
        return `
        <div class="card question-block" data-question-index="${qi}"
             style="margin-bottom:1.25rem; border-color:rgba(191,0,255,.2); animation: fade-in-up .35s ease both;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1rem;">
                <h3 style="font-family:'Space Grotesk',sans-serif; color:var(--purple); font-size:1rem; font-weight:600;">
                    Question <span class="q-number">${qi + 1}</span>
                </h3>
                <button type="button" class="btn btn-danger btn-sm remove-question-btn">Remove</button>
            </div>

            <div class="form-group">
                <label class="form-label">Question Text *</label>
                <textarea name="questions[${qi}][text]" class="form-textarea"
                          placeholder="What is the capital of France?" required
                          style="min-height:60px;"></textarea>
            </div>

            <div style="margin-bottom:.75rem;">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.6rem;">
                    <label class="form-label" style="margin:0;">Answers * <span style="font-weight:400; text-transform:none; letter-spacing:0;">(select the correct one)</span></label>
                    <span class="answer-count-label" style="color:var(--muted); font-size:.8rem;"></span>
                </div>
                <div class="answers-container"></div>
            </div>

            <button type="button" class="btn btn-cyan btn-sm add-answer-btn"
                    style="border:1.5px solid var(--cyan); color:var(--cyan); background:transparent; padding:.4rem .9rem; font-size:.8rem; border-radius:6px; cursor:pointer; font-family:inherit; font-weight:600; transition:all .2s;">
                + Add Answer
            </button>
        </div>`;
    }

    function addQuestion() {
        if (questionCount >= MAX_QUESTIONS) return;
        const qi = questionCount;
        questionCount++;

        container.insertAdjacentHTML('beforeend', createQuestionHTML(qi));
        const block = container.lastElementChild;
        const answersContainer = block.querySelector('.answers-container');

        // Start with 2 blank answers
        let answerCount = 0;
        function addAnswerToBlock(text, isCorrect) {
            const curCount = answersContainer.querySelectorAll('.answer-row').length;
            if (curCount >= MAX_ANSWERS) return;
            answersContainer.insertAdjacentHTML('beforeend', createAnswerHTML(qi, answerCount, text, isCorrect));
            answerCount++;
            updateAnswerCount(block);
        }

        addAnswerToBlock('', true);   // first is correct by default
        addAnswerToBlock('', false);

        block.querySelector('.add-answer-btn').addEventListener('click', () => {
            addAnswerToBlock('', false);
        });

        block.querySelector('.remove-question-btn').addEventListener('click', () => {
            block.remove();
            questionCount--;
            renumberQuestions();
            updateCountLabel();
        });

        // Delegate remove-answer clicks
        answersContainer.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-answer-btn')) {
                const rows = answersContainer.querySelectorAll('.answer-row');
                if (rows.length <= 2) return; // minimum 2 answers
                e.target.closest('.answer-row').remove();
                updateAnswerCount(block);
            }
        });

        updateCountLabel();
        updateAnswerCount(block);
    }

    function updateAnswerCount(block) {
        const rows = block.querySelectorAll('.answer-row');
        const label = block.querySelector('.answer-count-label');
        label.textContent = rows.length + ' / ' + MAX_ANSWERS;
        const addBtn = block.querySelector('.add-answer-btn');
        addBtn.disabled = rows.length >= MAX_ANSWERS;
        addBtn.style.opacity = rows.length >= MAX_ANSWERS ? '0.4' : '1';
    }

    function renumberQuestions() {
        const blocks = container.querySelectorAll('.question-block');
        blocks.forEach((block, i) => {
            block.querySelector('.q-number').textContent = i + 1;
        });
    }

    addBtn.addEventListener('click', addQuestion);

    // Start with 1 question
    addQuestion();
    updateCountLabel();
})();
</script>
@endsection
