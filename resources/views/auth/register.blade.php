<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Monitorbizz</title>
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
            --p6: #f5f3ff;
            --text-h:  #1e1344;
            --text-b:  #3b2f6e;
            --text-s:  #6d5fa0;
            --border:  #ddd6fe;
            --input-bg: #faf8ff;
            --err:     #dc2626;
            --ok:      #059669;
        }

        html, body {
            min-height: 100%;
            font-family: 'DM Sans', sans-serif;
            color: var(--text-h);
            overflow-x: hidden;
        }

        /* ── ANIMATED BACKGROUND ── */
        body { background: #f0ebff; position: relative; }

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
            background: linear-gradient(135deg, #e8e0ff 0%, #f3eeff 30%, #e0d4ff 60%, #ede5ff 100%);
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(70px);
            opacity: 0.82;
            will-change: transform;
        }

        .blob-1 {
            width: 600px; height: 600px;
            background: radial-gradient(circle, #b197fc 0%, #845ef7 50%, transparent 70%);
            top: -150px; left: -100px;
            animation: move1 6s ease-in-out infinite alternate;
        }
        .blob-2 {
            width: 520px; height: 520px;
            background: radial-gradient(circle, #d0bfff 0%, #9775fa 50%, transparent 70%);
            bottom: -100px; right: -100px;
            animation: move2 7s ease-in-out infinite alternate;
        }
        .blob-3 {
            width: 420px; height: 420px;
            background: radial-gradient(circle, #c5f6fa 0%, #a9e8f5 40%, transparent 70%);
            top: 35%; left: 50%;
            animation: move3 5s ease-in-out infinite alternate;
        }
        .blob-4 {
            width: 380px; height: 380px;
            background: radial-gradient(circle, #fcc2d7 0%, #e599f7 50%, transparent 70%);
            top: 15%; right: 8%;
            animation: move4 8s ease-in-out infinite alternate;
        }
        .blob-5 {
            width: 340px; height: 340px;
            background: radial-gradient(circle, #a9e8f5 0%, #74c0fc 50%, transparent 70%);
            bottom: 20%; left: 3%;
            animation: move5 6.5s ease-in-out infinite alternate;
        }

        @keyframes move1 {
            0%   { transform: translate(0px,   0px)   scale(1);    }
            100% { transform: translate(200px, 140px) scale(1.18); }
        }
        @keyframes move2 {
            0%   { transform: translate(0px,    0px)    scale(1);    }
            100% { transform: translate(-180px, -130px) scale(1.14); }
        }
        @keyframes move3 {
            0%   { transform: translate(0px,   0px)   scale(1);    }
            100% { transform: translate(-160px, 120px) scale(1.22); }
        }
        @keyframes move4 {
            0%   { transform: translate(0px,  0px)  scale(1);    }
            100% { transform: translate(-120px, 160px) scale(1.12); }
        }
        @keyframes move5 {
            0%   { transform: translate(0px, 0px)  scale(1);    }
            100% { transform: translate(140px, -110px) scale(1.16); }
        }

        /* ── PAGE LAYOUT ── */
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

        /* ── BRAND HEADER ── */
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

        /* ── CARD ── */
        .card {
            width: 100%;
            max-width: 820px;
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

        .card-bar {
            height: 3px;
            background: linear-gradient(90deg, #7c3aed, #a78bfa, #c084fc, #7c3aed);
            background-size: 300% 100%;
            animation: barMove 3s linear infinite;
        }
        @keyframes barMove { 0% { background-position: 0% 0; } 100% { background-position: 300% 0; } }

        .card-inner { padding: 2.5rem 2.8rem; }

        /* ── TWO-COLUMN GRID ── */
        .two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 2.8rem;
            align-items: start;
        }

        /* Vertical rule between columns */
        .col-rule {
            grid-column: 1 / -1;
            display: none; /* handled via border instead */
        }

        .two-col > div:first-child {
            border-right: 1.5px solid var(--border);
            padding-right: 2.8rem;
        }

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
            margin-bottom: 1.25rem;
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
        .fg { margin-bottom: .9rem; }

        label {
            display: block;
            font-size: .78rem; font-weight: 500;
            color: var(--text-b);
            margin-bottom: .35rem;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: .65rem .9rem;
            background: var(--input-bg);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: .85rem;
            color: var(--text-h);
            outline: none;
            transition: border-color .2s, box-shadow .2s, background .2s;
            -webkit-appearance: none;
            appearance: none;
        }

        input::placeholder, textarea::placeholder { color: #c4b5fd; }

        input:focus, select:focus, textarea:focus {
            border-color: var(--p1);
            background: #fff;
            box-shadow: 0 0 0 3.5px rgba(124,58,237,.13);
        }

        .is-invalid { border-color: var(--err) !important; }
        .is-invalid:focus { box-shadow: 0 0 0 3px rgba(220,38,38,.12) !important; }

        textarea { resize: vertical; min-height: 62px; }

        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='7' viewBox='0 0 11 7'%3E%3Cpath d='M1 1l4.5 4.5L10 1' stroke='%237c3aed' stroke-width='1.5' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right .8rem center;
            padding-right: 2.2rem;
            cursor: pointer;
        }
        select:disabled { opacity: .5; cursor: not-allowed; }

        .grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: .7rem; }

        /* Password */
        .pw-wrap { position: relative; }
        .pw-wrap input { padding-right: 2.6rem; }
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

        /* Helpers */
        .hint { font-size: .71rem; color: var(--text-s); margin-top: .28rem; }
        .ferr {
            font-size: .71rem; color: var(--err);
            margin-top: .28rem;
            display: flex; align-items: center; gap: .22rem;
        }

        /* ── INVITE BOX ── */
        .invite-box {
            display: flex; align-items: center; gap: .8rem;
            background: linear-gradient(135deg, var(--p5), #f0eaff);
            border: 1.5px solid var(--p4);
            border-radius: 14px;
            padding: .9rem 1rem;
            margin-bottom: 1.4rem;
        }
        .invite-ico {
            width: 38px; height: 38px; flex-shrink: 0;
            border-radius: 10px;
            background: var(--p1);
            display: flex; align-items: center; justify-content: center;
        }
        .invite-ico i { color: #fff; font-size: .85rem; }
        .invite-title { font-family: 'Sora',sans-serif; font-size: .85rem; font-weight: 600; color: var(--p2); }
        .invite-sub   { font-size: .75rem; color: var(--text-s); margin-top: .1rem; }

        /* ── PRICING BOX ── */
        .price-box {
            background: linear-gradient(135deg, var(--p5) 0%, #f3eeff 100%);
            border: 1.5px solid var(--p4);
            border-radius: 12px;
            padding: .9rem 1rem;
            margin-top: .5rem;
            display: none;
        }
        .price-title {
            font-family: 'Sora', sans-serif;
            font-size: .72rem; font-weight: 600;
            color: var(--p1); text-transform: uppercase;
            letter-spacing: .07em; margin-bottom: .6rem;
        }
        .price-row {
            display: flex; justify-content: space-between;
            font-size: .78rem; color: var(--text-b);
            padding: .15rem 0;
        }
        .price-row.total {
            font-weight: 700;
            font-family: 'Sora', sans-serif;
            color: var(--p2); font-size: .88rem;
            border-top: 1px solid var(--p4);
            margin-top: .4rem; padding-top: .5rem;
        }

        /* ── REP FEEDBACK ── */
        .rep-fb {
            font-size: .8rem;
            font-weight: 500;
            margin-top: .4rem;
            align-items: center;
            gap: .35rem;
            line-height: 1.4;
        }
        .rep-fb.ok       { display: flex; }
        .rep-fb.ok i,
        .rep-fb.ok span  { color: #059669 !important; }
        .rep-fb.bad      { display: flex; }
        .rep-fb.bad i,
        .rep-fb.bad span { color: #dc2626 !important; }

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
            margin-top: .5rem;
        }
        .btn::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(255,255,255,.12) 0%, transparent 100%);
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(109,40,217,.48); }
        .btn:active { transform: translateY(0); }

        /* ── FOOTER ── */
        .card-foot {
            text-align: center;
            margin-top: 1.2rem;
            padding-top: 1.2rem;
            border-top: 1px solid var(--border);
            font-size: .8rem; color: var(--text-s);
        }
        .card-foot a {
            color: var(--p1); font-weight: 600;
            text-decoration: none; transition: color .2s;
        }
        .card-foot a:hover { color: var(--p2); }

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
        @media (max-width: 680px) {
            .two-col {
                grid-template-columns: 1fr;
            }
            .two-col > div:first-child {
                border-right: none;
                border-bottom: 1.5px solid var(--border);
                padding-right: 0;
                padding-bottom: 1.4rem;
                margin-bottom: 1.4rem;
            }
            .card-inner { padding: 1.8rem 1.4rem; }
            .brand-name { font-size: 1.6rem; }
            .grid2      { grid-template-columns: 1fr; gap: 0; }
        }
    </style>
</head>
<body>

<div class="bg-layer">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
    <div class="blob blob-4"></div>
    <div class="blob blob-5"></div>
</div>

<div class="page">

    <div class="brand">
        <div class="brand-icon-wrap"><i class="fas fa-industry"></i></div>
        <div class="brand-name">Monitorbizz</div>
        <div class="brand-sub">
            @if($invitation)
                You've been invited to join <strong>{{ $invitation->business->name }}</strong>
            @else
                Create your account and start your free trial
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-bar"></div>
        <div class="card-inner">

            <form method="POST" action="{{ route('register') }}">
                @csrf

                @if($invitation)
                    {{-- ── INVITATION FLOW ── --}}
                    <input type="hidden" name="token" value="{{ $invitation->token }}">

                    <div class="invite-box">
                        <div class="invite-ico"><i class="fas fa-user-tag"></i></div>
                        <div>
                            <div class="invite-title">Team Invitation</div>
                            <div class="invite-sub">Team: {{ $invitation->getTeamDisplayName() }}</div>
                        </div>
                    </div>

                    <div class="sec-head">
                        <div class="sec-icon"><i class="fas fa-user"></i></div>
                        Your Details
                    </div>

                    <div class="fg">
                        <label>Full Name</label>
                        <input name="name" type="text" required
                            class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                            placeholder="Rajesh Kumar" value="{{ old('name') }}">
                        @error('name')<div class="ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="fg">
                        <label>Email Address</label>
                        <input name="email" type="email" required readonly
                            value="{{ old('email', $invitation->email ?? '') }}"
                            placeholder="{{ $invitation->email }}">
                        @error('email')<div class="ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="fg">
                        <label>Password</label>
                        <div class="pw-wrap">
                            <input id="pw1" name="password" type="password" required
                                class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                                placeholder="Minimum 8 characters">
                            <button type="button" class="pw-eye" onclick="togglePw('pw1','ic1')"><i id="ic1" class="fas fa-eye"></i></button>
                        </div>
                        @error('password')<div class="ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div class="fg">
                        <label>Confirm Password</label>
                        <div class="pw-wrap">
                            <input id="pw2" name="password_confirmation" type="password" required placeholder="Repeat password">
                            <button type="button" class="pw-eye" onclick="togglePw('pw2','ic2')"><i id="ic2" class="fas fa-eye"></i></button>
                        </div>
                    </div>

                    <button type="submit" class="btn"><i class="fas fa-user-plus"></i>&nbsp; Join Team</button>

                @else
                    {{-- ── REGISTRATION FLOW (two columns) ── --}}
                    <div class="two-col">

                        {{-- LEFT: Business Details --}}
                        <div>
                            <div class="sec-head">
                                <div class="sec-icon"><i class="fas fa-building"></i></div>
                                Business Details
                            </div>

                            <div class="fg">
                                <label>Business Name</label>
                                <input name="business_name" type="text" required
                                    class="{{ $errors->has('business_name') ? 'is-invalid' : '' }}"
                                    placeholder="e.g., Kumar Metal Works"
                                    value="{{ old('business_name') }}">
                                @error('business_name')<div class="ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>

                            <div class="fg">
                                <label>Phone Number</label>
                                <input id="business_phone" name="business_phone" type="tel" required
                                    class="{{ $errors->has('business_phone') ? 'is-invalid' : '' }}"
                                    placeholder="9876543210"
                                    value="{{ old('business_phone') }}"
                                    pattern="[6-9][0-9]{9}" maxlength="10">
                                <div class="hint">10-digit mobile number starting with 6–9</div>
                                @error('business_phone')<div class="ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>

                            <div class="fg">
                                <label>Address</label>
                                <textarea name="business_address" rows="2" required
                                    class="{{ $errors->has('business_address') ? 'is-invalid' : '' }}"
                                    placeholder="Industrial Area, Phase 2">{{ old('business_address') }}</textarea>
                                @error('business_address')<div class="ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>

                            <div class="fg">
                                <div class="grid2">
                                    <div>
                                        <label>State</label>
                                        <select id="business_state" name="business_state" required
                                            class="{{ $errors->has('business_state') ? 'is-invalid' : '' }}">
                                            <option value="">Select State</option>
                                            @foreach($states as $state)
                                                <option value="{{ $state->name }}" {{ old('business_state') == $state->name ? 'selected' : '' }}>
                                                    {{ $state->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('business_state')<div class="ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                                    </div>
                                    <div>
                                        <label>City</label>
                                        <select id="business_city" name="business_city" required disabled
                                            class="{{ $errors->has('business_city') ? 'is-invalid' : '' }}">
                                            <option value="">Select City</option>
                                        </select>
                                        @error('business_city')<div class="ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="fg">
                                <label>Sales Representative ID</label>
                                <input id="sales_representative_id" name="sales_representative_id" type="text" required
                                    class="{{ $errors->has('sales_representative_id') ? 'is-invalid' : '' }}"
                                    placeholder="e.g., MNBZ7A9X2K4Q"
                                    value="{{ old('sales_representative_id') }}">
                                <div id="rep-feedback" class="rep-fb" style="display:none;">
                                    <i id="rep-icon" class="fas fa-check-circle"></i>
                                    <span id="rep-text"></span>
                                </div>
                                <div class="hint">Enter the ID provided by your sales representative</div>
                                @error('sales_representative_id')<div class="ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- RIGHT: Plan + Owner --}}
                        <div>
                            <div class="sec-head">
                                <div class="sec-icon"><i class="fas fa-crown"></i></div>
                                Plan &amp; Owner Details
                            </div>

                            <div class="fg">
                                <label>Choose Your Plan</label>
                                <select id="plan_id" name="plan_id" required
                                    class="{{ $errors->has('plan_id') ? 'is-invalid' : '' }}">
                                    <option value="">Select a plan</option>
                                    @foreach($plans as $plan)
                                        <option value="{{ $plan->id }}"
                                            data-price="{{ $plan->price_per_user }}"
                                            data-min="{{ $plan->min_users }}"
                                            data-max="{{ $plan->max_users }}"
                                            {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                            {{ $plan->name }} — ₹{{ number_format($plan->price_per_user, 2) }}/user/month
                                        </option>
                                    @endforeach
                                </select>
                                <div class="hint">You can upgrade anytime</div>
                                @error('plan_id')<div class="ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>

                            <div id="user-count-section" style="display:none;" class="fg">
                                <label>Number of Users</label>
                                <input id="user_count" name="user_count" type="number" min="1" required
                                    class="{{ $errors->has('user_count') ? 'is-invalid' : '' }}"
                                    value="{{ old('user_count', 1) }}">
                                <div class="hint">Between <strong id="min-users">1</strong> – <strong id="max-users">1</strong> users</div>
                                @error('user_count')<div class="ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                            </div>

                            <div id="pricing-section" class="price-box">
                                <div class="price-title">Pricing Summary</div>
                                <div class="price-row"><span>Plan</span><span id="s-plan">—</span></div>
                                <div class="price-row"><span>Price/user</span><span>₹<span id="s-price">0</span>/mo</span></div>
                                <div class="price-row"><span>Users</span><span id="s-users">0</span></div>
                                <div class="price-row total"><span>Total</span><span>₹<span id="s-total">0</span>/month</span></div>
                            </div>

                            <div style="margin-top:1.2rem;">
                                <div class="sec-head">
                                    <div class="sec-icon"><i class="fas fa-user"></i></div>
                                    Owner Details
                                </div>

                                <div class="fg">
                                    <label>Full Name</label>
                                    <input name="name" type="text" required
                                        class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                                        placeholder="Rajesh Kumar"
                                        value="{{ old('name') }}">
                                    @error('name')<div class="ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                                </div>

                                <div class="fg">
                                    <label>Email Address</label>
                                    <input name="email" type="email" required
                                        class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                                        placeholder="rajesh@kumarworks.com"
                                        value="{{ old('email') }}">
                                    @error('email')<div class="ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                                </div>

                                <div class="fg">
                                    <label>Password</label>
                                    <div class="pw-wrap">
                                        <input id="pw1" name="password" type="password" required
                                            class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                                            placeholder="Minimum 8 characters">
                                        <button type="button" class="pw-eye" onclick="togglePw('pw1','ic1')"><i id="ic1" class="fas fa-eye"></i></button>
                                    </div>
                                    @error('password')<div class="ferr"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>@enderror
                                </div>

                                <div class="fg">
                                    <label>Confirm Password</label>
                                    <div class="pw-wrap">
                                        <input id="pw2" name="password_confirmation" type="password" required placeholder="Repeat password">
                                        <button type="button" class="pw-eye" onclick="togglePw('pw2','ic2')"><i id="ic2" class="fas fa-eye"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div><!-- /two-col -->

                    <button type="submit" class="btn" style="margin-top:1.6rem;">
                        <i class="fas fa-rocket"></i>&nbsp; Create My Account
                    </button>

                @endif

                <div class="card-foot">
                    Already have an account? <a href="{{ route('login') }}">Sign in</a>
                </div>

            </form>
        </div>
    </div>

</div><!-- /page -->

<script>
function togglePw(fid, iid) {
    const f = document.getElementById(fid), i = document.getElementById(iid);
    f.type = f.type === 'password' ? 'text' : 'password';
    i.classList.toggle('fa-eye');
    i.classList.toggle('fa-eye-slash');
}

document.getElementById('business_phone')?.addEventListener('input', function () {
    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
});

const planSel = document.getElementById('plan_id');
if (planSel) {
    planSel.addEventListener('change', function () {
        const opt   = this.options[this.selectedIndex];
        const ucSec = document.getElementById('user-count-section');
        const prSec = document.getElementById('pricing-section');
        if (this.value) {
            const price = parseFloat(opt.dataset.price) || 0;
            const min   = parseInt(opt.dataset.min)     || 1;
            const max   = parseInt(opt.dataset.max)     || 1;
            const uc    = document.getElementById('user_count');
            ucSec.style.display = 'block';
            prSec.style.display = 'block';
            uc.min = min; uc.max = max;
            uc.value = Math.max(min, Math.min(parseInt(uc.value) || min, max));
            document.getElementById('min-users').textContent = min;
            document.getElementById('max-users').textContent = max;
            calcPrice(opt.text.split('—')[0].trim(), price, uc.value);
        } else {
            ucSec.style.display = prSec.style.display = 'none';
        }
    });
}

document.getElementById('user_count')?.addEventListener('input', function () {
    const opt = planSel.options[planSel.selectedIndex];
    if (opt?.value) calcPrice(opt.text.split('—')[0].trim(), parseFloat(opt.dataset.price) || 0, this.value);
});

function calcPrice(name, ppu, count) {
    const u = parseInt(count) || 0;
    document.getElementById('s-plan').textContent  = name;
    document.getElementById('s-price').textContent = ppu.toFixed(2);
    document.getElementById('s-users').textContent = u;
    document.getElementById('s-total').textContent = (ppu * u).toFixed(2);
}

let repTimer;
document.getElementById('sales_representative_id')?.addEventListener('input', function () {
    clearTimeout(repTimer);
    const id = this.value.trim();
    const fb = document.getElementById('rep-feedback');
    const tx = document.getElementById('rep-text');
    const ic = document.getElementById('rep-icon');

    if (!id) {
        fb.style.display = 'none';
        fb.classList.remove('ok', 'bad');
        return;
    }

    repTimer = setTimeout(() => {
        fetch(`/api/validate-representative-id/${id}`)
            .then(r => r.json())
            .then(d => {
                fb.classList.remove('ok', 'bad');
                const label = d.valid
                    ? (d.full_name || d.name || 'Representative Verified')
                    : (d.message || d.error || 'Invalid Representative ID');
                tx.textContent = label;
                if (d.valid) {
                    ic.className = 'fas fa-check-circle';
                    fb.classList.add('ok');
                } else {
                    ic.className = 'fas fa-times-circle';
                    fb.classList.add('bad');
                }
                fb.style.display = 'flex';
            })
            .catch(() => {
                fb.style.display = 'none';
                fb.classList.remove('ok', 'bad');
            });
    }, 500);
});

const stateSel = document.getElementById('business_state');
const citySel  = document.getElementById('business_city');
if (stateSel && citySel) {
    stateSel.addEventListener('change', function () {
        citySel.innerHTML = '<option value="">Select City</option>';
        citySel.disabled  = true;
        if (this.value) {
            fetch(`/api/cities/${encodeURIComponent(this.value)}`)
                .then(r => r.json())
                .then(cities => {
                    cities.forEach(c => citySel.appendChild(new Option(c, c)));
                    citySel.disabled = false;
                })
                .catch(() => { citySel.innerHTML = '<option value="">Error loading cities</option>'; });
        }
    });
}
</script>
</body>
</html>