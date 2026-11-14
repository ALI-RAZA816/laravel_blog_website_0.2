@extends('layouts.admin')
@push('addpost')
    <link rel="stylesheet" href="{{ asset('css/add-post.css') }}">
    <!-- Summernote CSS -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet"> --}}
@endpush
@push('postjs')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script src="{{ asset('js/post.js') }}"></script>
@endpush
@section('title', 'Dashboard - Admin')
@section('page_title', 'Dashboard')

@section('content')
<!-- MAIN CONTENT START -->
<div class="container-fluid py-4 px-4">

    <div class="editor-layout">
        
        <!-- Left Column (Main Editor) -->
        <div class="editor-main">
            
            <!-- Title Input -->
            <input type="text" id="posttitle" class="post-title-input" placeholder="Post title">

            <!-- Editor Box -->
            <div class="editor-box">

                <!-- Textarea -->
                <textarea id="myeditor" name="content"></textarea>
                {{-- <textarea id="myeditor" name="content" class="form-control"></textarea> --}}

            </div>

        </div>

        <!-- Right Column (Sidebar Widgets) -->
        <div class="editor-sidebar">
            
            <!-- Featured Image -->
            <div class="widget-card">
                <h3 class="widget-title">Featured image</h3>
                <label for="coverImageInput" class="image-upload-box" id="imageUploadBox">
                    <i class="fa-regular fa-image"></i>
                    <p id="uploadText">Click to upload a cover image</p>

                    {{-- Preview image (shuru mein hidden) --}}
                    <img src="" alt="Cover" id="coverPreview" style="display:none; width:100%; border-radius:6px;">

                    {{-- File input (hidden) --}}
                    <input type="file" id="coverImageInput" name="cover_image" accept="image/*" style="display:none;">
                </label>
            </div>

            <!-- Category -->
            <div class="widget-card">
                <h3 class="widget-title">Category</h3>
                <select id="post-category" class="widget-select categories">
                    <option disabled selected>Select category</option>
                    <option>Mindfulness</option>
                    <option>Travel</option>
                </select>
            </div>

            <!-- Tags -->
            <div>
                <div class="widget-card">
                    <h3 class="widget-title">Tags</h3>
                    <input type="text" id="tagsinput" class="widget-input" placeholder="Type a tag and press Enter">
                    <div id="tags" class="tags-container mt-3">
                   </div>
                </div>
            </div>

            <!-- Status and Visibility -->
            <div class="widget-card">
                <h3 class="widget-title">Status and visibility</h3>
                
                <div class="status-row">
                    <span>Status</span>

                    <div class="toggle-switch" id="statusToggle" data-status="draft">
                        <div class="toggle-track">
                            <div class="toggle-thumb"></div>
                        </div>
                    </div>

                    {{-- Hidden input — form submit ke liye --}}
                    <input type="hidden" name="status" id="statusInput" value="draft">
                </div>

                <div class="status-row">
                    <span>Visibility</span>
                    <span class="status-value">Public</span>
                </div>

                <div class="status-row">
                    <span>Publish date</span>
                    <span class="status muted">Immediately</span>
                </div>

                <button type="button" class="btn-publish">Publish post</button>
            </div>

        </div>

    </div>

</div>
@endsection