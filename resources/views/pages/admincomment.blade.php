@extends('layouts.admin')
@push('comments')
    <link rel="stylesheet" href="{{ asset('css/comments.css') }}">
@endpush
@push('publicpost')
    <script src="{{ asset('js/publicpost.js') }}"></script>
@endpush
@section('title', 'Dashboard - Admin')
@section('page_title', 'Dashboard')

@section('content')

<!-- MAIN CONTENT START -->
<div class="container-fluid py-4 px-4" style="max-width: 1400px; margin: 0 auto;">

    <!-- Page Header -->
    <div class="page-header-row">
        <h1 class="page-title">Manage Comments</h1>
    </div>

    <!-- Stat Cards Row -->
    <div class="row g-4 mb-4">
        <!-- Total Comments -->
        <div class="col-md-6 col-xl-3">
            <div class="stat-card-custom">
                <div class="stat-content">
                    <span class="stat-label-top">Total Comments</span>
                    <h3 class="stat-value total-comments">126</h3>
                </div>
                <div class="stat-icon icon-black">
                    <i class="fa-regular fa-comment"></i>
                </div>
            </div>
        </div>
        
        <!-- Pending Review -->
        <div class="col-md-6 col-xl-3">
            <div class="stat-card-custom">
                <div class="stat-content">
                    <span class="stat-label-top">Pending Review</span>
                    <h3 class="stat-value pending-comments text-orange">14</h3>
                </div>
                <div class="stat-icon icon-yellow">
                    <i class="fa-regular fa-clipboard"></i>
                </div>
            </div>
        </div>

        <!-- Approved -->
        <div class="col-md-6 col-xl-3">
            <div class="stat-card-custom">
                <div class="stat-content">
                    <span class="stat-label-top">Approved</span>
                    <h3 class="stat-value approved-comments text-green">106</h3>
                </div>
                <div class="stat-icon icon-green">
                    <i class="fa-regular fa-circle-check"></i>
                </div>
            </div>
        </div>

        <!-- Flagged as Spam -->
        <div class="col-md-6 col-xl-3">
            <div class="stat-card-custom">
                <div class="stat-content">
                    <span class="stat-label-top">Flagged as Spam</span>
                    <h3 class="stat-value spam-comments text-red">3</h3>
                </div>
                <div class="stat-icon icon-red">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Container -->
    <div class="table-container">
        
        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <a href="#" data-filter = "all" class="filter-tab active">All</a>
            <a href="#" data-filter = "pending" class="filter-tab">Pending</a>
            <a href="#" data-filter = "approved" class="filter-tab">Approved </a>
            <a href="#" data-filter = "rejected" class="filter-tab">Rejected</a>
            <a href="#" data-filter = "spam" class="filter-tab">Spam </a>
        </div>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 20%;">Author</th>
                        <th style="width: 20%;">Comment Excerpt</th>
                        <th style="width: 20%;">On Post</th>
                        <th style="width: 12%;">Status</th>
                        <th style="width: 15%;">Date</th>
                        <th style="width: 13%;">Actions</th>
                    </tr>
                </thead>
                <tbody id="comment-table-body">
                    <!-- Row 1 -->
                    <tr>
                        <td>
                            <div class="author-cell">
                                <img src="https://i.pravatar.cc/100?img=12" class="avatar-img" alt="User">
                                <div>
                                    <p class="author-name">Usman Tariq</p>
                                    <p class="author-email">usman.tariq@example.com</p>
                                </div>
                            </div>
                        </td>
                        <td><p class="comment-excerpt">This post made my mo...</p></td>
                        <td><a href="#" class="post-link">The Art of the Slow ...</a></td>
                        <td><span class="status-badge badge-approved">Approved</span></td>
                        <td><span class="date-text">13 Oct 2026</span></td>
                        <td>
                            <div class="action-btn-group">
                                <button class="action-btn btn-approve"><i class="fa-solid fa-check"></i></button>
                                <button class="action-btn btn-reject"><i class="fa-solid fa-xmark"></i></button>
                                <button class="action-btn btn-spam"><i class="fa-solid fa-triangle-exclamation"></i></button>
                                <button class="action-btn btn-delete"><i class="fa-regular fa-trash-can"></i></button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 2 -->
                    <tr>
                        <td>
                            <div class="author-cell">
                                <div class="avatar-initials">BA</div>
                                <div>
                                    <p class="author-name">Bilal Ahmed</p>
                                    <p class="author-email">bilal.ahmed@example.com</p>
                                </div>
                            </div>
                        </td>
                        <td><p class="comment-excerpt">Beautifully written....</p></td>
                        <td><a href="#" class="post-link">The Art of the Slow ...</a></td>
                        <td><span class="status-badge badge-approved">Approved</span></td>
                        <td><span class="date-text">13 Oct 2026</span></td>
                        <td>
                            <div class="action-btn-group">
                                <button class="action-btn btn-approve"><i class="fa-solid fa-check"></i></button>
                                <button class="action-btn btn-reject"><i class="fa-solid fa-xmark"></i></button>
                                <button class="action-btn btn-spam"><i class="fa-solid fa-triangle-exclamation"></i></button>
                                <button class="action-btn btn-delete"><i class="fa-regular fa-trash-can"></i></button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 3 -->
                    <tr>
                        <td>
                            <div class="author-cell">
                                <div class="avatar-initials">AN</div>
                                <div>
                                    <p class="author-name">Ayesha Noor</p>
                                    <p class="author-email">ayesha.noor@example.com</p>
                                </div>
                            </div>
                        </td>
                        <td><p class="comment-excerpt">Would love a follow-...</p></td>
                        <td><a href="#" class="post-link">Small Rituals for a ...</a></td>
                        <td><span class="status-badge badge-pending">Pending</span></td>
                        <td><span class="date-text">11 Oct 2026</span></td>
                        <td>
                            <div class="action-btn-group">
                                <button class="action-btn btn-approve"><i class="fa-solid fa-check"></i></button>
                                <button class="action-btn btn-reject"><i class="fa-solid fa-xmark"></i></button>
                                <button class="action-btn btn-spam"><i class="fa-solid fa-triangle-exclamation"></i></button>
                                <button class="action-btn btn-delete"><i class="fa-regular fa-trash-can"></i></button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 4 -->
                    <tr>
                        <td>
                            <div class="author-cell">
                                <img src="https://i.pravatar.cc/100?img=12" class="avatar-img" alt="User">
                                <div>
                                    <p class="author-name">Usman Tariq</p>
                                    <p class="author-email">usman.tariq@example.com</p>
                                </div>
                            </div>
                        </td>
                        <td><p class="comment-excerpt">Walking without a de...</p></td>
                        <td><a href="#" class="post-link">Walking Without a De...</a></td>
                        <td><span class="status-badge badge-approved">Approved</span></td>
                        <td><span class="date-text">06 Oct 2026</span></td>
                        <td>
                            <div class="action-btn-group">
                                <button class="action-btn btn-approve"><i class="fa-solid fa-check"></i></button>
                                <button class="action-btn btn-reject"><i class="fa-solid fa-xmark"></i></button>
                                <button class="action-btn btn-spam"><i class="fa-solid fa-triangle-exclamation"></i></button>
                                <button class="action-btn btn-delete"><i class="fa-regular fa-trash-can"></i></button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 5 -->
                    <tr>
                        <td>
                            <div class="author-cell">
                                <div class="avatar-initials">BA</div>
                                <div>
                                    <p class="author-name">Bilal Ahmed</p>
                                    <p class="author-email">bilal.ahmed@example.com</p>
                                </div>
                            </div>
                        </td>
                        <td><p class="comment-excerpt">Where exactly in the...</p></td>
                        <td><a href="#" class="post-link">Three Days in the No...</a></td>
                        <td><span class="status-badge badge-pending">Pending</span></td>
                        <td><span class="date-text">02 Oct 2026</span></td>
                        <td>
                            <div class="action-btn-group">
                                <button class="action-btn btn-approve"><i class="fa-solid fa-check"></i></button>
                                <button class="action-btn btn-reject"><i class="fa-solid fa-xmark"></i></button>
                                <button class="action-btn btn-spam"><i class="fa-solid fa-triangle-exclamation"></i></button>
                                <button class="action-btn btn-delete"><i class="fa-regular fa-trash-can"></i></button>
                            </div>
                        </td>
                    </tr>
                    
                     <!-- Row 6 -->
                     <tr>
                        <td>
                            <div class="author-cell">
                                <div class="avatar-initials">AN</div>
                                <div>
                                    <p class="author-name">Ayesha Noor</p>
                                    <p class="author-email">ayesha.noor@example.com</p>
                                </div>
                            </div>
                        </td>
                        <td><p class="comment-excerpt">Spam link removed by...</p></td>
                        <td><a href="#" class="post-link">A Simple Bowl of So...</a></td>
                        <td><span class="status-badge badge-rejected">Rejected</span></td>
                        <td><span class="date-text">28 Sep 2026</span></td>
                        <td>
                            <div class="action-btn-group">
                                <button class="action-btn btn-approve"><i class="fa-solid fa-check"></i></button>
                                <button class="action-btn btn-reject"><i class="fa-solid fa-xmark"></i></button>
                                <button class="action-btn btn-spam"><i class="fa-solid fa-triangle-exclamation"></i></button>
                                <button class="action-btn btn-delete"><i class="fa-regular fa-trash-can"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="pagination-row">
            <p class="pagination-text">Showing 1 to 6 of 126 comments</p>
            <div class="pagination-controls">
                <a href="#" class="page-btn"><i class="fa-solid fa-chevron-left" style="font-size: 0.6rem; color: #d1d5db;"></i></a>
                <a href="#" class="page-btn active">1</a>
                <a href="#" class="page-btn">2</a>
                <a href="#" class="page-btn">3</a>
                <span class="page-dots">...</span>
                <a href="#" class="page-btn">21</a>
                <a href="#" class="page-btn"><i class="fa-solid fa-chevron-right" style="font-size: 0.6rem; color: #d1d5db;"></i></a>
            </div>
        </div>
    </div>

</div>
<!-- MAIN CONTENT END -->
@endsection