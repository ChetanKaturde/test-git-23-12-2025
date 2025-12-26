<!DOCTYPE html>
<html>
<head>
    <title>Test State City</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8">
    <h1 class="text-2xl mb-4">State City Test</h1>
    
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-2">State</label>
            <select id="company_state" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                <option value="">Select State</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium mb-2">City</label>
            <select id="company_city" class="w-full px-3 py-2 border border-gray-300 rounded-md" disabled>
                <option value="">Select City</option>
            </select>
        </div>
    </div>
    
    <script src="{{ asset('js/state-city.js') }}"></script>
</body>
</html>