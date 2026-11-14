function fetchRecord() {
    var currentPath = window.location.pathname;
    
    if (currentPath === '/forgotton') return;

    $.ajax({
        url: '/fetch-record',
        type: 'GET',
        success: function(response) {
            if (!response.user) {
                if (currentPath !== '/forgotton') window.location.href = '/forgotton';
                return;
            }

            if (response.user.send_link !== 1) {
                if (currentPath !== '/forgotton') window.location.href = '/forgotton';
            } 
            else if (response.user.otp_verified !== 1 && currentPath === '/reset-password') {
                window.location.href = '/verify';
            } 
            else if (response.user.otp_verified === 1 && currentPath === '/verify') {
                window.location.href = '/reset-password';
            }
        },
        error: function(xhr) {
            console.log(xhr);
            if (currentPath !== '/forgotton') window.location.href = '/forgotton';
        }
    });
}

$(document).ready(function () {
    fetchRecord();
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.btn-reset-submit').on('click', function (e) {
        e.preventDefault();

        var $btn = $(this);
        var password = $('#password').val();
        var confirmPassword = $('#password_confirmation').val();

        if (!password || !confirmPassword) {
            alert('Please fill in both password fields.');
            return;
        }

        if (password !== confirmPassword) {
            alert('Passwords do not match!');
            return;
        }

        if (password.length < 8) {
            alert('Password must be at least 8 characters long.');
            return;
        }

        var originalText = $btn.html();
        $btn.prop('disabled', true).html('Resetting... <i class="fa-solid fa-spinner fa-spin"></i>');

        $.ajax({
            url: '/resetpassword',
            type: 'POST',
            data: {
                password: password,
                password_confirmation: confirmPassword
            },
            success: function (res) {

                if (res.session) {
                    alert(res.session); // "Session Expired"
                    window.location.href = '/forgotton'; // Login page ya forgot page par bhej dein
                    return;
                }
                alert(res.message); 
                window.location.href = '/login'; 
                
            },
            error: function (xhr) {
                console.log(xhr.responseJSON);
                
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var firstError = Object.values(xhr.responseJSON.errors)[0][0];
                    alert(firstError);
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    alert(xhr.responseJSON.message);
                } else {
                    alert('Something went wrong. Please try again.');
                }
            },
            complete: function () {
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });

});