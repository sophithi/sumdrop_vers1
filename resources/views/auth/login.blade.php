<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.title') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=VT323&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&family=Kantumruy+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --room: #14171a;
            --casing: #2c3034;
            --casing-light: #3a3f44;
            --casing-dark: #1d2023;
            --screen: #0c1210;
            --amber: #ffb454;
            --amber-dim: #7a5a30;
            --key: #34383c;
            --key-text: #dfe3e6;
            --danger: #ff7a5c;
        }

        * { box-sizing: border-box; }

        html, body { height: 100%; margin: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(ellipse at 50% 0%, #1e2225 0%, var(--room) 60%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .terminal {
            width: 100%;
            max-width: 380px;
            background: linear-gradient(180deg, var(--casing-light) 0%, var(--casing) 12%, var(--casing-dark) 100%);
            border-radius: 20px;
            padding: 1.3rem 1.3rem 1.5rem;
            box-shadow:
                0 30px 60px rgba(0,0,0,0.6),
                inset 0 1px 0 rgba(255,255,255,0.06),
                inset 0 -2px 0 rgba(0,0,0,0.4);
            position: relative;
        }

        .screw {
            position: absolute;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 30%, #6b7176, #17191b);
        }
        .screw.tl { top: 10px; left: 10px; }
        .screw.tr { top: 10px; right: 10px; }
        .screw.bl { bottom: 10px; left: 10px; }
        .screw.br { bottom: 10px; right: 10px; }

        .screen {
            background: var(--screen);
            border-radius: 8px;
            padding: 1.1rem 1.2rem 1.3rem;
            position: relative;
            box-shadow: inset 0 2px 10px rgba(0,0,0,0.8), inset 0 0 0 1px #000;
            overflow: hidden;
        }

        .screen::after {
            content: "";
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                to bottom,
                rgba(255,255,255,0.025) 0px,
                rgba(255,255,255,0.025) 1px,
                transparent 1px,
                transparent 3px
            );
            pointer-events: none;
        }

        .readout {
            font-family: 'VT323', monospace;
            color: var(--amber);
            text-shadow: 0 0 6px rgba(255,180,84,0.65), 0 0 18px rgba(255,180,84,0.25);
            font-size: 1.9rem;
            letter-spacing: 0.04em;
            line-height: 1;
            display: flex;
            align-items: center;
            gap: 2px;
        }

        .cursor {
            display: inline-block;
            width: 10px;
            height: 1.5rem;
            background: var(--amber);
            box-shadow: 0 0 8px rgba(255,180,84,0.8);
            animation: blink 1s steps(1) infinite;
        }

        @keyframes blink { 50% { opacity: 0; } }

        .readout-sub {
            font-family: 'JetBrains Mono', monospace;
            color: var(--amber-dim);
            font-size: 0.66rem;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            margin-top: 0.35rem;
        }

        .status-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
            padding-top: 0.8rem;
            border-top: 1px solid rgba(255,180,84,0.15);
        }

        .status-row span {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.62rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--amber-dim);
        }

        .led {
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #56d97b;
            box-shadow: 0 0 6px #56d97b;
            margin-right: 5px;
        }

        .panel {
            padding: 1.3rem 0.2rem 0.2rem;
        }

        .field { margin-bottom: 1.1rem; }

        label {
            display: block;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.66rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #8a9096;
            margin-bottom: 0.45rem;
        }

        .slot {
            background: var(--screen);
            border-radius: 6px;
            box-shadow: inset 0 2px 6px rgba(0,0,0,0.7), inset 0 0 0 1px #000;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.62rem 0.75rem;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.92rem;
            border: none;
            background: transparent;
            color: var(--amber);
            caret-color: var(--amber);
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
        }

        input::placeholder { color: #4a4038; }

        .error {
            color: var(--danger);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.72rem;
            margin-top: 0.4rem;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin: 1.2rem 0 1.4rem;
        }

        .toggle {
            position: relative;
            width: 32px;
            height: 17px;
            flex-shrink: 0;
        }

        .toggle input {
            opacity: 0;
            width: 100%;
            height: 100%;
            margin: 0;
            cursor: pointer;
            position: absolute;
            z-index: 2;
        }

        .toggle .track {
            position: absolute;
            inset: 0;
            background: var(--casing-dark);
            border-radius: 10px;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.7);
        }

        .toggle .knob {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 13px;
            height: 13px;
            background: #6b7176;
            border-radius: 50%;
            transition: transform 0.15s ease, background 0.15s ease, box-shadow 0.15s ease;
        }

        .toggle input:checked ~ .knob {
            transform: translateX(15px);
            background: var(--amber);
            box-shadow: 0 0 6px rgba(255,180,84,0.7);
        }

        .remember-row span.label-text {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.76rem;
            color: #8a9096;
        }

        .enter-key {
            width: 100%;
            padding: 0.85rem;
            background: linear-gradient(180deg, var(--key) 0%, #23262a 100%);
            color: var(--amber);
            border: 1px solid #17191b;
            border-radius: 8px;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 600;
            font-size: 0.92rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            cursor: pointer;
            box-shadow: 0 3px 0 #17191b, 0 6px 14px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.05);
            transition: transform 0.08s ease, box-shadow 0.08s ease, text-shadow 0.15s ease;
        }

        .enter-key:hover {
            text-shadow: 0 0 8px rgba(255,180,84,0.7);
        }

        .enter-key:active {
            transform: translateY(3px);
            box-shadow: 0 0 0 #17191b, 0 2px 6px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.05);
        }

        .footnote {
            text-align: center;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.64rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #5b6166;
            margin-top: 1.1rem;
        }

        .lang-toggle {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.9rem;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.62rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .lang-toggle a {
            color: #5b6166;
            text-decoration: none;
            padding: 0.15rem 0.5rem;
            border-radius: 4px;
            border: 1px solid transparent;
        }

        .lang-toggle a.active {
            color: var(--amber);
            border-color: rgba(255, 180, 84, 0.35);
        }

        .lang-toggle a:hover {
            color: var(--amber);
        }

        /* The pixel-terminal monospace look is Latin-only; prioritize legibility
           over preserving it when the interface is actually in Khmer. */
        :lang(km) * {
            font-family: 'Kantumruy Pro', sans-serif !important;
        }
    </style>
</head>
<body>
    <div class="terminal">
        <span class="screw tl"></span><span class="screw tr"></span>
        <span class="screw bl"></span><span class="screw br"></span>

        <div class="screen">
            <div class="readout">SumDrop Coffee V1<span class="cursor"></span></div>
            <div class="readout-sub">Developer  &nbsp;·&nbsp; @sen_sothi </div>
            <div class="status-row">
                <span><span class="led"></span>{{ __('auth.info') }}</span>
                <span>{{ __('auth.staff_only') }}</span>
            </div>
        </div>

        <div class="panel">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="field">
                    <label for="email">{{ __('auth.email') }}</label>
                    <div class="slot">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@shop.com" required autofocus>
                    </div>
                    @error('email')<div class="error">&times; {{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label for="password">{{ __('auth.password') }}</label>
                    <div class="slot">
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                    </div>
                    @error('password')<div class="error">&times; {{ $message }}</div>@enderror
                </div>

                <div class="remember-row">
                    <span class="toggle">
                        <input type="checkbox" id="remember" name="remember">
                        <span class="track"></span>
                        <span class="knob"></span>
                    </span>
                    <span class="label-text">{{ __('auth.remember_device') }}</span>
                </div>

                <button type="submit" class="enter-key">{{ __('auth.enter') }} ▸</button>
            </form>

            <div class="footnote">{{ __('auth.locked_out') }}</div>
            <div class="lang-toggle">
                <a href="{{ route('language.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                <a href="{{ route('language.switch', 'km') }}" class="{{ app()->getLocale() === 'km' ? 'active' : '' }}">ខ្មែរ</a>
            </div>
        </div>
    </div>
</body>
</html>