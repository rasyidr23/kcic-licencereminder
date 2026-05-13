<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - KCIC Licence Reminder</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --kcic-red: #BE1E2D;
            --kcic-red-hover: #9b1824;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.6)), url('{{ asset('kcic-train-bg.jpg') }}') no-repeat center center;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 420px;
            padding: 40px;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .logo-box {
            width: 64px;
            height: 64px;
            background: white;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            box-shadow: 0 8px 16px rgba(190, 30, 45, 0.1);
            border: 1px solid #dee2e6;
        }
        .logo-box i {
            font-size: 28px;
            color: var(--kcic-red);
        }
        h2 {
            font-weight: 700;
            color: #212529;
            text-align: center;
            margin-bottom: 8px;
        }
        p.subtitle {
            color: #6c757d;
            text-align: center;
            margin-bottom: 32px;
            font-size: 0.95rem;
        }
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #495057;
            margin-bottom: 8px;
        }
        .form-control {
            border-radius: 12px;
            padding: 12px 16px;
            border: 1px solid #dee2e6;
            background-color: #f8f9fa;
            transition: all 0.2s;
        }
        .form-control:focus {
            background-color: #fff;
            border-color: var(--kcic-red);
            box-shadow: 0 0 0 4px rgba(190, 30, 45, 0.1);
        }
        .form-check-input:checked {
            background-color: var(--kcic-red);
            border-color: var(--kcic-red);
        }
        .btn-login {
            background: var(--kcic-red);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            width: 100%;
            margin-top: 16px;
            transition: all 0.2s;
            color: white;
        }
        .btn-login:hover {
            background: var(--kcic-red-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(190, 30, 45, 0.2);
            color: white;
        }
        .alert {
            border-radius: 12px;
            font-size: 0.85rem;
            border: none;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo-box" style="background: white; border: 1px solid #dee2e6;">
            <img src="{{ asset('favicon.png') }}" alt="KCIC Logo" style="width: 40px; height: 40px; object-fit: contain;">
        </div>
        <h2>Welcome Back</h2>
        <p class="subtitle">Please enter your details to sign in</p>

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@kcic.co.id">
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••">
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label small" for="remember">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary btn-login">Sign In</button>
        </form>
    </div>
</body>
</html>
