$(document).ready(function(){

    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
        }
    });

    const timeAgo = (date) => {
        if(!date) return 'Just now';
        const now = new Date();
        const created = new Date(date);
        const seconds = Math.floor((now - created) / 1000);
        if (seconds < 60) return "Just now";
        const minutes = Math.floor(seconds / 60);
        if (minutes < 60) return `${minutes}m ago`;
        const hours = Math.floor(minutes / 60);
        if (hours < 24) return `${hours}h ago`;
        const days = Math.floor(hours / 24);
        if (days < 7) return `${days}d ago`;
        return created.toLocaleDateString();
    };

    function postComments(){
        var params = new URLSearchParams(window.location.search);
        var postId = params.get('post');
        if(!postId) return;

        $.ajax({
            url:`/fetch-post-comments/${postId}`,
            type:'GET',
            success:function(data){
                var comments = data.postComments || data.comments || data.postComment; 
                var $wrapper = $('.comments-list'); 
                
                $wrapper.empty(); 

                if(!comments || comments.length === 0){
                    $wrapper.append('<p class="text-muted">No comments yet.</p>');
                    return;
                }

                // Current logged-in user check (Blade se ID lein)
                var currentUserId = $('#currentUserId').val();

                $.each(comments, function(index, comment){
                    var authorName = (comment.user && comment.user.name) ? comment.user.name : 'Unknown User';
                    var nameParts = authorName.trim().split(/\s+/);
                    var firstletter = (nameParts[0] || '').charAt(0).toUpperCase();
                    var secondLetter = (nameParts[1] || '').charAt(0).toUpperCase();

                    var avatarHtml = '';
                    if(comment.user && comment.user.image){
                        avatarHtml = `<img src="/uploads/${esc(comment.user.image)}" alt="${esc(authorName)}" class="comment-avatar">`;
                    } else {
                        avatarHtml = `<div class="comment-avatar-initials">${esc(firstletter)}${esc(secondLetter)}</div>`;
                    }

                    var commentDate = comment.created_at ? timeAgo(comment.created_at) : 'Recently';

                    // Check karein ke comment usi user ka hai ya nahi (Owner check)
                    var showOptions = (currentUserId == comment.user_id);

                    // 3-Dot Menu HTML
                    var menuHtml = '';
                    if(showOptions){
                        menuHtml = `
                        <div class="comment-options" data-id="${comment.id}">
                            <button class="btn-options"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                            <div class="options-dropdown">
                                <button class="btn-edit-comment"><i class="fa-solid fa-pen"></i> Edit</button>
                                <button class="btn-delete-comment"><i class="fa-solid fa-trash"></i> Delete</button>
                            </div>
                        </div>`;
                    }

                    var commentHtml = `
                        <div class="comment-item" id="comment-${esc(comment.id)}">
                            ${avatarHtml}
                            <div class="comment-content-wrapper">
                                <div class="comment-header d-flex justify-content-between align-items-start">
                                    <p class="comment-author mb-0">
                                        ${esc(authorName)}
                                        <span class="comment-time">${esc(commentDate)}</span>
                                    </p>
                                    ${menuHtml}
                                </div>
                                
                                <!-- Normal Text -->
                                <p class="comment-text text-display">${esc(comment.comment)}</p>
                                
                                <!-- Edit Input (Hidden by default) -->
                                <div class="edit-section d-none">
                                    <textarea class="form-control comment-edit-input mb-2">${esc(comment.comment)}</textarea>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-dark btn-update-comment" data-id="${comment.id}">Update</button>
                                        <button class="btn btn-sm btn-light btn-cancel-edit">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    $wrapper.append(commentHtml);
                });
            },
            error:function(xhr){
                console.log(xhr);
            }
        });
    }
    postComments();

    // ============================================
    // 3-Dot Menu Toggle
    // ============================================
    $(document).on('click', '.btn-options', function(e) {
        e.stopPropagation();
        $('.options-dropdown').removeClass('show'); // close all others
        $(this).next('.options-dropdown').toggleClass('show');
    });

    // Click anywhere to close menu
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.comment-options').length) {
            $('.options-dropdown').removeClass('show');
        }
    });

    // ============================================
    // Edit Button Click (Show Input)
    // ============================================
    $(document).on('click', '.btn-edit-comment', function(e) {
        e.preventDefault();
        var $item = $(this).closest('.comment-item');
        
        // Hide normal text, show edit section
        $item.find('.text-display').addClass('d-none');
        $item.find('.edit-section').removeClass('d-none');
        $item.find('.options-dropdown').removeClass('show');
    });

    // ============================================
    // Cancel Edit
    // ============================================
    $(document).on('click', '.btn-cancel-edit', function(e) {
        e.preventDefault();
        var $item = $(this).closest('.comment-item');
        
        $item.find('.text-display').removeClass('d-none');
        $item.find('.edit-section').addClass('d-none');
    });

    // ============================================
    // Update Comment
    // ============================================
    $(document).on('click', '.btn-update-comment', function(e) {
        e.preventDefault();
        var commentId = $(this).data('id');
        var $item = $(this).closest('.comment-item');
        var newComment = $item.find('.comment-edit-input').val().trim();

        var params = new URLSearchParams(window.location.search);
        var postId = params.get('post');

        if(!newComment) {
            alert('Comment cannot be empty');
            return;
        }

        var $btn = $(this);
        $btn.prop('disabled', true).text('Updating...');

        $.ajax({
            url: `/update-comment/${commentId}`,
            type: 'POST',
            data: {
                _method: 'PUT',
                comment: newComment,
                post_id: postId
            },
            success: function(res) {
                alert('Comment updated successfully!');
                postComments(); 
            },
            error: function(xhr) {
                console.log(xhr);
                alert('Failed to update comment');
                $btn.prop('disabled', false).text('Update');
            }
        });
    });

    // ============================================
    // Delete Comment
    // ============================================
    $(document).on('click', '.btn-delete-comment', function(e) {
        e.preventDefault();
        var $options = $(this).closest('.comment-options');
        var commentId = $options.data('id');
        
        if(!confirm('Are you sure you want to delete this comment?')) {
            return;
        }

        $.ajax({
            url: `/delete-comment/${commentId}`,
            type: 'DELETE',
            success: function(res) {
                alert('Comment deleted successfully!');
                postComments(); // Refresh list
            },
            error: function(xhr) {
                console.log(xhr);
                alert('Failed to delete comment');
            }
        });
    });

});