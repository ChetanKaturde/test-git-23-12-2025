@extends('layouts.app')
@section('title', 'Add Expense')
@section('page-title', 'Add Expense')

@section('content')
<div class="p-4 md:p-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Add New Expense</h1>
                <nav class="text-sm text-gray-500 mt-2 flex items-center space-x-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <a href="{{ route('expenses.index') }}" class="hover:text-blue-600 transition-colors">Expenses</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="font-medium text-gray-700">Add Expense</span>
                </nav>
            </div>
            <a href="{{ route('expenses.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Expenses
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Expense Details</h3>
            <p class="text-sm text-gray-600 mt-1">Enter the details of your business expense</p>
        </div>

        <form method="POST" action="{{ route('expenses.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        Expense Category <span class="text-red-500">*</span>
                    </label>
                    <select id="category" name="category"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('category') border-red-500 @enderror"
                            required>
                        <option value="">Select Category</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Amount (₹) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="amount" name="amount" step="0.01" min="0"
                           value="{{ old('amount') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('amount') border-red-500 @enderror"
                           placeholder="0.00" required>
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Expense Date -->
                <div>
                    <label for="expense_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Expense Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="expense_date" name="expense_date"
                           value="{{ old('expense_date', date('Y-m-d')) }}"
                           max="{{ date('Y-m-d') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('expense_date') border-red-500 @enderror"
                           required>
                    @error('expense_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Mode -->
                <div>
                    <label for="payment_mode" class="block text-sm font-medium text-gray-700 mb-2">
                        Payment Mode <span class="text-red-500">*</span>
                    </label>
                    <select id="payment_mode" name="payment_mode"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('payment_mode') border-red-500 @enderror"
                            required>
                        <option value="">Select Payment Mode</option>
                        @foreach($paymentModes as $key => $label)
                            <option value="{{ $key }}" {{ old('payment_mode') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('payment_mode')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description / Notes
                </label>
                <textarea id="description" name="description" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror"
                          placeholder="Enter expense description or notes...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Proof File -->
            <div>
                <label for="proof_file" class="block text-sm font-medium text-gray-700 mb-2">
                    Proof File (Optional)
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="proof_file" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                <span>Upload a file</span>
                                <input id="proof_file" name="proof_file" type="file" accept=".jpg,.jpeg,.png,.pdf" class="sr-only">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">
                            PNG, JPG, PDF up to 5MB
                        </p>
                    </div>
                </div>
                @error('proof_file')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('expenses.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Save Expense
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// File upload preview
document.getElementById('proof_file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const fileName = file.name;
        const fileSize = (file.size / 1024 / 1024).toFixed(2) + ' MB';

        // You could add file preview logic here
        console.log('Selected file:', fileName, fileSize);
    }
});
</script>
@endsection