$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.icon-grid').on('click', '.icon-box', function () {
        $('.icon-grid .icon-box').removeClass('active'); 
        $(this).addClass('active');                      
    });

    $('.add-category-sidebar').hide();
    $('.update-category-sidebar').hide();
    $('.btn-close-custom').on('click', function() {
        $('.add-category-sidebar').hide();
    });
    $('.btn-add-category').on('click', function() {
        $('.add-category-sidebar').show();
    });
  
    $('.btn-cancel').on('click', function() {
        $('.add-category-sidebar').hide();
    });
    $('.edit-close-btn').on('click', function() {
        $('.update-category-sidebar').hide();
    });

 
    // pagination();

    function fetchCategories(page = 1){
        $.ajax({
            url:`/fetchCategories?page=${page}`,
            type:'GET',
            success: function (data) {
                var categories = data.category.data;
                $('.admin-table tbody').empty();

                $.each(categories, function (index, cat) {
                    var row = `
                        <tr data-id="${cat.id}">
                            <td>
                                <div class="category-cell">
                                    <div class="category-icon-box">
                                        <i class="fa-solid ${esc(cat.icon)}"></i>
                                    </div>
                                    <span class="category-name">${esc(cat.name)}</span>
                                </div>
                            </td>
                            <td><span class="category-slug">${esc(cat.slug)}</span></td>
                            <td><span class="badge-count">${esc(cat.post_count)}</span></td>
                            <td>
                                <div class="action-icons">
                                    <i class="fa-solid fa-pencil editCategory" data-edit=${cat.id}></i>
                                    <i class="fa-regular fa-trash-can deleteCategory" data-deleteid=${cat.id}></i>
                                </div>
                            </td>
                        </tr>
                    `;

                    $('.admin-table tbody').append(row);
                });
                $('.pagination-text').empty();
                var pagination =  `Showing ${data.category.from} to ${data.category.to} of ${data.category.total} categories`
                $('.pagination-text').append(pagination);
                renderPagination(data);
            },
            error:function(xhr){
                  alert(xhr.responseJSON.message);
            }
        })
    }
    fetchCategories();

    function renderPagination(p) {
        var current = p.category.current_page;
        var last    = p.category.last_page;

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
        if (page) fetchCategories(page);
    });

    $('.createCategory').on('click',function(event){
        event.preventDefault();
        var categoryName = $('#categoryName').val().trim();
        var categorySlug = $('#categorySlug').val().trim();
        var categoryDesc = $('#categoryDesc').val().trim();
        var selected = $('.icon-grid .icon-box.active').data('icon');

        if(!categoryName){
            alert('Category name is required')
            return;
        }
        
        if(!categorySlug){
            alert('Slug is required')
            return;
        }

        if(!selected){
            alert('Select the icon name')
            return;
        }

        $.ajax({
            url:'/addCategory',
            type:'POST',
            data:{
                cat_name:categoryName,
                slug:categorySlug,
                description:categoryDesc,
                icon_name:selected,
            },
            success:function(data){
                alert(data.message);
                fetchCategories();
                $('.add-category-sidebar').hide();
                $('#categoryName').val('');
                $('#categorySlug').val('');
                $('#categoryDesc').val('');
            },
            error:function(xhr){
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    if (xhr.responseJSON.errors.cat_name) {
                        alert(xhr.responseJSON.errors.cat_name[0]);
                        return false;
                    }
                    if (xhr.responseJSON.errors.slug) {
                        alert(xhr.responseJSON.errors.slug[0]);
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


    $(document).on('click','.fa-pencil', function() {
        $('.update-category-sidebar').show();
        var editId = $(this).data('edit');
        if(!editId) return;
        $.ajax({
            url:`/fetch-single-category/${editId}`,
            type:'GET',
            success:function(data){
                $('#editcategoryname').val(data.category.name);
                $('#editcategoryslug').val(data.category.slug);
                $('#editcategorydesc').val(data.category.description);
                $('#editId').val(data.category.id);
                
                $('.icon-grid .icon-box').each(function(){
                    if($(this).data('selected') === data.category.icon){
                        $(this).addClass('active')
                    }else{
                        $(this).removeClass('active')
                    }
                })
            },
            error:function(xhr){
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    alert(xhr.responseJSON.message);
                    return false;
                } 
                else {
                    alert('Something went wrong');
                }
            }
        })
    });

    $('.btn-update-category').on('click',function(event){
        event.preventDefault();
        var editcatName = $('#editcategoryname').val().trim();
        var editcatSlug = $('#editcategoryslug').val().trim();
        var editcatDesc = $('#editcategorydesc').val().trim();
        var selectedIcon = $('.icon-grid .icon-box.active').data('selected');
        var editId = $('#editId').val();

        if(!editcatName){
            alert('Name is required');
            return;
        }
        
        if(!editcatSlug){
            alert('Slug is required');
            return;
        }

        if(!editId) return;

        $.ajax({
            url: `/updateCategory/${editId}`,
            type:'POST',
            data:{
                cat_name:editcatName,
                slug:editcatSlug,
                description:editcatDesc,
                icon:selectedIcon,
            },
            headers:{
                'X-HTTP-Method-Override':'PUT'
            },
            success:function(data){
                alert(data.message);
                fetchCategories();
                editcatDesc = '';
                editcatName = '';
                editcatSlug = '';
                $('.update-category-sidebar').hide();
            },
            error:function(xhr){
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    if (xhr.responseJSON.errors.cat_name) {
                        alert(xhr.responseJSON.errors.cat_name[0]);
                        return false;
                    }
                    if (xhr.responseJSON.errors.slug) {
                        alert(xhr.responseJSON.errors.slug[0]);
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

    $(document).on('click','.deleteCategory', function(event){
        event.preventDefault();
        if (!confirm('Are you sure you want to delete this category')) return;
        var deleteId = $(this).data('deleteid');
        $.ajax({
            url:`/deleteCategory/${deleteId}`,
            type:'DELETE',
            success:function(data){
                alert(data.message)
                fetchCategories();
            },
            error:function(xhr){
                  alert(xhr.responseJSON.message);
            }
        });
    });


});