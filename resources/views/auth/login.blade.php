@extends('layouts.auth')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #4f46e5, #3b82f6, #06b6d4);
        background-size: 300% 300%;
        animation: gradientShift 10s ease infinite;
        font-family: 'Poppins', sans-serif;
    }

    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .login-container {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .login-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        padding: 40px 35px;
        width: 100%;
        max-width: 400px;
        color: white;
        animation: fadeUp 1s ease forwards;
        transform: translateY(30px);
        opacity: 0;
    }

    @keyframes fadeUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-card h2 {
        font-weight: 700;
        text-align: center;
        margin-bottom: 1.5rem;
        color: #fff;
    }

    .form-control {
        border-radius: 10px;
        border: none;
        padding: 10px 14px;
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
    }

    .form-control:focus {
        background: rgba(255, 255, 255, 0.3);
        color: #fff;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
    }

    ::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }

    .btn-login {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        border: none;
        border-radius: 10px;
        padding: 10px;
        font-weight: 600;
        width: 100%;
        color: #fff;
        transition: all 0.3s ease;
    }

    .btn-login:hover {
        background: linear-gradient(135deg, #16a34a, #15803d);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(34, 197, 94, 0.4);
    }

    .login-footer {
        text-align: center;
        margin-top: 15px;
    }

    .login-footer a {
        color: #a5f3fc;
        text-decoration: none;
        font-weight: 500;
    }

    .login-footer a:hover {
        text-decoration: underline;
    }

    .logo {
        display: flex;
        justify-content: center;
        margin-bottom: 15px;
    }

    .logo img {
        width: 80px;
        height: 80px;
        filter: drop-shadow(0 4px 10px rgba(255, 255, 255, 0.3));
    }
</style>

<div class="login-container">
    <div class="login-card">
        <div class="logo">
            <img src="{{ asset('assets/image/Logo Assa.png') }}" alt="Logo">
        </div>

        <h2>ASSA Organization</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                       placeholder="Masukkan email kamu...">
                @error('email')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Kata Sandi</label>
                <input id="password" type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       name="password" required autocomplete="current-password"
                       placeholder="Masukkan password...">
                @error('password')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                       {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">Ingat saya</label>
            </div>

            <button type="submit" class="btn-login">Masuk</button>

            <div class="login-footer mt-3">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Lupa kata sandi?</a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
