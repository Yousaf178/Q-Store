@extends('layouts.app')

@section('title', 'Admin Dashboard - QShop')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="p-4 bg-dark text-white rounded-3 shadow-sm d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1"><i class="bi bi-speedometer2 text-warning me-2"></i> Admin Panel</h2>
                <p class="text-white-50 mb-0">Welcome back, {{ Auth::user()->name }}. Manage products, categories, and store users.</p>
            </div>
            <div>
                <span class="badge bg-danger fs-6 px-3 py-2">
                    <i class="bi bi-shield-lock me-1"></i> Administrator Access
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-primary text-white h-100">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase text-white-50 mb-2 fw-semibold">Total Products</h6>
                    <h2 class="display-6 fw-bold mb-0">{{ $productsCount ?? 0 }}</h2>
                </div>
                <div class="fs-1 text-white-50">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
            <div class="card-footer bg-primary-subtle border-0 py-2">
                <a href="{{ route('products.index') }}" class="text-primary text-decoration-none small fw-semibold d-flex justify-content-between align-items-center">
                    Manage Products <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-success text-white h-100">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase text-white-50 mb-2 fw-semibold">Total Categories</h6>
                    <h2 class="display-6 fw-bold mb-0">{{ $categoriesCount ?? 0 }}</h2>
                </div>
                <div class="fs-1 text-white-50">
                    <i class="bi bi-tags"></i>
                </div>
            </div>
            <div class="card-footer bg-success-subtle border-0 py-2">
                <a href="{{ route('categories.index') }}" class="text-success text-decoration-none small fw-semibold d-flex justify-content-between align-items-center">
                    Manage Categories <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-info text-white h-100">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase text-white-50 mb-2 fw-semibold">Registered Users</h6>
                    <h2 class="display-6 fw-bold mb-0">{{ $usersCount ?? 0 }}</h2>
                </div>
                <div class="fs-1 text-white-50">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <div class="card-footer bg-info-subtle border-0 py-2">
                <a href="{{ route('admin.users.index') }}" class="text-dark text-decoration-none small fw-semibold d-flex justify-content-between align-items-center">
                    Manage Users <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold"><i class="bi bi-lightning-charge text-warning me-2"></i> Quick Actions</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('products.create') }}" class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-plus-circle fs-3 mb-1"></i>
                            <span class="fw-semibold">Add New Product</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100 py-3 d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-list-check fs-3 mb-1"></i>
                            <span class="fw-semibold">View All Products</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('categories.index') }}" class="btn btn-outline-success w-100 py-3 d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-tags fs-3 mb-1"></i>
                            <span class="fw-semibold">Manage Categories</span>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-dark w-100 py-3 d-flex flex-column align-items-center justify-content-center">
                            <i class="bi bi-people fs-3 mb-1"></i>
                            <span class="fw-semibold">View All Users</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
