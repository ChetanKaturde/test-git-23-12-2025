@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Notifications</h4>
                    <button class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()">
                        Mark All as Read
                    </button>
                </div>

                <div class="card-body">
                    @if($notifications->count() > 0)
                        <div class="list-group">
                            @foreach($notifications as $notification)
                                <div class="list-group-item {{ $notification->read_at ? '' : 'list-group-item-warning' }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">
                                            {{ $notification->data['title'] ?? 'Notification' }}
                                            @if(!$notification->read_at)
                                                <span class="badge bg-primary">New</span>
                                            @endif
                                        </h6>
                                        <small>{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">{{ $notification->data['message'] ?? '' }}</p>

                                    @if(isset($notification->data['amount']))
                                        <small class="text-muted">Amount: ₹{{ number_format($notification->data['amount'], 2) }}</small>
                                    @endif

                                    @if(isset($notification->data['vendor']))
                                        <small class="text-muted"> | Vendor: {{ $notification->data['vendor'] }}</small>
                                    @endif

                                    <div class="mt-2">
                                        @if(isset($notification->data['url']))
                                            <a href="{{ $notification->data['url'] }}" class="btn btn-sm btn-primary">View</a>
                                        @endif

                                        @if(!$notification->read_at)
                                            <button class="btn btn-sm btn-outline-secondary" onclick="markAsRead('{{ $notification->id }}')">
                                                Mark as Read
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-3">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No notifications found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CSRF Token --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to mark as read');
        }
    })
    .catch(() => alert('Error occurred.'));
}

function markAllAsRead() {
    fetch('/notifications/read-all', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to mark all as read');
        }
    })
    .catch(() => alert('Error occurred.'));
}
</script>
@endsection
