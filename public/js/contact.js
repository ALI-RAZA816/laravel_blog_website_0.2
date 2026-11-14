$(document).ready(function () {

    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        }
    });


    $('#sendMessage').on('click', function(e) {
        e.preventDefault();
        var name = $('#name').val().trim();
        var email = $('#email').val().trim();
        var subject = $('#subject').val().trim();
        var message = $('#message').val().trim();

        if(!name){
            alert('Name is required');
            return;
        }

        if(!email){
            alert('Email is required');
            return;
        }

        if(!subject){
            alert('Subject is required');
        }

        if(!message){
            alert('Message is required');
        }

        $.ajax({
            url: '/send-message', 
            type: 'POST',
            data: {
                name:name,
                email:email,
                subject:subject,
                message:message
            },
            success: function(res) {
                alert(res.message);
            },
            error: function (xhr) {
                var r = xhr.responseJSON;
                alert(r && r.errors ? Object.values(r.errors)[0][0] : (r && r.message) || 'Error sending message');
            }
        });
    });
});