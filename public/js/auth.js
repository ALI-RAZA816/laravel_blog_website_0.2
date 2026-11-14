$(document).ready(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

  

    // register
    $('.submit-btn').on('click',function(event){
        event.preventDefault();

        var name = $("#name").val().trim();
        var email = $("#email").val().trim();
        var password = $("#password").val().trim();
        var password_confirmation = $("#password_confirmation").val().trim();
        
        if(!name){
            alert('Name is required');
            return false;
        }
       
        if(!email){
            alert('Email is required');
            return false;
        }
    
        if(!password){
            alert('Password is required');
            return false;
        }
    
        if(!password_confirmation){
            alert("Confirm password is required");
            return false;
        }
    
        if(password !== password_confirmation){
            alert("Password doesn't match");
            return false;
        }

        $.ajax({
            url:'/registerUser',
            type:'POST',
            data:{
                name:name,
                emailaddress:email,
                password:password,
                password_confirmation:password_confirmation
            },
            success:function(data){
                alert(data.message);
                window.location.href = '/login';
            },
           error: function (xhr) {
                var res = xhr.responseJSON;
                if (res && res.errors) { alert(Object.values(res.errors)[0][0]); }
                else if (res && res.message) { alert(res.message); }
                else { alert('Something went wrong'); }
            }
        });
        
    });

    $('#avatarToggle').on('click', function(e) {
        e.stopPropagation(); 
        $('#userDropdown').toggleClass('show');
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.user-profile-wrapper').length) {
            $('#userDropdown').removeClass('show');
        }
    });
    
    // login 
    $('.login-btn').on('click',function(event){
        event.preventDefault();

        
        var login_email = $("#login_email").val().trim();
        var login_password = $("#login_password").val().trim();
        var remember = $('#remember_me').is(':checked') ? 1 : 0;
       
        if(!login_email){
            alert('Email is required');
            return false;
        }
    
        if(!login_password){
            alert('Password is required');
            return false;
        }

        $.ajax({
            url:'/loginUser',
            type:'POST',
            data:{
                email:login_email,
                remember:remember,
                password:login_password,
            },
            success:function(data){
                if(data){
                    alert(data.message);
                    window.location.href="/";
                }
            },
            error: function (xhr) {
                var res = xhr.responseJSON;
                alert((res && res.message) ? res.message : 'Something went wrong');
            }
        });
        
    });
    
    // logout
    $('#logoutBtn').on('click',function(event){
        event.preventDefault();
        $.ajax({
            url:'/logoutUser',
            type:'POST',
            success:function(data){
                localStorage.clear();
                sessionStorage.clear();
                window.location.href = '/login'
            },
            error:function(xhr){
                  alert(xhr.responseJSON.message);
            }
        });

    });
});