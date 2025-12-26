@extends('layouts.app')

@section('title', 'Profit & Loss Report')
@section('page-title', 'Profit & Loss Report')

@section('content')
<div class="p-4 md:p-6 space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Profit & Loss Report</h1>
                <nav class="text-sm text-gray-500 mt-2 flex items-center space-x-2">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <a href="{{ route('reports.index') }}" class="hover:text-blue-600 transition-colors">Reports</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="font-medium text-gray-700">Profit & Loss</span>
                </nav>
                <p class="text-gray-600 mt-1">Revenue vs expenses analysis for the last 12 months</p>
            </div>
            <a href="{{ route('reports.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Reports
            </a>
        </div>
    </div>

    <!-- Year Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Revenue</p>
                    <p class="text-2xl font-bold text-green-600">₹{{ number_format($yearRevenue, 0) }}</p>
                    <div class="flex items-center mt-2 text-xs text-green-600">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span>{{ now()->format('Y') }}</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-rupee-sign text-green-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Expenses</p>
                    <p class="text-2xl font-bold text-red-600">₹{{ number_format($yearExpenses, 0) }}</p>
                    <div class="flex items-center mt-2 text-xs text-red-600">
                        <i class="fas fa-arrow-down mr-1"></i>
                        <span>{{ now()->format('Y') }}</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-red-100 to-red-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-receipt text-red-600 text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Net Profit</p>
                    <p class="text-2xl font-bold {{ $yearProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        ₹{{ number_format(abs($yearProfit), 0) }}
                    </p>
                    <div class="flex items-center mt-2 text-xs {{ $yearProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        <i class="fas fa-{{ $yearProfit >= 0 ? 'plus' : 'minus' }} mr-1"></i>
                        <span>{{ now()->format('Y') }}</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br {{ $yearProfit >= 0 ? 'from-green-100 to-green-200' : 'from-red-100 to-red-200' }} rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line {{ $yearProfit >= 0 ? 'text-green-600' : 'text-red-600' }} text-lg"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Profit Margin</p>
                    <p class="text-2xl font-bold {{ $yearProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $yearRevenue > 0 ? round(($yearProfit / $yearRevenue) * 100, 1) : 0 }}%
                    </p>
                    <div class="flex items-center mt-2 text-xs text-blue-600">
                        <i class="fas fa-percentage mr-1"></i>
                        <span>Of revenue</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center">
                    <i class="fas fa-percentage text-blue-600 text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly P&L Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Monthly Profit & Loss</h3>
            <p class="text-sm text-gray-600 mt-1">Revenue, expenses, and profit for the last 12 months</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Month</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Revenue</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Expenses</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Profit/Loss</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Margin</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($data as $month)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">{{ $month['month'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-green-600">₹{{ number_format($month['revenue'], 0) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-red-600">₹{{ number_format($month['expenses'], 0) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold {{ $month['profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $month['profit'] >= 0 ? '+' : '' }}₹{{ number_format($month['profit'], 0) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2">
                                    <div class="h-2 rounded-full {{ $month['profit'] >= 0 ? 'bg-green-600' : 'bg-red-600' }}"
                                         style="width: {{ $month['revenue'] > 0 ? min(abs($month['profit'] / $month['revenue']) * 100, 100) : 0 }}%"></div>
                                </div>
                                <span class="text-sm font-medium {{ $month['profit'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $month['revenue'] > 0 ? round(($month['profit'] / $month['revenue']) * 100, 1) : 0 }}%
                                </span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ now()->format('Y') }} Total</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-green-600">₹{{ number_format($yearRevenue, 0) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-red-600">₹{{ number_format($yearExpenses, 0) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold {{ $yearProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $yearProfit >= 0 ? '+' : '' }}₹{{ number_format($yearProfit, 0) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold {{ $yearProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $yearRevenue > 0 ? round(($yearProfit / $yearRevenue) * 100, 1) : 0 }}%
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Chart Placeholder -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Profit & Loss Trend</h3>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
            <div class="text-center">
                <i class="fas fa-chart-bar text-4xl text-gray-400 mb-2"></i>
                <p class="text-gray-500">Interactive chart would be implemented here</p>
                <p class="text-sm text-gray-400 mt-1">Showing revenue, expenses, and profit trends</p>
            </div>
        </div>
    </div>
</div>
@endsection