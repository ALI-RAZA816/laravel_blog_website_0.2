$(document).ready(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var currentQuery = 'all';     
    var currentField = 'search';

    function fetchPosts(query = 'all', field='search', page = 1) {

        currentQuery = query;
        currentField = field;

        $.ajax({
        url: '/fetchpost',
        type: 'GET',
        data: { query: query, field: field, page: page },  
        success:function(data){
                var posts = data.posts.data;
                $('.velocity').text(`+${data.velocity}%`);
                $('#post-record').empty();
                $.each(posts,function(index, post){
                    var avatarHtml = '';
                    if (post.author && post.author.image) {
                        avatarHtml = `<img src="/uploads/${post.author.image}" class="table-avatar" alt="">`;
                    } else {
                        var authorName = post.author ? post.author.name : 'Unknown';
                        var parts = authorName.trim().split(/\s+/);
                        var firstletter = (parts[0] || '').charAt(0).toUpperCase();
                        var secondLetter = (parts[1] || '').charAt(0).toUpperCase();
                        avatarHtml = `<div class="table-avatar initials">${firstletter}${secondLetter}</div>`;
                    }

                    var row = `
                    <tr>
                        <td><input type="checkbox" class="custom-checkbox post-checkbox" data-id="${post.id}"></td>
                        <td>
                            <p class="post-title-main">${esc(post.title)}</p>
                            <p class="post-slug">${esc(post.category.slug || '')}</p>
                        </td>
                        <td><span class="badge-category">${esc(post.category.name)}</span></td>
                        <td>
                            <div class="author-cell">
                                ${avatarHtml}
                                <span class="author-name">${esc(post.author.name)}</span>
                            </div>
                        </td>
                        <td>
                            <span class="${post.published === 'published' ? 'status-published' : 'status-draft'}">
                                ${post.published === 'published' ? 'Published' : 'Draft'}
                            </span>
                        </td>
                        <td style="font-size: 0.85rem; color: #6b7280;">${esc(post.date)}</td>
                        <td>
                            <div class="action-icons">
                                <a href="/post-preview?postid=${post.id}"><i class="fa-regular fa-eye"></i></a>
                                <a href="/edit-post?postid=${post.id}"> <i class="fa-solid fa-pencil"></i></a>
                                <i class="fa-regular fa-trash-can deletepost" data-id=${post.id}></i>
                            </div>
                        </td>
                        </tr>
                    `;
                    $('#post-record').append(row);
                });
                $('.pagination-text').empty();
                var pagination =  `Showing ${data.posts.from} to ${data.posts.to} of ${data.posts.total} categories`
                $('.pagination-text').append(pagination);
                renderPagination(data);
                updateSelectedCount();
                renderChart(data.this_month);
                console.log(data);
            },
            error:function(xhr){
                console.log(xhr);
            }
        });
    }
    if ($('#post-record').length) {
        fetchPosts('all', 'search', 1);
    }

   function renderChart(chartData){
    if (!chartData || chartData.length === 0) return;
    if (!document.getElementById('velocityChart')) return;
 
        var labels = [];
        var data = [];

        var monthNames = ["", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        var displayData = chartData;
        displayData = chartData.slice(-12);
 
        $.each(displayData, function(index, item) {
            var monthName = monthNames[item.month] || 'N/A'; 
            
            labels.push(monthName + ' ' + item.year); 
            
            data.push(item.total_post); 
        });
       const ctx = document.getElementById('velocityChart').getContext('2d');
               
       new Chart(ctx, {
           type: 'bar',
           data: {
               labels: labels,
               datasets: [{
                   label: 'Posts',
                   data: data,
                   backgroundColor: [
                       '#d1d5fc', '#d1d5fc', '#d1d5fc', '#d1d5fc', '#d1d5fc', '#d1d5fc', 
                       '#d1d5fc', '#d1d5fc', '#d1d5fc', '#d1d5fc', '#d1d5fc', 
                       '#2563eb' // Last bar is dark blue
                   ],
                   borderRadius: 4,
                   barPercentage: 0.6
               }]
           },
           options: {
               responsive: true,
               maintainAspectRatio: false,
               plugins: {
                   legend: { display: false }
               },
               scales: {
                   y: {
                       beginAtZero: true,
                       ticks: {
                            precision:0,
                            stepSize:1,
                           color: '#9ca3af',
                           font: { size: 10, family: 'Inter' }
                       },
                       grid: {
                           color: '#f3f4f6',
                           drawBorder: false
                       }
                   },
                   x: {
                       ticks: {
                           color: '#9ca3af',
                           font: { size: 9, family: 'Inter' }
                       },
                       grid: { display: false }
                   }
               }
           }
       });
   }

    function renderPagination(p) {
        var current = p.posts.current_page;
        var last    = p.posts.last_page;

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
        if (page) fetchPosts(currentQuery, currentField, page);
    });

    if ($('#myeditor').length) {
        $('#myeditor').summernote({
            height: 300,
            fontNames: ['Arial', 'Georgia', 'Courier New', 'Times New Roman', 'Helvetica', 'Verdana', 'Tahoma', 'Trebuchet MS'],
            fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '24', '28', '32', '36', '48'],
            toolbar: [
                ['style', ['style']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link', 'hr']],
                ['view', ['codeview', 'help']],
                ['history', ['undo', 'redo']]
            ]
        });
    }

    // ============================================
    // CATEGORIES — dynamic
    // ============================================
    function fetchCategories(selectedId) {
        $.ajax({
            url: '/postcategories',
            type: 'GET',
            success: function (data) {
                var categories = data.allCat || [];
                $('.categories').empty();
                $('.categories').append('<option disabled selected>Select category</option>');

                $.each(categories, function (index, cat) {
                    $('.categories').append(`<option value="${cat.id}">${esc(cat.name)}</option>`);
                });

                if (selectedId) {
                    $('.categories').val(selectedId);
                }
            },
            error: function (xhr) {
                console.log(xhr);
            }
        });
    }
    fetchCategories();

    // ============================================
    // IMAGE PREVIEW
    // ============================================
    var $box     = $('#imageUploadBox');
    var $input   = $('#coverImageInput');
    var $preview = $('#coverPreview');
    var $text    = $('#uploadText');
    var $icon    = $box.find('i');

    $input.on('change', function () {
        var file = this.files[0];
        if (!file) return;

        if (!file.type.match('image.*')) {
            alert('File type must be an image');
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
            $text.hide();
            $icon.hide();
            $box.addClass('has-image');
        };
        reader.readAsDataURL(file);
    });

    // ============================================
    // TAGS
    // ============================================
    var tags = [];

    function renderTags() {
        $('.tags-container').empty();

        $.each(tags, function (index, tag) {
            var tagHtml = `
                <span class="badge-tag">
                    ${esc(tag)}
                    <i class="fa-solid fa-xmark tag-remove" data-index="${index}"></i>
                </span>`;
            $('.tags-container').append(tagHtml);
        });
    }

    $('#tagsinput').on('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();

            var value = $(this).val().trim();
            if (!value) return;

            if (tags.length >= 5) {
                alert('Only 5 tags are allowed');
                return;
            }

            if (tags.includes(value)) {
                $(this).val('');
                return;
            }

            tags.push(value);
            $(this).val('');
            renderTags();
        }
    });

    $(document).on('click', '.tag-remove', function () {
        var index = $(this).data('index');
        tags.splice(index, 1);
        renderTags();
    });

    // ============================================
    // TOGGLE STATUS
    // ============================================
    // ============================================
    var $toggle     = $('#statusToggle');
    var $label      = $('#statusLabel');
    var $visibility = $('#visibilityValue');
    var $hidden     = $('#statusInput');

    var published = 'draft';

    $toggle.on('click', function () {
        var isPublished = !$(this).hasClass('active');

        $(this).toggleClass('active', isPublished);
        $(this).attr('data-status', isPublished ? 'published' : 'draft');

        published = isPublished ? 'published' : 'draft';

        $label.text(isPublished ? 'Published' : 'Draft');
        $visibility.text(isPublished ? 'Published' : 'Draft');
        $hidden.val(published);
    });


    // ============================================
    // FETCH SINGLE POST — edit form YA preview page
    // ============================================
    function fetchSinglePost() {
        var param  = new URLSearchParams(window.location.search);
        var postId = param.get('postid');
        if (!postId) return;

        $.ajax({
            url: `/fetchSingle/${postId}`,
            type: 'GET',
            success: function (data) {
                var post = data.post;
                console.log('Post:', post);

                // ==========================================
                // EDIT FORM (agar edit page ho)
                // ==========================================
                if ($('#posttitle').length) {
                    $('#posttitle').val(post.title || '');

                    if ($('#myeditor').length) {
                        $('#myeditor').summernote('code', post.description || '');
                    }

                    if (post.category && post.category.id) {
                        fetchCategories(post.category.id);
                    }

                    // Tags
                    tags = [];
                    if (Array.isArray(post.tags)) {
                        tags = post.tags;
                    } else if (typeof post.tags === 'string' && post.tags.trim() !== '') {
                        try {
                            tags = JSON.parse(post.tags);
                        } catch (e) {
                            tags = post.tags.split(',').map(t => t.trim()).filter(Boolean);
                        }
                    }
                    renderTags();

                    // Status toggle
                    var status      = post.published || post.status || 'draft';
                    var isPublished = (status === 'published' || status === 1 || status === true);

                    published = isPublished ? 'published' : 'draft';
                    $toggle.toggleClass('active', isPublished).attr('data-status', published);
                    $label.text(isPublished ? 'Published' : 'Draft');
                    $visibility.text(isPublished ? 'Published' : 'Draft');
                    $hidden.val(published);

                    // Featured image
                    if (post.image) {
                        $preview.attr('src', `/posts-images/${post.image}`).show();
                        $text.hide();
                        $icon.hide();
                        $box.addClass('has-image');
                    } else {
                        $preview.attr('src', '').hide();
                        $text.show();
                        $icon.show();
                        $box.removeClass('has-image');
                    }
                }

                // ==========================================
                // PREVIEW PAGE (agar preview page ho)
                // ==========================================
                if ($('#articleTitle').length) {

                    // Hero image
                    if (post.image) {
                        $('#heroImage').attr('src', `/posts-images/${post.image}`).show();
                    } else {
                        $('#heroImage').hide();
                    }

                    // Category badge
                    if (post.category && post.category.name) {
                        $('#heroBadge').text(post.category.name).show();
                        $('#postCategory').text(post.category.name);
                    } else {
                        $('#heroBadge').hide();
                        $('#postCategory').text('Uncategorized');
                    }

                    // Title
                    $('#articleTitle').text(post.title || 'Untitled');

                    // Author
                    var authorName = (post.author && post.author.name) ? post.author.name : 'Unknown';

                    if (post.author && post.author.image) {
                        $('#authorAvatar').html(
                            `<img src="/uploads/${esc(post.author.image)}" alt="${esc(authorName)}"
                                style="width:100%;height:100%;object-fit:cover;border-radius:50%;">`
                        );
                    } else {
                        var parts    = (authorName || '').trim().split(/\s+/);
                        var initials = ((parts[0] || '').charAt(0) + (parts[1] || '').charAt(0)).toUpperCase();
                        $('#authorAvatar').html(`<span>${initials || 'U'}</span>`);
                    }

                    $('#authorName').text(authorName);
                    $('#authorDate').text(post.created_at || post.date || '');

                    // Content
                    $('#articleBody').html(DOMPurify.sanitize(post.description || '<p>No content.</p>'));

                    // Tags
                    $('#postTags').empty();
                    var postTags = [];
                    if (Array.isArray(post.tags)) {
                        postTags = post.tags;
                    } else if (typeof post.tags === 'string' && post.tags.trim() !== '') {
                        try {
                            postTags = JSON.parse(post.tags);
                        } catch (e) {
                            postTags = post.tags.split(',').map(t => t.trim()).filter(Boolean);
                        }
                    }

                    if (postTags.length === 0) {
                        $('#postTags').html('<span class="badge-tag text-muted">No tags</span>');
                    } else {
                        $.each(postTags, function (i, tag) {
                            $('#postTags').append(`<span class="badge-tag">${esc(tag)}</span>`);
                        });
                    }

                    // Visibility
                    var status      = post.published || post.status || 'draft';
                    var isPublished = (status === 'published' || status === 1 || status === true);

                    $('#postVisibility')
                        .text(isPublished ? 'Published' : 'Draft')
                        .attr('class', 'visibility-status ' + (isPublished ? 'status-published' : 'status-draft'));
                }
            },
            error: function (xhr) {
                console.log(xhr);
                if (xhr.status === 404) alert('Post not found');
            }
        });
    }
    fetchSinglePost();


    // ============================================
    // UPDATE POST
    // ============================================
    $('.btn-update').on('click', function (event) {
        event.preventDefault();

        var param  = new URLSearchParams(window.location.search);
        var postId = param.get('postid');
        if (!postId) {
            alert('Post ID missing');
            return;
        }

        var postTitle    = $('#posttitle').val().trim();
        var postContent  = $('#myeditor').length ? $('#myeditor').summernote('code') : '';
        var postImage    = $('#coverImageInput')[0].files[0];
        var postCategory = $('#post-category').val();

        // Validation
        if (!postTitle) {
            alert('Title is required');
            return;
        }
        if (!postContent || postContent === '<p><br></p>') {
            alert('Description is required');
            return;
        }
        if (!postCategory) {
            alert('Please select category');
            return;
        }
        if (tags.length === 0) {
            alert('At least one tag is required');
            return;
        }

        var form = new FormData();
        form.append('title', postTitle);
        form.append('description', postContent);
        form.append('category', postCategory);
        form.append('tags', JSON.stringify(tags));
        form.append('published', published || 'draft');
        form.append('_method', 'PUT');

        if (postImage) {
            form.append('image', postImage);
        }

        $.ajax({
            url: `/updatepost/${postId}`,
            type: 'POST',
            data: form,
            contentType: false,
            processData: false,
            success: function (data) {
                alert(data.message);
                window.location.href = '/posts';
            },
            error: function (xhr) {
                console.log(xhr.responseJSON);
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    var firstKey = Object.keys(errors)[0];
                    alert(errors[firstKey][0]);
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    alert(xhr.responseJSON.message);
                } else {
                    alert('Something went wrong');
                }
            }
        });
    });

        // ============================================
    // DELETE POST
    // ============================================
    $(document).on('click', '.deletepost', function (event) {
        event.preventDefault();

        var postId = $(this).data('id');
        if (!postId) {
            alert('Post ID missing');
            return;
        }

        if (!confirm('Are you sure you want to delete this?')) return;

        var $row = $(this).closest('tr');

        $.ajax({
            url: `/deletepost/${postId}`,
            type: 'POST',
            data: {
                _method: 'DELETE'
            },
            success: function (data) {
                alert(data.message);
                $row.fadeOut(300, function () { $(this).remove(); });
                fetchPosts();   // list refresh
            },
            error: function (xhr) {
                console.log(xhr.responseJSON);
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    alert(xhr.responseJSON.message);
                } else {
                    alert('Something went wrong');
                }
            }
        });
    });

    // ============================================
    // BULK DELETE — select all + count
    // ============================================
    function updateSelectedCount() {
        var count = $('.post-checkbox:checked').length;
        $('#selectedCount').text(count);

        if (count > 0) {
            $('#bulkDeleteBtn').show(); 
            $('#searchinput').hide();
        } else {
            $('#searchinput').show(); 
            $('#bulkDeleteBtn').hide();
        }

        // Select All checkbox ka state
        var total = $('.post-checkbox').length;
        $('#selectAllPosts').prop('checked', total > 0 && count === total);
    }

    // Individual checkbox
    $(document).on('change', '.post-checkbox', function () {
        updateSelectedCount();
    });

    // Select All
    $(document).on('change', '#selectAllPosts', function () {
        var isChecked = $(this).prop('checked');
        $('.post-checkbox').prop('checked', isChecked);
        updateSelectedCount();
    });

    // Bulk delete
    $(document).on('click', '#bulkDeleteBtn', function () {
        var ids = [];

        $('.post-checkbox:checked').each(function () {
            ids.push($(this).data('id'));
        });

        if (ids.length === 0) {
            alert('Select the post to delete');
            return;
        }

        if (!confirm('Are you sure you want to delete this?')) return;

        $.ajax({
            url: '/deleteposts',
            type: 'POST',
            data: {
                _method: 'DELETE',
                ids: ids
            },
            success: function (data) {
                alert(data.message);
                $('#selectAllPosts').prop('checked', false);
                fetchPosts();  
            },
            error: function (xhr) {
                console.log(xhr.responseJSON);
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    alert(xhr.responseJSON.message);
                } else {
                    alert('Something went wrong');
                }
            }
        });
    });


    // ============================================
    // SEARCH — 1 second debounce
    // ============================================
    var searchTimer;

    $('#searchinput').on('input', function () {
        clearTimeout(searchTimer);

        var value = $(this).val().trim();

        searchTimer = setTimeout(function () {
            var query    = value || 'all';

            fetchPosts(query, 'search', 1);
        }, 1000);
    });


    // ============================================
    // CATEGORY FILTER
    // ============================================
    $('.categories').on('change', function () {
        var query    = $(this).val().trim() || 'all';

        fetchPosts(query, 'category', 1);
    });


    // ============================================
    // STATUS FILTER
    // ============================================
    $(document).on('click', '.status-tab', function () {
        $('.status-tab').removeClass('active');
        $(this).addClass('active');

        var query    = $(this).data('status') || 'all';

        fetchPosts(query, 'status', 1);
    });


});