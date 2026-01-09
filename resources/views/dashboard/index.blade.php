@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="welcome-section">
    <div class="welcome-content text-center">
        <h1 class="welcome-title">
            <i class="fas fa-hand-wave me-3"></i>
            Welcome back, {{ Auth::user()->name }}!
        </h1>
        <p class="welcome-subtitle">
            Ready to manage your inventory efficiently? Your {{ Auth::user()->getTeamDisplayName() }} dashboard is at your service.
        </p>
        <small class="text-muted">
            <i class="fas fa-clock me-1"></i>Last login: {{ $stats['last_login'] }}
        </small>
    </div>
</div>

<!-- Onboarding Checklist -->
<div class="row mb-4">
    @include('components.onboarding-checklist')
</div>

<!-- Dashboard Stats -->
<div class="row g-4 mb-4">
    @if(Auth::user()->isAdmin())
        {{-- Total Users --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stats-card users">
                <div class="stats-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-number">{{ $stats['total_users'] ?? 0 }}</div>
                <div class="stats-label">Total Users</div>
                <div class="stats-sublabel">{{ $stats['active_users'] ?? 0 }} active</div>
            </div>
        </div>

        {{-- Total Vendors --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stats-card vendors">
                <div class="stats-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="stats-number">{{ $stats['total_vendors'] ?? 0 }}</div>
                <div class="stats-label">Total Vendors</div>
            </div>
        </div>

        {{-- Inventory Items --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stats-card inventory">
                <div class="stats-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stats-number">{{ $stats['total_inventory_items'] ?? 0 }}</div>
                <div class="stats-label">Inventory Items</div>
                @if(($stats['low_stock_items'] ?? 0) > 0)
                    <div class="stats-sublabel text-warning">
                        {{ $stats['low_stock_items'] }} low stock
                    </div>
                @endif
                @if(($stats['out_of_stock'] ?? 0) > 0)
                    <div class="stats-sublabel text-danger">
                        {{ $stats['out_of_stock'] }} out of stock
                    </div>
                @endif
            </div>
        </div>

        {{-- Purchase Orders --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stats-card orders">
                <div class="stats-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stats-number">{{ $stats['total_purchase_orders'] ?? 0 }}</div>
                <div class="stats-label">Purchase Orders</div>
                <div class="stats-sublabel">{{ $stats['pending_orders'] ?? 0 }} pending</div>
            </div>
        </div>

        {{-- Warehouses --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stats-card warehouses">
                <div class="stats-icon">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div class="stats-number">{{ $stats['total_warehouses'] ?? 0 }}</div>
                <div class="stats-label">Warehouses</div>
            </div>
        </div>

       {{-- Quality Checks --}}
<div class="col-xl-3 col-lg-6 col-md-6">
    <div class="stats-card quality">
        <div class="stats-icon">
            <i class="fas fa-vials"></i> {{-- FontAwesome icon --}}
        </div>
        <div class="stats-number">{{ $stats['pending_quality_checks'] ?? 0 }}</div>
        <div class="stats-label">Pending Quality Checks</div>
    </div>
</div>
  <div class="col-xl-3 col-lg-6 col-md-6">
    <div class="stats-card quality approved">
        <div class="stats-icon">
            <i class="fas fa-check-circle text-success"></i>
        </div>
        <div class="stats-number">{{ $stats['approved_quality_checks'] ?? 0 }}</div>
        <div class="stats-label">Approved Quality Checks</div>
    </div>
</div>

        {{-- Recent Logins --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stats-card logins">
                <div class="stats-icon">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
                <div class="stats-number">{{ $stats['recent_logins'] ?? 0 }}</div>
                <div class="stats-label">Recent Logins</div>
            </div>
        </div>
  
@elseif(Auth::user()->role === 'purchase_team')
    {{-- ✅ Purchase Team sees order-focused stats --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="stats-card orders">
            <div class="stats-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stats-number">
                {{ $stats['total_orders'] ?? 'N/A' }}
            </div>
            <div class="stats-label">Total Orders</div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="stats-card pending">
            <div class="stats-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stats-number">
                {{ $stats['pending_orders'] ?? 'N/A' }}
            </div>
            <div class="stats-label">Pending Orders</div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="stats-card vendors">
            <div class="stats-icon">
                <i class="fas fa-truck"></i>
            </div>
            <div class="stats-number">
                {{ $stats['total_vendors'] ?? 'N/A' }}
            </div>
            <div class="stats-label">Total Vendors</div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="stats-card budget">
            <div class="stats-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stats-number">
                ₹{{ number_format($stats['budget_utilized'] ?? 0, 0) }}
            </div>
            <div class="stats-sublabel">This month</div>
        </div>
    </div>
        
   @elseif(Auth::user()->role === 'inventory_manager')
    {{-- Inventory Manager sees inventory-focused stats --}}
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="stats-card inventory">
            <div class="stats-icon">
                <i class="fas fa-boxes"></i>
            </div>
            <div class="stats-number">{{ $stats['total_items'] ?? 0 }}</div>
            <div class="stats-label">Total Items</div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="stats-card low-stock">
            <div class="stats-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stats-number">{{ $stats['low_stock_items'] ?? 0 }}</div>
            <div class="stats-label">Low Stock Items</div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="stats-card out-of-stock">
            <div class="stats-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stats-number">{{ $stats['out_of_stock'] ?? 0 }}</div>
            <div class="stats-label">Out of Stock</div>
        </div>
    </div>
    
    <div class="col-xl-3 col-lg-6 col-md-6">
        <div class="stats-card warehouses">
            <div class="stats-icon">
                <i class="fas fa-warehouse"></i>
            </div>
            <div class="stats-number">{{ $stats['total_warehouses'] ?? 0 }}</div>
            <div class="stats-label">Warehouses</div>
        </div>
    </div>

        
<!--  @else
        {{-- Default user stats --}}
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stats-card profile">
                <div class="stats-icon">
                    <i class="fas fa-user"></i>
                </div>
<div class="stats-number">
    {{ $stats['profile_completion'] ?? 0 }}%<br>
</div>
                <div class="stats-label">Profile Completion</div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stats-card role">
                <div class="stats-icon">
                    <i class="fas fa-id-badge"></i>
                </div>
                <div class="stats-number">{{ $stats['role'] ?? 'User' }}</div>
                <div class="stats-label">Your Role</div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stats-card activity">
                <div class="stats-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stats-number">{{ $stats['last_activity'] ?? 'Never' }}</div>
                <div class="stats-label">Last Activity</div>
            </div>
        </div>
        
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="stats-card created">
                <div class="stats-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stats-number">{{ $stats['account_created'] ?? 'Unknown' }}</div>
                <div class="stats-label">Account Created</div>
            </div>
        </div> -->
    @endif
</div>
<!-- Quick Actions -->
<div class="col-lg-8">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
        </div>
        <div class="card-body">
            @php
                use Illuminate\Support\Facades\Auth;

                $user = Auth::user();
                $teamDisplay = $user->getTeamDisplayName();
                $team = strtolower($teamDisplay ?: '');

                $excludedRoutes = collect([
                    'dashboard',
                    'reports.index',
                    'admin.users',
                    'barcode.dashboard',
                    'dashboard.blocks.all',
                ]);

                $excludedTitles = collect([
                    'dashboard',
                    'reports & analytics',
                    'report analysis',
                    'user management',
                    'barcode management',
                    'view blocks',
                ]);

                $quickActions = collect($navigationItems ?? [])->filter(function($item, $key) use ($user, $team, $excludedRoutes, $excludedTitles) {
                    $route = $item['route'] ?? '';
                    $title = strtolower(trim($item['title'] ?? ''));

                    if ($key === 'dashboard' || $key === 'reports') return false;
                    if ($excludedRoutes->contains(is_array($route) ? $route[0] : $route)) return false;
                    if ($excludedTitles->contains($title)) return false;

                    if ($user->isAdmin()) return true;

                    if ($team === 'purchase team') {
                        return in_array($title, ['purchase orders']);
                    }

                    if ($team === 'inventory manager') {
                        return in_array($title, ['inventory control', 'barcode management']);
                    }

                    return false;
                });
            @endphp

            <div class="row g-3">
                {{-- Render quick actions --}}
                @forelse($quickActions as $item)
                @php
    try {
        $url = is_array($item['route'])
            ? route($item['route'][0], $item['route'][1])
            : route($item['route']);
    } catch (\Exception $e) {
        $title = isset($item['title']) ? $item['title'] : 'Unknown';
        \Log::error("Invalid route for Quick Action '{$title}': " . $e->getMessage());
        $url = '#';
    }
@endphp


                    <div class="col-md-6 col-lg-4">
                        <a href="{{ $url }}" class="btn btn-outline-primary w-100 position-relative d-flex flex-column align-items-center py-3"
                           title="{{ $item['title'] ?? 'Quick Action' }}">
                            <i class="{{ $item['icon'] ?? 'fas fa-link' }} fa-2x mb-2"></i>
                            <span>{{ $item['title'] ?? 'Untitled' }}</span>

                            @if(!empty($item['view_only']))
                                <span class="badge bg-secondary position-absolute top-0 start-100 translate-middle">
                                    View Only
                                </span>
                            @endif

                            @if(!empty($item['section']))
                                <small class="text-muted mt-1">{{ $item['section'] }}</small>
                            @endif
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            No quick actions available. Please contact your administrator for access to additional features.
                        </div>
                    </div>
                @endforelse

                {{-- Always show Reports --}}
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary w-100 d-flex flex-column align-items-center py-3" title="Reports & Analytics">
                        <i class="fas fa-file-alt fa-2x mb-2"></i>
                        <span>Reports & Analytics</span>
                        <small class="text-muted mt-1">Reports</small>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>



@if(Auth::user()->isAdmin())
<!-- Notifications Panel -->
<div class="col-lg-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-bell me-2"></i>Recent Notifications</h5>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="badge bg-primary">{{ auth()->user()->unreadNotifications->count() }}</span>
            @endif
        </div>
        <div class="card-body">
            <div id="notifications-container" style="max-height: 400px; overflow-y: auto;">
                @if(auth()->user()->unreadNotifications->count() > 0)
                    @foreach(auth()->user()->unreadNotifications as $notification)
                        @php
                            $data = is_array($notification->data) ? $notification->data : json_decode($notification->data, true);
                            $purchaseOrder = \App\Models\PurchaseOrder::find($data['order_id'] ?? null);
                            $orderExists = $purchaseOrder !== null;
                        @endphp

                        <div class="notification-item alert alert-info d-flex justify-content-between align-items-start mb-3"
                             id="notification-{{ $notification->id }}">
                            <div class="flex-grow-1">
                                <strong>{{ $data['title'] ?? 'Notification' }}</strong>
                                <p class="mb-2">{{ $data['message'] ?? 'No message' }}</p>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                </small>

                                {{-- Deleted PO --}}
                                @if(($data['type'] ?? '') === 'purchase_order_deleted')
                                    <div class="alert alert-warning mt-2 mb-0 p-2">
                                        Purchase Order <strong>#{{ $data['purchase_order_number'] ?? 'N/A' }}</strong> was deleted by user 
                                        <strong>{{ $data['deleted_by'] ?? 'Unknown' }}</strong>.
                                    </div>
                                @endif

                                {{-- Updated PO --}}
                                @if(($data['type'] ?? '') === 'purchase_order_updated')
                                    <div class="alert alert-info mt-2 mb-0 p-2">
                                        PO <strong>#{{ $data['purchase_order_number'] ?? 'N/A' }}</strong> was updated by 
                                        <strong>{{ $data['updated_by'] ?? 'Unknown' }}</strong>.
                                        @if(isset($data['changes']))
                                            <ul class="mt-2 mb-0">
                                                @foreach($data['changes'] as $field => $values)
                                                    <li><strong>{{ ucfirst($field) }}</strong>: "{{ $values['old'] ?? 'N/A' }}" → "{{ $values['new'] ?? 'N/A' }}"</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @endif

                                {{-- Approve Button (Only for valid PO + permission) --}}
                                @if(isset($data['approve_url']) && $orderExists && Auth::user()->can('approve', $purchaseOrder))
                                    <form action="{{ $data['approve_url'] }}" method="POST" class="mt-2 d-inline-block">
                                        @csrf
                                        <input type="hidden" name="notification_id" value="{{ $notification->id }}">
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                @endif
                            </div>

                            <div class="ms-3">
                                <div class="btn-group-vertical" role="group" aria-label="Notification Actions">
                                    @if(isset($data['url']))
                                        @if($orderExists)
                                            <a href="{{ $data['url'] }}" class="btn btn-sm btn-primary mb-1" title="View Purchase Order">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-secondary mb-1" disabled title="Purchase order has been deleted">
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                        @endif
                                    @endif

                                    <button class="btn btn-sm btn-outline-success mb-1" 
                                            onclick="markAsRead('{{ $notification->id }}')" 
                                            title="Mark as read">
                                        <i class="fas fa-check"></i>
                                    </button>

                                    <button class="btn btn-sm btn-outline-danger" 
                                            onclick="dismissNotification('{{ $notification->id }}')" 
                                            title="Dismiss notification">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if(auth()->user()->unreadNotifications->count() > 1)
                        <div class="text-center mt-3">
                            <button class="btn btn-outline-success btn-sm me-2" onclick="markAllAsRead()">
                                <i class="fas fa-check-double me-1"></i>Mark All as Read
                            </button>
                            <button class="btn btn-outline-danger btn-sm" onclick="dismissAllNotifications()">
                                <i class="fas fa-trash me-1"></i>Dismiss All
                            </button>
                        </div>
                    @endif
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                        <p>No new notifications.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif



@endsection

@section('scripts')
<script>
    // Enhanced notification management functions
    function dismissNotification(notificationId) {
        if (!confirm('Are you sure you want to dismiss this notification? This action cannot be undone.')) {
            return;
        }
        
        const notificationElement = document.getElementById(`notification-${notificationId}`);
        const sidebarNotificationElement = document.getElementById(`sidebar-notification-${notificationId}`);
        
        // Show loading state
        if (notificationElement) {
            notificationElement.style.opacity = '0.5';
        }
        if (sidebarNotificationElement) {
            sidebarNotificationElement.style.opacity = '0.5';
        }
        
        fetch(`/notifications/${notificationId}/dismiss`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove notification from both places
                if (notificationElement) {
                    notificationElement.remove();
                }
                if (sidebarNotificationElement) {
                    sidebarNotificationElement.remove();
                }
                
                // Check if no notifications left
                checkEmptyNotifications();
                
                // Show success message
                showToast('Notification dismissed successfully', 'success');
            } else {
                // Restore opacity on error
                if (notificationElement) {
                    notificationElement.style.opacity = '1';
                }
                if (sidebarNotificationElement) {
                    sidebarNotificationElement.style.opacity = '1';
                }
                showToast('Error dismissing notification', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Restore opacity on error
            if (notificationElement) {
                notificationElement.style.opacity = '1';
            }
            if (sidebarNotificationElement) {
                sidebarNotificationElement.style.opacity = '1';
            }
            showToast('Error dismissing notification', 'error');
        });
    }
    
    function markAsRead(notificationId) {
        fetch(`/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const notificationElement = document.getElementById(`notification-${notificationId}`);
                if (notificationElement) {
                    notificationElement.classList.remove('alert-info');
                    notificationElement.classList.add('alert-success');
                    notificationElement.style.opacity = '0.7';
                }
                showToast('Notification marked as read', 'success');
            } else {
                showToast('Error marking notification as read', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error marking notification as read', 'error');
        });
    }
    
    function dismissAllNotifications() {
        if (!confirm('Are you sure you want to dismiss ALL notifications? This action cannot be undone.')) {
            return;
        }
        
        fetch('/notifications/dismiss-all', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                showToast('Error dismissing all notifications', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error dismissing all notifications', 'error');
        });
    }
    
    function markAllAsRead() {
        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelectorAll('.notification-item').forEach(item => {
                    item.classList.remove('alert-info');
                    item.classList.add('alert-success');
                    item.style.opacity = '0.7';
                });
                showToast('All notifications marked as read', 'success');
            } else {
                showToast('Error marking all notifications as read', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error marking all notifications as read', 'error');
        });
    }
    
    function checkEmptyNotifications() {
        const notificationContainer = document.getElementById('notifications-container');
        if (notificationContainer && notificationContainer.children.length === 0) {
            notificationContainer.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                    <p>No notifications to display.</p>
                </div>
            `;
        }
    }
    
    function showToast(message, type = 'info') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(toast);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 5000);
    }

    // Auto-refresh notifications for admin users
    @if(Auth::user()->isAdmin())
        setInterval(function() {
            // Check for new notifications
            fetch('/admin/notifications/count')
                .then(response => response.json())
                .then(data => {
                    if (data.count > 0) {
                        // Update notification indicator if needed
                        console.log('New notifications available:', data.count);
                    }
                })
                .catch(error => console.error('Error checking notifications:', error));
        }, 60000); // Check every minute
    @endif
</script>
@endsection