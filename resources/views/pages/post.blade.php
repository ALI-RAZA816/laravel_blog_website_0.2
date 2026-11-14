@extends('layouts.admin')
@push('post')
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
@endpush
@push('fechpost')
<script src="{{ asset('js/fetch-post.js') }}"></script>
@endpush

@section('title', 'Dashboard - Admin')
@section('page_title', 'Dashboard')

@section('content')
<!-- MAIN CONTENT START -->
<div class="container-fluid py-4 px-4" style="max-width: 1400px; margin: 0 auto;">

    <!-- Page Header -->
    <div class="page-header-row">
        <div>
            <h1 class="page-title">Manage Posts</h1>
            <p class="page-subtitle">Dashboard <span>></span> Posts</p>
        </div>
        <a class="text-decoration-none"" href="{{ url('/add-post') }}"><button class="btn-add-post ">
            <i class="fa-solid fa-plus"></i> Add New Post
        </button></a>
    </div>

    <!-- Filters Row -->
    <div class="filters-row">
        <div class="search-box">
            <input type="text" id="searchinput" placeholder="Search post">
            <button type="button" id="bulkDeleteBtn" class="btn-delete-selected" style="display:none;">
                <i class="fa-regular fa-trash-can"></i> Delete Selected (<span id="selectedCount">0</span>)
            </button>
        </div>
        
        <!-- Category select -->
        <select class="category-select categories" id="categoryFilter">
            <option value="all" selected>All Categories</option>
        </select>

        <!-- Status tabs -->
        <div class="status-tabs">
            <button class="status-tab active" data-status="all">All</button>
            <button class="status-tab" data-status="published">Published</button>
            <button class="status-tab" data-status="draft">Draft</button>
        </div>
    </div>

    <!-- Table Container -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" class="custom-checkbox" id="selectAllPosts">
                        </th>
                        <th>Post Title</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="post-record">
                    <!-- Row 1 -->
                    {{-- <tr>
                        <td><input type="checkbox" class="custom-checkbox"></td>
                        <td>
                            <p class="post-title-main">The Art of the Slow Morning</p>
                            <p class="post-slug">mindfulness</p>
                        </td>
                        <td><span class="badge-category">Mindfulness</span></td>
                        <td>
                            <div class="author-cell">
                                <img src="https://i.pravatar.cc/100?img=47" class="table-avatar" alt="Author">
                                <span class="author-name">Sana Khan</span>
                            </div>
                        </td>
                        <td><span class="status-published">Published</span></td>
                        <td style="font-size: 0.85rem; color: #6b7280;">12 Oct 2026</td>
                        <td>
                            <div class="action-icons">
                                <a href="{{ url('/post-preview') }}"><i class="fa-regular fa-eye"></i></a>
                                <a href="{{ url('/edit-post') }}"> <i class="fa-solid fa-pencil"></i></a>
                                <i class="fa-regular fa-trash-can"></i>
                            </div>
                        </td>
                    </tr> --}}

                    <!-- Row 2 -->
                    {{-- <tr>
                        <td><input type="checkbox" class="custom-checkbox"></td>
                        <td>
                            <p class="post-title-main">Small Rituals for a Calmer Home</p>
                            <p class="post-slug">home-interiors</p>
                        </td>
                        <td><span class="badge-category">Home & Interiors</span></td>
                        <td>
                            <div class="author-cell">
                                <div class="table-avatar initials">MS</div>
                                <span class="author-name">Maryam Siddiqui</span>
                            </div>
                        </td>
                        <td><span class="status-published">Published</span></td>
                        <td style="font-size: 0.85rem; color: #6b7280;">08 Oct 2026</td>
                        <td>
                            <div class="action-icons">
                                <a href="{{ url('/post-preview') }}"><i class="fa-regular fa-eye"></i></a>
                                <a href="{{ url('/edit-post') }}"> <i class="fa-solid fa-pencil"></i></a>
                                <i class="fa-regular fa-trash-can"></i>
                            </div>
                        </td>
                    </tr> --}}

                    <!-- Row 3 -->
                    {{-- <tr>
                        <td><input type="checkbox" class="custom-checkbox"></td>
                        <td>
                            <p class="post-title-main">Walking Without a Destination</p>
                            <p class="post-slug">nature</p>
                        </td>
                        <td><span class="badge-category">Nature</span></td>
                        <td>
                            <div class="author-cell">
                                <img src="https://i.pravatar.cc/100?img=33" class="table-avatar" alt="Author">
                                <span class="author-name">Hamza Malik</span>
                            </div>
                        </td>
                        <td><span class="status-published">Published</span></td>
                        <td style="font-size: 0.85rem; color: #6b7280;">03 Oct 2026</td>
                        <td>
                            <div class="action-icons">
                               <a href="{{ url('/post-preview') }}"><i class="fa-regular fa-eye"></i></a>
                                <a href="{{ url('/edit-post') }}"> <i class="fa-solid fa-pencil"></i></a>
                                <i class="fa-regular fa-trash-can"></i>
                            </div>
                        </td>
                    </tr> --}}

                    <!-- Row 4 -->
                    {{-- <tr>
                        <td><input type="checkbox" class="custom-checkbox"></td>
                        <td>
                            <p class="post-title-main">Why I Stopped Counting My Steps</p>
                            <p class="post-slug">wellness</p>
                        </td>
                        <td><span class="badge-category">Wellness</span></td>
                        <td>
                            <div class="author-cell">
                                <img src="https://i.pravatar.cc/100?img=47" class="table-avatar" alt="Author">
                                <span class="author-name">Sana Khan</span>
                            </div>
                        </td>
                        <td><span class="status-published">Published</span></td>
                        <td style="font-size: 0.85rem; color: #6b7280;">27 Sep 2026</td>
                        <td>
                            <div class="action-icons">
                               <a href="{{ url('/post-preview') }}"><i class="fa-regular fa-eye"></i></a>
                                <a href="{{ url('/edit-post') }}"> <i class="fa-solid fa-pencil"></i></a>
                                <i class="fa-regular fa-trash-can"></i>
                            </div>
                        </td>
                    </tr> --}}

                    <!-- Row 5 (Draft) -->
                    {{-- <tr>
                        <td><input type="checkbox" class="custom-checkbox"></td>
                        <td>
                            <p class="post-title-main">Decluttering Without Guilt</p>
                            <p class="post-slug">home-interiors</p>
                        </td>
                        <td><span class="badge-category">Home & Interiors</span></td>
                        <td>
                            <div class="author-cell">
                                <img src="https://i.pravatar.cc/100?img=44" class="table-avatar" alt="Author">
                                <span class="author-name">Zainab Fatima</span>
                            </div>
                        </td>
                        <td><span class="status-draft">Draft</span></td>
                        <td style="font-size: 0.85rem; color: #6b7280;">09 Sep 2026</td>
                        <td>
                            <div class="action-icons">
                                <a href="{{ url('/post-preview') }}"><i class="fa-regular fa-eye"></i></a>
                                <a href="{{ url('/edit-post') }}"> <i class="fa-solid fa-pencil"></i></a>
                                <i class="fa-regular fa-trash-can"></i>
                            </div>
                        </td>
                    </tr> --}}
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="pagination-row">
            <p class="pagination-text">Showing 1 to 8 of 48 posts</p>
            <div class="pagination-controls">
                <a href="#" class="page-btn"><i class="fa-solid fa-chevron-left" style="font-size: 0.6rem;"></i></a>
                <a href="#" class="page-btn active">1</a>
                <a href="#" class="page-btn">2</a>
                <a href="#" class="page-btn">3</a>
                <span class="page-dots">...</span>
                <a href="#" class="page-btn">6</a>
                <a href="#" class="page-btn"><i class="fa-solid fa-chevron-right" style="font-size: 0.6rem;"></i></a>
            </div>
        </div>
    </div>

    <!-- Bottom Cards Row -->
    <div class="bottom-cards-row">
        
        <!-- Content Velocity Chart -->
        <div class="content-card">
            <h4 class="card-heading">Content Velocity</h4>
            <p class="card-subtext">Your publishing frequency is up <span class="velocity">12.5%</span> this month.</p>
            <div class="chart-wrapper">
                <canvas id="velocityChart"></canvas>
            </div>
        </div>

        <!-- AI Suggestions Card -->
        <div class="content-card ai-card">
            <div class="ai-icon-wrapper">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
            </div>
            <h4 class="ai-heading">AI Suggestions</h4>
            <p class="ai-text">Generate SEO optimized meta descriptions for your drafts automatically.</p>
            <button class="btn-ai-action">Try Magic Write</button>
        </div>

    </div>

