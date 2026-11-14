@extends('layouts.admin')

@section('title', 'Settings - Admin')
@section('page_title', 'Settings')

@push('setting')
    <link rel="stylesheet" href="{{ asset('css/adminsetting.css') }}">
@endpush
@push('settingjs')
    <script src="{{ asset('js/settings.js') }}"></script>
@endpush

@section('content')

<!-- MAIN CONTENT START -->
<div class="container-fluid px-4 pb-4" style="max-width: 1400px; margin: 0 auto;">

    <!-- Page Header -->
    <div class="page-header-row mt-2">
        <div>
            <h1 class="page-title">General settings</h1>
            <p class="page-subtitle">Set your blog's name, logo and social links.</p>
        </div>
        <button class="btn-save-changes" id="saveSettings">
            <i class="fa-regular fa-floppy-disk"></i> Save changes
        </button>
    </div>

    <div class="settings-layout">
        
        <!-- Left Column (Main Info) -->
        <div class="settings-main">
            
            <!-- Brand Identity Card -->
            <div class="settings-card">
                <div class="card-title-header">
                    <i class="fa-solid fa-pen-nib"></i>
                    <h3 class="card-title">Brand identity</h3>
                </div>

                <div class="form-group-custom">
                    <label class="form-label-custom">Site title</label>
                    <input type="text" id="siteTitle" class="form-control-custom" value="">
                </div>

                <div class="form-group-custom">
                    <label class="form-label-custom">Tagline</label>
                    <textarea id="siteDesc" class="form-control-custom"></textarea>
                </div>

                <div class="form-group-custom mb-0">
                    <label class="form-label-custom">Footer copyright text</label>
                    <input type="text" id="siteCopyright" class="form-control-custom" value="">
                </div>
            </div>

            <!-- Social Links Card -->
            <div class="settings-card">
                <div class="card-title-header">
                    <i class="fa-solid fa-share-nodes"></i>
                    <h3 class="card-title">Social links</h3>
                </div>

                <div class="row">
                    <!-- Facebook -->
                    <div class="col-md-6">
                        <div class="form-group-custom">
                            <label class="form-label-custom">Facebook URL</label>
                            <div class="social-input-group">
                                <div class="social-prefix"><i class="fa-brands fa-facebook-f"></i></div>
                                <input id="fbUrl" type="text" value="">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Twitter -->
                    <div class="col-md-6">
                        <div class="form-group-custom">
                            <label class="form-label-custom">Twitter / X URL</label>
                            <div class="social-input-group">
                                <div class="social-prefix"><i class="fa-brands fa-square-x-twitter"></i></div>
                                <input id="twUrl" type="text" value="">
                            </div>
                        </div>
                    </div>

                    <!-- Instagram -->
                    <div class="col-md-6">
                        <div class="form-group-custom mb-0">
                            <label class="form-label-custom">Instagram URL</label>
                            <div class="social-input-group">
                                <div class="social-prefix"><i class="fa-brands fa-instagram"></i></div>
                                <input id="igUrl" type="text" value="">
                            </div>
                        </div>
                    </div>
                    
                    <!-- LinkedIn -->
                    <div class="col-md-6">
                        <div class="form-group-custom mb-0">
                            <label class="form-label-custom">LinkedIn URL</label>
                            <div class="social-input-group">
                                <div class="social-prefix"><i class="fa-brands fa-linkedin-in"></i></div>
                                <input id="liUrl" type="text" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column (Sidebar) -->
        <div class="settings-sidebar">
            
            <!-- Site Logo Card -->
            <div class="settings-card">
                <div class="card-title-header">
                    <i class="fa-regular fa-image"></i>
                    <h3 class="card-title">Site logo</h3>
                </div>

                <label for="siteLogoInput" class="logo-upload-box" id="logoUploadBox">
                    <div class="upload-icon-circle" id="logoPlaceholder">
                        <i class="fa-solid fa-camera"></i>
                    </div>

                    <!-- Preview image -->
                    <img src="" alt="Logo" id="logoPreview"
                        style="display:none; max-width:100%; max-height:120px; object-fit:contain; margin-bottom:0.5rem;">

                    <!-- Text (hide hoga jab image aaye) -->
                    <div id="logoText">
                        <h6>Click to upload a logo</h6>
                        <p>SVG, PNG or JPG, up to 2MB</p>
                    </div>

                    <input type="file" id="siteLogoInput" accept="image/*" style="display:none;">
                </label>

                <div class="current-logo-row">
                    <span>Current logo</span>
                    <a href="#" class="remove-link" id="removeLogo">Remove</a>
                </div>
            </div>

        </div>

    </div>

</div>
<!-- MAIN CONTENT END -->

@endsection