$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

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
            ['insert', ['link','hr']],
            ['view', ['codeview', 'help']],
            ['history', ['undo', 'redo']]
        ],
    });

    function fetchCategories(){
        $.ajax({
            url:`/postcategories`,
            type:'GET',
            success: function (data) {
                var categories = data.allCat;
                $('.categories').empty();
                $('.categories').append('<option disabled selected>Select category</option>');
                $.each(categories, function(index, cat){
                    var option = `<option value=${cat.id}>${esc(cat.name)}</option>`;
                    $('.categories').append(option);
                });
            },
            error:function(xhr){
                  alert(xhr.responseJSON.message);
            }
        })
    }
    fetchCategories();

    
    

    var $box      = $('#imageUploadBox');
    var $input    = $('#coverImageInput');
    var $preview  = $('#coverPreview');
    var $text     = $('#uploadText');
    var $icon     = $box.find('i');

    $input.on('change', function () {
        var file = this.files[0];
        if (!file) return;

        if (!file.type.match('image.*')) {
            alert('File type must be an image');
            $input.val('');
            return;
        }

        if (file.size > 3 * 1024 * 1024) {
            alert('File size must be 3MB or less ');
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

    var tags = [];

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

    function renderTags() {
        $('.tags-container').empty();

        $.each(tags, function (index, tag) {
            var tagHtml = `
                <span class="tag-chip">
                    ${esc(tag)}
                    <i class="fa-solid fa-xmark tag-remove" data-index="${index}"></i>
                </span>`;
            $('.tags-container').append(tagHtml);
        });
    }

    // Remove handler
    $(document).on('click', '.tag-remove', function () {
        var index = $(this).data('index');
        tags.splice(index, 1);                   
        renderTags();                        
    });

    var $toggle = $('#statusToggle');
    var $label  = $('#statusLabel');
    var $status  = $('.status-value');
    var $statusInput  = $('#statusInput');

    var published ='draft';
    $toggle.on('click', function () {
        var isPublished = !$(this).hasClass('active');

            $(this).toggleClass('active', isPublished);
            $(this).attr('data-status', isPublished ? 'published' : 'draft');
            published = isPublished ? 'published' : 'draft';
            $label.text(isPublished ? 'Published' : 'Draft');
            $status.text(isPublished ? 'Published' : 'Draft');
            $statusInput.val(published);
    });

    $('.btn-publish').on('click', function () {

        var $btn        = $(this);
        var postTitle   = $('#posttitle').val().trim();
        var postContent = $('#myeditor').summernote('code').trim();   // textarea .val() ki jagah summernote se lo
        var postImage   = $('#coverImageInput')[0].files[0];
        var postCatgory = $('#post-category').val();

        if (!postTitle) { alert('Title is required'); return; }
        if (!postContent || postContent === '<p><br></p>') { alert('Description is required'); return; }
        if (!postImage) { alert('Image is required'); return; }
        if (!postCatgory) { alert('Please select category'); return; }
        if (tags.length === 0) { alert('At least one post tag is required'); return; }

        var form = new FormData();
        form.append('title', postTitle);
        form.append('description', postContent);
        form.append('image', postImage);
        form.append('category', postCatgory);
        form.append('tags', JSON.stringify(tags));
        form.append('published', published);

        $btn.prop('disabled', true).text('Publishing...');   // double-click band

        $.ajax({
            url: '/addpost',
            type: 'POST',
            data: form,
            contentType: false,
            processData: false,
            success: function (data) {
                alert(data.message);
                window.location.href = '/posts';
            },
            error: function (xhr) {
                $btn.prop('disabled', false).text('Publish post');   // error par button wapas on
                var r = xhr.responseJSON;
                if (r && r.errors) {
                    alert(Object.values(r.errors)[0][0]);
                } else if (r && r.message) {
                    alert(r.message);
                } else {
                    alert('Something went wrong');
                }
            }
        });
    });



});