</div>
@endsection

{{-- @push('script')
    <script>
        // Chart Initialization
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('velocityChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Nov 25', 'Dec 25', 'Jan 26', 'Feb 26', 'Mar 26', 'Apr 26', 'May 26', 'Jun 26', 'Jul 26', 'Aug 26', 'Sep 26', 'Oct 26'],
                    datasets: [{
                        label: 'Posts',
                        data: [2, 3, 3, 4, 3, 5, 4, 6, 5, 7, 6, 5],
                        backgroundColor: [
                            '#d1d5fc', '#d1d5fc', '#d1d5fc', '#d1d5fc', '#d1d5fc', '#d1d5fc', 
                            '#d1d5fc', '#d1d5fc', '#d1d5fc', '#d1d5fc', '#d1d5fc', 
                            '#2563eb' // Last bar is dark blue
                        ],
                        borderRadius: 4,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 8,
                            ticks: {
                                stepSize: 2,
                                color: '#9ca3af',
                                font: { size: 10, family: 'Inter' }
                            },
                            grid: {
                                color: '#f3f4f6',
                                drawBorder: false
                            }
                        },
                        x: {
                            ticks: {
                                color: '#9ca3af',
                                font: { size: 9, family: 'Inter' }
                            },
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
@endpush --}}
