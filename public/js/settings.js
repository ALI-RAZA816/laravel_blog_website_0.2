$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var removeLogoFlag = false;

    // ============================================
    // FETCH SETTINGS — Page load par data bharna
    // ============================================
    function fetchSettings() {
        $.ajax({
            url: '/fetchsettings',
            type: 'GET',
            success: function (data) {
                var s = data.setting || {};

                $('#siteTitle').val(s.site_title || '');
                $('#siteDesc').val(s.site_description || '');
                $('#siteCopyright').val(s.site_copyright || '');
                $('#fbUrl').val(s.f_url || '');
                $('#twUrl').val(s.t_url || '');
                $('#igUrl').val(s.i_url || '');
                $('#liUrl').val(s.l_url || '');

                // Image Preview handling
                if (s.site_logo) {
                    $('#logoPreview').attr('src', `/posts-images/${s.site_logo}`).show();
                    $('#logoPlaceholder').hide();
                    $('#logoText').hide(); // Text hide
                } else {
                    $('#logoPreview').attr('src', '').hide();
                    $('#logoPlaceholder').show();
                    $('#logoText').show(); // Text show
                }

                removeLogoFlag = false;
            },
            error: function (xhr) {
                console.log(xhr);
            }
        });
    }
    fetchSettings();

    // ============================================
    // IMAGE PREVIEW — Jab user logo select kare
    // ============================================
    $('#siteLogoInput').on('change', function () {
        var file = this.files[0];
        if (!file) return;

        // Validation: Type
        var allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/svg+xml', 'image/webp'];
        if (allowed.indexOf(file.type) === -1) {
            alert('Sirf JPG, JPEG, PNG, SVG ya WEBP allowed hain');
            $(this).val('');
            return;
        }

        // Validation: Size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Logo 2MB se chota hona chahiye');
            $(this).val('');
            return;
        }

        // Show Preview
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#logoPreview').attr('src', e.target.result).show();
            $('#logoPlaceholder').hide();
            $('#logoText').hide(); // Text hide
        };
        reader.readAsDataURL(file);

        removeLogoFlag = false;
    });

    // ============================================
    // REMOVE LOGO — Direct AJAX delete
    // ============================================
    $('#removeLogo').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var $btn = $(this);
        $btn.text('Removing...'); 

        $.ajax({
            url: '/removelogo',
            type: 'POST',
            data: {
                _method: 'DELETE'
            },
            success: function (data) {
                alert(data.message);
                
                // UI Update
                $('#siteLogoInput').val('');
                $('#logoPreview').attr('src', '').hide();
                $('#logoPlaceholder').show();
                $('#logoText').show(); 
            },
            error: function (xhr) {
                console.log(xhr.responseJSON);
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    alert(xhr.responseJSON.message);
                } else {
                    alert('Something went wrong');
                }
            },
            complete: function () {
                $btn.text('Remove'); // Wapas text
            }
        });
    });

    // ============================================
    // SAVE SETTINGS — Update
    // ============================================
    $('#saveSettings').on('click', function (event) {
        event.preventDefault();

        var siteTitle = $('#siteTitle').val().trim();
        var siteDesc  = $('#siteDesc').val().trim();
        var siteCopy  = $('#siteCopyright').val().trim();
        var fbUrl     = $('#fbUrl').val().trim();
        var twUrl     = $('#twUrl').val().trim();
        var igUrl     = $('#igUrl').val().trim();
        var liUrl     = $('#liUrl').val().trim();

        // Validation
        if (!siteTitle) {
            alert('Site title is required');
            $('#siteTitle').focus();
            return;
        }

        var urlPattern = /^(https?:\/\/)?([\w\-]+\.)+[\w\-]+(\/[\w\-._~:/?#[\]@!$&'()*+,;=]*)?$/i;
        if (fbUrl && !urlPattern.test(fbUrl)) { alert('Facebook URL valid nahi hai'); $('#fbUrl').focus(); return; }
        if (twUrl && !urlPattern.test(twUrl)) { alert('Twitter URL valid nahi hai'); $('#twUrl').focus(); return; }
        if (igUrl && !urlPattern.test(igUrl)) { alert('Instagram URL valid nahi hai'); $('#igUrl').focus(); return; }
        if (liUrl && !urlPattern.test(liUrl)) { alert('LinkedIn URL valid nahi hai'); $('#liUrl').focus(); return; }

        var form = new FormData();
        form.append('site_title', siteTitle);
        form.append('site_desc', siteDesc);
        form.append('site_copyright', siteCopy);
        form.append('f_url', fbUrl);
        form.append('t_url', twUrl);
        form.append('i_url', igUrl);
        form.append('l_url', liUrl);
        form.append('remove_logo', removeLogoFlag ? 1 : 0);

        var logoFile = $('#siteLogoInput')[0].files[0];
        if (logoFile) {
            form.append('site_logo', logoFile);
        }

        // Button loading state
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: '/savesettings',
            type: 'POST',
            data: form,
            contentType: false,
            processData: false,
            success: function (data) {
                alert(data.message);
                fetchSettings(); // Refresh
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
            },
            complete: function () {
                $btn.prop('disabled', false).html('<i class="fa-regular fa-floppy-disk"></i> Save changes');
            }
        });
    });

});