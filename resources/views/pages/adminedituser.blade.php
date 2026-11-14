@extends('layouts.admin')
@push('edituser')
    <link rel="stylesheet" href="{{ asset('css/edituser.css') }}">
@endpush
@section('title', 'Dashboard - Admin')
@section('page_title', 'Dashboard')
@push('usersjs')
    <script src="{{ asset('js/user.js') }}"></script>
@endpush
@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Admin</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    {{-- <link rel="stylesheet" href="{{ asset('css/admin-edit-user.css') }}"> --}}
</head>
<body>

<!-- MAIN CONTENT START -->
<div class="container-fluid py-4 px-4">

    <!-- Page Header -->
    <div class="page-header-row d-flex flex-column">
        <h1 class="page-title">Edit user</h1>
        <p class="page-subtitle">Update this user's profile, role and account access.</p>
    </div>

    <div class="form-layout">
        
        <!-- Left Column (Main Info) -->
        <div class="form-main">
            
            <!-- Personal Information Card -->
            <div class="form-card">
                <div class="card-title-header">
                    <h3 class="card-title">Personal information</h3>
                </div>

                <div class="row">
                    <!-- Avatar Section -->
                    <div class="col-md-3">
                        <div class="upload-circle" id="uploadCircle">
                        {{-- Default camera icon --}}
                        <div class="upload-placeholder">
                            <i class="fa-solid fa-camera"></i>
                            <span>Upload</span>
                        </div>

                        {{-- Preview image --}}
                        <img src="" alt="Profile" id="previewImage" class="upload-preview" style="display:none;">

                        {{-- Remove button (shuru mein hidden) --}}
                        <button type="button" class="remove-image" id="removeImage" style="display:none;">
                            <i class="fa-solid fa-xmark"></i>
                        </button>

                        {{-- File input (hidden) --}}
                        <input type="file" id="profileImage" name="profile_image" accept="image/*" style="display:none;">
                    </div>
                        {{-- <a href="#" class="change-photo-link">Change photo</a> --}}
                    </div>

                    <!-- Inputs -->
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label class="form-label-custom">Full name</label>
                                    <input type="text" id="editname" class="form-control-custom" value="Sana Khan">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-custom">
                                    <label class="form-label-custom">Username</label>
                                    <input type="text" id="editusername" class="form-control-custom" value="sanakhan">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group-custom mb-0">
                            <label class="form-label-custom">Email address</label>
                            <input type="email" id="editemail" class="form-control-custom" value="sana.khan@example.com">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Biography Card -->
            <div class="form-card">
                <div class="card-title-header">
                    <h3 class="card-title">Biography</h3>
                </div>
                
                <div class="form-group-custom mb-0">
                    <label class="form-label-custom">Short bio</label>
                    <textarea id="editbio" class="form-control-custom">Writer and editor focused on mindful mornings, quiet routines and slower living. Sana shapes the voice of the SlowLiving blog.</textarea>
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
                    <select id="editrole" class="form-select-custom">
                        <option disabled selected>Select role</option>
                        <option value='editor'>Editor</option>
                        <option value='admin'>Admin</option>
                        <option value='author'>Author</option>
                        <option value='user'>User</option>
                    </select>
                </div>

               <div class="toggle-row d-flex justify-content-between align-items-center">
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
                    <label class="form-label-custom">Password</label>
                    <button type="button" class="btn-reset-link">
                        <i class="fa-solid fa-arrow-rotate-right"></i> Send reset link
                    </button>
                    <p class="last-changed-text">Last changed 3 months ago</p>
                </div>

                <div class="security-divider"></div>

                <div class="toggle-row" style="margin-top: 0;">
                    <div class="toggle-text">
                        <h6>Two-factor authentication</h6>
                        <p>Currently off</p>
                    </div>
                    <!-- Off (Grey) Toggle -->
                    <div class="toggle-switch off">
                        <div class="toggle-thumb"></div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <button class="btn-save updateuser">
                <i class="fa-regular fa-floppy-disk"></i> Save changes
            </button>

        </div>

    </div>

</div>
<!-- MAIN CONTENT END -->
@endsection
