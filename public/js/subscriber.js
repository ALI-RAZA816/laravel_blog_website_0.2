$(document).ready(function(){

    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        }
    });


    $('.newsletter__btn').on('click', function (e) {
        e.preventDefault();
    
        var email = $('.newsletter__input').val().trim();
    
        // Validation
        if (!email) {
            alert('Please enter your email address.');
            return;
        }
    
        $('.newsletter__btn').prop('disabled', true).text('Joining...');
    
        $.ajax({
            url: '/newsletter-subscribe', 
            type: 'POST',
            data: {
                newsletter: email 
            },
            success: function (res) {
                alert(res.message);
                $('.newsletter__input').val('');        
            },
            error: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.newsletter) {
                    alert(xhr.responseJSON.errors.newsletter[0]);
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    alert(xhr.responseJSON.message);
                } else {
                    alert('Something went wrong. Please try again.');
                }
            },
            complete: function () {
                $('.newsletter__btn').prop('disabled', false).text('Join the Circle');
            }
        });
    });

});

    

