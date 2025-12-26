<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Monitorbizz - Professional Invoicing & Quotations for Indian Businesses</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .gradient-bg { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); }
        .logo-text { font-family: 'Inter', system-ui, sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen" x-data="{ showContact: false }">
    <!-- Hero Section -->
    <div class="gradient-bg py-20">
        <div class="max-w-6xl mx-auto px-4 text-center text-white">
            <h1 class="text-5xl font-bold mb-4 logo-text">Monitorbizz</h1>
            <p class="text-2xl mb-4 font-semibold">Professional Invoicing & Quotations for Indian Businesses</p>
            <p class="text-lg mb-12 max-w-3xl mx-auto leading-relaxed opacity-90">
                GST-compliant quotes, invoices, and payments — ready in minutes. Free for up to 50 invoices/month.
            </p>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                <a href="{{ route('register') }}" 
                   class="bg-white text-indigo-600 font-semibold py-3 px-8 rounded-lg hover:bg-gray-100 transition-all duration-200 transform hover:scale-105">
                    Start Free Trial
                </a>
                <a href="#" 
                   class="bg-indigo-800 text-white font-semibold py-3 px-8 rounded-lg hover:bg-indigo-900 transition-all duration-200">
                    Watch 2-Min Demo
                </a>
            </div>
            
            <!-- Status Badge -->
            <div class="inline-flex items-center space-x-2 bg-green-500 text-white px-4 py-2 rounded-full">
                <div class="w-2 h-2 bg-white rounded-full animate-pulse"></div>
                <span class="font-semibold">50 invoices/month + 2 team members — forever free</span>
            </div>
        </div>
    </div>

    <!-- Target Audiences Section -->
    <div class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Perfect for Your Business Type</h2>
                <p class="text-gray-600 text-lg">Start with what you need, upgrade when you grow</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Service Businesses -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-100 p-8 rounded-xl border-2 border-indigo-200">
                    <div class="text-center mb-6">
                        <div class="text-4xl mb-4">📄</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Service Businesses</h3>
                        <p class="text-gray-600">Consultants, Freelancers, Agencies</p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg border border-indigo-200 mb-6">
                        <p class="text-gray-700 text-lg font-medium text-center">
                            "Send professional quotes, track payments, and get paid faster — no manufacturing complexity."
                        </p>
                    </div>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="text-green-500 font-bold">✅</div>
                            <span class="text-gray-700">Professional Quotations & Invoices</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="text-green-500 font-bold">✅</div>
                            <span class="text-gray-700">Payment Tracking & Reminders</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="text-green-500 font-bold">✅</div>
                            <span class="text-gray-700">Customer Management</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="text-green-500 font-bold">✅</div>
                            <span class="text-gray-700">GST-Compliant Reports</span>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-2xl font-bold text-indigo-600 mb-1">Forever Free</div>
                        <p class="text-sm text-gray-600">50 invoices/month, 2 team members</p>
                    </div>
                </div>
                
                <!-- Small Manufacturers -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-100 p-8 rounded-xl border-2 border-purple-200">
                    <div class="text-center mb-6">
                        <div class="text-4xl mb-4">🏭</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Small Manufacturers</h3>
                        <p class="text-gray-600">Workshops, Fabricators</p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg border border-purple-200 mb-6">
                        <p class="text-gray-700 text-lg font-medium text-center">
                            "Manage quotes, invoices, inventory, and work orders in one system."
                        </p>
                    </div>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="text-green-500 font-bold">✅</div>
                            <span class="text-gray-700">Everything in Service Plan</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="text-green-500 font-bold">✅</div>
                            <span class="text-gray-700">Inventory Management</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="text-green-500 font-bold">✅</div>
                            <span class="text-gray-700">Work Order Tracking</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="text-green-500 font-bold">✅</div>
                            <span class="text-gray-700">Purchase Orders</span>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600 mb-1">Paid Plans</div>
                        <p class="text-sm text-gray-600">Unlimited invoices, advanced reports, priority support</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Features Section -->
    <div class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Everything You Need for Professional Billing</h2>
                <p class="text-gray-600 text-lg">Get paid faster with automated workflows and professional documents</p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-3xl mb-4">✅</div>
                    <h3 class="text-xl font-semibold mb-2">Branded GST Invoices</h3>
                    <p class="text-gray-600">Your logo, address, T&C, and HSN codes on every PDF</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-3xl mb-4">✅</div>
                    <h3 class="text-xl font-semibold mb-2">Payment Tracking</h3>
                    <p class="text-gray-600">Record cash, UPI, or bank payments; see real-time balances</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-3xl mb-4">✅</div>
                    <h3 class="text-xl font-semibold mb-2">Aging Reports</h3>
                    <p class="text-gray-600">Know who owes you and for how long (0–30, 31–60, 60+ days)</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-3xl mb-4">✅</div>
                    <h3 class="text-xl font-semibold mb-2">Team Collaboration</h3>
                    <p class="text-gray-600">Invite up to 2 teammates for free; assign roles</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-3xl mb-4">✅</div>
                    <h3 class="text-xl font-semibold mb-2">Built for India</h3>
                    <p class="text-gray-600">Financial year support, GSTIN, PAN, multi-currency ready</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-3xl mb-4">🚀</div>
                    <h3 class="text-xl font-semibold mb-2">Get Paid Faster</h3>
                    <p class="text-gray-600">Automated reminders and professional follow-ups</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Trusted by Businesses Across India</h2>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="text-gray-700 mb-4 italic">"Finally, GST-compliant invoices that look professional. Our customers love the clean PDFs with our logo."</p>
                    <div class="font-semibold text-indigo-600">— Digital Marketing Agency, Mumbai</div>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="text-gray-700 mb-4 italic">"Aging reports helped us identify ₹2.5L in pending payments. Now we follow up systematically."</p>
                    <div class="font-semibold text-indigo-600">— Consulting Firm, Bangalore</div>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="text-gray-700 mb-4 italic">"Free plan gave us 6 months to test. When we upgraded to manufacturing features, it was seamless."</p>
                    <div class="font-semibold text-indigo-600">— Metal Fabrication, Pune</div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="gradient-bg py-20">
        <div class="max-w-4xl mx-auto px-4 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">Start Professional Billing Today</h2>
            <p class="text-lg mb-8 opacity-90">
                Join thousands of Indian businesses using Monitorbizz for GST-compliant invoicing. 
                Free for 50 invoices/month. No credit card required.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" 
                   class="bg-white text-indigo-600 font-semibold py-3 px-8 rounded-lg hover:bg-gray-100 transition-all duration-200 transform hover:scale-105">
                    Start Free Trial
                </a>
                <button @click="showContact = true" 
                        class="bg-indigo-800 text-white font-semibold py-3 px-8 rounded-lg hover:bg-indigo-900 transition-all duration-200">
                    Contact Sales
                </button>
            </div>
        </div>
    </div>

    <!-- Contact Modal -->
    <div x-show="showContact" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg max-w-md w-full p-6" @click.away="showContact = false">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">Contact Us</h3>
                <button @click="showContact = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form @submit.prevent="submitContact" class="space-y-4" x-data="{ submitting: false, message: '', messageType: '' }">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="tel" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Business Type</label>
                    <select name="business_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select your business type</option>
                        <option value="metal_workshop">Metal Workshop</option>
                        <option value="furniture">Furniture Manufacturing</option>
                        <option value="auto_garage">Auto Garage</option>
                        <option value="textile">Textile Manufacturing</option>
                        <option value="other">Other Manufacturing</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea name="message" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tell us about your manufacturing needs..."></textarea>
                </div>
                <div x-show="message" class="p-3 rounded-md text-sm" :class="messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'" x-text="message"></div>
                <div class="flex gap-3">
                    <button type="submit" :disabled="submitting" class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors disabled:opacity-50">
                        <span x-show="!submitting">Send Message</span>
                        <span x-show="submitting">Sending...</span>
                    </button>
                    <button type="button" @click="showContact = false" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <h3 class="text-2xl font-bold mb-4 logo-text">Monitorbizz</h3>
            <p class="text-gray-400 mb-6">Invoicing & ERP for Indian SMEs</p>
            <p class="text-gray-500 text-sm">© 2025 Monitorbizz — Invoicing & ERP for Indian SMEs</p>
        </div>
    </footer>

    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    <script>
        function submitContact(event) {
            const form = event.target;
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            
            // Get Alpine.js data
            const alpineData = Alpine.$data(form);
            alpineData.submitting = true;
            alpineData.message = '';
            
            fetch('/contact', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                alpineData.submitting = false;
                if (data.success) {
                    alpineData.message = data.message;
                    alpineData.messageType = 'success';
                    form.reset();
                    setTimeout(() => {
                        Alpine.$data(document.body).showContact = false;
                    }, 2000);
                } else {
                    alpineData.message = data.message || 'An error occurred. Please try again.';
                    alpineData.messageType = 'error';
                }
            })
            .catch(error => {
                alpineData.submitting = false;
                alpineData.message = 'Network error. Please check your connection and try again.';
                alpineData.messageType = 'error';
            });
        }
    </script>
</body>
</html>