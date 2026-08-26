@extends('layouts.app')

@section('title', 'Sign Up / Register - QShop')

@section('content')
<div class="row justify-content-center mt-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white py-3 text-center">
                <h4 class="mb-0 fw-bold"><i class="bi bi-person-plus me-2"></i> Create an Account</h4>
                <p class="mb-0 small text-white-50">Register as a User or Administrator</p>
            </div>

            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 small">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="e.g. John Doe"
                                required
                                autofocus
                            >
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="name@example.com"
                                required
                            >
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Account Role</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="role" id="role_user" value="user" {{ old('role', 'user') === 'user' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary w-100 text-start d-flex align-items-center" for="role_user">
                                    <i class="bi bi-person me-2 fs-5"></i>
                                    <div>
                                        <div class="fw-bold">Regular User</div>
                                        <div class="small text-muted" style="font-size: 0.75rem;">Browse & view products</div>
                                    </div>
                                </label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="role" id="role_admin" value="admin" {{ old('role') === 'admin' ? 'checked' : '' }}>
                                <label class="btn btn-outline-danger w-100 text-start d-flex align-items-center" for="role_admin">
                                    <i class="bi bi-shield-lock me-2 fs-5"></i>
                                    <div>
                                        <div class="fw-bold">Administrator</div>
                                        <div class="small text-muted" style="font-size: 0.75rem;">Full CRUD control</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        @error('role')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Min 6 characters"
                                    required
                                >
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    class="form-control"
                                    placeholder="Repeat password"
                                    required
                                >
                            </div>
                        </div>
                    </div>

                    <div class="d-grid mb-3 mt-2">
                        <button type="submit" class="btn btn-success btn-lg fw-semibold">
                            <i class="bi bi-person-check me-1"></i> Register Account
                        </button>
                    </div>

                    <div class="text-center">
                        <p class="text-muted small mb-0">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Sign In / Login</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
