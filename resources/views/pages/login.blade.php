<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SlowLiving - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{asset('css/login.css')}}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    
    <!-- Brand Title -->
    <div class="text-center">
        <h1 class="brand-title">SlowLiving Blog</h1>
    </div>

    <!-- Login Card -->
    <div class="auth-card">
        <div class="text-center">
            <!-- Lock Icon -->
            <div class="icon-box">
                <i class="fa-solid fa-lock"></i>
            </div>

            <h2 class="mb-1" style="font-size: 1.75rem;">Welcome back</h2>
            <p class="text-muted mb-4" style="font-size: 0.9rem;">Sign in to your editorial account</p>
        </div>

        <form>

            <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="login_email" name="email" placeholder="name@example.com" required>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <label for="password" class="form-label mb-0">Password</label>
                    <a href="{{ url('/forgotton') }}" class="forgot-link">Forgot Password?</a>
                </div>
                <div class="position-relative mt-2">
                    <input type="password" class="form-control" id="login_password" name="password" placeholder="••••••••" required>
                    <i class="fa-solid fa-eye password-toggle" id="togglePassword"></i>
                </div>
            </div>

            <!-- Remember me -->
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                <label class="form-check-label" for="remember_me">Remember me</label>
            </div>

            <!-- Login Button -->
            <button type="submit" class="btn login-btn btn-dark-custom">Login</button>
        </form>

        <!-- Register Link -->
        <div class="text-center auth-footer">
            Don't have an account? <a href="{{ url('/register') }}">Register</a>
        </div>
    </div>

    <!-- Bottom Legal Text -->
    <div class="bottom-legal">
        By continuing, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
    </div>
    <script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>