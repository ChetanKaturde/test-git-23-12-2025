<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Monitorbizz</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .gradient-bg { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); }
        .logo-text { font-family: 'Inter', system-ui, sans-serif; }
        .auth-bg {
            background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #f5576c);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            position: relative;
        }
        .auth-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.3) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
        }
        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 
                0 8px 32px rgba(31, 38, 135, 0.37),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }
        .glass-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            animation: shimmer 3s ease-in-out infinite;
        }
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        .glass-form {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="auth-bg min-h-screen p-4">
    <div class="flex items-center justify-center min-h-screen py-8">
    <div class="w-full max-w-md relative z-10">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="gradient-bg w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-industry text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 logo-text">Monitorbizz</h1>
            <p class="text-gray-600 mt-2">Sign in to your account</p>
        </div>

        <!-- Form Card -->
        <div class="glass-card rounded-2xl p-8 relative overflow-hidden">
            <div class="glass-form rounded-xl p-6 relative z-10">
            <!-- Error Display -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                        <div>
                            <p class="text-red-800 font-medium">Please check your credentials</p>
                            @foreach ($errors->all() as $error)
                                <p class="text-red-700 text-sm">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" 
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('email') border-red-500 @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="Enter your email address"
                               required>
                    </div>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" 
                               class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('password') border-red-500 @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="Enter your password"
                               required>
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                            <i id="toggleIcon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                    </div>
                    <a href="#" onclick="alert('Forgot password functionality will be implemented soon.')" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                        Forgot password?
                    </a>
                </div>
                
                <button type="submit" class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors font-medium">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Sign In
                </button>
            </form>

            <div class="text-center pt-6 border-t border-gray-200 mt-6">
                <p class="text-gray-600 text-sm">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Start free trial</a>
                </p>
            </div>
            </div>
        </div>

        <!-- Back to Homepage -->
        <div class="text-center mt-6">
            <a href="/" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Homepage
            </a>
        </div>
    </div>
    </div>

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