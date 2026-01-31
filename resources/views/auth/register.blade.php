<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Monitorbizz</title>
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
            <p class="text-gray-600 mt-2">
                @if($invitation)
                    Join {{ $invitation->business->name }}
                @else
                    Start your free trial
                @endif
            </p>
        </div>

        <!-- Form Card -->
        <div class="glass-card rounded-2xl p-8 relative overflow-hidden">
            <div class="glass-form rounded-xl p-6 relative z-10">
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf
                @if($invitation)
                    <input type="hidden" name="token" value="{{ $invitation->token }}">
                    
                    <!-- Invitation Info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="fas fa-user-tag text-blue-600 mr-3"></i>
                            <div>
                                <p class="text-blue-800 font-medium">Team Invitation</p>
                                <p class="text-blue-700 text-sm">Team: {{ $invitation->getTeamDisplayName() }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Business Details -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-building text-indigo-600 mr-2"></i>
                            Business Details
                        </h3>
                        
                        <div>
                            <label for="business_name" class="block text-sm font-medium text-gray-700 mb-2">Business Name</label>
                            <input id="business_name" name="business_name" type="text" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('business_name') border-red-500 @enderror"
                                   placeholder="e.g., Kumar Metal Works" value="{{ old('business_name') }}">
                            @error('business_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="business_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input id="business_phone" name="business_phone" type="tel" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('business_phone') border-red-500 @enderror"
                                   placeholder="9876543210" value="{{ old('business_phone') }}" 
                                   pattern="[6-9][0-9]{9}" maxlength="10">
                            <p class="text-gray-500 text-xs mt-1">10-digit mobile number starting with 6-9</p>
                            @error('business_phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="business_address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea id="business_address" name="business_address" rows="2" required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('business_address') border-red-500 @enderror"
                                      placeholder="Industrial Area, Phase 2, Mumbai">{{ old('business_address') }}</textarea>
                            @error('business_address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="plan_id" class="block text-sm font-medium text-gray-700 mb-2">Choose Your Plan</label>
                            <select id="plan_id" name="plan_id" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('plan_id') border-red-500 @enderror">
                                <option value="">Select a plan</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" data-price="{{ $plan->price_per_user }}" data-min="{{ $plan->min_users }}" data-max="{{ $plan->max_users }}"
                                            {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} - ₹{{ number_format($plan->price_per_user, 2) }}/user/month
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-gray-500 text-xs mt-1">You can upgrade anytime</p>
                            @error('plan_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="user-count-section" style="display: none;">
                            <label for="user_count" class="block text-sm font-medium text-gray-700 mb-2">Number of Users</label>
                            <input id="user_count" name="user_count" type="number" min="1" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('user_count') border-red-500 @enderror"
                                   value="{{ old('user_count', 1) }}">
                            <p class="text-gray-500 text-xs mt-1" id="user-count-help">Select between <span id="min-users">1</span> - <span id="max-users">1</span> users</p>
                            @error('user_count')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="pricing-section" class="bg-indigo-50 border border-indigo-200 rounded-lg p-4" style="display: none;">
                            <h4 class="text-sm font-semibold text-indigo-800 mb-2">Pricing Summary</h4>
                            <div class="space-y-1 text-sm">
                                <div class="flex justify-between">
                                    <span>Plan:</span>
                                    <span id="selected-plan">None</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Price per user:</span>
                                    <span>₹<span id="price-per-user">0</span>/month</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Users:</span>
                                    <span id="selected-users">0</span>
                                </div>
                                <hr class="border-indigo-300">
                                <div class="flex justify-between font-semibold">
                                    <span>Total:</span>
                                    <span>₹<span id="total-price">0</span>/month</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="sales_representative_id" class="block text-sm font-medium text-gray-700 mb-2">Sales Representative ID</label>
                            <input id="sales_representative_id" name="sales_representative_id" type="text" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('sales_representative_id') border-red-500 @enderror"
                                   placeholder="e.g., MNBZ7A9X2K4Q" value="{{ old('sales_representative_id') }}">
                            <div id="representative-name" class="mt-2 text-sm text-green-600 hidden">
                                <i class="fas fa-check-circle mr-1"></i>
                                <span id="rep-name-text"></span>
                            </div>
                            <p class="text-gray-500 text-xs mt-1">Enter the ID provided by your sales representative</p>
                            @error('sales_representative_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <hr class="border-gray-200">
                @endif

                <!-- User Details -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user text-indigo-600 mr-2"></i>
                        {{ $invitation ? 'Your Details' : 'Owner Details' }}
                    </h3>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input id="name" name="name" type="text" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('name') border-red-500 @enderror"
                               placeholder="Rajesh Kumar" value="{{ old('name') }}">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input id="email" name="email" type="email" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('email') border-red-500 @enderror"
                               placeholder="{{ $invitation ? $invitation->email : 'rajesh@kumarworks.com' }}" 
                               value="{{ old('email', $invitation->email ?? '') }}"
                               {{ $invitation ? 'readonly' : '' }}>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" required
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors @error('password') border-red-500 @enderror"
                                   placeholder="Minimum 8 characters">
                            <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <i id="password-icon" class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <div class="relative">
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                   placeholder="Repeat your password">
                            <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                <i id="password_confirmation-icon" class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors font-medium">
                    <i class="fas fa-{{ $invitation ? 'user-plus' : 'rocket' }} mr-2"></i>
                    {{ $invitation ? 'Join Team' : 'Create My Account' }}
                </button>

                <div class="text-center pt-4 border-t border-gray-200">
                    <p class="text-gray-600 text-sm">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Sign in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Phone number formatting
        document.getElementById('business_phone')?.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
        });

        // Plan selection and pricing calculation
        document.getElementById('plan_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const userCountSection = document.getElementById('user-count-section');
            const pricingSection = document.getElementById('pricing-section');

            if (this.value) {
                const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                const minUsers = parseInt(selectedOption.getAttribute('data-min')) || 1;
                const maxUsers = parseInt(selectedOption.getAttribute('data-max')) || 1;

                // Show user count section
                userCountSection.style.display = 'block';
                pricingSection.style.display = 'block';

                // Update user count constraints
                const userCountInput = document.getElementById('user_count');
                userCountInput.min = minUsers;
                userCountInput.max = maxUsers;
                userCountInput.value = Math.max(minUsers, Math.min(parseInt(userCountInput.value) || minUsers, maxUsers));

                // Update help text
                document.getElementById('min-users').textContent = minUsers;
                document.getElementById('max-users').textContent = maxUsers;

                // Update pricing
                updatePricing(selectedOption.text.split(' - ')[0], price, userCountInput.value);
            } else {
                userCountSection.style.display = 'none';
                pricingSection.style.display = 'none';
            }
        });

        // User count change
        document.getElementById('user_count').addEventListener('input', function() {
            const planSelect = document.getElementById('plan_id');
            const selectedOption = planSelect.options[planSelect.selectedIndex];
            if (selectedOption && selectedOption.value) {
                const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                updatePricing(selectedOption.text.split(' - ')[0], price, this.value);
            }
        });

        function updatePricing(planName, pricePerUser, userCount) {
            const users = parseInt(userCount) || 0;
            const total = pricePerUser * users;

            document.getElementById('selected-plan').textContent = planName;
            document.getElementById('price-per-user').textContent = pricePerUser.toFixed(2);
            document.getElementById('selected-users').textContent = users;
            document.getElementById('total-price').textContent = total.toFixed(2);
        }

        // Sales Representative ID validation
        let repIdTimeout;
        document.getElementById('sales_representative_id')?.addEventListener('input', function(e) {
            clearTimeout(repIdTimeout);
            const repId = this.value.trim();
            const nameDiv = document.getElementById('representative-name');
            const nameText = document.getElementById('rep-name-text');

            if (repId.length === 0) {
                nameDiv.classList.add('hidden');
                nameText.textContent = '';
                return;
            }

            repIdTimeout = setTimeout(() => {
                fetch(`/api/validate-representative-id/${repId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.valid) {
                            nameText.textContent = data.name;
                            nameDiv.classList.remove('hidden', 'text-red-600');
                            nameDiv.classList.add('text-green-600');
                            nameDiv.querySelector('i').className = 'fas fa-check-circle mr-1';
                        } else {
                            nameText.textContent = data.message;
                            nameDiv.classList.remove('hidden', 'text-green-600');
                            nameDiv.classList.add('text-red-600');
                            nameDiv.querySelector('i').className = 'fas fa-times-circle mr-1';
                        }
                    })
                    .catch(error => {
                        console.error('Error validating representative ID:', error);
                        nameDiv.classList.add('hidden');
                    });
            }, 500);
        });
    </script>
</body>
</html>