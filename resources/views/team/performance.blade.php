@extends('layouts.app')
@section('title', 'Performance Dashboard')
@section('page-title', 'Performance Dashboard')

@section('content')
@php
$inputType = 'date';
$inputName = 'date_value';
$inputLabel = 'Select Date';
$inputPlaceholder = '';
$inputMin = '';
$inputMax = '';
$showQuarter = false;
if ($selectedFilterType === 'day') {
    $inputType = 'date';
    $inputName = 'date';
    $inputLabel = 'Select Date';
} elseif ($selectedFilterType === 'month') {
    $inputType = 'month';
    $inputName = 'month';
    $inputLabel = 'Select Month';
} elseif ($selectedFilterType === 'year') {
    $inputType = 'number';
    $inputName = 'year';
    $inputLabel = 'Select Year';
    $inputPlaceholder = 'e.g. 2024';
    $inputMin = '2000';
    $inputMax = '2100';
} elseif ($selectedFilterType === 'quarter') {
    $inputType = 'number';
    $inputName = 'year';
    $inputLabel = 'Select Year';
    $inputPlaceholder = 'e.g. 2024';
    $inputMin = '2000';
    $inputMax = '2100';
    $showQuarter = true;
}
@endphp
<div class="p-6">
<div class="bg-white rounded-lg shadow p-6">
<h3 class="text-lg font-medium text-gray-900 mb-4">Commodity Performance</h3>

<!-- Filter Form -->
<form method="POST" action="{{ route('team.performance') }}" class="mb-6">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label for="filter_type" class="block text-sm font-medium text-gray-700 mb-2">Filter Type</label>
            <select name="filter_type" id="filter_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="day" {{ old('filter_type', $selectedFilterType) === 'day' ? 'selected' : '' }}>Specific Day</option>
                <option value="month" {{ old('filter_type', $selectedFilterType) === 'month' ? 'selected' : '' }}>Specific Month</option>
                <option value="year" {{ old('filter_type', $selectedFilterType) === 'year' ? 'selected' : '' }}>Specific Year</option>
                <option value="quarter" {{ old('filter_type', $selectedFilterType) === 'quarter' ? 'selected' : '' }}>Specific Quarter</option>
            </select>
        </div>
        <div id="date_input_container">
            <label for="date_value" class="block text-sm font-medium text-gray-700 mb-2" id="date_label">{{ $inputLabel }}</label>
            <input type="{{ $inputType }}" name="{{ $inputName }}" id="date_value" value="{{ old($inputName, $selectedDateValue) }}" placeholder="{{ $inputPlaceholder }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" {{ $inputMin ? 'min="' . $inputMin . '"' : '' }} {{ $inputMax ? 'max="' . $inputMax . '"' : '' }} required>
        </div>
        <div id="quarter_container" style="display: {{ $showQuarter ? 'block' : 'none' }};">
            <label for="quarter" class="block text-sm font-medium text-gray-700 mb-2">Quarter</label>
            <select name="quarter" id="quarter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="1" {{ old('quarter', $selectedQuarter) == '1' ? 'selected' : '' }}>Q1 (Jan-Mar)</option>
                <option value="2" {{ old('quarter', $selectedQuarter) == '2' ? 'selected' : '' }}>Q2 (Apr-Jun)</option>
                <option value="3" {{ old('quarter', $selectedQuarter) == '3' ? 'selected' : '' }}>Q3 (Jul-Sep)</option>
                <option value="4" {{ old('quarter', $selectedQuarter) == '4' ? 'selected' : '' }}>Q4 (Oct-Dec)</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Apply Filter</button>
        </div>
    </div>
</form>

@if($selectedFilterType && $selectedDateValue)
<div class="mb-4">
    <p class="text-sm text-gray-600">Selected Period:
        @if($selectedFilterType === 'day')
            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $selectedDateValue)->format('F j, Y') }}
        @elseif($selectedFilterType === 'month')
            {{ \Carbon\Carbon::createFromFormat('Y-m', $selectedDateValue)->format('F Y') }}
        @elseif($selectedFilterType === 'year')
            {{ $selectedDateValue }}
        @elseif($selectedFilterType === 'quarter')
            Q{{ $selectedQuarter }} {{ $selectedDateValue }} ({{ ['Jan-Mar', 'Apr-Jun', 'Jul-Sep', 'Oct-Dec'][$selectedQuarter - 1] }})
        @endif
    </p>
