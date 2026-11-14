@extends('layouts.admin')
@push('post-preview')
    <link rel="stylesheet" href="{{ asset('css/post-preview.css') }}">
@endpush
@push('fechpost')
    <script src="{{ asset('js/fetch-post.js') }}"></script>
@endpush
@section('title', 'Dashboard - Admin')
@section('page_title', 'Dashboard')

@section('content')
<!-- MAIN CONTENT START -->
<div class="preview-layout px-3">
    
    <!-- Left Column (Article Content) -->
    <div class="preview-main">
        
        <!-- Hero Image Area -->
        <div class="post-hero">
            <img src="" alt="" id="heroImage">
            <span class="hero-badge" id="heroBadge"></span>
        </div>

        <!-- Article Content -->
        <div class="article-content">
            <h1 class="article-title" id="articleTitle"></h1>
            
            <!-- Author -->
            <div class="author-row">
                <div class="author-icon-placeholder" id="authorAvatar">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="author-info">
                    <p class="author-name mb-0" id="authorName"></p>
                    <p class="author-date mb-0" id="authorDate"></p>
                </div>
            </div>

            <!-- Body Text (JS se aayega) -->
            <div id="articleBody"></div>
        </div>
    </div>

    <!-- Right Column (Sidebar) -->
    <div class="preview-sidebar">
        
        <!-- Category and tags -->
        <div class="widget-card">
            <h3 class="widget-title">Category and tags</h3>
            
            <span class="widget-label">Category</span>
            <span class="badge-category-dark" id="postCategory"></span>

            <span class="widget-label">Tags</span>
            <div class="tags-container" id="postTags"></div>

            <!-- Visibility -->
            <div class="visibility-row">
                <span class="visibility-label">
                    <i class="fa-regular fa-eye"></i> Visibility
                </span>
                <span class="visibility-status" id="postVisibility"></span>
            </div>
        </div>

    </div>

</div>

<!-- Page Footer -->
<footer class="page-footer">
    <div>&copy; 2026 Admin Workspace. All rights reserved.</div>
    <div class="footer-links">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms of Service</a>
        <a href="#">Help Center</a>
    </div>
</footer>
@endsection