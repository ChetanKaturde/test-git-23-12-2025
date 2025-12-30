@extends('layouts.app')

@section('page-title', 'Reports')

@section('content')
<div class="p-4 md:p-6 space-y-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Reports</h1>
                <nav class="text-sm text-gray-500 mt-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Home</a>
                    > <span class="font-medium">Reports</span>
                </nav>
            </div>
        </div>
    </div>

    <!-- Reports Grid -->
    <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-6 max-w-4xl mx-auto">
        <!-- Who owes me money? -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-6 mb-6">
                <div class="w-16 h-16 bg-red-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">💰</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Who owes me money?</h3>
                    <p class="text-lg text-gray-600">See overdue invoices by customer</p>
                </div>
                @canViewInModule('reports')
                <a href="{{ route('reports.aging') }}" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-lg font-medium">
                    View Aging Report
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
                @endcanViewInModule
            </div>
        </div>

        <!-- Business Expenses -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-6 mb-6">
                <div class="w-16 h-16 bg-orange-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">💸</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Business Expenses</h3>
                    <p class="text-lg text-gray-600">Track and analyze your business expenses</p>
                </div>
                @canViewInModule('reports')
                <a href="{{ route('reports.expenses') }}" class="inline-flex items-center px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-lg font-medium">
                    View Expense Report
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
                @endcanViewInModule
            </div>
        </div>

        <!-- Profit & Loss -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-6 mb-6">
                <div class="w-16 h-16 bg-purple-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">📊</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Profit & Loss</h3>
                    <p class="text-lg text-gray-600">Revenue vs expenses analysis</p>
                </div>
                @canViewInModule('reports')
                <a href="{{ route('reports.profit-loss') }}" class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-lg font-medium">
                    View P&L Report
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
                @endcanViewInModule
            </div>
        </div>

        <!-- What's selling? -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-6 mb-6">
                <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">📈</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">What's selling?</h3>
                    <p class="text-lg text-gray-600">Top commodities by revenue</p>
                </div>
                <button class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-lg font-medium opacity-50 cursor-not-allowed">
                    Coming Soon
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- How fast do I get paid? -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-6 mb-6">
                <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center">
                    <span class="text-2xl">⏱️</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">How fast do I get paid?</h3>
                    <p class="text-lg text-gray-600">Average days to receive payment</p>
                </div>
                <button class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-lg font-medium opacity-50 cursor-not-allowed">
                    Coming Soon
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection