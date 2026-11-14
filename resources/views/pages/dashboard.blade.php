@extends('layouts.admin')
@push('dashboard')
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endpush
@section('title', 'Dashboard - Admin')
@section('page_title', 'Dashboard')

@section('content')

    <!-- Page Header -->
    <div class="page-header-row">
        <div>
            <h1 class="page-title">System Overview</h1>
            <p class="page-subtitle">Welcome back. Here's what happened in the last 24 hours.</p>
        </div>
        <a class="text-decoration-none" href="{{ url('add-post') }}"><button class="btn-create-post">
            <i class="fa-solid fa-plus"></i> Create Post
        </button></a>
    </div>

    <!-- Stat Cards Row -->
    <div class="row g-4 mb-4">
        <!-- Total Posts -->
        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon blue"><i class="fa-regular fa-file-lines"></i></div>
                    <span class="stat-badge postAvg green">+12.5%</span>
                </div>
                <p class="stat-label">Total Posts</p>
                <h3 class="stat-value total-posts">48</h3>
            </div>
        </div>
        <!-- Total Comments -->
        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon yellow"><i class="fa-regular fa-comment"></i></div>
                    <span class="stat-badge commentsAvg green">+8.3%</span>
                </div>
                <p class="stat-label">Total Comments</p>
                <h3 class="stat-value total-comments">126</h3>
            </div>
        </div>
        <!-- Total Users -->
        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon green"><i class="fa-solid fa-users"></i></div>
                    <span class="stat-badge gray">Stable</span>
                </div>
                <p class="stat-label">Total Users</p>
                <h3 class="stat-value total-users">8</h3>
            </div>
        </div>
        <!-- Total Views -->
        <div class="col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon red"><i class="fa-regular fa-eye"></i></div>
                    <span class="stat-badge avgViews green">+15.2%</span>
                </div>
                <p class="stat-label">Total Views</p>
                <h3 class="stat-value total-views">18.4k</h3>
            </div>
        </div>
    </div>

    <!-- Chart & Recent Comments Row -->
    <div class="row g-4 mb-4">
        
        <!-- Chart Column -->
        <div class="col-lg-8">
            <div class="panel-card">
                <div class="panel-header">
                    <h4 class="panel-title">Views Per Month</h4>
                    <select class="form-select form-select-custom">
                        <option value=12>Last 12 Months</option>
                        <option value=6>Last 6 Months</option>
                    </select>
                </div>
                <div class="chart-container">
                    <canvas id="viewsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Comments Column -->
        <div class="col-lg-4">
            <div class="panel-card">
                <h4 class="panel-title mb-3">Recent Comments</h4>
                <div style="height: 300px" class="overflow-hidden recent-comment-list-item">
                    <div class="comment-list-item">
                        <img src="https://i.pravatar.cc/100?img=47" class="comment-avatar" alt="User">
                        <div class="comment-content">
                            <p class="comment-author-name">Usman Tariq</p>
                            <p class="comment-excerpt">This post made my mo...</p>
                        </div>
                    </div>

                    {{-- <div class="comment-list-item">
                        <div class="comment-avatar dark">BA</div>
                        <div class="comment-content">
                            <p class="comment-author-name">Bilal Ahmed</p>
                            <p class="comment-excerpt">Beautifully written....</p>
                        </div>
                    </div>
    
                    <div class="comment-list-item">
                        <div class="comment-avatar dark">AN</div>
                        <div class="comment-content">
                            <p class="comment-author-name">Ayesha Noor</p>
                            <p class="comment-excerpt">Would love a follow-...</p>
                        </div>
                    </div>
    
                    <div class="comment-list-item">
                        <img src="https://i.pravatar.cc/100?img=12" class="comment-avatar" alt="User">
                        <div class="comment-content">
                            <p class="comment-author-name">Usman Tariq</p>
                            <p class="comment-excerpt">Walking without a de...</p>
                        </div>
                    </div> --}}
                </div>


                <a href="{{ url('/comments') }}" class="btn-outline-custom">View All Comments</a>
            </div>
        </div>
    </div>

    <!-- Recent Posts Table Row -->
    <div class="panel-card mt-4">
        <div class="table-header-custom">
            <h4 class="panel-title">Recent Posts</h4>
            <a href="#" class="see-all-link">See Full List</a>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Post Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="recent-post-list-item">
                    <!-- Row 1 -->
                    <tr>
                        <td>
                            <p class="post-title-main">The Art of the Slow Morning</p>
                            <p class="post-slug">mindfulness</p>
                        </td>
                        <td><span class="badge-category">Mindfulness</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://i.pravatar.cc/100?img=47" class="table-avatar" alt="Author">
                                <span style="font-size: 0.85rem; font-weight: 500;">Sana Khan</span>
                            </div>
                        </td>
                        <td><span class="status-published">Published</span></td>
                        <td style="font-size: 0.85rem; color: #555;">12 Oct 2026</td>
                        <td><i class="fa-regular fa-eye action-icon"></i></td>
                    </tr>
                    
                    <!-- Row 2 -->
                    <tr>
                        <td>
                            <p class="post-title-main">Small Rituals for a Calmer Home</p>
                            <p class="post-slug">home-interiors</p>
                        </td>
                        <td><span class="badge-category">Home & Interiors</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="table-avatar d-flex align-items-center justify-content-center" style="background:#111; color:#fff; font-size:0.6rem; font-weight:bold;">MS</div>
                                <span style="font-size: 0.85rem; font-weight: 500;">Maryam Siddiqui</span>
                            </div>
                        </td>
                        <td><span class="status-published">Published</span></td>
                        <td style="font-size: 0.85rem; color: #555;">08 Oct 2026</td>
                        <td><i class="fa-regular fa-eye action-icon"></i></td>
                    </tr>

                    <!-- Row 3 -->
                    <tr>
                        <td>
                            <p class="post-title-main">Walking Without a Destination</p>
                            <p class="post-slug">nature</p>
                        </td>
                        <td><span class="badge-category">Nature</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://i.pravatar.cc/100?img=33" class="table-avatar" alt="Author">
                                <span style="font-size: 0.85rem; font-weight: 500;">Hamza Malik</span>
                            </div>
                        </td>
                        <td><span class="status-published">Published</span></td>
                        <td style="font-size: 0.85rem; color: #555;">03 Oct 2026</td>
                        <td><i class="fa-regular fa-eye action-icon"></i></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection
