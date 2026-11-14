@extends('layouts.app')
@section('content')
@push('singleview')
    <link rel="stylesheet" href="{{asset('css/postview.css')}}">
@endpush
@push('publicpost')
    <script src="{{asset('js/publicpost.js')}}"></script>
@endpush
@push('comments')
    <script src="{{asset('js/comment.js')}}"></script>
@endpush

{{-- ============================================= --}}
{{-- HERO SECTION WITH IMAGE --}}
{{-- ============================================= --}}
{{-- ============================================= --}}
{{-- HERO SECTION --}}
{{-- ============================================= --}}
@if(Auth::check())
    <input type="hidden" id="currentUserId" value="{{ Auth::id() }}">
@endif
<section class="post-hero">
    <!-- Hero Image using <img> tag -->
    <img src="https://images.unsplash.com/photo-1499750310107-5fef28a66643?q=80&w=2070&auto=format&fit=crop" alt="The Art of the Slow Morning" class="post-hero-img">
    
    <div class="post-hero-overlay"></div>

    <div class="container position-relative z-1">
        <div class="post-hero-content">
            <span class="post-category-badge">Mindfulness</span>
            <h1 class="post-hero-title">The Art of the Slow Morning</h1>
        </div>
    </div>
</section>


{{-- ============================================= --}}
{{-- AUTHOR ROW --}}
{{-- ============================================= --}}
<div class="container">
    <div class="author-row d-flex align-items-center gap-3">
        <img src="https://i.pravatar.cc/100?img=47" alt="Sana Khan" class="author-avatar">
        <div>
            <p class="author-name">Sana Khan</p>
            <p class="author-date">12 Oct 2026</p>
        </div>
    </div>
</div>

{{-- ============================================= --}}
{{-- ARTICLE BODY --}}
{{-- ============================================= --}}
<div class="container">
    <article class="article-body">

        <p>Before the phone, before the kettle, there is a window of quiet. Here is how to protect it, and why it changes the rest of your day.</p>

        <p>For years I woke to an alarm and reached for my phone before my eyes had fully opened. Within a minute I was inside other people's emergencies: unread emails, headlines, and messages that could easily have waited. The day had begun, but I had not been invited to it.</p>

        <p>A slow morning is not about waking up at five or building an elaborate routine. It is simply the decision to let the first part of the day belong to you. Even twenty minutes is enough to notice that the light in the room changes, that the house has its own soft sounds, and that your mind is calmer than you assumed.</p>

        <h2>Start with the smallest possible change</h2>

        <p>The mistake most of us make is trying to overhaul everything at once. Instead, pick one thing: leave the phone charging in another room, open the curtains before you do anything else, or drink your first glass of water standing at the window. One small anchor is easier to keep than a list of ten promises.</p>

        <p class="quote">"You do not need more time in the morning. You need a little more attention in it."</p>

        <p>When I began keeping the first hour phone-free, the change was subtle at first. I noticed I was less hurried at breakfast. I noticed the taste of tea. By the second week, I noticed that I was kinder in the small moments of the day, simply because I had started it unhurried.</p>

        <p>Some things that helped me settle into the habit:</p>

        <ul>
            <li>Setting out a mug and a notebook the night before.</li>
            <li>Replacing the phone alarm with a simple clock.</li>
            <li>Spending five minutes looking out of a window with no agenda.</li>
        </ul>

        <p>Some mornings will still be rushed, and that is fine. The practice is not a test. It is an open door, and you can walk through it again tomorrow.</p>

        <hr class="article-divider">
        
    </article>

    {{-- ============================================= --}}
    {{-- COMMENTS SECTION --}}
    {{-- ============================================= --}}
    <section class="comments-section">

        <h3 class="comments-title">Comments (2)</h3>

        {{-- Comment Form --}}
        @if (Auth::check() && Auth::id())
        <div class="d-flex gap-3 mb-4">
            <div class="comment-avatar-initials dark">AR</div>
            <div class="flex-grow-1">
                <textarea class="comment-textarea" placeholder="Add a comment..."></textarea>
                <div class="text-end mt-2">
                    <button type="button" class="btn-post-comment">Post Comment</button>
                </div>
            </div>
        </div>
        @else
            <p class="text-muted mb-5 fs-6">Please <a href="{{ url('/login') }}" class="text-primary">login</a> to post a comment.</p>
        @endif
        <div class="comments-list">
            {{-- Comments will be dynamically loaded here --}}  
            {{-- Comment 1 --}}
            <div class="comment-item">
                <img src="https://i.pravatar.cc/100?img=12" alt="Usman Tariq" class="comment-avatar">
                <div>
                    <p class="comment-author">
                        Usman Tariq
                        <span class="comment-time">1 day ago</span>
                    </p>
                    <p class="comment-text">This post made my morning. I tried the no-phone first hour and it really works.</p>
                </div>
            </div>
    
            {{-- Comment 2 --}}
            <div class="comment-item">
                <div class="comment-avatar-initials">BA</div>
                <div>
                    <p class="comment-author">
                        Bilal Ahmed
                        <span class="comment-time">1 day ago</span>
                    </p>
                    <p class="comment-text">Beautifully written. The part about the window of quiet stayed with me.</p>
                </div>
            </div>
        </div>

    </section>
</div>
@endsection