@extends('layouts.admin')
@push('adduser')
    <link rel="stylesheet" href="{{ asset('css/adduser.css') }}">
@endpush
@section('title', 'Dashboard - Admin')
@section('page_title', 'Dashboard')

@section('content')
@push('usersjs')
    <script src="{{ asset('js/user.js') }}"></script>
@endpush

<!-- MAIN CONTENT START -->
<div class="container-fluid py-4 px-4">

    <!-- Page Header -->
    <div class="page-header-row d-flex flex-column">
        <div class="breadcrumb-custom ">
            Users <span>/</span> Add new user
        </div>
        <h1 class="page-title">Add new user</h1>
    </div>

    <div class="form-layout">
        
        <!-- Left Column (Main Info) -->
        <div class="form-main">
            
            <!-- Personal Information Card -->
            <div class="form-card">
                <div class="card-title-header">
                    <h3 class="card-title">Personal information</h3>
                </div>

                <!-- Profile Photo Upload -->
                <div class="profile-upload-row">
                   <div class="upload-circle" id="uploadCircle">
                        {{-- Default camera icon --}}
                        <div class="upload-placeholder">
                            <i class="fa-solid fa-camera"></i>
                            <span>Upload</span>
                        </div>

                        {{-- Preview image (shuru mein hidden) --}}
                        <img src="" alt="Profile" id="previewImage" class="upload-preview" style="display:none;">

                        {{-- Remove button (shuru mein hidden) --}}
                        <button type="button" class="remove-image" id="removeImage" style="display:none;">
                            <i class="fa-solid fa-xmark"></i>
                        </button>

                        {{-- File input (hidden) --}}
                        <input type="file" id="profileImage" name="profile_image" accept="image/*" style="display:none;">
                    </div>
                    <div class="upload-info">
                        <h6>Profile photo</h6>
                        <p>Square image, at least 400&times;400px. JPG or PNG.</p>
                    </div>
                </div>

                <!-- Inputs Grid -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group-custom">
                            <label class="form-label-custom">Full name</label>
                            <input type="text" id="name" class="form-control-custom" placeholder="e.g. Jane Doe">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group-custom">
                            <label class="form-label-custom">Username</label>
                            <input type="text" id="username" class="form-control-custom" placeholder="jdoe88">
                        </div>
                    </div>
                </div>

                <div class="form-group-custom mb-0">
                    <label class="form-label-custom">Email address</label>
                    <input type="email" id="email" class="form-control-custom" placeholder="jane.doe@example.com">
                </div>
            </div>

            <!-- Biography Card -->
            <div class="form-card">
                <div class="card-title-header">
                    <h3 class="card-title">Biography</h3>
                    <span class="card-optional">Optional</span>
                </div>
                
                <div class="form-group-custom mb-0">
                    <textarea class="form-control-custom" id="bio" placeholder="A short biography about the user"></textarea>
                </div>
            </div>

        </div>

        <!-- Right Column (Sidebar Settings) -->
        <div class="form-sidebar">
            
            <!-- Account Settings Card -->
            <div class="form-card">
                <div class="card-title-header">
                    <h3 class="card-title">Account</h3>
                </div>

                <div class="form-group-custom">
                    <label class="form-label-custom">User role</label>
                    <select class="form-select-custom" id="roles">
                        <option disabled selected>Select role</option>
                        <option value="admin">Admin</option>
                        <option value="editor">Editor</option>
                        <option value="author">Author</option>
                        <option value="user">User</option>
                    </select>
                </div>

                <div class="toggle-row">
                    <div class="toggle-text">
                        <h6>Account active</h6>
                        <p>Allow user to log in</p>
                    </div>
                   <div class="toggle-switch" id="accountActiveToggle" data-active="false">
                        <div class="toggle-thumb"></div>
                    </div>

                    {{-- Hidden input --}}
                    <input type="hidden" name="is_active" id="isActiveInput" value="0">
                </div>
            </div>

            <!-- Security Settings Card -->
            <div class="form-card">
                <div class="card-title-header">
                    <h3 class="card-title">Security</h3>
                </div>

                <div class="form-group-custom">
                    <label class="form-label-custom">Initial password</label>
                    <input type="password" id="userpassword" class="form-control-custom">
                </div>

                <div class="form-group-custom">
                    <label class="form-label-custom">Confirm password</label>
                    <input type="password" id="userconfirmpassword" class="form-control-custom">
                </div>

                <div class="checkbox-row">
                    <input type="checkbox" class="custom-checkbox" id="welcomeCheck">
                    <label class="checkbox-label" for="welcomeCheck">Send welcome instructions</label>
                </div>
            </div>

            <!-- Submit Button -->
            <button class="btn-submit createUser">Add user</button>

        </div>

    </div>

</div>
<!-- MAIN CONTENT END -->
@endsection
