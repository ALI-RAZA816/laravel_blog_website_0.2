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

$(document).ready(function(){
    fetchRecord();
    
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        }
    });



    // ============================================
    // 1. OTP BOXES LOGIC (Auto-move & Backspace)
    // ============================================

    var $otpInputs = $('.otp-input');

    $otpInputs.on('input', function (e) {
        var $this = $(this);
        var value = $this.val();

        $this.val(value.replace(/[^0-9]/g, ''));

        if ($this.val().length === 1) {
            $this.next('.otp-input').focus();
        }
    });

    $otpInputs.on('keydown', function (e) {
        if (e.key === 'Backspace' && $(this).val() === '') {
            $(this).prev('.otp-input').focus();
        }
    });

    $otpInputs.on('paste', function (e) {
        e.preventDefault();
        var pastedData = (e.originalEvent || e).clipboardData.getData('text');
        var digits = pastedData.replace(/[^0-9]/g, '').split('').slice(0, 6);

        $otpInputs.each(function (index) {
            $(this).val(digits[index] || '');
        });
        
        if (digits.length > 0) {
            $otpInputs.eq(digits.length - 1).focus();
        }
    });

    // ============================================
    // 2. VERIFY OTP AJAX
    // ============================================
    $('.btn-reset-link').on('click', function (e) { 
        e.preventDefault();

        var $btn = $(this);
        
        var otpCode = '';
        $otpInputs.each(function () {
            otpCode += $(this).val();
        });

        if (otpCode.length !== 6) {
            alert('Please enter all 6 digits of the OTP.');
            return;
        }

        // var userEmail = $('#userEmail').val();

        var originalText = $btn.html();
        $btn.prop('disabled', true).html('Verifying... <i class="fa-solid fa-spinner fa-spin"></i>');

        $.ajax({
            url: '/verify-otp',
            type: 'POST',
            data: {
                // email: userEmail, // Backend ko email chahiye
                code: otpCode    
            },
            success: function (res) {
                if (res.session) {
                    alert(res.session); // "Session Expired"
                    window.location.href = '/forgotton'; // Login page ya forgot page par bhej dein
                    return;
                }
                
                alert(res.message); 
                window.location.href = '/reset-password'; 
            },
            error: function (xhr) {
                console.log(xhr.responseJSON);
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    alert(xhr.responseJSON.message); // "Invalid OTP code" ya "OTP expired"
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    alert('Invalid OTP format');
                } else {
                    alert('Something went wrong. Please try again.');
                }
                
                // Error aane par boxes khali karein aur pehle box par focus karein
                $otpInputs.val('');
                $otpInputs.first().focus();
            },
            complete: function () {
                $btn.prop('disabled', false).html(originalText);
            }
        });
    });

    // ============================================
    // 3. RESEND TIMER (Optional)
    // ============================================
    var timerInterval;
    var timeLeft = 120 // 45 seconds



    $('#resendBtn').on('click', function (e) {
        e.preventDefault();

        var $btn = $(this);
        var originalText = $btn.html();
        var sessionEmail = ($('.session_email').val() || '').trim();

        if (!sessionEmail) {
            alert('Session expired. Please start again.');
            window.location.href = '/forgotton';
            return;
        }

        $btn.prop('disabled', true).text('Sending...');

        $.ajax({
            url: '/forgot-password',
            type: 'POST',
            data: { email: sessionEmail },
            success: function (res) {
                alert(res.message);
                window.location.href = '/verify';   // page reload hoga, timer khud dobara shuru hoga
            },
            error: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.email) {
                    alert(xhr.responseJSON.errors.email[0]);
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

    function startResendTimer() {
        $('.resend-btn').hide();
        timerInterval = setInterval(function () {
            timeLeft--;
            
            var minutes = Math.floor(timeLeft / 60);
            var seconds = timeLeft % 60;
            
            var formattedTime = (minutes < 10 ? '0' : '') + minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
            
            // Timer text update karein
            $('.resend-timer strong').text(formattedTime);

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                $('.resend-timer').hide(); 
                $('.resend-btn').show();  
            }
        }, 1000);
    }

    // // Page load par timer start karein
    startResendTimer();

});