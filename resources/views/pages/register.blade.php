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
    <link rel="stylesheet" href="{{asset('css/register.css')}}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    <!-- Register Card -->
    <div class="auth-card">
        <div class="text-center">
            <h2 class="mb-1" style="font-size: 2rem;">Create Account</h2>
            <p class="text-muted mb-4" style="font-size: 0.9rem;">Create your editorial account</p>
        </div>

        <form>
            <!-- Full Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter full name">
            </div>

            <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" >
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="position-relative">
                    <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" >
                    <i class="fa-solid fa-eye password-toggle" id="togglePassword"></i>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <div class="position-relative">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="••••••••" >
                    <i class="fa-solid fa-eye password-toggle" id="toggleConfirmPassword"></i>
                </div>
            </div>

            <!-- Create Account Button -->
            <button type="submit" class="btn submit-btn btn-dark-custom">Create Account</button>
        </form>

        <!-- Login Link -->
        <div class="text-center auth-footer">
            Already have an account? <a href="{{ url('/login') }}" class="register-link-blue">Login</a>
        </div>
    </div>

    <!-- Bottom Legal Text -->
    <div class="bottom-legal">
        By continuing, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
    </div>
    <script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>