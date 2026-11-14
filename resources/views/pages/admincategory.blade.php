@extends('layouts.admin')
@push('category')
    <link rel="stylesheet" href="{{ asset('css/category.css') }}">
@endpush
@section('title', 'Dashboard - Admin')
@section('page_title', 'Dashboard')
@push('scriptCategory')
    <script src="{{ asset('js/category.js') }}"></script>
@endpush
@section('content')
<!-- MAIN CONTENT START -->
<div class="container-fluid position-relative py-4 px-4" style="max-width: 1300px; margin: 0 auto;">

    <!-- Page Header -->
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Manage Categories</h1>
            <p class="page-subtitle">Dashboard <span>/</span> Categories</p>
        </div>
        <button class="btn-add-category">
            <i class="fa-solid fa-plus"></i> Add Category
        </button>
    </div>

    <!-- Table Container -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 40%;">Name</th>
                        <th style="width: 30%;">Slug</th>
                        <th style="width: 15%;">Post Count</th>
                        <th style="width: 15%; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row 1 -->
                    <tr>
                        <td>
                            <div class="category-cell">
                                <div class="category-icon-box">
                                    <i class="fa-solid fa-brain"></i>
                                </div>
                                <span class="category-name">Mindfulness</span>
                            </div>
                        </td>
                        <td><span class="category-slug">mindfulness</span></td>
                        <td><span class="badge-count">14</span></td>
                        <td>
                            <div class="action-icons">
                                <i class="fa-solid fa-pencil"></i>
                                <i class="fa-regular fa-trash-can"></i>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="pagination-row">
            <p class="pagination-text">Showing 1 to 6 of 6 categories</p>
            <div class="pagination-controls">
                <a href="#" class="page-btn"><i class="fa-solid fa-chevron-left" style="font-size: 0.6rem; color: #d1d5db;"></i></a>
                <a href="#" class="page-btn active">1</a>
                <a href="#" class="page-btn"><i class="fa-solid fa-chevron-right" style="font-size: 0.6rem; color: #d1d5db;"></i></a>
            </div>
        </div>
    </div>
    
    <!-- SIDEBAR ADD CATEGORY START -->
    <div class="add-category-sidebar">
        
        <!-- Header -->
        <div class="sidebar-header">
            <h2 class="sidebar-title">Add category</h2>
            <button class="btn-close-custom">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    
        <!-- Form Area -->
        <form >

            <!-- Category Name -->
            <div class="form-group">
                <label class="form-label">Category name</label>
                <input type="text" id="categoryName" class="form-control-custom" placeholder="e.g. Sustainable living">
            </div>
    
            <!-- Slug -->
            <div class="form-group">
                <label class="form-label">Slug</label>
                <div class="slug-input-group">
                    <span class="slug-prefix">blog.com/</span>
                    <input type="text" id="categorySlug" class="form-control-custom" placeholder="sustainable-living">
                </div>
            </div>
    
            <!-- Description -->
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea class="form-control-custom" id="categoryDesc" placeholder="A short overview of this category"></textarea>
            </div>
    
            <!-- Icon Selection -->
            <div class="form-group mb-4">
                <label class="form-label">Icon</label>
                <div class="icon-grid">
                    <div class="icon-box" data-icon="fa-leaf"><i class="fa-solid fa-leaf"></i></div>
                    <div class="icon-box" data-icon="fa-cannabis"><i class="fa-solid fa-cannabis"></i></div>
                    <div class="icon-box" data-icon="fa-book-open"><i class="fa-solid fa-book-open"></i></div>
                    <div class="icon-box" data-icon="fa-house"><i class="fa-solid fa-house"></i></div>
                    <div class="icon-box" data-icon="fa-brain"><i class="fa-solid fa-brain"></i></div>
                    <div class="icon-box" data-icon="fa-globe"><i class="fa-solid fa-globe"></i></div>
                </div>
            </div>
    
            <!-- Footer Buttons -->
            <div class="sidebar-footer">
                <button type="button" class="btn-cancel">Cancel</button>
                <button type="submit" class="btn-create createCategory">Create category</button>
            </div>
    
        </form>
    
    </div>
    <!-- SIDEBAR ADD CATEGORY END -->

    <!-- SIDEBAR EDIT CATEGORY START -->
    <div class="update-category-sidebar">
        
        <!-- Header -->
        <div class="sidebar-header">
            <h2 class="sidebar-title">Update Category</h2>
            <button class="btn-close-custom edit-close-btn">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Form Area -->
        <form action="" method="">
            
            <!-- Category Name -->
            <div class="form-group">
                <label class="form-label">Category Name</label>
                <input type="text" class="form-control-custom" id="editcategoryname">
            </div>

            <!-- Slug -->
            <div class="form-group">
                <label class="form-label">Slug (URL)</label>
                <div class="slug-input-group">
                    <span class="slug-prefix">blog.com/</span>
                    <input type="text" class="form-control-custom" id="editcategoryslug">
                </div>
            </div>

            <!-- Description -->
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea class="form-control-custom" id="editcategorydesc"></textarea>
            </div>
            
            {{-- fetch id from data base for updating required recored --}}
            <input type="hidden" id="editId">

            <!-- Icon Selection -->
            <div class="form-group mb-4">
                <label class="form-label">Select Icon</label>
                <div class="icon-grid">
                    <div data-selected="fa-leaf" class="icon-box"><i class="fa-solid fa-leaf"></i></div>
                    <div data-selected="fa-cannabis" class="icon-box"><i class="fa-solid fa-cannabis"></i></div>
                    <div data-selected="fa-book-open" class="icon-box"><i class="fa-solid fa-book-open"></i></div>
                    <div data-selected="fa-house" class="icon-box"><i class="fa-solid fa-house"></i></div>
                    <div data-selected="fa-brain" class="icon-box"><i class="fa-solid fa-brain"></i></div>
                    <div data-selected="fa-globe" class="icon-box"><i class="fa-solid fa-globe"></i></div>
                </div>
            </div>

            <!-- Update Button -->
            <div>
                <button type="submit" class="btn-update-category">Update Category</button>
            </div>

        </form>

    </div>
    <!-- SIDEBAR EDIT CATEGORY END -->

</div>

<!-- Page Footer -->
<footer class="page-footer">
    <div>&copy; 2026 SlowLiving Blog. All rights reserved.</div>
    <div class="footer-links">
        <a href="#">Terms of Service</a>
        <a href="#">Privacy Policy</a>
    </div>
</footer>
@endsection