</div>
@endif

@if($commodityStats)
<div class="space-y-6">
    <!-- Best Selling Commodity -->
    @if($commodityStats['best_selling'])
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h4 class="text-lg font-medium text-gray-900 mb-4">Best Selling Commodity</h4>
        <div class="bg-green-50 p-4 rounded-lg">
            <p class="text-lg font-semibold text-green-800">{{ $commodityStats['best_selling']->commodity }}</p>
            <p class="text-sm text-gray-600">Quantity Sold: {{ number_format($commodityStats['best_selling']->total_quantity, 2) }}</p>
            <p class="text-sm text-gray-600">Revenue Generated: ₹{{ number_format($commodityStats['best_selling']->total_revenue, 2) }}</p>
        </div>
    </div>
    @endif

    <!-- Least Selling Commodity -->
    @if($commodityStats['least_selling'] && $commodityStats['least_selling']->commodity !== ($commodityStats['best_selling']->commodity ?? ''))
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h4 class="text-lg font-medium text-gray-900 mb-4">Least Selling Commodity</h4>
        <div class="bg-yellow-50 p-4 rounded-lg">
            <p class="text-lg font-semibold text-yellow-800">{{ $commodityStats['least_selling']->commodity }}</p>
            <p class="text-sm text-gray-600">Quantity Sold: {{ number_format($commodityStats['least_selling']->total_quantity, 2) }}</p>
            <p class="text-sm text-gray-600">Revenue Generated: ₹{{ number_format($commodityStats['least_selling']->total_revenue, 2) }}</p>
        </div>
    </div>
    @endif

    <!-- Not Selling Commodities -->
    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <h4 class="text-lg font-medium text-gray-900 mb-4">Not Selling Commodities</h4>
        @if($commodityStats['not_selling']->isNotEmpty())
        <div class="space-y-2">
            @foreach($commodityStats['not_selling'] as $commodity)
            <div class="bg-red-50 p-3 rounded-lg">
                <p class="text-sm font-medium text-red-800">{{ $commodity->commodity }}</p>
                <p class="text-xs text-gray-600">Quantity: 0 | Revenue: ₹0.00</p>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-sm text-gray-500">All commodities had sales during this period.</p>
        @endif
    </div>
</div>
@elseif($selectedFilterType)
<p class="text-gray-500 text-center py-4">No data available for selected period.</p>
@endif
</div>
</div>

<script>
document.getElementById('filter_type').addEventListener('change', function() {
    const filterType = this.value;
    const dateInput = document.getElementById('date_value');
    const label = document.getElementById('date_label');
    const quarterContainer = document.getElementById('quarter_container');

    // Reset date input value
    dateInput.value = '';

    if (filterType === 'day') {
        dateInput.type = 'date';
        label.textContent = 'Select Date';
        dateInput.placeholder = '';
        dateInput.name = 'date';
        dateInput.required = true;
        quarterContainer.style.display = 'none';
    } else if (filterType === 'month') {
        dateInput.type = 'month';
        label.textContent = 'Select Month';
        dateInput.placeholder = '';
        dateInput.name = 'month';
        dateInput.required = true;
        quarterContainer.style.display = 'none';
    } else if (filterType === 'year') {
        dateInput.type = 'number';
        label.textContent = 'Select Year';
        dateInput.placeholder = 'e.g. 2024';
        dateInput.min = '2000';
        dateInput.max = '2100';
        dateInput.name = 'year';
        dateInput.required = true;
        quarterContainer.style.display = 'none';
    } else if (filterType === 'quarter') {
        dateInput.type = 'number';
        label.textContent = 'Select Year';
        dateInput.placeholder = 'e.g. 2024';
        dateInput.min = '2000';
        dateInput.max = '2100';
        dateInput.name = 'year';
        dateInput.required = true;
        quarterContainer.style.display = 'block';
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const filterType = document.getElementById('filter_type').value;
    if (filterType === 'quarter') {
        document.getElementById('quarter_container').style.display = 'block';
    }
});
</script>
@endsection