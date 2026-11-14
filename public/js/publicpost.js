$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    const timeAgo = (date) => {
    
        const now = new Date();
        const created = new Date(date);

        const seconds = Math.floor((now - created) / 1000);

        if (seconds < 5) {
            return "Just now";
        }

        if (seconds < 60) {
            return `${seconds}s ago`;
        }

        const minutes = Math.floor(seconds / 60);

        if (minutes < 60) {
            return `${minutes}m ago`;
        }

        const hours = Math.floor(minutes / 60);

        if (hours < 24) {
            return `${hours}h ago`;
        }

        const days = Math.floor(hours / 24);

        if (days < 7) {
            return `${days}d ago`;
        }

        const weeks = Math.floor(days / 7);

        if (weeks < 4) {
            return `${weeks}w ago`;
        }

        const months = Math.floor(days / 30);

        if (months < 12) {
            return `${months}mo ago`;
        }

        const years = Math.floor(days / 365);

        return `${years}y ago`;
    };

    function fetchPublicPost(page=1) {
        $.ajax({
            url: `/public-post?page=${page}`,
            type: 'GET',
            success: function (data) {
                var posts = data.allPost.data;
                var popular = data.popularPost;
                $('.stories__grid').empty();
                $('.popular').empty();

                $.each(posts, function (index, post) {
                    var postHtml = ` <article class="card">
                                    <a href="/single-view?post=${post.id}" class="card__image">
                                        <img src="posts-images/${post.image}" alt="${esc(post.title)}">
                                    </a>
                                    <div class="card__meta">
                                        <span class="card__cat">${esc(post.category.name)}</span>
                                        <span class="card__info"><i class="fa-regular fa-clock"></i>${timeAgo(post.created_at)}</span>
                                        <span class="card__info"><i class="fa-regular fa-calendar"></i> ${post.date}</span>
                                    </div>
                                    <h3 class="card__title"><a href="/single-view?post=${post.id}">${esc(post.title)}</a></h3>
                                </article>
                                `;
                    $('.stories__grid').append(postHtml);
                });

                $.each(popular, function (index, post) {
                    var postHtml = `   <li class="popular__item">
                                        <a href="/single-view?post=${post.id}" class="popular__thumb<img src="posts-images/${esc(post.image)}" alt="${esc(post.title)}"></a>
                                        <div class="popular__body">
                                            <h5 class="popular__title"><a href="/single-view?post=${post.id}">${esc(post.title)}</a></h5>
                                            <div class="popular__meta">
                                                <span class="popular__date">${esc(post.date)}</span>
                                                <span class="popular__badge">${esc(post.category.name)}</span>
                                            </div>
                                        </div>
                                    </li>
                                `;
                    $('.popular').append(postHtml);
                });
                $('.pagination-text').empty();
                var pagination = `Showing ${data.allPost.from} to ${data.allPost.to} of ${data.allPost.total} posts`
                $('.pagination-text').append(pagination);
                renderPostPagination(data); 
            },
            error: function (xhr) {
                console.log(xhr);
            }
        });
    };

    if ($('.stories__grid').length) { fetchPublicPost(); }
    function renderPostPagination(p){
        var current = p.allPost.current_page;
        var last    = p.allPost.last_page;

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
        if (!$('.stories__grid').length) return;     // <-- nayi line
        e.preventDefault();
        if ($(this).prop('disabled')) return;
        var page = $(this).data('page');
        if (page) fetchPublicPost(page);
    });


    function publicCategory() {
        $.ajax({
            url: '/public-category',
            type: 'GET',
            success: function (data) {
                var categories = data.allCategories;
                $('.tags').empty();
                categories.forEach(function(category) {
                    $('.tags').append(`<a href="#" class="tags__item">${esc(category.name)} (${esc(category.post_count)})</a>`);
                });
            },
            error: function (xhr) {
                console.log(xhr);
            }
        });
    }
    publicCategory();


    function fetchSinglePost()
    {
        var params = new URLSearchParams(window.location.search);
        var postId = params.get('post');
        if(!postId) return;

        $.ajax({
            url: `/public-single-post/${postId}`,
            type: 'GET',
            success: function (data) {
                if(data.post.category.name){
                    $('.post-category-badge').text(data.post.category.name);
                }
                if(data.post.title){
                    $('.post-hero-title').text(data.post.title);
                }

                if(data.post.author.image){
                    $('.author-avatar').attr('src', `uploads/${data.post.author.image}`);
                }

                if(data.post.author.name){
                    $('.author-name').text(data.post.author.name);
                }

                if(data.post.date){
                    $('.author-date').text(data.post.date);
                }

                if(data.post.description){
                    $('.article-body').html(DOMPurify.sanitize(data.post.description));
                }
                if(data.post.image){
                    $('.post-hero-img').attr('src', `posts-images/${data.post.image}`);
                }
            },
            error: function (xhr) {
                console.log(xhr);
            }
        });
    }
    fetchSinglePost();

    function fetchComments(query = 'all', page=1){
        $.ajax({
            url: `/fetchComment?query=${query}&page=${page}`,
            type: 'GET',
            success: function (data) {
                console.log(data);
                var comments = data.comments.data;
                var c = data.counts;
                $('.total-comments').text(fmt(c.total));
                $('.pending-comments').text(fmt(c.pending));
                $('.approved-comments').text(fmt(c.approved));
                $('.rejected-comments').text(fmt(c.rejected));
                $('.spam-comments').text(fmt(c.spam));
                $('#comment-table-body').empty();
                $.each(comments, function (index, comment) {
                    var nameParts    = (comment.name || '').trim().split(/\s+/);
                    var firstletter  = (nameParts[0] || '').charAt(0).toUpperCase();
                    var secondLetter = (nameParts[1] || '').charAt(0).toUpperCase();

                    var commentHtml = `<tr>
                        <td>
                            <div class="author-cell">`;
                            if(comment.user.image){
                                commentHtml += `<img src="uploads/${esc(comment.user.image)}" class="avatar-img" alt="User">`;
                            }else{
                                commentHtml += `<div class="avatar-placeholder">${firstletter}${secondLetter}</div>`;
                            }
                            commentHtml +=` <div>
                                    <p class="author-name">${esc(comment.user.name)}</p>
                                    <p class="author-email">${esc(comment.user.email)}</p>
                                </div>
                            </div>
                        </td>
                        <td><p class="comment-excerpt">${esc(comment.comment)}</p></td>
                        <td><a href="#" class="post-link">${esc(comment.post.title)}</a></td>
                        <td><span class="status-badge ${comment.status === 'approved' ? 'badge-approved' : comment.status === 'rejected' ? 'badge-rejected' : comment.status === 'spam' ? 'badge-spam' : 'badge-pending'}">
                            ${esc(comment.status)}
                        </span></td>
                        <td><span class="date-text">${esc(comment.date)}</span></td>
                        <td>
                            <div class="action-btn-group">
                                <button data-type="approved" data-id="${comment.id}" class="action-btn btn-approve"><i class="fa-solid fa-check"></i></button>
                                <button data-type="rejected" data-id="${comment.id}" class="action-btn btn-reject"><i class="fa-solid fa-xmark"></i></button>
                                <button data-type="spam" data-id="${esc(comment.id)}" class="action-btn btn-spam"><i class="fa-solid fa-triangle-exclamation"></i></button>
                                <button data-id="${comment.id}" class="action-btn btn-delete"><i class="fa-regular fa-trash-can"></i></button>
                            </div>
                        </td>
                    </tr>`;
                    $('#comment-table-body').append(commentHtml);
                });
                $('.pagination-text').empty();
                var pagination =  `Showing ${data.comments.from} to ${data.comments.to} of ${data.comments.total} comments`
                $('.pagination-text').append(pagination);
                renderCommentPagination(data);
            },
            error: function (xhr) {
                console.log(xhr);
            }
        });
    }
    if ($('#comment-table-body').length) { fetchComments('all', 1); } 

    function renderCommentPagination(p){
        if (!p.comments) {
            $('.pagination-controls').empty();
            return; 
        }
        var current = p.comments.current_page;
        var last    = p.comments.last_page;

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
        if (!$('#comment-table-body').length) return;    
        e.preventDefault();
        if ($(this).prop('disabled')) return;
        var page = $(this).data('page');
        if (page) fetchComments('all', page);
    });

    $('.btn-post-comment').prop('disabled', true);
    $('.comment-textarea').on('input', function() {
        if($(this).val().trim() !== '') {
            $('.btn-post-comment').prop('disabled', false);
        }else{
            $('.btn-post-comment').prop('disabled', true);
        }
    });

    $('.btn-post-comment').on('click',function (event){
        event.preventDefault();
        var params = new URLSearchParams(window.location.search);
        var postId = params.get('post');
        var comment = $('.comment-textarea').val().trim();
        if(!comment) return;

        $.ajax({
            url: '/add-comment',
            type: 'POST',
            data:{
                comment:comment,
                post_id:postId
            },
            success: function (data) {
                if(data.message){
                    $('.comment-textarea').val('');
                    alert('Comment added successfully!');
                }else{
                    alert('Failed to add comment.');
                }
            },
            error: function (xhr) {
                var r = xhr.responseJSON;
                if (xhr.status === 429) {
                    alert('Too many comments. Please wait a minute and try again.');
                } else if (xhr.status === 401) {
                    alert('Please login to comment.');
                    window.location.href = '/login';
                } else if (r && r.errors) {
                    alert(Object.values(r.errors)[0][0]);
                } else {
                    alert((r && r.message) || 'Failed to add comment.');
                }
            }
        }); 
    });

    $(document).on('click', '.action-btn', function (e) {
        e.preventDefault();
        var actionType = $(this).data('type');
        var commentId = $(this).data('id');

        $.ajax({
            url: `/update-comment-status/${commentId}`,
            type: 'POST',
            headers:{
                'X-HTTP-Method-Override':'PUT'
            },
            data: {
                status: actionType,
            },
            success: function (data) {
                if (data.message) {
                    fetchComments();
                    alert(data.message);
                } else {
                    alert('Failed to update comment status.');
                }
            },
            error: function (xhr) {
                console.log(xhr);
            }
        });
    });

    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        var commentId = $(this).data('id');
        if (!confirm('Are you sure you want to delete this?')) return;

        $.ajax({
            url: `/delete-comment/${commentId}`,
            type: 'DELETE',
            success: function (data) {
                if (data.message) {
                    fetchComments();
                    alert(data.message);
                } else {
                    alert('Failed to delete comment.');
                }
            },
            error: function (xhr) {
                console.log(xhr);
            }
        });
    });

    $('.filter-tab').on('click', function (e) {
        e.preventDefault();
        var filter = $(this).data('filter');
        $('.filter-tab').removeClass('active');
        $(this).addClass('active');
        fetchComments(filter, 1);
    });
    
    
});