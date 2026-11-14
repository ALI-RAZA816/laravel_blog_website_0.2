@extends('layouts.admin')
@push('message')
    <link rel="stylesheet" href="{{ asset('css/adminmessage.css') }}">
     <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
@endpush
@push('messagejs')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
@endpush
@section('title', 'Dashboard - Admin')
@section('page_title', 'Dashboard')

@section('content')

{{-- <input type="hidden" id="reverbKey" value="{{ env('REVERB_APP_KEY') }}">
<input type="hidden" id="reverbHost" value="{{ env('REVERB_HOST') }}">
<input type="hidden" id="reverbPort" value="{{ env('REVERB_PORT') }}"> --}}

<!-- MAIN CONTENT START -->
<div class="container-fluid px-4" style="max-width: 1500px; margin: 0 auto;">

    <!-- Page Header -->
    <div class="page-header-row">
        <h1 class="page-title">Contact messages</h1>
        <p class="page-subtitle">Read and reply to inquiries sent from your Contact page.</p>
    </div>

    <!-- Stat Cards Row -->
    <div class="row g-4 mb-4">
        <!-- Total Inquiries -->
        <div class="col-md-4">
            <div class="stat-card-custom">
                <div class="stat-content">
                    <span class="stat-label-top">Total inquiries</span>
                    <div class="stat-value-row">
                        <h3 class="stat-value total-inquiries">5</h3>
                    </div>
                </div>
                <i class="fa-regular fa-envelope stat-icon"></i>
            </div>
        </div>
        
        <!-- Unread -->
        <div class="col-md-4">
            <div class="stat-card-custom">
                <div class="stat-content">
                    <span class="stat-label-top">Unread</span>
                    <div class="stat-value-row">
                        <h3 class="stat-value unread-inquiries">2</h3>
                        <span class="badge-new">New</span>
                    </div>
                </div>
                <i class="fa-regular fa-envelope-open stat-icon"></i>
            </div>
        </div>

        <!-- Average response time -->
        <div class="col-md-4">
            <div class="stat-card-custom">
                <div class="stat-content">
                    <span class="stat-label-top">Average response time</span>
                    <div class="stat-value-row">
                        <h3 class="stat-value avg-response-time">4.2 hrs</h3>
                    </div>
                </div>
                <i class="fa-regular fa-clock stat-icon"></i>
            </div>
        </div>
    </div>

    <!-- Messaging Interface Layout -->
    <div class="messages-layout">
        
        <!-- Left Sidebar (List) -->
        <div class="msg-sidebar">
            <!-- Filter Tabs -->
            <div class="msg-tabs">
                <a href="#" class="msg-tab active">All <span class="msg-tab-count totalcount">5</span></a>
                <a href="#" class="msg-tab">Unread <span class="msg-tab-count unreadcount">2</span></a>
            </div>

            <!-- Message List -->
            <div class="msg-list">
                
                <!-- Message 1 (Active) -->
                <div class="msg-item active unread">
                    <div class="msg-item-top">
                        <p class="msg-item-sender">Farah Iqbal <span class="badge-unread-sm">Unread</span></p>
                        <span class="msg-item-date">14 Oct 2026</span>
                    </div>
                    <p class="msg-item-subject">Guest post enquiry</p>
                    <p class="msg-item-excerpt">Hi team, I run a small wellness newsletter and would love to contribute a guest post. Could you share yo...</p>
                    <p class="msg-item-meta">farah.iqbal@example.com &bull; Not replied</p>
                </div>

                <!-- Message 2 -->
                <div class="msg-item unread">
                    <div class="msg-item-top">
                        <p class="msg-item-sender">Daniyal Sheikh <span class="badge-unread-sm">Unread</span></p>
                        <span class="msg-item-date">13 Oct 2026</span>
                    </div>
                    <p class="msg-item-subject">Loved the northern hills guide</p>
                    <p class="msg-item-excerpt">Just wanted to say thank you for the travel guide. We followed it last weekend and it was perfect.</p>
                    <p class="msg-item-meta">daniyal.sheikh@example.com &bull; Not replied</p>
                </div>

                <!-- Message 3 -->
                <div class="msg-item">
                    <div class="msg-item-top">
                        <p class="msg-item-sender">Hira Baig</p>
                        <span class="msg-item-date">11 Oct 2026</span>
                    </div>
                    <p class="msg-item-subject">Newsletter not arriving</p>
                    <p class="msg-item-excerpt">I subscribed two weeks ago but haven't received any email yet. Could you check?</p>
                    <p class="msg-item-meta">hira.baig@example.com &bull; Not replied</p>
                </div>

                <!-- Message 4 -->
                <div class="msg-item">
                    <div class="msg-item-top">
                        <p class="msg-item-sender">Omar Farooq</p>
                        <span class="msg-item-date">08 Oct 2026</span>
                    </div>
                    <p class="msg-item-subject">Advertising partnership</p>
                    <p class="msg-item-excerpt">We are a ceramics studio and would like to discuss a sponsored post. What are your rates?</p>
                    <p class="msg-item-meta">omar.farooq@example.com &bull; Not replied</p>
                </div>
            </div>

            <!-- Pagination -->
           <div class="msg-sidebar-pagination">
                <!-- Previous Button -->
                <button class="page-arrow" id="prevPageBtn"><i class="fa-solid fa-chevron-left"></i></button>
                
                <!-- Pagination Text -->
                <span id="paginationText">1 to 5 of 5 inquiries</span>
                
                <!-- Next Button -->
                <button class="page-arrow" id="nextPageBtn"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>

        <!-- Right Panel (Detail) -->
        <div class="msg-detail">
            
            <!-- Detail Header -->
            <div class="msg-detail-header">
                <span class="detail-header-label">Contact form</span>
                <div class="detail-header-actions">
                    <button class="btn-icon"><i class="fa-regular fa-envelope"></i></button>
                    <button class="btn-reply-top"><i class="fa-solid fa-reply"></i> Reply</button>
                </div>
            </div>

            <!-- Detail Content -->
            <div class="msg-detail-content">
                
                <div class="sender-profile">
                    <div class="avatar-circle">FI</div>
                    <div class="sender-info">
                        <h4>Farah Iqbal</h4>
                        <p>farah.iqbal@example.com</p>
                    </div>
                </div>

                <h2 class="email-subject">Guest post enquiry</h2>
                <div class="email-meta">
                    <i class="fa-regular fa-envelope"></i> Sent via slowlivingblog.com/contact
                </div>

                <div class="email-body">
                    <p>Hi team, I run a small wellness newsletter and would love to contribute a guest post. Could you share your guidelines?</p>
                </div>

            </div>

            <!-- Reply Editor -->
            <div class="reply-editor-wrapper">
                <textarea class="reply-textarea" id="replyEditor" placeholder="Write your reply"></textarea>
                <div class="reply-footer">
                    <button class="btn-send-reply"><i class="fa-solid fa-paper-plane"></i> Send reply</button>
                </div>
            </div>

        </div>

    </div>

    <!-- Page Footer -->
    <footer class="page-footer">
        <div class="footer-left">
            <span class="footer-brand">SlowLiving Blog</span>
            <span>Version 2.4.0 Management Portal</span>
        </div>
        <div class="footer-links">
            <a href="#">Terms of Service</a>
            <a href="#">Privacy Policy</a>
            <span>&copy; 2026 Admin Workspace</span>
        </div>
    </footer>

</div>
<!-- MAIN CONTENT END -->
<!-- MAIN CONTENT END -->
@endsection
