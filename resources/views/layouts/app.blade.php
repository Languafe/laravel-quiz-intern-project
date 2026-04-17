<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'QuizNeon') — Quiz System</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|space-grotesk:400,500,600,700" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --cyan: #00f5ff;
            --purple: #bf00ff;
            --green: #00ff87;
            --pink: #ff006e;
            --bg: #060609;
            --surface: rgba(255,255,255,0.03);
            --border: rgba(0,245,255,0.18);
            --border-hover: rgba(0,245,255,0.5);
            --text: #d0d8e8;
            --muted: #6b7a99;
        }

        @keyframes glow-pulse {
            0%,100% { box-shadow: 0 0 8px rgba(0,245,255,.25), 0 0 30px rgba(0,245,255,.08); }
            50%      { box-shadow: 0 0 20px rgba(0,245,255,.5), 0 0 60px rgba(0,245,255,.2); }
        }
        @keyframes gradient-text {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes float {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(-5px); }
        }
        @keyframes scanline {
            0%   { transform: translateY(-100%); }
            100% { transform: translateY(100vh); }
        }
        @keyframes fade-in-up {
            from { opacity:0; transform:translateY(20px); }
            to   { opacity:1; transform:translateY(0); }
        }
        @keyframes scale-in {
            from { opacity:0; transform:scale(.95); }
            to   { opacity:1; transform:scale(1); }
        }
        @keyframes spin-slow {
            to { transform: rotate(360deg); }
        }

        html, body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Instrument Sans', sans-serif;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(0,245,255,.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,245,255,.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        body::after {
            content: '';
            position: fixed;
            left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--cyan), transparent);
            opacity: .25;
            animation: scanline 8s linear infinite;
            pointer-events: none;
            z-index: 0;
        }

        .page-wrapper {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* NAV */
        nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--border);
            background: rgba(6,6,9,.8);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .nav-logo {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 1.3rem;
            background: linear-gradient(90deg, var(--cyan), var(--purple), var(--green));
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradient-text 4s ease infinite;
            text-decoration: none;
        }
        .nav-links { display: flex; gap: 1rem; align-items: center; }
        .nav-link {
            color: var(--muted);
            text-decoration: none;
            font-size: .9rem;
            padding: .4rem .8rem;
            border-radius: 6px;
            transition: color .2s, background .2s;
        }
        .nav-link:hover { color: var(--cyan); background: rgba(0,245,255,.06); }

        /* MAIN */
        main { flex: 1; padding: 2rem; max-width: 900px; width: 100%; margin: 0 auto; }

        /* CARDS */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1.75rem;
            transition: border-color .3s, box-shadow .3s, transform .3s;
            animation: fade-in-up .4s ease both;
        }
        .card:hover {
            border-color: var(--border-hover);
            box-shadow: 0 0 30px rgba(0,245,255,.12);
            transform: translateY(-2px);
        }
        .card-glow { animation: glow-pulse 3s ease-in-out infinite; }

        /* HEADINGS */
        h1.neon {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 2.2rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--cyan) 0%, var(--purple) 50%, var(--green) 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradient-text 5s ease infinite;
            margin-bottom: .5rem;
        }
        h2.section-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--cyan);
            text-shadow: 0 0 12px rgba(0,245,255,.4);
            margin-bottom: 1rem;
        }

        /* BUTTONS */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .65rem 1.4rem;
            border-radius: 8px;
            font-size: .9rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all .25s;
            border: none;
            font-family: inherit;
        }
        .btn-primary {
            background: transparent;
            border: 1.5px solid var(--cyan);
            color: var(--cyan);
            text-shadow: 0 0 8px rgba(0,245,255,.5);
            box-shadow: 0 0 12px rgba(0,245,255,.2), inset 0 0 12px rgba(0,245,255,.05);
        }
        .btn-primary:hover {
            background: rgba(0,245,255,.12);
            box-shadow: 0 0 24px rgba(0,245,255,.4), inset 0 0 16px rgba(0,245,255,.1);
            transform: translateY(-1px);
        }
        .btn-secondary {
            background: transparent;
            border: 1.5px solid var(--purple);
            color: var(--purple);
            text-shadow: 0 0 8px rgba(191,0,255,.5);
            box-shadow: 0 0 12px rgba(191,0,255,.15);
        }
        .btn-secondary:hover {
            background: rgba(191,0,255,.1);
            box-shadow: 0 0 24px rgba(191,0,255,.35);
            transform: translateY(-1px);
        }
        .btn-success {
            background: transparent;
            border: 1.5px solid var(--green);
            color: var(--green);
            text-shadow: 0 0 8px rgba(0,255,135,.5);
            box-shadow: 0 0 12px rgba(0,255,135,.15);
        }
        .btn-success:hover {
            background: rgba(0,255,135,.1);
            box-shadow: 0 0 24px rgba(0,255,135,.35);
            transform: translateY(-1px);
        }
        .btn-danger {
            background: transparent;
            border: 1.5px solid var(--pink);
            color: var(--pink);
            text-shadow: 0 0 8px rgba(255,0,110,.5);
            box-shadow: 0 0 12px rgba(255,0,110,.15);
        }
        .btn-danger:hover {
            background: rgba(255,0,110,.1);
            box-shadow: 0 0 24px rgba(255,0,110,.3);
        }
        .btn-sm { padding: .4rem .9rem; font-size: .8rem; }

        /* FORM ELEMENTS */
        .form-group { margin-bottom: 1.25rem; }
        .form-label {
            display: block;
            font-size: .85rem;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: .4rem;
            text-transform: uppercase;
            letter-spacing: .08em;
        }
        .form-input, .form-textarea {
            width: 100%;
            background: rgba(0,0,0,.4);
            border: 1px solid rgba(0,245,255,.2);
            border-radius: 8px;
            padding: .7rem 1rem;
            color: var(--text);
            font-family: inherit;
            font-size: .95rem;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }
        .form-input:focus, .form-textarea:focus {
            border-color: var(--cyan);
            box-shadow: 0 0 0 3px rgba(0,245,255,.12);
        }
        .form-textarea { resize: vertical; min-height: 80px; }
        .form-input::placeholder, .form-textarea::placeholder { color: var(--muted); opacity: .6; }

        /* BADGES */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: .2rem .6rem;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 600;
        }
        .badge-cyan { background: rgba(0,245,255,.1); color: var(--cyan); border: 1px solid rgba(0,245,255,.3); }
        .badge-green { background: rgba(0,255,135,.1); color: var(--green); border: 1px solid rgba(0,255,135,.3); }
        .badge-pink  { background: rgba(255,0,110,.1);  color: var(--pink);  border: 1px solid rgba(255,0,110,.3); }

        /* ALERTS */
        .alert {
            padding: .9rem 1.2rem;
            border-radius: 8px;
            margin-bottom: 1.25rem;
            font-size: .9rem;
            animation: scale-in .3s ease;
        }
        .alert-success { background: rgba(0,255,135,.08); border: 1px solid rgba(0,255,135,.3); color: var(--green); }
        .alert-error   { background: rgba(255,0,110,.08); border: 1px solid rgba(255,0,110,.3); color: var(--pink); }

        /* DIVIDER */
        .divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 1.5rem 0;
        }

        /* VALIDATION ERRORS */
        .error-text { color: var(--pink); font-size: .82rem; margin-top: .3rem; }

        /* QUIZ ANSWER OPTION */
        .answer-option { display: flex; gap: .75rem; align-items: flex-start; }
        .answer-option input[type="radio"] { display: none; }
        .answer-option label {
            display: flex;
            align-items: center;
            gap: .75rem;
            width: 100%;
            padding: .85rem 1.1rem;
            border: 1.5px solid rgba(0,245,255,.15);
            border-radius: 10px;
            cursor: pointer;
            background: rgba(0,0,0,.3);
            transition: all .2s;
            font-size: .95rem;
        }
        .answer-option label:hover {
            border-color: var(--cyan);
            background: rgba(0,245,255,.05);
            transform: translateX(4px);
        }
        .answer-option input[type="radio"]:checked + label {
            border-color: var(--cyan);
            background: rgba(0,245,255,.1);
            box-shadow: 0 0 16px rgba(0,245,255,.2);
            color: var(--cyan);
        }
        .radio-dot {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2px solid var(--muted);
            flex-shrink: 0;
            transition: all .2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .answer-option input[type="radio"]:checked + label .radio-dot {
            border-color: var(--cyan);
            background: var(--cyan);
            box-shadow: 0 0 8px var(--cyan);
        }

        /* STAGGER animations */
        .card:nth-child(1) { animation-delay: .05s; }
        .card:nth-child(2) { animation-delay: .1s; }
        .card:nth-child(3) { animation-delay: .15s; }
        .card:nth-child(4) { animation-delay: .2s; }
        .card:nth-child(5) { animation-delay: .25s; }
        .card:nth-child(6) { animation-delay: .3s; }
    </style>
</head>
<body>
<div class="page-wrapper">
    <nav>
        <a href="{{ route('quizzes.index') }}" class="nav-logo">⚡ QuizNeon</a>
        <div class="nav-links">
            <a href="{{ route('quizzes.index') }}" class="nav-link">All Quizzes</a>
            <a href="{{ route('quizzes.create') }}" class="btn btn-primary btn-sm">+ Create Quiz</a>
        </div>
    </nav>

    <main>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @yield('content')
    </main>
</div>
</body>
</html>
