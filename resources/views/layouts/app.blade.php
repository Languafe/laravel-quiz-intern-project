<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'QuizNet') }} // NEURAL INTERFACE</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=share-tech-mono:400|orbitron:400,700,900" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
                html { font-size: 16px; }
                body { font-family: 'Share Tech Mono', monospace; background: #050508; color: #e0e0ff; min-height: 100vh; overflow-x: hidden; }
                img, video { max-width: 100%; display: block; }
                button, input, select, textarea { font: inherit; color: inherit; }
                a { color: inherit; text-decoration: none; }
            </style>
        @endif

        <style>
            :root {
                --cyan: #00f5ff;
                --magenta: #ff00aa;
                --green: #39ff14;
                --red: #ff3333;
                --yellow: #ffdd00;
                --bg: #050508;
                --bg2: #0a0a12;
                --bg3: #0f0f1a;
                --border: rgba(0, 245, 255, 0.3);
                --font-mono: 'Share Tech Mono', monospace;
                --font-head: 'Orbitron', monospace;
            }

            body {
                font-family: var(--font-mono);
                background-color: var(--bg);
                color: #c8c8e8;
                min-height: 100vh;
                position: relative;
            }

            /* Scanline overlay */
            body::before {
                content: '';
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: repeating-linear-gradient(
                    0deg,
                    transparent,
                    transparent 2px,
                    rgba(0, 0, 0, 0.08) 2px,
                    rgba(0, 0, 0, 0.08) 4px
                );
                pointer-events: none;
                z-index: 9999;
            }

            /* Grid background */
            body::after {
                content: '';
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background-image:
                    linear-gradient(rgba(0, 245, 255, 0.03) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(0, 245, 255, 0.03) 1px, transparent 1px);
                background-size: 40px 40px;
                pointer-events: none;
                z-index: 0;
            }

            .cp-container {
                position: relative;
                z-index: 1;
                max-width: 900px;
                margin: 0 auto;
                padding: 2rem 1.5rem;
            }

            .cp-nav {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 1rem 1.5rem;
                border-bottom: 1px solid var(--border);
                background: rgba(5, 5, 8, 0.95);
                position: sticky;
                top: 0;
                z-index: 100;
                backdrop-filter: blur(10px);
            }

            .cp-logo {
                font-family: var(--font-head);
                font-size: 1.2rem;
                font-weight: 900;
                color: var(--cyan);
                text-shadow: 0 0 10px var(--cyan), 0 0 20px var(--cyan);
                letter-spacing: 0.1em;
                animation: flicker 4s infinite;
            }

            .cp-nav-links {
                display: flex;
                gap: 1.5rem;
            }

            .cp-nav-link {
                color: #888;
                font-size: 0.8rem;
                letter-spacing: 0.1em;
                text-transform: uppercase;
                transition: color 0.2s, text-shadow 0.2s;
                position: relative;
            }

            .cp-nav-link::before {
                content: '> ';
                color: var(--cyan);
                opacity: 0;
                transition: opacity 0.2s;
            }

            .cp-nav-link:hover {
                color: var(--cyan);
                text-shadow: 0 0 8px var(--cyan);
            }

            .cp-nav-link:hover::before {
                opacity: 1;
            }

            /* Cyberpunk card */
            .cp-card {
                background: var(--bg2);
                border: 1px solid var(--border);
                position: relative;
                padding: 1.5rem;
                clip-path: polygon(0 0, calc(100% - 16px) 0, 100% 16px, 100% 100%, 16px 100%, 0 calc(100% - 16px));
            }

            .cp-card::before {
                content: '';
                position: absolute;
                top: 0; left: 0; right: 0; bottom: 0;
                background: linear-gradient(135deg, rgba(0,245,255,0.05) 0%, transparent 50%);
                pointer-events: none;
            }

            /* Neon button */
            .cp-btn {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.6rem 1.4rem;
                font-family: var(--font-head);
                font-size: 0.75rem;
                font-weight: 700;
                letter-spacing: 0.15em;
                text-transform: uppercase;
                border: 1px solid var(--cyan);
                background: transparent;
                color: var(--cyan);
                cursor: pointer;
                transition: all 0.2s;
                clip-path: polygon(0 0, calc(100% - 8px) 0, 100% 8px, 100% 100%, 8px 100%, 0 calc(100% - 8px));
                position: relative;
                overflow: hidden;
            }

            .cp-btn::before {
                content: '';
                position: absolute;
                inset: 0;
                background: var(--cyan);
                opacity: 0;
                transition: opacity 0.2s;
            }

            .cp-btn:hover {
                color: var(--bg);
                box-shadow: 0 0 20px var(--cyan), inset 0 0 20px rgba(0,245,255,0.1);
            }

            .cp-btn:hover::before { opacity: 1; }

            .cp-btn span { position: relative; z-index: 1; }

            .cp-btn-magenta {
                border-color: var(--magenta);
                color: var(--magenta);
            }

            .cp-btn-magenta::before { background: var(--magenta); }

            .cp-btn-magenta:hover {
                color: var(--bg);
                box-shadow: 0 0 20px var(--magenta), inset 0 0 20px rgba(255,0,170,0.1);
            }

            .cp-btn-green {
                border-color: var(--green);
                color: var(--green);
            }

            .cp-btn-green::before { background: var(--green); }

            .cp-btn-green:hover {
                color: var(--bg);
                box-shadow: 0 0 20px var(--green);
            }

            .cp-btn-red {
                border-color: var(--red);
                color: var(--red);
                padding: 0.3rem 0.8rem;
                font-size: 0.65rem;
            }

            .cp-btn-red::before { background: var(--red); }

            .cp-btn-red:hover {
                color: var(--bg);
                box-shadow: 0 0 15px var(--red);
            }

            /* Neon input */
            .cp-input {
                width: 100%;
                padding: 0.6rem 1rem;
                background: rgba(0,245,255,0.04);
                border: 1px solid rgba(0,245,255,0.2);
                color: #e0e0ff;
                font-family: var(--font-mono);
                font-size: 0.9rem;
                outline: none;
                transition: border-color 0.2s, box-shadow 0.2s;
            }

            .cp-input:focus {
                border-color: var(--cyan);
                box-shadow: 0 0 10px rgba(0,245,255,0.2), inset 0 0 10px rgba(0,245,255,0.05);
            }

            .cp-input::placeholder { color: rgba(200,200,232,0.3); }

            /* Section heading */
            .cp-heading {
                font-family: var(--font-head);
                font-weight: 700;
                color: var(--cyan);
                text-shadow: 0 0 10px var(--cyan);
                letter-spacing: 0.08em;
                text-transform: uppercase;
            }

            .cp-subheading {
                font-family: var(--font-head);
                font-size: 0.7rem;
                letter-spacing: 0.15em;
                text-transform: uppercase;
                color: var(--magenta);
                text-shadow: 0 0 6px var(--magenta);
            }

            /* Neon divider */
            .cp-divider {
                height: 1px;
                background: linear-gradient(90deg, transparent, var(--cyan), transparent);
                margin: 1.5rem 0;
                opacity: 0.5;
            }

            /* Quiz list item */
            .cp-quiz-item {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 1rem 1.2rem;
                border: 1px solid rgba(0,245,255,0.15);
                background: var(--bg3);
                transition: all 0.2s;
                cursor: pointer;
                clip-path: polygon(0 0, calc(100% - 10px) 0, 100% 10px, 100% 100%, 10px 100%, 0 calc(100% - 10px));
                position: relative;
                overflow: hidden;
            }

            .cp-quiz-item::before {
                content: '';
                position: absolute;
                left: 0; top: 0; bottom: 0;
                width: 3px;
                background: var(--cyan);
                box-shadow: 0 0 10px var(--cyan);
                transition: width 0.2s;
            }

            .cp-quiz-item:hover {
                border-color: var(--cyan);
                box-shadow: 0 0 20px rgba(0,245,255,0.1);
                background: rgba(0,245,255,0.05);
            }

            .cp-quiz-item:hover::before { width: 6px; }

            /* Answer option */
            .cp-answer {
                display: flex;
                align-items: center;
                gap: 1rem;
                padding: 0.8rem 1rem;
                border: 1px solid rgba(0,245,255,0.15);
                background: var(--bg3);
                cursor: pointer;
                transition: all 0.2s;
                user-select: none;
            }

            .cp-answer:hover {
                border-color: rgba(0,245,255,0.4);
                background: rgba(0,245,255,0.05);
            }

            .cp-answer.selected {
                border-color: var(--cyan);
                background: rgba(0,245,255,0.1);
                box-shadow: 0 0 15px rgba(0,245,255,0.2);
            }

            .cp-answer.correct {
                border-color: var(--green);
                background: rgba(57,255,20,0.08);
                box-shadow: 0 0 15px rgba(57,255,20,0.2);
            }

            .cp-answer.wrong {
                border-color: var(--red);
                background: rgba(255,51,51,0.08);
                box-shadow: 0 0 15px rgba(255,51,51,0.15);
            }

            .cp-radio {
                width: 16px;
                height: 16px;
                border: 1px solid var(--cyan);
                border-radius: 50%;
                flex-shrink: 0;
                position: relative;
                transition: all 0.2s;
            }

            .cp-answer.selected .cp-radio::after,
            .cp-answer.correct .cp-radio::after {
                content: '';
                position: absolute;
                inset: 3px;
                border-radius: 50%;
                background: var(--cyan);
                box-shadow: 0 0 6px var(--cyan);
            }

            .cp-answer.correct .cp-radio { border-color: var(--green); }
            .cp-answer.correct .cp-radio::after { background: var(--green); box-shadow: 0 0 6px var(--green); }
            .cp-answer.wrong .cp-radio { border-color: var(--red); }

            /* Score display */
            .cp-score {
                font-family: var(--font-head);
                font-size: 3rem;
                font-weight: 900;
                text-align: center;
                animation: pulse-glow 2s ease-in-out infinite;
            }

            .cp-score.good { color: var(--green); text-shadow: 0 0 20px var(--green), 0 0 40px var(--green); }
            .cp-score.ok { color: var(--yellow); text-shadow: 0 0 20px var(--yellow), 0 0 40px var(--yellow); }
            .cp-score.bad { color: var(--red); text-shadow: 0 0 20px var(--red), 0 0 40px var(--red); }

            /* Tag */
            .cp-tag {
                display: inline-block;
                padding: 0.1rem 0.6rem;
                font-size: 0.65rem;
                letter-spacing: 0.15em;
                text-transform: uppercase;
                border: 1px solid rgba(0,245,255,0.4);
                color: var(--cyan);
                font-family: var(--font-head);
            }

            /* Question block in create */
            .cp-question-block {
                border: 1px solid rgba(255,0,170,0.2);
                background: rgba(255,0,170,0.02);
                padding: 1.2rem;
                position: relative;
                clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 12px, 100% 100%, 0 100%);
            }

            /* Answer input row */
            .cp-answer-row {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .cp-correct-toggle {
                width: 20px;
                height: 20px;
                border: 1px solid rgba(0,245,255,0.3);
                border-radius: 50%;
                flex-shrink: 0;
                cursor: pointer;
                transition: all 0.2s;
                position: relative;
            }

            .cp-correct-toggle.active {
                border-color: var(--green);
                box-shadow: 0 0 8px var(--green);
            }

            .cp-correct-toggle.active::after {
                content: '';
                position: absolute;
                inset: 4px;
                border-radius: 50%;
                background: var(--green);
                box-shadow: 0 0 4px var(--green);
            }

            /* Animations */
            @keyframes flicker {
                0%, 95%, 100% { opacity: 1; }
                96% { opacity: 0.6; }
                97% { opacity: 1; }
                98% { opacity: 0.4; }
                99% { opacity: 1; }
            }

            @keyframes pulse-glow {
                0%, 100% { filter: brightness(1); }
                50% { filter: brightness(1.3); }
            }

            @keyframes slide-in {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            @keyframes glitch {
                0%, 100% { clip-path: inset(0 0 100% 0); transform: translateX(0); }
                10% { clip-path: inset(10% 0 60% 0); transform: translateX(-2px); }
                20% { clip-path: inset(60% 0 10% 0); transform: translateX(2px); }
                30% { clip-path: inset(0 0 100% 0); transform: translateX(0); }
            }

            .animate-in { animation: slide-in 0.3s ease-out; }

            /* Loading state */
            .cp-loading::after {
                content: '...';
                animation: loading-dots 1s steps(3, end) infinite;
            }

            @keyframes loading-dots {
                0% { content: '.'; }
                33% { content: '..'; }
                66% { content: '...'; }
            }

            /* Error state */
            .cp-error {
                color: var(--red);
                font-size: 0.75rem;
                padding: 0.4rem 0;
                display: flex;
                align-items: center;
                gap: 0.4rem;
            }

            .cp-error::before { content: '!! '; }

            /* Progress bar */
            .cp-progress {
                height: 3px;
                background: rgba(0,245,255,0.1);
                position: relative;
                overflow: hidden;
            }

            .cp-progress-bar {
                height: 100%;
                background: linear-gradient(90deg, var(--cyan), var(--magenta));
                box-shadow: 0 0 8px var(--cyan);
                transition: width 0.4s ease;
            }
        </style>

        @livewireStyles
    </head>
    <body>
        <nav class="cp-nav">
            <a href="{{ route('quizzes.index') }}" class="cp-logo">
                QUIZ_NET
            </a>
            <div class="cp-nav-links">
                <a href="{{ route('quizzes.index') }}" class="cp-nav-link">Quizzes</a>
                <a href="{{ route('quizzes.create') }}" class="cp-nav-link">New Quiz</a>
            </div>
        </nav>

        <main>
            {{ $slot }}
        </main>

        @livewireScripts
    </body>
</html>
