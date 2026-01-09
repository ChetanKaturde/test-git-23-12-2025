@extends('layouts.app')
@section('title', 'Expense Details')
@section('page-title', 'Expense Details')

@section('content')
<div class="p-4 md:p-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Expense Details</h1>
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
                <a href="{{ route('expenses.edit', $expense) }}"
                   class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
                <a href="{{ route('expenses.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Expenses
                </a>
            </div>
        </div>
    </div>

    <!-- Expense Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Expense Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Category</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-semibold bg-blue-100 text-blue-800 mt-1">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $expense->getCategoryDisplayName() }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Amount</label>
                            <p class="text-2xl font-bold text-gray-900 mt-1">₹{{ number_format($expense->amount, 2) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Expense Date</label>
                            <p class="text-sm text-gray-900 mt-1">{{ $expense->expense_date->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $expense->expense_date->diffForHumans() }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600">Payment Mode</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-semibold bg-gray-100 text-gray-800 mt-1">
                                <i class="fas fa-credit-card mr-1"></i>
                                {{ $expense->getPaymentModeDisplayName() }}
                            </span>
                        </div>
                    </div>

                    @if($expense->description)
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Description</label>
                        <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $expense->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Proof File -->
            @if($expense->proof_file_path)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Proof Document</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Expense Proof</p>
                                <p class="text-xs text-gray-500">{{ basename($expense->proof_file_path) }}</p>
                            </div>
                        </div>
                        <a href="{{ asset($expense->proof_file_path) }}"
                           target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-eye mr-2"></i>
                            View File
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Audit Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Audit Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Created By</label>
                        <div class="flex items-center mt-1">
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                <span class="text-xs font-medium text-gray-600">{{ substr($expense->createdBy->name, 0, 2) }}</span>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $expense->createdBy->name }}</p>
                                <p class="text-xs text-gray-500">{{ $expense->createdBy->email }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Created At</label>
                        <p class="text-sm text-gray-900 mt-1">{{ $expense->created_at->format('M d, Y H:i') }}</p>
                        <p class="text-xs text-gray-500">{{ $expense->created_at->diffForHumans() }}</p>
                    </div>
                    @if($expense->updated_at != $expense->created_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Last Updated</label>
                        <p class="text-sm text-gray-900 mt-1">{{ $expense->updated_at->format('M d, Y H:i') }}</p>
                        <p class="text-xs text-gray-500">{{ $expense->updated_at->diffForHumans() }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('expenses.edit', $expense) }}"
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Expense
                    </a>
                    <form method="POST" action="{{ route('expenses.destroy', $expense) }}" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors"
                                onclick="return confirm('Are you sure you want to delete this expense? This action cannot be undone.')">
                            <i class="fas fa-trash mr-2"></i>
                            Delete Expense
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection