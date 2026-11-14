<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - SlowLiving Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    {{-- <link rel="stylesheet" href="{{asset('css/forgotton.css')}}"> --}}
    <link rel="stylesheet" href="{{asset('css/forgotton.css')}}">

</head>
<body>
    {{-- @section('title', 'Forgot Password - SlowLiving Blog') --}}
    <!-- Forgot Password Card -->
    <div class="auth-card">
        <div class="text-center">
            <!-- Key Icon -->
            <div class="icon-box">
                <i class="fa-solid fa-key" style="transform: rotate(-45deg);"></i>
            </div>
            
            <h2 class="mb-3" style="font-size: 1.75rem;">Forgot your password?</h2>
            
            <p class="forgot-desc">
                Enter your registered email address and we'll send you instructions to reset your password and restore access to your account.
            </p>
        </div>

        <form>
            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-icon-wrapper">
                    <i class="fa-regular fa-envelope"></i>
                    <input type="email" class="form-control input-with-icon" id="email" name="email" placeholder="name@example.com">
                </div>
            </div>

            <!-- Send Reset Link Button -->
            <button type="submit" class="btn btn-dark-custom btn-reset-link">
                Send Reset Link <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>

        <!-- Divider -->
        <div class="auth-divider"></div>

        <!-- Return to Login -->
        <div class="text-center">
            <a href="{{ url('/login') }}" class="login-return-link">
                <i class="fa-solid fa-arrow-left"></i> Remember your password? <strong>Log in</strong>
            </a>
        </div>
    </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="{{ asset('js/reset-password.js') }}"></script>
</body>
</html>