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

    $('.btn-reset-link').on('click', function (e) {
        e.preventDefault(); 
    
        var $form = $(this).closest('form');
        var $btn = $(this);
        
        var formData = $form.serialize(); 
    
        if (!$('#email').val().trim()) {
            alert('Please enter your email address.');
            return;
        }
    
        var originalText = $btn.html();
        $btn.prop('disabled', true).html('Sending... <i class="fa-solid fa-spinner fa-spin"></i>');
    
        $.ajax({
            url: '/forgot-password', 
            type: 'POST',
            data: formData, 
            success: function (res) {
                console.log(res);
                
                alert(res.message);
                
                window.location.href = '/verify'; 
            },
            error: function (xhr) {
                console.log(xhr.responseJSON);
                
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


  

    
});







  
