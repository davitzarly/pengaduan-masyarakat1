@extends('layouts.app')

@section('title', 'Login - Sistem Pengaduan')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .login-container {
        width: 100%;
        max-width: 450px;
        padding: 20px;
    }
    
    .login-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        padding: 50px 40px;
        animation: slideUp 0.6s ease-out;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .login-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .login-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    }
    
    .login-icon i {
        font-size: 40px;
        color: white;
    }
    
    .login-title {
        font-size: 28px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 8px;
    }
    
    .login-subtitle {
        color: #718096;
        font-size: 14px;
    }
    
    .form-group {
        margin-bottom: 24px;
        position: relative;
    }
    
    .form-label {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 8px;
        font-size: 14px;
    }
    
    .input-wrapper {
        position: relative;
    }
    
    .input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #a0aec0;
        font-size: 18px;
    }
    
    .form-control {
        padding: 14px 16px 14px 48px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: #f7fafc;
    }
    
    .form-control:focus {
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        outline: none;
    }
    
    .btn-login {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }
    
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
    }
    
    .btn-login:active {
        transform: translateY(0);
    }
    
    .alert-danger {
        background: #fed7d7;
        border: 1px solid #fc8181;
        color: #c53030;
        padding: 12px 16px;
        border-radius: 12px;
        margin-bottom: 24px;
        font-size: 14px;
    }
    
    .back-link {
        text-align: center;
        margin-top: 24px;
    }
    
    .back-link a {
        color: white;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .back-link a:hover {
        text-decoration: underline;
    }
    
    /* Responsive Styles */
    @media (max-width: 767.98px) {
        body {
            padding: 20px 0;
        }
        
        .login-container {
            padding: 10px;
        }
        
        .login-card {
            padding: 40px 24px;
            border-radius: 20px;
        }
        
        .login-icon {
            width: 64px;
            height: 64px;
            font-size: 32px;
        }
        
        .login-title {
            font-size: 24px;
        }
        
        .form-control {
            padding: 12px 14px 12px 44px;
            font-size: 16px; /* Prevents zoom on iOS */
        }
        
        .btn-login {
            padding: 12px;
            font-size: 15px;
        }
    }
    
    @media (max-width: 575.98px) {
        .login-card {
            padding: 32px 20px;
        }
        
        .login-title {
            font-size: 22px;
        }
        
        .login-subtitle {
            font-size: 13px;
        }
    }
</style>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <div class="login-icon">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
            <h1 class="login-title">Selamat Datang</h1>
            <p class="login-subtitle">Masuk ke Sistem Pengaduan Masyarakat</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-wrapper">
                    <i class="bi bi-envelope-fill input-icon"></i>
                    <input type="email" name="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrapper">
                    <i class="bi bi-lock-fill input-icon"></i>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>
            
            <button class="btn-login" type="submit">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Dashboard
            </button>
        </form>
    </div>
    
    <div class="back-link">
        <a href="{{ url('/') }}">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
        </a>
    </div>
</div>
@endsection
