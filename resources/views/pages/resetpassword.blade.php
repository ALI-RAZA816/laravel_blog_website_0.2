<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SlowLiving Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Same CSS jo Forgot Password mein use ki hai -->
    <link rel="stylesheet" href="{{ asset('css/forgotton.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="auth-card">
        <div class="text-center">
            <div class="icon-box">
                <i class="fa-solid fa-lock"></i>
            </div>
            
            <h2 class="mb-3" style="font-size: 1.75rem;">Set New Password</h2>
            
            <p class="forgot-desc">
                Please enter your new password below. Make sure it is strong and secure.
            </p>
        </div>

        <form id="resetPasswordForm">
            <!-- New Password -->
            <div class="mb-4">
                <label for="password" class="form-label">New Password</label>
                <div class="input-icon-wrapper">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" class="form-control input-with-icon" id="password" name="password" placeholder="••••••••" required>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <div class="input-icon-wrapper">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" class="form-control input-with-icon" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="button" class="btn btn-dark-custom btn-reset-submit">
                Reset Password <i class="fa-solid fa-check"></i>
            </button>
        </form>

        <!-- Return to Login -->
        <div class="auth-divider"></div>
        <div class="text-center">
            <a href="{{ url('/login') }}" class="login-return-link">
                <i class="fa-solid fa-arrow-left"></i> Back to <strong>Log in</strong>
            </a>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ asset('js/changepassword.js') }}"></script>
</body>
</html>