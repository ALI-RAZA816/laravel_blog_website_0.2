@extends('layouts.admin')
@push('editpost')
    <link rel="stylesheet" href="{{ asset('css/edit-post.css') }}">
@endpush
@push('postjs')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
@endpush
@push('fechpost')
    <script src="{{ asset('js/fetch-post.js') }}"></script>
@endpush
@section('title', 'Dashboard - Admin')
@section('page_title', 'Dashboard')

@section('content')

<!-- MAIN CONTENT START -->
<div class="container-fluid py-4 px-4">

    <div class="edit-layout">
        
        <!-- Left Column (Main Editor) -->
        <div class="edit-main">
            
            <!-- Title Box -->
            <div class="post-title-box">
                <input type="text" class="post-title-text" id="posttitle" placeholder="Post title">
            </div>

            <!-- Editor Box -->
            <div class="editor-box">
                <textarea id="myeditor" name="content"></textarea>
            </div>

        </div>

        <!-- Right Column (Sidebar Widgets) -->
        <div class="edit-sidebar">
            
            <!-- Featured Image -->
            <div class="widget-card">
                <h3 class="widget-title">Featured Image</h3>
                <div class="featured-image-preview" id="featuredImagePreview">
                    <label for="coverImageInput" class="image-upload-box" id="imageUploadBox">
                        <i class="fa-regular fa-image"></i>
                        <p id="uploadText">Click to upload a cover image</p>
                        <img src="" alt="Cover" id="coverPreview" style="display:none; width:100%; border-radius:6px;">
                        <input type="file" id="coverImageInput" name="cover_image" accept="image/*" style="display:none;">
                    </label>
                </div>
            </div>

            <!-- Categories -->
            <div class="widget-card">
                <h3 class="widget-title">Categories</h3>
                <select class="widget-select categories" id="post-category">
                    <option disabled selected>Select category</option>
                </select>
            </div>

            <!-- Tags -->
            <div class="widget-card">
                <h3 class="widget-title">Tags</h3>
                <div class="tags-container" id="tags"></div>
                <input type="text" class="widget-input" id="tagsinput" placeholder="Add a tag...">
            </div>

            <!-- Status & Visibility -->
            <div class="widget-card">
                <h3 class="widget-title">Status & Visibility</h3>
                
            <div class="status-row">
                <span>Status</span>

                <div class="toggle-switch" id="statusToggle" data-status="draft">
                    <div class="toggle-track">
                        <div class="toggle-thumb"></div>
                    </div>
                    <span class="toggle-label" id="statusLabel"></span>
                </div>

                <input type="hidden" name="status" id="statusInput" value="draft">
            </div>

            <div class="status-row">
                <span>Visibility</span>
                <span class="status-value" id="visibilityValue">Draft</span>
            </div>

            <div class="status-row">
                <span>Publish date</span>
                <span class="status muted">Immediately</span>
            </div>
                <button type="button" class="btn-update">Update Post</button>
            </div>

        </div>

    </div>

</div>
<!-- MAIN CONTENT END -->
@endsection