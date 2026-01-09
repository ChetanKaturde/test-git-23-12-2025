@extends('layouts.app')
@section('title', 'Edit Expense')
@section('page-title', 'Edit Expense')

@section('content')
<div class="p-4 md:p-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Expense</h1>
                <nav class="text-sm text-gray-500 mt-2 flex items-center space-x-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <a href="{{ route('expenses.index') }}" class="hover:text-blue-600 transition-colors">Expenses</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="font-medium text-gray-700">#{{ $expense->id }}</span>
                </nav>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('expenses.show', $expense) }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                    <i class="fas fa-eye mr-2"></i>
                    View
                </a>
                <a href="{{ route('expenses.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Expenses
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Edit Expense Details</h3>
            <p class="text-sm text-gray-600 mt-1">Update the expense information</p>
        </div>

        <form method="POST" action="{{ route('expenses.update', $expense) }}" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

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
                            <option value="{{ $key }}" {{ old('category', $expense->category) == $key ? 'selected' : '' }}>{{ $label }}</option>
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
                           value="{{ old('amount', $expense->amount) }}"
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
                           value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}"
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
                            <option value="{{ $key }}" {{ old('payment_mode', $expense->payment_mode) == $key ? 'selected' : '' }}>{{ $label }}</option>
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
                          placeholder="Enter expense description or notes...">{{ old('description', $expense->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Proof File -->
            @if($expense->proof_file_path)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Current Proof File
                </label>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ basename($expense->proof_file_path) }}</p>
                            <p class="text-xs text-gray-500">Current file</p>
                        </div>
                    </div>
                    <a href="{{ asset($expense->proof_file_path) }}"
                       target="_blank"
                       class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-xs">
                        <i class="fas fa-eye mr-1"></i>
                        View
                    </a>
                </div>
            </div>
            @endif

            <!-- Proof File -->
            <div>
                <label for="proof_file" class="block text-sm font-medium text-gray-700 mb-2">
                    Replace Proof File (Optional)
                </label>
                <div id="upload-area" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="proof_file" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                <span>Upload a new file</span>
                                <input id="proof_file" name="proof_file" type="file" accept=".jpg,.jpeg,.png,.pdf" class="sr-only">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">
                            PNG, JPG, PDF up to 5MB
                        </p>
                    </div>
                </div>
                <div id="file-info" class="mt-1 hidden flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i id="file-icon" class="fas fa-file text-green-600 text-sm"></i>
                        </div>
                        <div>
                            <p id="file-name" class="text-sm font-medium text-gray-900">New file selected</p>
                            <p id="file-size" class="text-xs text-gray-500">File size</p>
                        </div>
                    </div>
                    <button type="button" id="remove-file" class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-xs">
                        <i class="fas fa-trash mr-1"></i>
                        Remove
                    </button>
                </div>
                @error('proof_file')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('expenses.show', $expense) }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Update Expense
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// File upload functionality
const fileInput = document.getElementById('proof_file');
const uploadArea = document.getElementById('upload-area');
const fileInfo = document.getElementById('file-info');
const fileName = document.getElementById('file-name');
const fileSize = document.getElementById('file-size');
const fileIcon = document.getElementById('file-icon');
const removeFileBtn = document.getElementById('remove-file');

function updateFileDisplay(file) {
    if (file) {
        fileName.textContent = file.name;
        fileSize.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';

        // Set icon based on file type
        const fileType = file.type;
        if (fileType.startsWith('image/')) {
            fileIcon.className = 'fas fa-image text-green-600 text-sm';
        } else if (fileType === 'application/pdf') {
            fileIcon.className = 'fas fa-file-pdf text-red-600 text-sm';
        } else {
            fileIcon.className = 'fas fa-file text-blue-600 text-sm';
        }

        uploadArea.classList.add('hidden');
        fileInfo.classList.remove('hidden');
    } else {
        uploadArea.classList.remove('hidden');
        fileInfo.classList.add('hidden');
        fileInput.value = '';
    }
}

fileInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    updateFileDisplay(file);
});

removeFileBtn.addEventListener('click', function() {
    updateFileDisplay(null);
});

// Drag and drop functionality
uploadArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    uploadArea.classList.add('border-indigo-500', 'bg-indigo-50');
});

uploadArea.addEventListener('dragleave', function(e) {
    e.preventDefault();
    uploadArea.classList.remove('border-indigo-500', 'bg-indigo-50');
});

uploadArea.addEventListener('drop', function(e) {
    e.preventDefault();
    uploadArea.classList.remove('border-indigo-500', 'bg-indigo-50');

    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        updateFileDisplay(files[0]);
    }
});
</script>
@endsection