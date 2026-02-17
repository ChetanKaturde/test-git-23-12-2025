<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Monitorbizz</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --p1: #7c3aed;
            --p2: #6d28d9;
            --p3: #a78bfa;
            --p4: #c4b5fd;
            --p5: #ede9fe;
            --text-h:  #1e1344;
            --text-b:  #3b2f6e;
            --text-s:  #6d5fa0;
            --border:  #ddd6fe;
            --input-bg: #faf8ff;
            --err:     #dc2626;
        }

        html, body {
            min-height: 100%;
            font-family: 'DM Sans', sans-serif;
            color: var(--text-h);
            overflow-x: hidden;
        }

        body { background: #ece6ff; position: relative; }

        /* ── BACKGROUND BLOBS — slightly different palette from register ── */
        .bg-layer {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }

        .bg-layer::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(160deg, #e4d9ff 0%, #f0eaff 35%, #ddd4ff 65%, #eae0ff 100%);
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(70px);
            opacity: 0.8;
            will-change: transform;
        }

        /* Login blobs — warmer tones, different positions & sizes from register */
        .blob-1 {
            width: 650px; height: 650px;
            background: radial-gradient(circle, #c4b5fd 0%, #7c3aed 55%, transparent 70%);
            bottom: -180px; left: -120px;
            animation: lmove1 7s ease-in-out infinite alternate;
        }
        .blob-2 {
            width: 480px; height: 480px;
            background: radial-gradient(circle, #f5d0fe 0%, #d946ef 50%, transparent 70%);
            top: -80px; right: -80px;
            animation: lmove2 5.5s ease-in-out infinite alternate;
        }
        .blob-3 {
            width: 400px; height: 400px;
            background: radial-gradient(circle, #fde68a 0%, #f59e0b 45%, transparent 70%);
            top: 45%; right: 25%;
            animation: lmove3 8s ease-in-out infinite alternate;
        }
        .blob-4 {
            width: 360px; height: 360px;
            background: radial-gradient(circle, #bfdbfe 0%, #60a5fa 50%, transparent 70%);
            top: 10%; left: 20%;
            animation: lmove4 6s ease-in-out infinite alternate;
        }
        .blob-5 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, #bbf7d0 0%, #34d399 50%, transparent 70%);
            bottom: 15%; right: 5%;
            animation: lmove5 9s ease-in-out infinite alternate;
        }

        @keyframes lmove1 {
            0%   { transform: translate(0px, 0px)     scale(1);    }
            100% { transform: translate(160px, -120px) scale(1.15); }
        }
        @keyframes lmove2 {
            0%   { transform: translate(0px, 0px)      scale(1);    }
            100% { transform: translate(-140px, 110px) scale(1.18); }
        }
        @keyframes lmove3 {
            0%   { transform: translate(0px, 0px)     scale(1);    }
            100% { transform: translate(-100px, -90px) scale(1.2);  }
        }
        @keyframes lmove4 {
            0%   { transform: translate(0px, 0px)    scale(1);    }
            100% { transform: translate(120px, 100px) scale(1.12); }
        }
        @keyframes lmove5 {
            0%   { transform: translate(0px, 0px)     scale(1);    }
            100% { transform: translate(-80px, -130px) scale(1.14); }
        }

        /* ── PAGE ── */
        .page {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1.5rem;
        }

        /* ── BRAND ── */
        .brand {
            text-align: center;
            margin-bottom: 2rem;
            animation: fadeDown .65s cubic-bezier(.22,1,.36,1) both;
        }

        .brand-icon-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 64px; height: 64px;
            border-radius: 20px;
            background: linear-gradient(135deg, #9b59f7 0%, #6d28d9 100%);
            box-shadow: 0 12px 40px rgba(109,40,217,.4), 0 0 0 8px rgba(167,139,250,.2);
            margin-bottom: 1.1rem;
        }
        .brand-icon-wrap i { color: #fff; font-size: 1.6rem; }

        .brand-name {
            font-family: 'Sora', sans-serif;
            font-size: 2rem; font-weight: 700;
            color: var(--p2);
            letter-spacing: -.03em;
        }
        .brand-sub {
            font-size: .9rem; color: var(--text-s);
            margin-top: .3rem; font-weight: 400;
        }

        /* ── CARD — narrower than register, centered single column ── */
        .card {
            width: 100%;
            max-width: 460px;
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(24px) saturate(160%);
            -webkit-backdrop-filter: blur(24px) saturate(160%);
            border-radius: 28px;
            border: 1.5px solid rgba(255,255,255,0.9);
            box-shadow:
                0 2px 0 rgba(255,255,255,.7) inset,
                0 32px 80px rgba(109,40,217,.18),
                0 8px 24px rgba(109,40,217,.1);
            overflow: hidden;
            animation: fadeUp .65s .08s cubic-bezier(.22,1,.36,1) both;
        }

        /* Shimmer bar — reversed direction from register for variation */
        .card-bar {
            height: 3px;
            background: linear-gradient(90deg, #c084fc, #a78bfa, #7c3aed, #c084fc);
            background-size: 300% 100%;
            animation: barMove 3s linear infinite;
        }
        @keyframes barMove { 0% { background-position: 300% 0; } 100% { background-position: 0% 0; } }

        .card-inner { padding: 2.4rem 2.5rem 2.2rem; }

        /* ── ERROR ALERT ── */
        .alert-err {
            display: flex;
            align-items: flex-start;
            gap: .75rem;
            background: #fef2f2;
            border: 1.5px solid #fecaca;
            border-radius: 12px;
            padding: .9rem 1rem;
            margin-bottom: 1.5rem;
        }
        .alert-err-icon {
            width: 32px; height: 32px; flex-shrink: 0;
            border-radius: 8px;
            background: #fee2e2;
            display: flex; align-items: center; justify-content: center;
        }
        .alert-err-icon i { color: var(--err); font-size: .8rem; }
        .alert-err-title { font-family: 'Sora', sans-serif; font-size: .82rem; font-weight: 600; color: #991b1b; }
        .alert-err-msg   { font-size: .76rem; color: #b91c1c; margin-top: .2rem; }

        /* ── SECTION HEADING ── */
        .sec-head {
            display: flex;
            align-items: center;
            gap: .5rem;
            font-family: 'Sora', sans-serif;
            font-size: .78rem; font-weight: 600;
            color: var(--p1);
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: 1.4rem;
            padding-bottom: .6rem;
            border-bottom: 1.5px solid var(--border);
        }
        .sec-icon {
            width: 26px; height: 26px;
            border-radius: 8px;
            background: var(--p5);
            display: flex; align-items: center; justify-content: center;
        }
        .sec-icon i { color: var(--p1); font-size: .7rem; }

        /* ── FORM ELEMENTS ── */
        .fg { margin-bottom: 1rem; }

        label {
            display: block;
            font-size: .78rem; font-weight: 500;
            color: var(--text-b);
            margin-bottom: .38rem;
        }

        /* Input with leading icon */
        .input-wrap {
            position: relative;
        }
        .input-wrap .lead-icon {
            position: absolute;
            left: .9rem; top: 50%;
            transform: translateY(-50%);
            color: var(--p3);
            font-size: .82rem;
            pointer-events: none;
            transition: color .2s;
        }
        .input-wrap input { padding-left: 2.6rem !important; }
        .input-wrap:focus-within .lead-icon { color: var(--p1); }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: .7rem .9rem;
            background: var(--input-bg);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: .88rem;
            color: var(--text-h);
            outline: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
            -webkit-appearance: none;
            appearance: none;
        }

        input::placeholder { color: #c4b5fd; }

        input:focus {
            border-color: var(--p1);
            background: #fff;
            box-shadow: 0 0 0 3.5px rgba(124,58,237,.13);
        }

        .is-invalid { border-color: var(--err) !important; }
        .is-invalid:focus { box-shadow: 0 0 0 3px rgba(220,38,38,.12) !important; }

        .ferr {
            font-size: .71rem; color: var(--err);
            margin-top: .28rem;
            display: flex; align-items: center; gap: .22rem;
        }

        /* Password eye */
        .pw-wrap { position: relative; }
        .pw-wrap input { padding-right: 2.6rem; padding-left: 2.6rem !important; }
        .pw-eye {
            position: absolute;
            right: .75rem; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: var(--p3); cursor: pointer;
            padding: .15rem;
            transition: color .2s;
            line-height: 1;
        }
        .pw-eye:hover { color: var(--p1); }

        /* ── REMEMBER + FORGOT ── */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.4rem;
            margin-top: .2rem;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: .45rem;
            font-size: .78rem;
            color: var(--text-b);
            cursor: pointer;
            user-select: none;
        }

        .remember input[type="checkbox"] {
            width: 15px; height: 15px;
            padding: 0;
            border: 1.5px solid var(--border);
            border-radius: 4px;
            background: var(--input-bg);
            cursor: pointer;
            accent-color: var(--p1);
            flex-shrink: 0;
        }

        .forgot-link {
            font-size: .78rem;
            color: var(--p1);
            font-weight: 600;
            text-decoration: none;
            transition: color .2s;
        }
        .forgot-link:hover { color: var(--p2); }

        /* ── SUBMIT BUTTON ── */
        .btn {
            width: 100%;
            padding: .88rem 1.5rem;
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: 'Sora', sans-serif;
            font-size: .92rem; font-weight: 600;
            cursor: pointer;
            letter-spacing: .01em;
            transition: transform .15s, box-shadow .2s;
            box-shadow: 0 6px 24px rgba(109,40,217,.38);
            position: relative; overflow: hidden;
        }
        .btn::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(255,255,255,.12) 0%, transparent 100%);
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(109,40,217,.48); }
        .btn:active { transform: translateY(0); }

        /* ── DIVIDER ── */
        .or-divider {
            display: flex;
            align-items: center;
            gap: .8rem;
            margin: 1.4rem 0;
            font-size: .74rem;
            color: var(--text-s);
            font-weight: 500;
        }
        .or-divider::before,
        .or-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* ── FOOTER ── */
        .card-foot {
            text-align: center;
            margin-top: 1.3rem;
            padding-top: 1.3rem;
            border-top: 1px solid var(--border);
            font-size: .8rem; color: var(--text-s);
        }
        .card-foot a {
            color: var(--p1); font-weight: 600;
            text-decoration: none; transition: color .2s;
        }
        .card-foot a:hover { color: var(--p2); }

        /* Back link */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            margin-top: 1.5rem;
            font-size: .8rem;
            color: rgba(109,40,217,.6);
            text-decoration: none;
            font-weight: 500;
            transition: color .2s;
            animation: fadeUp .65s .2s cubic-bezier(.22,1,.36,1) both;
        }
        .back-link:hover { color: var(--p2); }

        /* ── ANIMATIONS ── */
        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 500px) {
            .card-inner { padding: 1.8rem 1.4rem; }
            .brand-name  { font-size: 1.6rem; }
            .form-options { flex-direction: column; align-items: flex-start; gap: .6rem; }
        }
    </style>
</head>
<body>

<!-- Animated background -->
<div class="bg-layer">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
    <div class="blob blob-4"></div>
    <div class="blob blob-5"></div>
</div>

<div class="page">

    <!-- Brand -->
    <div class="brand">
        <div class="brand-icon-wrap"><i class="fas fa-industry"></i></div>
        <div class="brand-name">Monitorbizz</div>
        <div class="brand-sub">Welcome back! Sign in to continue</div>
    </div>

    <!-- Card -->
    <div class="card">
        <div class="card-bar"></div>
        <div class="card-inner">

            {{-- Error Alert --}}
            @if ($errors->any())
                <div class="alert-err">
                    <div class="alert-err-icon"><i class="fas fa-exclamation-circle"></i></div>
                    <div>
                        <div class="alert-err-title">Please check your credentials</div>
                        @foreach ($errors->all() as $error)
                            <div class="alert-err-msg">{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="sec-head">
                <div class="sec-icon"><i class="fas fa-sign-in-alt"></i></div>
                Sign In
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="fg">
                    <label for="email">Email Address</label>
                    <div class="input-wrap">
                        <i class="fas fa-envelope lead-icon"></i>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="rajesh@kumarworks.com"
                               class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                               required>
                    </div>
                    @error('email')
                        <div class="ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="fg">
                    <label for="password">Password</label>
                    <div class="input-wrap pw-wrap">
                        <i class="fas fa-lock lead-icon"></i>
                        <input type="password"
                               id="password"
                               name="password"
                               placeholder="Enter your password"
                               class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                               required>
                        <button type="button" class="pw-eye" onclick="togglePw()">
                            <i id="pw-icon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-options">
                    <label class="remember">
                        <input type="checkbox" id="remember" name="remember">
                        Remember me
                    </label>
                    <a href="#" class="forgot-link" onclick="alert('Forgot password functionality will be implemented soon.')">
                        Forgot password?
                    </a>
                </div>

                <button type="submit" class="btn">
                    <i class="fas fa-sign-in-alt"></i>&nbsp; Sign In
                </button>

                <div class="card-foot">
                    Don't have an account? <a href="{{ route('register') }}">Start free trial</a>
                </div>

            </form>
        </div>
    </div>

    <a href="/" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Homepage
    </a>

</div>

<script>
function togglePw() {
    const f = document.getElementById('password');
    const i = document.getElementById('pw-icon');
    f.type = f.type === 'password' ? 'text' : 'password';
    i.classList.toggle('fa-eye');
    i.classList.toggle('fa-eye-slash');
}
</script>
</body>
</html>