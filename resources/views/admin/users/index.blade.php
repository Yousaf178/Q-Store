@extends('layouts.app')

@section('title', 'Registered Users - Admin Panel')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h2 class="fw-bold mb-1"><i class="bi bi-people text-primary me-2"></i> User Management</h2>
                <p class="text-muted mb-0">View, search, and monitor all registered user and administrator accounts.</p>
            </div>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Admin Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Metric Badges Row -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="p-3 bg-white rounded shadow-sm border d-flex align-items-center justify-content-between">
            <div>
                <small class="text-muted text-uppercase fw-semibold">Total Accounts</small>
                <h3 class="fw-bold mb-0 text-dark">{{ $totalUsers }}</h3>
            </div>
            <div class="fs-1 text-primary-emphasis opacity-75">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="p-3 bg-white rounded shadow-sm border d-flex align-items-center justify-content-between">
            <div>
                <small class="text-muted text-uppercase fw-semibold">Administrators</small>
                <h3 class="fw-bold mb-0 text-danger">{{ $adminCount }}</h3>
            </div>
            <div class="fs-1 text-danger opacity-75">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="p-3 bg-white rounded shadow-sm border d-flex align-items-center justify-content-between">
            <div>
                <small class="text-muted text-uppercase fw-semibold">Regular Users</small>
                <h3 class="fw-bold mb-0 text-primary">{{ $regularUserCount }}</h3>
            </div>
            <div class="fs-1 text-primary opacity-75">
                <i class="bi bi-person-check-fill"></i>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filter Card -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-3">
        <form action="{{ route('admin.users.index') }}" method="GET">
            <div class="row g-2 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search by name or email..."
                            value="{{ request('search') }}"
                        >
                    </div>
                </div>

                <div class="col-md-4">
                    <select name="role" class="form-select">
                        <option value="">All Account Roles</option>
                        <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Regular Users Only</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrators Only</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        Filter
                    </button>
                    @if(request('search') || request('role'))
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary" title="Reset Filters">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
@if($users->count())
    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th width="60">ID</th>
                        <th>User Details</th>
                        <th>Role</th>
                        <th>Orders Count</th>
                        <th>Registered Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td>
                                <span class="text-muted fw-semibold">#{{ $u->id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div 
                                        class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white shadow-sm"
                                        style="width: 40px; height: 40px; background-color: {{ $u->isAdmin() ? '#dc3545' : '#0d6efd' }}; font-size: 0.9rem;"
                                    >
                                        {{ strtoupper(substr($u->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $u->name }}</div>
                                        <div class="small text-muted">{{ $u->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $u->isAdmin() ? 'bg-danger' : 'bg-primary' }} text-uppercase px-2 py-1">
                                    <i class="bi {{ $u->isAdmin() ? 'bi-shield-lock' : 'bi-person' }} me-1"></i>
                                    {{ $u->role }}
                                </span>
                            </td>
                            <td>
                                @if($u->orders_count > 0)
                                    <span class="badge bg-success-subtle text-success fw-semibold">
                                        <i class="bi bi-bag-check me-1"></i> {{ $u->orders_count }} order(s)
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted border">
                                        0 orders
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="small text-dark">{{ $u->created_at ? $u->created_at->format('d M Y') : 'N/A' }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ $u->created_at ? $u->created_at->format('h:i A') : '' }}</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white border-0 py-3">
            {{ $users->links() }}
        </div>
    </div>
@else
    <div class="card shadow-sm border-0 text-center py-5">
        <div class="card-body">
            <i class="bi bi-people text-muted" style="font-size: 3.5rem;"></i>
            <h5 class="fw-bold mt-3 mb-1">No users found</h5>
            <p class="text-muted mb-0">Try changing your search terms or filters.</p>
        </div>
    </div>
@endif
@endsection
