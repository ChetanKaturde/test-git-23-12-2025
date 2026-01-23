<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Super Admin Login - Monitorbizz</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-dark: #4f46e5;
            --secondary-color: #8b5cf6;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #06b6d4;
            --dark-bg: #0f172a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
            overflow: hidden;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: absolute;
            width: 150%;
            height: 150%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(99, 102, 241, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(99, 102, 241, 0.15) 0%, transparent 50%);
            animation: backgroundShift 20s ease-in-out infinite;
        }

        @keyframes backgroundShift {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(10px, 10px) rotate(5deg); }
        }

        /* Floating Shapes */
        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 20s ease-in-out infinite;
        }

        .shape-1 {
            top: 10%;
            left: 10%;
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            animation-delay: 0s;
        }

        .shape-2 {
            top: 60%;
            left: 80%;
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 30px;
            animation-delay: 5s;
        }

        .shape-3 {
            top: 80%;
            left: 20%;
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 20px;
            transform: rotate(45deg);
            animation-delay: 10s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 1.75rem 1.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 30% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 60%),
                radial-gradient(circle at 70% 50%, rgba(255, 255, 255, 0.05) 0%, transparent 60%);
        }

        .brand-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
            backdrop-filter: blur(10px);
            position: relative;
            animation: iconPulse 3s ease-in-out infinite;
        }

        @keyframes iconPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .brand-icon i {
            font-size: 1.75rem;
            color: white;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
        }

        .brand-text {
            font-size: 1.625rem;
            font-weight: 800;
            color: white;
            margin-bottom: 0.25rem;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .brand-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.875rem;
            font-weight: 500;
            position: relative;
        }

        .login-body {
            padding: 1.5rem 1.75rem;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .welcome-text h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--dark-bg);
            margin-bottom: 0.25rem;
        }

        .welcome-text p {
            color: #64748b;
            font-size: 0.875rem;
        }

        .form-group {
            margin-bottom: 1.125rem;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            color: var(--dark-bg);
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-label i {
            color: var(--primary-color);
            font-size: 0.9375rem;
        }

        .password-wrapper {
            position: relative;
        }

        .form-control {
            height: 48px;
            padding: 0.625rem 0.875rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .password-wrapper .form-control {
            padding-right: 2.75rem;
        }

        .password-toggle {
            position: absolute;
            right: 0.875rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            font-size: 1rem;
            transition: color 0.2s ease;
        }

        .password-toggle:hover {
            color: var(--primary-color);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            background: white;
            outline: none;
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
            background: #fef2f2;
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .invalid-feedback {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            margin-top: 0.5rem;
            font-size: 0.8125rem;
            color: var(--danger-color);
        }

        .btn-login {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            font-size: 0.9375rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
            margin-top: 1.5rem;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            padding: 1rem 1.5rem 1.25rem;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }

        .login-footer p {
            color: #64748b;
            font-size: 0.8125rem;
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 575.98px) {
            body {
                padding: 0.75rem;
            }

            .login-container {
                max-width: 100%;
                width: 100%;
                margin: 0 auto;
            }

            .login-card {
                width: 100%;
            }

            .login-header {
                padding: 1.5rem 1.25rem;
            }

            .brand-icon {
                width: 56px;
                height: 56px;
            }

            .brand-icon i {
                font-size: 1.625rem;
            }

            .brand-text {
                font-size: 1.5rem;
            }

            .brand-subtitle {
                font-size: 0.8125rem;
            }

            .login-body {
                padding: 1.25rem 1.25rem;
            }

            .welcome-text {
                margin-bottom: 1.25rem;
            }

            .welcome-text h2 {
                font-size: 1.125rem;
            }

            .welcome-text p {
                font-size: 0.8125rem;
            }

            .form-control {
                height: 46px;
                font-size: 0.875rem;
            }

            .btn-login {
                height: 46px;
                font-size: 0.875rem;
            }

            .login-footer {
                padding: 0.875rem 1.25rem 1rem;
            }

            .login-footer p {
                font-size: 0.75rem;
            }
        }

        @media (min-width: 576px) and (max-width: 767.98px) {
            .login-container {
                max-width: 480px;
            }
        }

        @media (min-width: 768px) {
            .login-container {
                max-width: 500px;
            }

            .login-body {
                padding: 1.75rem 2rem;
            }
        }

        /* Desktop - Make card fit better */
        @media (min-width: 1200px) {
            .login-container {
                max-width: 520px;
            }
        }
    </style>
</head>
<body>
    <!-- Floating Shapes -->
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>

    <!-- Login Container -->
    <div class="login-container">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <div class="brand-icon">
                    <i class="fas fa-crown"></i>
                </div>
                <h1 class="brand-text">Monitorbizz</h1>
                <p class="brand-subtitle">Super Admin Control Panel</p>
            </div>

            <!-- Body -->
            <div class="login-body">
                <div class="welcome-text">
                    <h2>Welcome Back!</h2>
                    <p>Sign in to access your admin dashboard</p>
                </div>

                <form method="POST" action="{{ route('superadmin.login.post') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>
                            Email Address
                        </label>
                        <input 
                            type="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            placeholder="admin@monitorbizz.com" 
                            required 
                            autofocus
                        >
                        @error('email')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i>
                            Password
                        </label>
                        <div class="password-wrapper">
                            <input 
                                type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                id="password" 
                                name="password" 
                                placeholder="Enter your password" 
                                required
                            >
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Sign In to Dashboard</span>
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="login-footer">
                <p>
                    <i class="fas fa-shield-alt me-1"></i>
                    Secured with enterprise-grade encryption
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>