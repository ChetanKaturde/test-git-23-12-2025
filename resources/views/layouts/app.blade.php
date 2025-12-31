<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Monitorbizz</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .touch-target {
            min-height: 44px;
            display: flex;
            align-items: center;
        }
        
        @media (max-width: 768px) {
            .mobile-search {
                width: 100%;
                margin-bottom: 1rem;
            }
            
            .mobile-table {
                font-size: 0.875rem;
            }
            
            .mobile-card {
                padding: 1rem;
                margin-bottom: 0.5rem;
            }
            
            .mobile-button {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }
            
            /* Mobile header spacing */
            .mobile-header-spacing {
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
            }
            
            /* Mobile content spacing */
            .mobile-content {
                padding: 1rem;
            }
            
            /* Mobile grid adjustments */
            .mobile-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            /* Mobile stats cards */
            .mobile-stat-card {
                padding: 1rem;
                margin-bottom: 0.75rem;
            }
            
            /* Mobile dropdown positioning */
            .mobile-dropdown {
                right: 0;
                left: auto;
                min-width: 12rem;
            }
            
            /* Prevent chart/card overlapping */
            .chart-container {
                position: relative;
                z-index: 1;
                margin-bottom: 1.5rem;
            }
            
            .card-grid {
                position: relative;
                z-index: 2;
                clear: both;
            }
        }
        
        /* Responsive layout fixes */
        .responsive-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        @media (min-width: 768px) {
            .responsive-container {
                gap: 2rem;
            }
        }
        
        /* Ensure proper stacking */
        .dashboard-section {
            position: relative;
            width: 100%;
            overflow: hidden;
        }
        
        /* Loading states */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
        
        .spinner {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        /* Toast positioning fixes */
        #toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 9999;
            max-width: 24rem;
            width: calc(100vw - 2rem);
        }
        
        @media (max-width: 640px) {
            #toast-container {
                top: 0.5rem;
                right: 0.5rem;
                left: 0.5rem;
                width: auto;
                max-width: none;
            }
        }
        
        .toast-notification {
            max-width: 100%;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="hidden md:flex md:w-64 md:flex-col">
            <div class="flex flex-col flex-grow overflow-y-auto bg-white border-r">
                <!-- Logo -->
                <div class="flex items-center flex-shrink-0 px-6 py-4 border-b">
                    <h1 class="text-xl font-bold text-blue-600">Monitorbizz</h1>
                </div>
                
                <!-- Business Info -->
                <div class="px-6 py-4 border-b bg-gray-50">
                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()?->business?->name ?? 'Default Workshop' }}</p>
                    <p class="text-xs text-gray-500">{{ auth()->user()?->getRoleDisplayName() ?? 'User' }} • {{ auth()->user()?->business?->subscription_tier === 'billing_sales' ? 'Sales & Billing' : 'Manufacturing System' }}</p>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-4 space-y-2" x-data="sidebarNavigation()">
                    <!-- Dashboard - Always show -->
                    <div class="space-y-1">
                        <a href="{{ route('dashboard') }}" data-tour="dashboard" class="@if(request()->routeIs('dashboard')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif group flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-150">
                            <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                            </svg>
                            Dashboard
                        </a>
                    </div>

                    <!-- OPERATIONS Section -->
                    @if(array_intersect(['materials', 'machines', 'work_orders', 'inventory'], $allowedModules))
                        <div class="space-y-1">
                            <button @click="toggleSection('operations')" class="w-full flex items-center justify-between px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                <span>Operations</span>
                                <svg :class="{'rotate-90': sections.operations}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <div x-show="sections.operations" x-transition class="space-y-1 ml-2">
                                
                                @if(in_array('materials', $allowedModules) && auth()->check() && auth()->user()->canViewModule('materials'))
                                    <a href="{{ route('materials.index') }}" data-tour="materials" class="@if(request()->routeIs('materials.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif group flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        Commodities
                                    </a>
                                @endif
                                
                                @if(in_array('machines', $allowedModules) && auth()->check() && auth()->user()->canViewModule('machines'))
                                    <a href="{{ route('machines.index') }}" data-tour="machines" class="@if(request()->routeIs('machines.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif group flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                        </svg>
                                        Machines
                                    </a>
                                @endif
                                
                                @if(in_array('work_orders', $allowedModules) && auth()->check() && auth()->user()->canViewModule('work_orders'))
                                    <a href="{{ route('work-orders.index') }}" data-tour="work-orders" class="@if(request()->routeIs('work-orders.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif group flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                        @if(auth()->user()->role === 'operator')
                                            My Tasks
                                        @else
                                            Work Orders
                                        @endif
                                    </a>
                                @endif
                                
                                @if(in_array('inventory', $allowedModules) && auth()->check() && auth()->user()->canViewModule('inventory'))
                                    <a href="{{ route('inventory.index') }}" data-tour="inventory" class="@if(request()->routeIs('inventory.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif group flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        Inventory
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- SALES & BILLING Section -->
                    <div class="space-y-1">
                        <button @click="toggleSection('sales')" class="w-full flex items-center justify-between px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700">
                            <span>Sales & Billing</span>
                            <svg :class="{'rotate-90': sections.sales}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <div x-show="sections.sales" x-transition class="space-y-1 ml-2">
                            @canViewInModule('customers')
                                <a href="{{ route('customers.index') }}" class="@if(request()->routeIs('customers.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif group flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-150">
                                    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                    Customers
                                </a>
                            @endcanViewInModule
                            
                            @canViewInModule('materials')
                                <a href="{{ route('materials.index') }}" class="@if(request()->routeIs('materials.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif group flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-150">
                                    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    Commodity
                                </a>
                            @endcanViewInModule
                            
                            @canViewInModule('quotations')
                                <a href="{{ route('quotations.index') }}" class="@if(request()->routeIs('quotations.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif group flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-150">
                                    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Quotations
                                </a>
                            @endcanViewInModule
                            
                            @canViewInModule('invoices')
                                <a href="{{ route('invoices.index') }}" class="@if(request()->routeIs('invoices.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif group flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-150">
                                    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Invoices
                                </a>
                            @endcanViewInModule
                        </div>
                    </div>

                    <!-- PROCUREMENT Section -->
                    @if(array_intersect(['vendors', 'purchase_orders'], $allowedModules) && (auth()->user()->canViewModule('vendors') || auth()->user()->canViewModule('purchase_orders')))
                        <div class="space-y-1">
                            <button @click="toggleSection('procurement')" class="w-full flex items-center justify-between px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                <span>Procurement</span>
                                <svg :class="{'rotate-90': sections.procurement}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <div x-show="sections.procurement" x-transition class="space-y-1 ml-2">
                                @canViewInModule('vendors')
                                    <a href="{{ route('vendors.index') }}" class="@if(request()->routeIs('vendors.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif group flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M10 3L8 21l5-7 5 7-2-18h-6z"></path>
                                        </svg>
                                        Vendors
                                    </a>
                                @endcanViewInModule
                                
                                @canViewInModule('purchase_orders')
                                    <a href="{{ route('purchase-orders.index') }}" data-tour="purchase-orders" class="@if(request()->routeIs('purchase-orders.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif group flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                                        </svg>
                                        Purchase Orders
                                    </a>
                                @endcanViewInModule
                            </div>
                        </div>
                    @endif

                    <!-- MANAGEMENT Section -->
                    @if(auth()->user()->canViewModule('team') || auth()->user()->canViewModule('reports') || in_array(auth()->user()->role, ['admin', 'manager']))
                        <div class="space-y-1">
                            <button @click="toggleSection('management')" class="w-full flex items-center justify-between px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                <span>Management</span>
                                <svg :class="{'rotate-90': sections.management}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <div x-show="sections.management" x-transition class="space-y-1 ml-2">
                                @canViewInModule('team')
                                    <a href="{{ route('team.index') }}" class="@if(request()->routeIs('team.index')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif group flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Team
                                    </a>
                                @endcanViewInModule
                                
                                @if(auth()->check() && auth()->user()->isAdmin())
                                    <a href="{{ route('business.profile') }}" class="@if(request()->routeIs('business.profile')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif group flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        Business Profile
                                    </a>
                                @endif
                                
                                @canViewInModule('reports')
                                    <a href="{{ route('reports.index') }}" class="@if(request()->routeIs('reports.index')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif group flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Reports
                                    </a>
                                    
                                    <a href="{{ route('activity-log.index') }}" class="@if(request()->routeIs('activity-log.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif group flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Activity Log
                                    </a>
                                    
                                    <a href="{{ route('reports.aging') }}" class="@if(request()->routeIs('reports.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif group flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Aging Report
                                    </a>
                                @endcanViewInModule
                                
                                @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'manager']))
                                    <a href="{{ route('team.performance') }}" class="@if(request()->routeIs('team.performance')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif group flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Performance
                                    </a>
                                @endif

                                @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'manager']))
                                    <a href="{{ route('expenses.index') }}" class="@if(request()->routeIs('expenses.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif group flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Expenses
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </nav>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <!-- Desktop Header -->
                <div class="hidden md:flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <h2 class="text-lg font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Global Search -->
                        <div class="relative" x-data="globalSearch()">
                            <div class="relative">
                                <input type="text" 
                                       x-model="query" 
                                       @input.debounce.300ms="search()"
                                       @focus="showResults = true"
                                       @keydown.escape="showResults = false"
                                       placeholder="Search..."
                                       class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                            
                            <!-- Search Results -->
                            <div x-show="showResults && results.length > 0" 
                                 @click.away="showResults = false"
                                 class="absolute z-50 mt-1 w-96 bg-white rounded-md shadow-lg border border-gray-200 max-h-96 overflow-y-auto right-0">
                                <template x-for="result in results" :key="result.url">
                                    <a :href="result.url" 
                                       class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0"
                                       @click="showResults = false">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <i :class="result.icon" class="text-gray-400"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900" x-text="result.title"></p>
                                                <p class="text-sm text-gray-500" x-text="result.subtitle"></p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800" x-text="result.type"></span>
                                            </div>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </div>
                        
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" data-tour="profile-menu" class="flex items-center space-x-2 text-sm text-gray-700 hover:text-gray-900">
                                <span>{{ auth()->user()?->name ?? 'User' }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> My Account
                                </a>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('team.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-users mr-2"></i> Team
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Mobile Header -->
                <div class="md:hidden">
                    <!-- Top row with menu and profile -->
                    <div class="flex items-center justify-between px-4 py-3">
                        <div class="flex items-center space-x-3">
                            <button class="text-gray-500 hover:text-gray-700 p-2 touch-target" onclick="toggleMobileMenu()">
                                <i class="fas fa-bars text-lg"></i>
                            </button>
                            <h2 class="text-lg font-semibold text-gray-900 truncate">@yield('page-title', 'Dashboard')</h2>
                        </div>
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button @click="open = !open" class="flex items-center space-x-2 text-sm text-gray-700 hover:text-gray-900 p-2">
                                <span class="hidden sm:inline">{{ auth()->user()?->name ?? 'User' }}</span>
                                <i class="fas fa-user sm:hidden"></i>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> My Account
                                </a>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('team.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-users mr-2"></i> Team
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Search row -->
                    <div class="px-4 pb-3">
                        <div class="relative" x-data="globalSearch()">
                            <div class="relative">
                                <input type="text" 
                                       x-model="query" 
                                       @input.debounce.300ms="search()"
                                       @focus="showResults = true"
                                       @keydown.escape="showResults = false"
                                       placeholder="Search..."
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                            
                            <!-- Mobile Search Results -->
                            <div x-show="showResults && results.length > 0" 
                                 @click.away="showResults = false"
                                 class="absolute z-50 mt-1 w-full bg-white rounded-md shadow-lg border border-gray-200 max-h-64 overflow-y-auto">
                                <template x-for="result in results" :key="result.url">
                                    <a :href="result.url" 
                                       class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0"
                                       @click="showResults = false">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <i :class="result.icon" class="text-gray-400"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900" x-text="result.title"></p>
                                                <p class="text-xs text-gray-500" x-text="result.subtitle"></p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800" x-text="result.type"></span>
                                            </div>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <!-- Toast Container -->
                <div id="toast-container" class="space-y-2 pointer-events-none">
                    <!-- Toasts will be inserted here -->
                </div>
                
                @if(session('success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showToast('{{ session('success') }}', 'success');
                        });
                    </script>
                @endif
                
                @if(session('error'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showToast('{{ session('error') }}', 'error');
                        });
                    </script>
                @endif
                
                <div class="md:p-0">
                    @yield('content')
                </div>
            </main>
        </div>
        

        
        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="md:hidden fixed inset-0 z-50 hidden">
            <div class="fixed inset-0 bg-gray-600 bg-opacity-75" onclick="toggleMobileMenu()"></div>
            <div class="fixed inset-y-0 left-0 max-w-xs w-full bg-white shadow-xl">
                <div class="flex items-center justify-between px-4 py-4 border-b">
                    <h1 class="text-xl font-bold text-blue-600">Monitorbizz</h1>
                    <button onclick="toggleMobileMenu()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <nav class="px-4 py-4 space-y-2" x-data="mobileNavigation()">
                    <!-- OPERATIONS Section -->
                    @if(array_intersect(['materials', 'machines', 'work_orders', 'inventory'], $allowedModules))
                        <div class="space-y-1">
                            <button @click="toggleSection('operations')" class="w-full flex items-center justify-between px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700 touch-target">
                                <span>Operations</span>
                                <svg :class="{'rotate-90': sections.operations}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <div x-show="sections.operations" x-transition class="space-y-1 ml-2">
                                <a href="{{ route('dashboard') }}" class="@if(request()->routeIs('dashboard')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif flex items-center px-3 py-2 text-sm rounded-md touch-target transition-colors duration-150">
                                    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                                    </svg>
                                    Dashboard
                                </a>
                                
                                @if(in_array('materials', $allowedModules) && auth()->check() && auth()->user()->canViewModule('materials'))
                                    <a href="{{ route('materials.index') }}" class="@if(request()->routeIs('materials.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif flex items-center px-3 py-2 text-sm rounded-md touch-target transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        Commodities
                                    </a>
                                @endif
                                
                                @if(in_array('machines', $allowedModules) && auth()->check() && auth()->user()->canViewModule('machines'))
                                    <a href="{{ route('machines.index') }}" class="@if(request()->routeIs('machines.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif flex items-center px-3 py-2 text-sm rounded-md touch-target transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                        </svg>
                                        Machines
                                    </a>
                                @endif
                                
                                @if(in_array('work_orders', $allowedModules) && auth()->check() && auth()->user()->canViewModule('work_orders'))
                                    <a href="{{ route('work-orders.index') }}" class="@if(request()->routeIs('work-orders.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif flex items-center px-3 py-2 text-sm rounded-md touch-target transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                        @if(auth()->user()->role === 'operator')
                                            My Tasks
                                        @else
                                            Work Orders
                                        @endif
                                    </a>
                                @endif
                                
                                @if(in_array('inventory', $allowedModules) && auth()->check() && auth()->user()->canViewModule('inventory'))
                                    <a href="{{ route('inventory.index') }}" class="@if(request()->routeIs('inventory.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif flex items-center px-3 py-2 text-sm rounded-md touch-target transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        Inventory
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- SALES & BILLING Section -->
                    <div class="space-y-1">
                        <button @click="toggleSection('sales')" class="w-full flex items-center justify-between px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700 touch-target">
                            <span>Sales & Billing</span>
                            <svg :class="{'rotate-90': sections.sales}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <div x-show="sections.sales" x-transition class="space-y-1 ml-2">
                            @canViewInModule('customers')
                                <a href="{{ route('customers.index') }}" class="@if(request()->routeIs('customers.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif flex items-center px-3 py-2 text-sm rounded-md touch-target transition-colors duration-150">
                                    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                    Customers
                                </a>
                            @endcanViewInModule
                            
                            @canViewInModule('materials')
                                <a href="{{ route('materials.index') }}" class="@if(request()->routeIs('materials.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif flex items-center px-3 py-2 text-sm rounded-md touch-target transition-colors duration-150">
                                    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    Commodity
                                </a>
                            @endcanViewInModule
                            
                            @canViewInModule('quotations')
                                <a href="{{ route('quotations.index') }}" class="@if(request()->routeIs('quotations.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif flex items-center px-3 py-2 text-sm rounded-md touch-target transition-colors duration-150">
                                    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Quotations
                                </a>
                            @endcanViewInModule
                            
                            @canViewInModule('invoices')
                                <a href="{{ route('invoices.index') }}" class="@if(request()->routeIs('invoices.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif flex items-center px-3 py-2 text-sm rounded-md touch-target transition-colors duration-150">
                                    <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Invoices
                                </a>
                            @endcanViewInModule
                        </div>
                    </div>

                    <!-- PROCUREMENT Section -->
                    @if(array_intersect(['vendors', 'purchase_orders'], $allowedModules) && (auth()->user()->canViewModule('vendors') || auth()->user()->canViewModule('purchase_orders')))
                        <div class="space-y-1">
                            <button @click="toggleSection('procurement')" class="w-full flex items-center justify-between px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700 touch-target">
                                <span>Procurement</span>
                                <svg :class="{'rotate-90': sections.procurement}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <div x-show="sections.procurement" x-transition class="space-y-1 ml-2">
                                @if(in_array('vendors', $allowedModules) && auth()->check() && auth()->user()->canViewModule('vendors'))
                                    <a href="{{ route('vendors.index') }}" class="@if(request()->routeIs('vendors.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif flex items-center px-3 py-2 text-sm rounded-md touch-target transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M10 3L8 21l5-7 5 7-2-18h-6z"></path>
                                        </svg>
                                        Vendors
                                    </a>
                                @endif
                                
                                @if(in_array('purchase_orders', $allowedModules) && auth()->check() && auth()->user()->canViewModule('purchase_orders'))
                                    <a href="{{ route('purchase-orders.index') }}" class="@if(request()->routeIs('purchase-orders.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif flex items-center px-3 py-2 text-sm rounded-md touch-target transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17M17 13v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                                        </svg>
                                        Purchase Orders
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- MANAGEMENT Section -->
                    @if(auth()->user()->canViewModule('team') || auth()->user()->canViewModule('reports') || in_array(auth()->user()->role, ['admin', 'manager']))
                        <div class="space-y-1">
                            <button @click="toggleSection('management')" class="w-full flex items-center justify-between px-3 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-700 touch-target">
                                <span>Management</span>
                                <svg :class="{'rotate-90': sections.management}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <div x-show="sections.management" x-transition class="space-y-1 ml-2">
                                @if(auth()->check() && auth()->user()->canViewModule('team'))
                                    <a href="{{ route('team.index') }}" class="@if(request()->routeIs('team.index')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif flex items-center px-3 py-2 text-sm rounded-md touch-target transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Team
                                    </a>
                                @endif
                                
                                @if(auth()->check() && auth()->user()->isAdmin())
                                    <a href="{{ route('business.profile') }}" class="@if(request()->routeIs('business.profile')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif flex items-center px-3 py-2 text-sm rounded-md touch-target transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        Business Profile
                                    </a>
                                @endif
                                
                                @if(auth()->check() && auth()->user()->canViewModule('reports'))
                                    <a href="{{ route('reports.index') }}" class="@if(request()->routeIs('reports.index')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif flex items-center px-3 py-2 text-sm rounded-md touch-target transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Reports
                                    </a>
                                    
                                    <a href="{{ route('activity-log.index') }}" class="@if(request()->routeIs('activity-log.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif flex items-center px-3 py-2 text-sm rounded-md touch-target transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Activity Log
                                    </a>
                                    
                                    <a href="{{ route('reports.aging') }}" class="@if(request()->routeIs('reports.*')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif flex items-center px-3 py-2 text-sm rounded-md touch-target transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Aging Report
                                    </a>
                                @endif
                                
                                @if(auth()->check() && in_array(auth()->user()->role, ['admin', 'manager']))
                                    <a href="{{ route('team.performance') }}" class="@if(request()->routeIs('team.performance')) bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-medium @else text-gray-600 hover:bg-gray-50 hover:text-gray-900 @endif flex items-center px-3 py-2 text-sm rounded-md touch-target transition-colors duration-150">
                                        <svg class="mr-3 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        Performance
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </nav>
            </div>
        </div>
    </div>
    
    <script>
        // Modern toast notification function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast-notification transform transition-all duration-300 ease-in-out translate-x-full opacity-0 w-full bg-white shadow-lg rounded-lg pointer-events-auto flex ring-1 ring-black ring-opacity-5 mb-2`;
            
            const iconColor = type === 'success' ? 'text-green-400' : type === 'error' ? 'text-red-400' : 'text-blue-400';
            const textColor = type === 'success' ? 'text-green-800' : type === 'error' ? 'text-red-800' : 'text-blue-800';
            
            toast.innerHTML = `
                <div class="flex-1 w-0 p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 ${iconColor}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                ${type === 'success' ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />' : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />'}
                            </svg>
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-sm font-medium ${textColor} break-words">${message}</p>
                        </div>
                    </div>
                </div>
                <div class="flex border-l border-gray-200">
                    <button onclick="this.parentElement.parentElement.remove()" class="w-full border border-transparent rounded-none rounded-r-lg p-4 flex items-center justify-center text-sm font-medium text-gray-600 hover:text-gray-500 focus:outline-none">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            `;
            
            const container = document.getElementById('toast-container');
            if (container) {
                container.appendChild(toast);
                
                // Animate in
                setTimeout(() => {
                    toast.classList.remove('translate-x-full', 'opacity-0');
                    toast.classList.add('translate-x-0', 'opacity-100');
                }, 100);
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    toast.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            }
        }
        
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
        
        function globalSearch() {
            return {
                query: '',
                results: [],
                showResults: false,
                loading: false,
                
                async search() {
                    if (this.query.length < 2) {
                        this.results = [];
                        this.showResults = false;
                        return;
                    }
                    
                    this.loading = true;
                    
                    try {
                        const response = await fetch(`/api/search?q=${encodeURIComponent(this.query)}`);
                        const data = await response.json();
                        this.results = data.results || [];
                        this.showResults = this.results.length > 0;
                    } catch (error) {
                        console.error('Search error:', error);
                        this.results = [];
                        this.showResults = false;
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
        
        function sidebarNavigation() {
            return {
                sections: {
                    operations: true,
                    sales: true,
                    procurement: false,
                    management: false
                },
                
                init() {
                    // Load saved state from localStorage
                    const saved = localStorage.getItem('sidebar-sections');
                    if (saved) {
                        this.sections = { ...this.sections, ...JSON.parse(saved) };
                    }
                },
                
                toggleSection(section) {
                    this.sections[section] = !this.sections[section];
                    // Save state to localStorage
                    localStorage.setItem('sidebar-sections', JSON.stringify(this.sections));
                }
            }
        }
        
        function mobileNavigation() {
            return {
                sections: {
                    operations: false,
                    sales: false,
                    procurement: false,
                    management: false
                },
                
                init() {
                    // Load saved state from localStorage
                    const saved = localStorage.getItem('mobile-sidebar-sections');
                    if (saved) {
                        this.sections = { ...this.sections, ...JSON.parse(saved) };
                    }
                },
                
                toggleSection(section) {
                    this.sections[section] = !this.sections[section];
                    // Save state to localStorage
                    localStorage.setItem('mobile-sidebar-sections', JSON.stringify(this.sections));
                }
            }
        }
        
        // Simple tooltip system
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            document.querySelectorAll('[data-tooltip]').forEach(element => {
                let tooltip = null;
                
                element.addEventListener('mouseenter', function(e) {
                    const text = this.getAttribute('data-tooltip');
                    const position = this.getAttribute('data-tooltip-position') || 'top';
                    
                    tooltip = document.createElement('div');
                    tooltip.className = 'fixed z-50 bg-gray-900 text-white text-xs px-3 py-2 rounded-lg shadow-lg pointer-events-none max-w-xs';
                    tooltip.textContent = text;
                    document.body.appendChild(tooltip);
                    
                    const rect = this.getBoundingClientRect();
                    const tooltipRect = tooltip.getBoundingClientRect();
                    
                    let top, left;
                    
                    switch(position) {
                        case 'bottom':
                            top = rect.bottom + 8;
                            left = rect.left + (rect.width - tooltipRect.width) / 2;
                            break;
                        case 'left':
                            top = rect.top + (rect.height - tooltipRect.height) / 2;
                            left = rect.left - tooltipRect.width - 8;
                            break;
                        case 'right':
                            top = rect.top + (rect.height - tooltipRect.height) / 2;
                            left = rect.right + 8;
                            break;
                        default: // top
                            top = rect.top - tooltipRect.height - 8;
                            left = rect.left + (rect.width - tooltipRect.width) / 2;
                    }
                    
                    // Keep tooltip within viewport
                    top = Math.max(8, Math.min(top, window.innerHeight - tooltipRect.height - 8));
                    left = Math.max(8, Math.min(left, window.innerWidth - tooltipRect.width - 8));
                    
                    tooltip.style.top = `${top}px`;
                    tooltip.style.left = `${left}px`;
                    
                    // Animate in
                    tooltip.style.opacity = '0';
                    tooltip.style.transform = 'scale(0.8)';
                    requestAnimationFrame(() => {
                        tooltip.style.transition = 'opacity 0.2s, transform 0.2s';
                        tooltip.style.opacity = '1';
                        tooltip.style.transform = 'scale(1)';
                    });
                });
                
                element.addEventListener('mouseleave', function() {
                    if (tooltip) {
                        tooltip.style.opacity = '0';
                        tooltip.style.transform = 'scale(0.8)';
                        setTimeout(() => tooltip.remove(), 200);
                        tooltip = null;
                    }
                });
            });
            
            // Show tour button for new users
            if (!localStorage.getItem('monitorbizz-tour-completed')) {
                setTimeout(() => {
                    const tourBtn = document.createElement('button');
                    tourBtn.className = 'fixed bottom-4 right-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-lg z-30 flex items-center space-x-2 transition-colors';
                    tourBtn.innerHTML = '<i class="fas fa-question-circle"></i><span>Take Tour</span>';
                    tourBtn.onclick = () => {
                        showToast('Welcome to Monitorbizz! Explore the navigation menu to get started.', 'success');
                        localStorage.setItem('monitorbizz-tour-completed', 'true');
                        tourBtn.remove();
                    };
                    document.body.appendChild(tourBtn);
                }, 2000);
            }
        });
    </script>
</body>
</html>