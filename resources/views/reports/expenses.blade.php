@extends('layouts.app')

@section('title', 'Expense Report')
@section('page-title', 'Expense Report')

@section('content')
<div class="p-4 md:p-6 space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Expense Report</h1>
                <nav class="text-sm text-gray-500 mt-2 flex items-center space-x-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <a href="{{ route('reports.index') }}" class="hover:text-blue-600 transition-colors">Reports</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="font-medium text-gray-700">Expenses</span>
                </nav>
                <p class="text-gray-600 mt-1">Analyze your business expenses by category and time period</p>
            </div>
            <a href="{{ route('reports.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Reports
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Categories</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $expensesByCategory->count() }}</p>
                    <div class="flex items-center mt-2 text-xs text-blue-600">
                        <i class="fas fa-tags mr-1"></i>
                        <span>Expense types</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-tags text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">This Year</p>
                    <p class="text-2xl font-bold text-gray-900">₹{{ number_format($totalExpensesThisYear, 0) }}</p>
                    <div class="flex items-center mt-2 text-xs text-green-600">
                        <i class="fas fa-calendar mr-1"></i>
                        <span>{{ now()->format('Y') }}</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar text-green-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">This Month</p>
                    <p class="text-2xl font-bold text-gray-900">₹{{ number_format(collect($monthlyExpenses)->last()['total'] ?? 0, 0) }}</p>
                    <div class="flex items-center mt-2 text-xs text-orange-600">
                        <i class="fas fa-calendar-day mr-1"></i>
                        <span>{{ now()->format('M Y') }}</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-orange-100 to-orange-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-day text-orange-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Avg Monthly</p>
                    <p class="text-2xl font-bold text-gray-900">₹{{ number_format(collect($monthlyExpenses)->avg('total') ?? 0, 0) }}</p>
                    <div class="flex items-center mt-2 text-xs text-purple-600">
                        <i class="fas fa-chart-line mr-1"></i>
                        <span>Last 12 months</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Expenses by Category -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Expenses by Category</h3>
            <p class="text-sm text-gray-600 mt-1">Breakdown of expenses across different categories</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Count</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Percentage</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $totalAmount = $expensesByCategory->sum('total');
                    @endphp
                    @foreach($expensesByCategory as $category)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-tag text-blue-600 text-sm"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-semibold text-gray-900">{{ $category->category_display }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-semibold bg-gray-100 text-gray-800">
                                {{ $category->count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">₹{{ number_format($category->total, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $totalAmount > 0 ? ($category->total / $totalAmount) * 100 : 0 }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $totalAmount > 0 ? round(($category->total / $totalAmount) * 100, 1) : 0 }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Monthly Trends Chart Placeholder -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Expense Trends</h3>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
            <div class="text-center">
                <i class="fas fa-chart-line text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-500">Chart visualization would be implemented here</p>
                <p class="text-sm text-gray-400 mt-1">Showing last 12 months of expense data</p>
            </div>
        </div>
        <!-- Simple table as fallback -->
        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Month</th>
                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($monthlyExpenses as $month)
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $month['month'] }}</td>
                        <td class="px-4 py-2 text-sm font-medium text-gray-900">₹{{ number_format($month['total'], 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection