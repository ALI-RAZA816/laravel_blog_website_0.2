@extends('layouts.admin')
@push('users')
    <link rel="stylesheet" href="{{ asset('css/adminuser.css') }}">
@endpush
@section('title', 'Dashboard - Admin')
@section('page_title', 'Dashboard')

@section('content')
@push('usersjs')
    <script src="{{ asset('js/user.js') }}"></script>
@endpush

<!-- MAIN CONTENT START -->
<div class="container-fluid py-4 px-4" style="max-width: 1400px; margin: 0 auto;">

    <!-- Page Header -->
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Manage Users</h1>
            <p class="page-subtitle">Overview and moderation of the blog community.</p>
        </div>
        <a class="text-decoration-none" href="{{ url('/add-user') }}"><button class="btn-add-user">
            <i class="fa-solid fa-user-plus"></i> Add New User
        </button>
        </a>
    </div>

    <!-- Stat Cards Row -->
    <div class="row g-4 mb-4">
        <!-- Total Users -->
        <div class="col-md-6 col-xl-3">
            <div class="stat-card-custom">
                <div class="stat-icon icon-black">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label-top">Total Users</span>
                    <h3 class="stat-value totaluser">8</h3>
                </div>
            </div>
        </div>
        
        <!-- Editors -->
        <div class="col-md-6 col-xl-3">
            <div class="stat-card-custom">
                <div class="stat-icon icon-blue">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label-top">Editors</span>
                    <h3 class="stat-value editoruser">2</h3>
                </div>
            </div>
        </div>

        <!-- New This Week -->
        <div class="col-md-6 col-xl-3">
            <div class="stat-card-custom">
                <div class="stat-icon icon-green">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label-top">New This Week</span>
                    <h3 class="stat-value thisweek">0</h3>
                </div>
            </div>
        </div>

        <!-- Blocked -->
        <div class="col-md-6 col-xl-3">
            <div class="stat-card-custom">
                <div class="stat-icon icon-red">
                    <i class="fa-solid fa-circle-minus"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label-top ">Blocked</span>
                    <h3 class="stat-value blockeduser">1</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="table-container">
        
        <!-- Table Header & Filters -->
        <div class="table-header-area">
            <h4 class="table-title">Registered Users</h4>
            
            <div class="filter-controls">
                <input type="text" id="searchInput" class="search-input" placeholder="Search user, email">
                
                <select id="rolefilter" class="select-filter">
                    <option value="all">Role: All</option>
                    <option value="admin">Admin</option>
                    <option value="editor">Editor</option>
                    <option value="author">Author</option>
                    <option value="user">User</option>
                </select>

                <select id="statusfilter" class="select-filter">
                    <option value="all">Status: All</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="blocked">Blocked</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">Name</th>
                        <th style="width: 25%;">Email</th>
                        <th style="width: 15%;">Role</th>
                        <th style="width: 15%;">Join Date</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 10%;">Actions</th>
                    </tr>
                </thead>
                <tbody id="user-record">
                    <!-- Row 1 -->
                    <tr>
                        <td>
                            <div class="user-cell">
                                <div class="avatar-initials">AR</div>
                                <p class="user-name">Ali Raza</p>
                            </div>
                        </td>
                        <td><span class="email-text">razadeveloper816@gmail.com</span></td>
                        <td><span class="role-badge role-admin">Admin</span></td>
                        <td><span class="date-text">05 Jan 2026</span></td>
                        <td><span class="status-active">Active</span></td>
                        <td>
                            <div class="action-icons">
                                <a href="{{ url('/edit-user') }}"><i class="fa-solid fa-pencil"></i></a>
                                <i class="fa-regular fa-trash-can"></i>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="pagination-row">
            <p class="pagination-text">Showing 1 to 8 of 8 users</p>
            <div class="pagination-controls">
                <a href="#" class="page-btn"><i class="fa-solid fa-chevron-left" style="font-size: 0.6rem; color: #d1d5db;"></i></a>
                <a href="#" class="page-btn active">1</a>
                <a href="#" class="page-btn"><i class="fa-solid fa-chevron-right" style="font-size: 0.6rem; color: #d1d5db;"></i></a>
            </div>
        </div>
    </div>

</div>

<!-- Page Footer -->
<footer class="page-footer">
    <div>&copy; 2026 SlowLiving Blog. All rights reserved.</div>
    <div class="footer-links">
        <a href="#">Terms of Service</a>
        <a href="#">Privacy Policy</a>
        <a href="#">Support</a>
    </div>
</footer>
@endsection