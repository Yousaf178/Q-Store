@extends('layouts.app')

@section('title', 'Login - QShop')

@section('content')
<div class="row justify-content-center mt-3">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3 text-center">
                <h4 class="mb-0 fw-bold"><i class="bi bi-box-arrow-in-right me-2"></i> Account Login</h4>
                <p class="mb-0 small text-white-50">Sign in to your User or Admin account</p>
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

                <form action="{{ route('login') }}" method="POST">
                    @csrf

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
                                autofocus
                            >
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="••••••••"
                                required
                            >
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label small" for="remember">Remember me on this device</label>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                        </button>
                    </div>

                    <div class="text-center">
                        <p class="text-muted small mb-0">
                            Don't have an account? 
                            <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">Create account / Sign Up</a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Demo Credentials Helper -->
            <div class="card-footer bg-light border-0 py-3 text-center">
                <div class="small text-muted mb-2 fw-semibold">Demo Credentials:</div>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="fillCredentials('admin@example.com', 'password')">
                        <i class="bi bi-shield-lock"></i> Fill Admin
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="fillCredentials('user@example.com', 'password')">
                        <i class="bi bi-person"></i> Fill User
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fillCredentials(email, password) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = password;
}
</script>
@endsection
