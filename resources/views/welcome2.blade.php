<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Purchase Order - Frontend Design</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .form-input {
            @apply block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200;
        }
        .form-select {
            @apply block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition duration-200;
        }
        .btn-primary {
            @apply px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 shadow-md;
        }
        .btn-secondary {
            @apply px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200 shadow-md;
        }
        .calculated-field {
            @apply bg-blue-50 border-blue-200 text-blue-900 font-semibold;
        }
        .section-card {
            @apply bg-white shadow-lg rounded-xl border border-gray-100 hover:shadow-xl transition-shadow duration-300;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        .floating-label {
            transition: all 0.2s ease-in-out;
        }
        .form-group:focus-within .floating-label {
            transform: translateY(-1.5rem) scale(0.9);
            color: #3B82F6;
        }
        .notification {
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
    <!-- Top Navigation Bar -->
    <nav class="bg-white shadow-md border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <i class="fas fa-shopping-cart text-2xl text-blue-600 mr-3"></i>
                    <h1 class="text-xl font-bold text-gray-900">Purchase Order System</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Welcome, Admin</span>
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-sm"></i>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="section-card mb-8">
            <div class="gradient-bg px-8 py-6 rounded-t-xl">
                <div class="flex items-center justify-between text-white">
                    <div>
                        <h2 class="text-3xl font-bold flex items-center">
                            <i class="fas fa-plus-circle mr-3"></i>
                            Create New Purchase Order
                        </h2>
                        <p class="mt-2 text-blue-100">
                            Fill in the details below to create a new purchase order. All calculations are done automatically.
                        </p>
                    </div>
                    <div class="text-right bg-white bg-opacity-20 px-4 py-3 rounded-lg">
                        <div class="text-sm text-blue-100">Order #</div>
                        <div class="text-xl font-bold" id="orderNumber">PO-2025-0001</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-center space-x-4">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold">1</div>
                    <span class="ml-2 text-sm font-medium text-blue-600">Vendor Info</span>
                </div>
                <div class="w-16 h-1 bg-gray-200 rounded"></div>
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-semibold">2</div>
                    <span class="ml-2 text-sm font-medium text-gray-600">Material Details</span>
                </div>
                <div class="w-16 h-1 bg-gray-200 rounded"></div>
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-semibold">3</div>
                    <span class="ml-2 text-sm font-medium text-gray-600">Pricing & Review</span>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <form id="purchaseOrderForm" class="space-y-8">
            <!-- Vendor Information Section -->
            <div class="section-card">
                <div class="px-8 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-building mr-3 text-emerald-600"></i>
                        Vendor Information
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Select your supplier and payment terms</p>
                </div>
                <div class="px-8 py-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="form-group">
                            <label for="vendor_id" class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-store mr-2 text-emerald-600"></i>
                                Vendor/Supplier *
                            </label>
                            <select name="vendor_id" id="vendor_id" class="form-select" required>
                                <option value="">Choose your supplier...</option>
                                <option value="1">🏭 ABC Steel Corporation</option>
                                <option value="2">⚙️ XYZ Metal Industries</option>
                                <option value="3">🌐 Global Raw Materials Ltd.</option>
                                <option value="4">⭐ Premium Suppliers Inc.</option>
                                <option value="5">🏗️ Industrial Materials Co.</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="payment_terms" class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-calendar-alt mr-2 text-emerald-600"></i>
                                Payment Terms *
                            </label>
                            <select name="payment_terms" id="payment_terms" class="form-select" required>
                                <option value="">Select payment terms...</option>
                                <option value="immediate">💳 Immediate Payment</option>
                                <option value="net_15">📅 Net 15 Days</option>
                                <option value="net_30">📅 Net 30 Days</option>
                                <option value="net_45">📅 Net 45 Days</option>
                                <option value="net_60">📅 Net 60 Days</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Material Information Section -->
            <div class="section-card">
                <div class="px-8 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-cubes mr-3 text-purple-600"></i>
                        Material Information
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Specify the material type and quantities</p>
                </div>
                <div class="px-8 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="form-group">
                            <label for="material_type" class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-hammer mr-2 text-purple-600"></i>
                                Material Type *
                            </label>
                            <select name="material_type" id="material_type" class="form-select" required>
                                <option value="">Select material...</option>
                                <option value="steel">🔩 Steel</option>
                                <option value="copper">🟤 Copper</option>
                                <option value="aluminum">⚪ Aluminum</option>
                                <option value="iron">⚫ Iron</option>
                                <option value="brass">🟡 Brass</option>
                                <option value="stainless_steel">✨ Stainless Steel</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="weight" class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-weight-hanging mr-2 text-purple-600"></i>
                                Weight per Unit (kg) *
                            </label>
                            <div class="relative">
                                <input type="number" name="weight" id="weight" step="0.01" min="0" 
                                       class="form-input pr-8" placeholder="100.50" required>
                                <span class="absolute right-3 top-2 text-gray-500 text-sm">kg</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="quantity" class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-boxes mr-2 text-purple-600"></i>
                                Quantity (Units) *
                            </label>
                            <input type="number" name="quantity" id="quantity" min="1" 
                                   class="form-input" placeholder="5" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Information Section -->
            <div class="section-card">
                <div class="px-8 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-calculator mr-3 text-orange-600"></i>
                        Pricing Information
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Enter unit price and tax information</p>
                </div>
                <div class="px-8 py-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="form-group">
                            <label for="unit_price" class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-rupee-sign mr-2 text-orange-600"></i>
                                Unit Price per kg (₹) *
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500 font-semibold">₹</span>
                                <input type="number" name="unit_price" id="unit_price" step="0.01" min="0" 
                                       class="form-input pl-8" placeholder="850.00" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="gst_percent" class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-percentage mr-2 text-orange-600"></i>
                                GST Percentage (%) *
                            </label>
                            <div class="relative">
                                <input type="number" name="gst_percent" id="gst_percent" step="0.01" min="0" max="100" 
                                       class="form-input pr-8" placeholder="18.00" required>
                                <span class="absolute right-3 top-2 text-gray-500 font-semibold">%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Auto-Calculated Totals Section -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-100 shadow-lg rounded-xl border-2 border-blue-300">
                <div class="px-8 py-6 border-b border-blue-200">
                    <h3 class="text-xl font-bold text-blue-900 flex items-center">
                        <i class="fas fa-chart-line mr-3 text-blue-600 pulse-animation"></i>
                        Order Summary (Live Calculation)
                    </h3>
                    <p class="text-sm text-blue-700 mt-1">These values update automatically as you type</p>
                </div>
                <div class="px-8 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white rounded-lg p-4 shadow-sm border border-blue-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-coins mr-2 text-blue-600"></i>
                                Base Price (₹)
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500 font-bold">₹</span>
                                <input type="text" id="base_price_display" class="form-input pl-8 calculated-field text-lg" 
                                       value="0.00" readonly>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Weight × Quantity × Unit Price
                            </p>
                        </div>
                        
                        <div class="bg-white rounded-lg p-4 shadow-sm border border-blue-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-receipt mr-2 text-blue-600"></i>
                                GST Amount (₹)
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500 font-bold">₹</span>
                                <input type="text" id="gst_amount_display" class="form-input pl-8 calculated-field text-lg" 
                                       value="0.00" readonly>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                (Base Price × GST%) ÷ 100
                            </p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-lg p-4 shadow-sm border-2 border-green-300">
                            <label class="block text-sm font-bold text-green-800 mb-3">
                                <i class="fas fa-money-check-alt mr-2 text-green-600"></i>
                                Total Amount (₹)
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-green-600 font-bold text-lg">₹</span>
                                <input type="text" id="total_amount_display" class="form-input pl-8 font-bold text-xl text-green-800 bg-green-50 border-green-300" 
                                       value="0.00" readonly>
                            </div>
                            <p class="text-xs text-green-600 mt-2 font-semibold">
                                <i class="fas fa-info-circle mr-1"></i>
                                Base Price + GST Amount
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information Section -->
            <div class="section-card">
                <div class="px-8 py-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-sticky-note mr-3 text-yellow-600"></i>
                        Additional Information
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Any special instructions or notes</p>
                </div>
                <div class="px-8 py-6">
                    <div>
                        <label for="notes" class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-comment-alt mr-2 text-yellow-600"></i>
                            Notes/Comments (Optional)
                        </label>
                        <textarea name="notes" id="notes" rows="4" class="form-input" 
                                  placeholder="Add any special instructions, delivery requirements, quality specifications, or additional notes here..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="section-card">
                <div class="px-8 py-6 flex flex-col sm:flex-row gap-4 justify-end">
                    <button type="button" class="btn-secondary flex items-center justify-center">
                        <i class="fas fa-times mr-2"></i>
                        Cancel Order
                    </button>
                    <button type="button" id="previewBtn" class="px-6 py-3 bg-yellow-600 text-white font-semibold rounded-lg hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition duration-200 shadow-md flex items-center justify-center">
                        <i class="fas fa-eye mr-2"></i>
                        Preview Order
                    </button>
                    <button type="submit" id="submitBtn" class="btn-primary flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>
                        <span id="submitText">Create Purchase Order</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Preview Modal -->
    <div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-file-invoice mr-3 text-blue-600"></i>
                        Purchase Order Preview
                    </h3>
                    <button type="button" id="closePreview" class="text-gray-400 hover:text-gray-600 text-2xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div id="previewContent" class="px-6 py-4">
                <!-- Preview content will be inserted here -->
            </div>
            <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-xl">
                <div class="flex justify-end space-x-4">
                    <button type="button" id="closePreviewBtn" class="btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Edit
                    </button>
                    <button type="button" id="confirmCreate" class="btn-primary">
                        <i class="fas fa-check-circle mr-2"></i>
                        Confirm & Create Order
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Notification (Hidden by default) -->
    <div id="successNotification" class="fixed top-4 right-4 bg-green-50 border border-green-200 rounded-lg p-4 shadow-lg z-50 max-w-sm hidden notification">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-400 text-lg"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">Purchase Order Created Successfully!</p>
                <p class="text-xs text-green-600 mt-1">Order #PO-2025-0001 has been submitted.</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get form elements
            const weightInput = document.getElementById('weight');
            const quantityInput = document.getElementById('quantity');
            const unitPriceInput = document.getElementById('unit_price');
            const gstPercentInput = document.getElementById('gst_percent');
            
            const basePriceDisplay = document.getElementById('base_price_display');
            const gstAmountDisplay = document.getElementById('gst_amount_display');
            const totalAmountDisplay = document.getElementById('total_amount_display');
            
            const form = document.getElementById('purchaseOrderForm');
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const previewBtn = document.getElementById('previewBtn');
            const previewModal = document.getElementById('previewModal');
            const closePreview = document.getElementById('closePreview');
            const closePreviewBtn = document.getElementById('closePreviewBtn');
            const confirmCreate = document.getElementById('confirmCreate');
            const successNotification = document.getElementById('successNotification');

            // Generate random order number
            function generateOrderNumber() {
                const year = new Date().getFullYear();
                const randomNum = Math.floor(Math.random() * 9999) + 1;
                return `PO-${year}-${randomNum.toString().padStart(4, '0')}`;
            }
            document.getElementById('orderNumber').textContent = generateOrderNumber();

            // Calculate totals function with enhanced formatting
            function calculateTotals() {
                const weight = parseFloat(weightInput.value) || 0;
                const quantity = parseInt(quantityInput.value) || 0;
                const unitPrice = parseFloat(unitPriceInput.value) || 0;
                const gstPercent = parseFloat(gstPercentInput.value) || 0;

                // Calculate base price
                const basePrice = weight * quantity * unitPrice;
                
                // Calculate GST amount
                const gstAmount = (basePrice * gstPercent) / 100;
                
                // Calculate total amount
                const totalAmount = basePrice + gstAmount;

                // Format numbers for Indian locale
                const formatNumber = (num) => new Intl.NumberFormat('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(num);

                // Update display fields
                basePriceDisplay.value = formatNumber(basePrice);
                gstAmountDisplay.value = formatNumber(gstAmount);
                totalAmountDisplay.value = formatNumber(totalAmount);

                // Add visual feedback for calculations
                if (basePrice > 0) {
                    [basePriceDisplay, gstAmountDisplay, totalAmountDisplay].forEach(field => {
                        field.style.transform = 'scale(1.02)';
                        setTimeout(() => {
                            field.style.transform = 'scale(1)';
                        }, 150);
                    });
                }
            }

            // Add event listeners for real-time calculation
            [weightInput, quantityInput, unitPriceInput, gstPercentInput].forEach(input => {
                input.addEventListener('input', calculateTotals);
                input.addEventListener('change', calculateTotals);
                
                // Add focus effects
                input.addEventListener('focus', function() {
                    this.parentNode.style.transform = 'scale(1.02)';
                });
                input.addEventListener('blur', function() {
                    this.parentNode.style.transform = 'scale(1)';
                });
            });

            // Form submission handling
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75');
                submitText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating Order...';
                
                // Simulate form submission
                setTimeout(() => {
                    showSuccessNotification();
                    resetForm();
                }, 2000);
            });

            // Preview functionality
            previewBtn.addEventListener('click', function() {
                generatePreview();
                previewModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });

            [closePreview, closePreviewBtn].forEach(btn => {
                btn.addEventListener('click', function() {
                    previewModal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                });
            });

            confirmCreate.addEventListener('click', function() {
                previewModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                form.dispatchEvent(new Event('submit'));
            });

            // Generate preview content
            function generatePreview() {
                const getSelectText = (id) => {
                    const select = document.getElementById(id);
                    return select && select.selectedOptions[0] ? select.selectedOptions[0].text : 'Not selected';
                };

                const vendor = getSelectText('vendor_id');
                const materialType = getSelectText('material_type');
                const paymentTerms = getSelectText('payment_terms');
                const weight = weightInput.value || '0';
                const quantity = quantityInput.value || '0';
                const unitPrice = unitPriceInput.value || '0.00';
                const gstPercent = gstPercentInput.value || '0';
                const notes = document.getElementById('notes').value;

                const previewHTML = `
                    <div class="space-y-6">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-lg border border-blue-200">
                            <h4 class="font-bold text-lg mb-4 text-blue-900 flex items-center">
                                <i class="fas fa-building mr-2"></i>
                                Vendor Information
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <strong class="text-gray-700">Vendor:</strong>
                                    <p class="text-gray-900">${vendor}</p>
                                </div>
                                <div>
                                    <strong class="text-gray-700">Payment Terms:</strong>
                                    <p class="text-gray-900">${paymentTerms}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-lg border border-purple-200">
                            <h4 class="font-bold text-lg mb-4 text-purple-900 flex items-center">
                                <i class="fas fa-cubes mr-2"></i>
                                Material Details
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <strong class="text-gray-700">Material Type:</strong>
                                    <p class="text-gray-900">${materialType}</p>
                                </div>
                                <div>
                                    <strong class="text-gray-700">Weight per Unit:</strong>
                                    <p class="text-gray-900">${weight} kg</p>
                                </div>
                                <div>
                                    <strong class="text-gray-700">Quantity:</strong>
                                    <p class="text-gray-900">${quantity} units</p>
                                </div>
                                <div>
                                    <strong class="text-gray-700">Unit Price:</strong>
                                    <p class="text-gray-900">₹${unitPrice} per kg</p>
                                </div>
                                <div>
                                    <strong class="text-gray-700">GST Percentage:</strong>
                                    <p class="text-gray-900">${gstPercent}%</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-lg border-2 border-green-300">
                            <h4 class="font-bold text-lg mb-4 text-green-900 flex items-center">
                                <i class="fas fa-calculator mr-2"></i>
                                Financial Summary
                            </h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-green-200">
                                    <span class="font-medium text-gray-700">Base Price:</span>
                                    <span class="font-bold text-lg text-green-800">₹${basePriceDisplay.value}</span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-green-200">
                                    <span class="font-medium text-gray-700">GST Amount (${gstPercent}%):</span>
                                    <span class="font-bold text-lg text-green-800">₹${gstAmountDisplay.value}</span>
                                </div>
                                <div class="flex justify-between items-center py-3 bg-green-100 px-4 rounded-lg">
                                    <span class="font-bold text-xl text-green-900">Total Amount:</span>
                                    <span class="font-bold text-2xl text-green-900">₹${totalAmountDisplay.value}</span>
                                </div>
                            </div>
                        </div>
                        
                        ${notes ? `
                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-6 rounded-lg border border-yellow-200">
                            <h4 class="font-bold text-lg mb-3 text-yellow-900 flex items-center">
                                <i class="fas fa-sticky-note mr-2"></i>
                                Additional Notes
                            </h4>
                            <p class="text-gray-700 leading-relaxed">${notes}</p>
                        </div>
                        ` : ''}
                    </div>
                `;
                
                document.getElementById('previewContent').innerHTML = previewHTML;
            }

            // Show success notification
            function showSuccessNotification() {
                successNotification.classList.remove('hidden');
                setTimeout(() => {
                    successNotification.classList.add('hidden');
                }, 5000);
            }

            // Reset form
            function resetForm() {
                form.reset();
                calculateTotals();
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-75');
                submitText.innerHTML = '<i class="fas fa-save mr-2"></i>Create Purchase Order';
                document.getElementById('orderNumber').textContent = generateOrderNumber();
            }

            // Add input validation and user feedback
            const requiredFields = [
                { field: document.getElementById('vendor_id'), name: 'Vendor' },
                { field: document.getElementById('payment_terms'), name: 'Payment Terms' },
                { field: document.getElementById('material_type'), name: 'Material Type' },
                { field: weightInput, name: 'Weight' },
                { field: quantityInput, name: 'Quantity' },
                { field: unitPriceInput, name: 'Unit Price' },
                { field: gstPercentInput, name: 'GST Percentage' }
            ];

            // Real-time validation
            requiredFields.forEach(({field, name}) => {
                field.addEventListener('blur', function() {
                    validateField(this, name);
                });
                
                field.addEventListener('input', function() {
                    clearFieldError(this);
                });
            });

            function validateField(field, fieldName) {
                const isValid = field.value.trim() !== '';
                
                if (!isValid) {
                    field.classList.add('border-red-500', 'bg-red-50');
                    showFieldError(field, `${fieldName} is required`);
                } else {
                    field.classList.remove('border-red-500', 'bg-red-50');
                    clearFieldError(field);
                }
                
                return isValid;
            }

            function showFieldError(field, message) {
                clearFieldError(field);
                const errorDiv = document.createElement('div');
                errorDiv.className = 'text-red-600 text-sm mt-1 error-message';
                errorDiv.innerHTML = `<i class="fas fa-exclamation-circle mr-1"></i>${message}`;
                field.parentNode.appendChild(errorDiv);
            }

            function clearFieldError(field) {
                const existingError = field.parentNode.querySelector('.error-message');
                if (existingError) {
                    existingError.remove();
                }
            }

            // Form validation before submission
            function validateForm() {
                let isFormValid = true;
                
                requiredFields.forEach(({field, name}) => {
                    if (!validateField(field, name)) {
                        isFormValid = false;
                    }
                });

                // Additional validation for numeric fields
                const numericFields = [
                    { field: weightInput, name: 'Weight', min: 0.01 },
                    { field: quantityInput, name: 'Quantity', min: 1 },
                    { field: unitPriceInput, name: 'Unit Price', min: 0.01 },
                    { field: gstPercentInput, name: 'GST Percentage', min: 0, max: 100 }
                ];

                numericFields.forEach(({field, name, min, max}) => {
                    const value = parseFloat(field.value);
                    if (value < min) {
                        showFieldError(field, `${name} must be at least ${min}`);
                        isFormValid = false;
                    }
                    if (max && value > max) {
                        showFieldError(field, `${name} cannot exceed ${max}`);
                        isFormValid = false;
                    }
                });

                return isFormValid;
            }

            // Update form submission to include validation
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (!validateForm()) {
                    showNotification('Please fix the errors before submitting', 'error');
                    return;
                }
                
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-75');
                submitText.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating Order...';
                
                // Simulate form submission
                setTimeout(() => {
                    showSuccessNotification();
                    resetForm();
                }, 2000);
            });

            // Utility function for notifications
            function showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 max-w-sm notification`;
                
                const colors = {
                    success: 'bg-green-50 border-green-200 text-green-800',
                    error: 'bg-red-50 border-red-200 text-red-800',
                    info: 'bg-blue-50 border-blue-200 text-blue-800'
                };
                
                const icons = {
                    success: 'fas fa-check-circle',
                    error: 'fas fa-exclamation-circle',
                    info: 'fas fa-info-circle'
                };
                
                notification.className += ` ${colors[type]} border`;
                notification.innerHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="${icons[type]}"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">${message}</p>
                        </div>
                        <div class="ml-auto pl-3">
                            <button onclick="this.parentElement.parentElement.parentElement.remove()" 
                                    class="hover:opacity-75">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 5000);
            }

            // Add keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                // Ctrl+Enter to submit form
                if (e.ctrlKey && e.key === 'Enter') {
                    e.preventDefault();
                    form.dispatchEvent(new Event('submit'));
                }
                
                // Escape to close modal
                if (e.key === 'Escape' && !previewModal.classList.contains('hidden')) {
                    previewModal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }
            });

            // Initialize with default GST rate
            gstPercentInput.value = '18.00';
            calculateTotals();
        });
    </script>
</body>
</html>