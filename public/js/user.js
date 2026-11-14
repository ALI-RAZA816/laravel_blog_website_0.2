$(document).ready(function () {
    var url = 'http://localhost:8000';
    var filters = { query: '', role: 'all', status: 'all' };
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function fetchUser( page = 1){
        $.ajax({
            url:`/fetchusers?`,
            type:'GET',
            data: { query: filters.query, role: filters.role, status: filters.status, page: page },
            success:function(data){
                console.log(data);
                var c = data.counts;
                $('.totaluser').text(fmt(c.total));
                $('.editoruser').text(fmt(c.editor));
                $('.blockeduser').text(fmt(c.blocked));
                $('.thisweek').text(fmt(data.this_week));
                var users = data.users.data;
                $('#user-record').empty();
                $.each(users,function(index, user){
                    var nameParts    = (user.name || '').trim().split(/\s+/);
                    var firstletter  = (nameParts[0] || '').charAt(0).toUpperCase();
                    var secondLetter = (nameParts[1] || '').charAt(0).toUpperCase();
                    
                    var row = `
                        <tr>
                            <td>
                                <div class="user-cell">`;
                                if(user.image){
                                    row += `<div style='height:40px;width:40px;' class='overflow-hidden rounded-5'><img  style="width:100%;height:100%;object-fit:cover;display:block;" src='/uploads/${esc(user.image)}'/></div>
                                            `;
                                }else{
                                    row += `<div class="avatar-initials">${esc(firstletter)}${esc(secondLetter)}</div>`
                                }
                                row += `<p class="user-name">${esc(user.name)}</p>
                                </div>
                            </td>
                            <td><span class="email-text">${esc(user.email)}</span></td>
                            <td><span class="role-badge text-capitalize ${user.role === 'admin'? 'role-admin' : user.role === 'editor' ? 'role-editor' : user.role === 'author' ? 'role-author' : user.role === 'user' ? 'role-user' : ''}">${esc(user.role)}</span></td>
                            <td><span class="date-text">${esc(user.join_date)}</span></td>
                            <td><span class="text-capitalize ${user.status === 'active'? 'status-active' : user.status === 'inactive' ? 'status-inactive' : 'status-blocked'}">${esc(user.status)}</span></td>
                            <td>
                                <div class="action-icons">
                                    <a href="/edit-user?userid=${user.id}"><i class="fa-solid fa-pencil"></i></a>
                                    <i class="fa-regular fa-trash-can deleteuser" data-deleteid=${user.id}></i>
                                </div>
                            </td>
                        </tr>
                    `;
                    $('#user-record').append(row);
                });
                $('.pagination-text').empty();
                var pagination =  `Showing ${data.users.from} to ${data.users.to} of ${data.users.total} categories`
                $('.pagination-text').append(pagination);
                renderPagination(data);
            },
            error:function(xhr){
                console.log(xhr);
            }
        })
    }
    fetchUser(1);

    function renderPagination(p) {
        var current = p.users.current_page;
        var last    = p.users.last_page;

        $('.pagination-controls').empty();
        var pages = '';

        // Previous
        if (current <= 1) {
            pages += `<button disabled class="page-btn previous-btn">
                        <i class="fa-solid fa-chevron-left" style="font-size:0.6rem;color:#d1d5db;"></i>
                    </button>`;
        } else {
            pages += `<button data-page="${current - 1}" class="page-btn previous-btn">
                        <i class="fa-solid fa-chevron-left" style="font-size:0.6rem;color:#d1d5db;"></i>
                    </button>`;
        }

        // Page numbers
        for (var i = 1; i <= last; i++) {
            if (i === 1 || i === last || Math.abs(i - current) <= 2) {
                pages += `<button data-page="${i}" class="page-btn ${i === current ? 'active' : ''}">${i}</button>`;
            } else if (Math.abs(i - current) === 3) {
                pages += `<span class="page-btn disabled">...</span>`;
            }
        }

        // Next
        if (current >= last) {
            pages += `<button disabled class="page-btn next-btn">
                        <i class="fa-solid fa-chevron-right" style="font-size:0.6rem;color:#d1d5db;"></i>
                    </button>`;
        } else {
            pages += `<button data-page="${current + 1}" class="page-btn next-btn">
                        <i class="fa-solid fa-chevron-right" style="font-size:0.6rem;color:#d1d5db;"></i>
                    </button>`;
        }

        $('.pagination-controls').append(pages);
    }

    $(document).on('click', '.pagination-controls .page-btn', function (e) {
        e.preventDefault();
        if ($(this).prop('disabled')) return;     
        var page = $(this).data('page');
        if (page) fetchUser(page);
    });

    var searchTimer;
    $('#searchInput').on('input', function () {
        clearTimeout(searchTimer);    
        filters.query = $(this).val();   
        searchTimer = setTimeout(function () {
            fetchUser(1);
        }, 1000);
    });

    $('#rolefilter').on('change',function(){
        filters.role = $(this).val();
        setTimeout(()=>{
            fetchUser(1);
        });
    });
    $('#statusfilter').on('change',function(){
        filters.status = $(this).val();
        setTimeout(()=>{
            fetchUser(1);
        });
    });

    $(document).on('click','.deleteuser',function(){
        var deleteid = $(this).data('deleteid');
        if(!deleteid) return;
        if (!confirm('Are you sure you want to delete this?')) return;

        $.ajax({
            url:`/deleteUser/${deleteid}`,
            type:'DELETE',
            success:function(data){
                alert(data.message);
                fetchUser('','all','all',1);
            },
            error:function(xhr){
                  alert(xhr.responseJSON.message);
            }
        })
    })

    var $circle    = $('#uploadCircle');
    var $input     = $('#profileImage');
    var $preview   = $('#previewImage');
    var $placeholder = $circle.find('.upload-placeholder');
    var $removeBtn = $('#removeImage');
    var image = '';

    $circle.on('click', function (e) {
        if ($(e.target).closest('#removeImage').length) return;
        $input[0].click();
    });
    
    $input.on('change', function (e) {
        var file = this.files[0];
        image = $('#profileImage')[0].files[0];
        
        if (!file) return;
        
        if (!file.type.match('image.*')) {
            alert('File must be an image');
            $input.val('');
            return;
        }

        if (file.size > 3 * 1024 * 1024) {
            alert('File size must be 3MB or less');
            $input.val('');
            return;
        }
        
        var reader = new FileReader();
        
        reader.onload = function (e) {
            $preview.attr('src', e.target.result).show();
            $placeholder.hide();
            $removeBtn.show();
        };

        reader.readAsDataURL(file);
    });

    $removeBtn.on('click', function (e) {
        e.stopPropagation();         
        $input.val('');          
        $preview.attr('src', '').hide();
        $placeholder.show();
        $removeBtn.hide();
    });


    var $toggle = $('#accountActiveToggle');
    var $activeInput = $('#isActiveInput');   
    $toggle.on('click', function () {
        var isActive = !$(this).hasClass('active');

        $(this).toggleClass('active', isActive);
        $(this).attr('data-active', isActive ? 'true' : 'false');
        $activeInput.val(isActive ? '1' : '0');
    });

    var userrole = '';
    $("#roles").on('change',function(event){
        userrole = event.target.value;
        if(userrole === 'user'){
            $('#welcomeCheck').prop('disabled', true);
        }else{
            $('#welcomeCheck').prop('disabled', false);
        }
    });


    var instruction = false;
    $('#welcomeCheck').on('change',function(event){
        var isChecked = event.target.checked;
        instruction = isChecked ? true : false;
    });
    
    $('.createUser').on('click',function(event){
        event.preventDefault();
        var name = $('#name').val().trim();
        var username = $('#username').val().trim();
        var email = $('#email').val().trim();
        var bio = $('#bio').val().trim();
        var userpassword = $('#userpassword').val().trim();
        var userconfirmpassword = $('#userconfirmpassword').val().trim();
        var status = $activeInput.val() === '1' ? 'active' : 'blocked'
        if(!name){
            alert('Name is required');
            return false;
        }
        if(!username){
            alert('Username is required');
            return false;
        }
        if(!email){
            alert('Email is required');
            return false;
        }

        if(!userpassword){
            alert('Password is required');
            return false;
        }
        if(!userconfirmpassword){
            alert('Password is required');
            return false;
        }
        if(userpassword !== userconfirmpassword){
            alert("Password doesn't match");
            return false;
        }
        if(!userrole){
            alert("Select the user role");
            return false;
        }

        

        var form = new FormData();

        form.append('name', name);
        form.append('username', username);
        form.append('emailaddress', email);
        form.append('bio', bio);
        form.append('image', image);
        form.append('role', userrole);
        form.append('password', userpassword);
        form.append('instruction', instruction);
        form.append('password_confirmation', userconfirmpassword);
        form.append('status', status);
        $.ajax({
            url:'/addUser',
            type:'POST',
            data:form,
            contentType:false,
            processData:false,
            success:function(data){
                alert(data.message);
                window.location.href = '/users';
            },
            error:function(xhr){
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    if (xhr.responseJSON.errors.name) {
                        alert(xhr.responseJSON.errors.name[0]);
                        return false;
                    }
                    if (xhr.responseJSON.errors.username) {
                        alert(xhr.responseJSON.errors.username[0]);
                        return false;
                    }
                    if (xhr.responseJSON.errors.emailaddress) {
                        alert(xhr.responseJSON.errors.emailaddress[0]);
                        return false;
                    }
                    if (xhr.responseJSON.errors.role) {
                        alert(xhr.responseJSON.errors.role[0]);
                        return false;
                    }
                    if (xhr.responseJSON.errors.password) {
                        alert(xhr.responseJSON.errors.password[0]);
                        return false;
                    }
                } 
                else if (xhr.responseJSON && xhr.responseJSON.message) {
                    alert(xhr.responseJSON.message);
                } 
                else {
                    alert('Something went wrong');
                }
            }
        })
    });


    function fetchEditUser(){
        var param = new URLSearchParams(window.location.search);
        var editId = param.get('userid');

        if(!editId) return;
        $.ajax({
            url:`/fetchedituser/${editId}`,
            type:'GET',
            success:function(data){
                $('#editname').val(data.user.name);
                $('#editusername').val(data.user.username);
                $('#editemail').val(data.user.email);
                $('#editbio').val(data.user.bio);
                $('#editrole').val(data.user.role);

                if (data.user.status === 'active') {
                    $('#accountActiveToggle').addClass('active').attr('data-active', 'true');
                    $('#isActiveInput').val('1');
                } else {
                    $('#accountActiveToggle').removeClass('active').attr('data-active', 'false');
                    $('#isActiveInput').val('0');
                }
                if (data.user.image) {
                    $('#previewImage')
                        .attr('src', `/uploads/${data.user.image}`)
                        .show();
                    $('#uploadCircle .upload-placeholder').hide();
                    $('#removeImage').show();
                } else {
                    $('#previewImage').attr('src', '').hide();
                    $('#uploadCircle .upload-placeholder').show();
                    $('#removeImage').hide();
                }

            },
            error:function(xhr){
                console.log(xhr.message);
            }
        });
    }
    fetchEditUser();

    $('.updateuser').on('click',function(event){
        event.preventDefault();
        var param = new URLSearchParams(window.location.search);
        var editId = param.get('userid');

        var editname = $('#editname').val();
        var editusername = $('#editusername').val();
        var editemail = $('#editemail').val();
        var editbio = $('#editbio').val();
        var editrole = $('#editrole').val();
        var imageFile = $('#profileImage')[0].files[0];
        var status = $activeInput.val() === '1' ? 'active' : 'blocked'

        if(!editname){
            alert('Name field is required');
            return false;
        }
        if(!editusername){
            alert('Username field is required');
            return false;
        }
        if(!editemail){
            alert('Email field is required');
            return false;
        }

        if(!editrole){
            alert('Role field is required');
            return false;
        }

        var form = new FormData();

        form.append('name', editname);
        form.append('username', editusername);
        form.append('email', editemail);
        form.append('bio', editbio);
        if (imageFile) {
            form.append('image', imageFile);
        }
        form.append('role', editrole);
        form.append('status', status);

        $.ajax({
            url:`/edituser/${editId}`,
            type:'POST',
            headers:{
                'X-HTTP-Method-Override':'PUT'
            },
            data:form,
            contentType:false,
            processData:false,
            success:function(data){
                alert(data.message);
                window.location.href = '/users';
            },
            error:function(xhr){
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    if (xhr.responseJSON.errors.name) {
                        alert(xhr.responseJSON.errors.name[0]);
                        return false;
                    }
                    if (xhr.responseJSON.errors.username) {
                        alert(xhr.responseJSON.errors.username[0]);
                        return false;
                    }
                    if (xhr.responseJSON.errors.emailaddress) {
                        alert(xhr.responseJSON.errors.emailaddress[0]);
                        return false;
                    }
                    if (xhr.responseJSON.errors.role) {
                        alert(xhr.responseJSON.errors.role[0]);
                        return false;
                    }
                    if (xhr.responseJSON.errors.password) {
                        alert(xhr.responseJSON.errors.password[0]);
                        return false;
                    }
                } 
                else if (xhr.responseJSON && xhr.responseJSON.message) {
                    alert(xhr.responseJSON.message);
                } 
                else {
                    alert('Something went wrong');
                }
            }
        })

    });

    // $('#profileImage').on('change',function(event){
    //     event.preventDefault();
    //     var param = new URLSearchParams(window.location.search);
    //     var editId = param.get('userid');
    //     var imageFile = $(this)[0].files[0];
    //     var maxSize = 3 * 1024 * 1024;   // 3MB in bytes
    //     if (imageFile.size > maxSize) {
    //         alert('File size must be 3MB or less');
    //         $(this).val('');
    //         return;
    //     }

    //     var form = new FormData();
    //     if (imageFile) {
    //         form.append('image', imageFile);
    //     }

    //     $.ajax({
    //         url:`/updateImage/${editId}`,
    //         type:'POST',
    //         headers:{
    //             'X-HTTP-Method-Override':'PUT'
    //         },
    //         data:form,
    //         contentType:false,
    //         processData:false,
    //         success:function(data){
    //             alert(data.message);
    //         },
    //         error:function(xhr){
    //               alert(xhr.responseJSON.message);
    //         }
    //     })
    // });
   

});