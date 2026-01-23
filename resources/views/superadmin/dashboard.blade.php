@extends('layouts.superadmin')

@section('title', 'Dashboard - Super Admin')

@section('content')
<style>
    .page-header {
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark-bg);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .page-subtitle {
        color: var(--text-light);
        font-size: 1rem;
    }

    .page-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-light);
        font-size: 0.875rem;
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--card-color), var(--card-color-light));
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }

    .stat-card.primary {
        --card-color: #6366f1;
        --card-color-light: #818cf8;
    }

    .stat-card.success {
        --card-color: #10b981;
        --card-color-light: #34d399;
    }

    .stat-card.info {
        --card-color: #06b6d4;
        --card-color-light: #22d3ee;
    }

    .stat-card.warning {
        --card-color: #f59e0b;
        --card-color-light: #fbbf24;
    }

    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        background: linear-gradient(135deg, var(--card-color), var(--card-color-light));
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .stat-trend {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.625rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .stat-trend.up {
        background: #ecfdf5;
        color: #065f46;
    }

    .stat-trend.neutral {
        background: #f1f5f9;
        color: #64748b;
    }

    .stat-content {
        margin-bottom: 1rem;
    }

    .stat-value {
        font-size: 2.25rem;
        font-weight: 700;
        color: var(--dark-bg);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: var(--text-light);
        font-size: 0.875rem;
        font-weight: 500;
    }

    .stat-footer {
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }

    .stat-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--card-color);
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .stat-link:hover {
        gap: 0.75rem;
    }

    /* Quick Actions Card */
    .quick-actions-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid var(--border-color);
    }

    .card-header-custom {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .card-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.125rem;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--dark-bg);
        margin: 0;
    }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1rem;
    }

    .action-btn {
        display: flex;
        align-items: center;
        padding: 1.25rem 1.5rem;
        background: white;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .action-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 0;
        height: 100%;
        background: linear-gradient(135deg, var(--btn-color), var(--btn-color-light));
        transition: width 0.3s ease;
        z-index: 0;
    }

    .action-btn:hover::before {
        width: 100%;
    }

    .action-btn:hover {
        border-color: var(--btn-color);
        transform: translateX(4px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    .action-btn.primary {
        --btn-color: #6366f1;
        --btn-color-light: #818cf8;
    }

    .action-btn.success {
        --btn-color: #10b981;
        --btn-color-light: #34d399;
    }

    .action-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--btn-color), var(--btn-color-light));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
        margin-right: 1rem;
        flex-shrink: 0;
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
    }

    .action-btn:hover .action-icon {
        background: white;
        color: var(--btn-color);
    }

    .action-content {
        flex: 1;
        position: relative;
        z-index: 1;
    }

    .action-title {
        font-weight: 600;
        font-size: 1rem;
        color: var(--dark-bg);
        margin-bottom: 0.25rem;
        transition: color 0.3s ease;
    }

    .action-btn:hover .action-title {
        color: white;
    }

    .action-desc {
        font-size: 0.875rem;
        color: var(--text-light);
        margin: 0;
        transition: color 0.3s ease;
    }

    .action-btn:hover .action-desc {
        color: rgba(255, 255, 255, 0.9);
    }

    .action-arrow {
        color: var(--text-light);
        font-size: 1.25rem;
        margin-left: 1rem;
        flex-shrink: 0;
        position: relative;
        z-index: 1;
        transition: all 0.3s ease;
    }

    .action-btn:hover .action-arrow {
        color: white;
        transform: translateX(4px);
    }

    /* Responsive */
    @media (max-width: 991.98px) {
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .stat-card {
            padding: 1.25rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            font-size: 1.25rem;
        }

        .stat-value {
            font-size: 1.75rem;
        }
    }

    @media (max-width: 767.98px) {
        .page-title {
            font-size: 1.5rem;
        }

        .page-header {
            margin-bottom: 1.5rem;
        }

        .quick-actions-card {
            padding: 1.5rem;
        }

        .actions-grid {
            grid-template-columns: 1fr;
        }

        .stat-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
    }

    @media (max-width: 575.98px) {
        .page-title {
            font-size: 1.25rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-chart-line" style="color: var(--primary-color);"></i>
                    Dashboard Overview
                </h1>
                <p class="page-subtitle">Monitor and manage your platform performance</p>
            </div>
            <div class="page-meta">
                <i class="fas fa-calendar-alt"></i>
                <span>{{ now()->format('l, F j, Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <!-- Messages Card -->
        <div class="stat-card primary">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-trend neutral">
                    <i class="fas fa-minus"></i>
                    <span>Total</span>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $contactMessagesCount ?? 0 }}</div>
                <div class="stat-label">Contact Messages</div>
            </div>
            <div class="stat-footer">
                <a href="{{ route('superadmin.contact-messages') }}" class="stat-link">
                    <span>View all messages</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Businesses Card -->
        <div class="stat-card success">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i>
                    <span>Active</span>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $businessOwnersCount ?? 0 }}</div>
                <div class="stat-label">Business Owners</div>
            </div>
            <div class="stat-footer">
                <a href="{{ route('superadmin.business-owners') }}" class="stat-link">
                    <span>Manage businesses</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Active Businesses Card -->
        <div class="stat-card info">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-trend up">
                    <i class="fas fa-arrow-up"></i>
                    <span>Live</span>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $activeBusinessesCount ?? 0 }}</div>
                <div class="stat-label">Active Businesses</div>
            </div>
            <div class="stat-footer">
                <span class="stat-link">
                    <span>Currently operating</span>
                    <i class="fas fa-circle" style="font-size: 0.5rem; color: #10b981;"></i>
                </span>
            </div>
        </div>

        <!-- Recent Messages Card -->
        <div class="stat-card warning">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-trend neutral">
                    <i class="fas fa-hourglass-half"></i>
                    <span>24h</span>
                </div>
            </div>
            <div class="stat-content">
                <div class="stat-value">{{ $recentMessagesCount ?? 0 }}</div>
                <div class="stat-label">New Messages</div>
            </div>
            <div class="stat-footer">
                <span class="stat-link">
                    <span>Last 24 hours</span>
                    <i class="fas fa-bell"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions-card">
        <div class="card-header-custom">
            <div class="card-icon">
                <i class="fas fa-bolt"></i>
            </div>
            <h2 class="card-title">Quick Actions</h2>
        </div>
        
        <div class="actions-grid">
            <a href="{{ route('superadmin.contact-messages') }}" class="action-btn primary">
                <div class="action-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="action-content">
                    <div class="action-title">Contact Messages</div>
                    <p class="action-desc">View and manage customer inquiries</p>
                </div>
                <i class="fas fa-chevron-right action-arrow"></i>
            </a>

            <a href="{{ route('superadmin.business-owners') }}" class="action-btn success">
                <div class="action-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="action-content">
                    <div class="action-title">Business Owners</div>
                    <p class="action-desc">View and manage registered businesses</p>
                </div>
                <i class="fas fa-chevron-right action-arrow"></i>
            </a>
        </div>
    </div>
</div>
@endsection