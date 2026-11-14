@extends('layouts.app')

@section('title', 'SlowLiving - About')

@push('about')
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
@endpush

@section('content')

{{-- ============ HERO + STORY ============ --}}
<div class="about-top">

    {{-- Hero --}}
    <section class="about-hero">
        <div class="container">
            <div class="row gx-4 align-items-center">
                <div class="col-lg-6">
                    <span class="about-hero__label">About Our Blog Platform</span>
                    <h1 class="about-hero__title">A simple space to write, publish and share your stories with the world.</h1>
                    <p class="about-hero__quote">"Great content deserves a great home. We make publishing easy so you can focus on what you want to say."</p>
                </div>
                <div class="col-lg-6 mt-4 mt-lg-0">
                    <div class="about-hero__image">
                        <img src="https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1272&h=680&q=80" alt="About SlowLiving">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Story --}}
    <section class="about-story">
        <div class="container">
            <div class="about-story__box">
                <h2 class="about-story__heading">Why We Built This Blog</h2>

                <p>Managing a blog should not feel complicated. Many writers spend more time fighting with tools than actually writing. We wanted a platform where creating, editing and publishing posts takes only a few clicks.</p>

                <p>Our content management system lets admins and authors handle everything from one dashboard, including posts, categories, tags and media. No technical knowledge is needed, just your ideas and a little time.</p>

                <blockquote class="about-story__quote">"When the tools stay out of the way, the writing gets better."</blockquote>

                <p>This blog is a place for tutorials, tips, stories and insights on technology, lifestyle and creativity. Whether you are a reader looking for something valuable or a writer ready to share, you are in the right place.</p>
            </div>
        </div>
    </section>
</div>

{{-- ============ FEATURES ============ --}}
<section class="about-features">
    <div class="container">

        <div class="about-features__head">
            <h2 class="about-features__title">What You Can Do Here</h2>
            <p class="about-features__sub">The key features that power our blog.</p>
        </div>

        {{-- Row 1 --}}
        <div class="row gx-4">
            <div class="col-lg-6">
                <div class="feature-card">
                    <span class="feature-card__icon"><i class="fa-solid fa-camera"></i></span>
                    <h3 class="feature-card__title">Media Library</h3>
                    <p class="feature-card__text">Upload and manage images for your posts in one place. Add featured images and visuals that make every article more engaging.</p>
                    <div class="feature-card__image">
                        <img src="https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?auto=format&fit=crop&w=1172&h=300&q=80" alt="Media Library">
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mt-4 mt-lg-0">
                <div class="feature-card">
                    <span class="feature-card__circle"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <h3 class="feature-card__title feature-card__title--first">Search &amp; Discovery</h3>
                    <p class="feature-card__text">Readers can quickly find posts by keywords, categories and tags.</p>
                </div>
            </div>
        </div>

        {{-- Row 2 (mini cards, right side) --}}
        <div class="row gx-4 about-features__mini">
            <div class="col-lg-6 offset-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="mini-card mini-card--dark">
                            <span class="mini-card__label"><i class="fa-regular fa-pen-to-square"></i> Simplicity</span>
                            <h4 class="mini-card__title">Clean Editor</h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mini-card mini-card--light">
                            <span class="mini-card__label"><i class="fa-solid fa-user-group"></i> Community</span>
                            <h4 class="mini-card__title">Reader Friendly</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<div class="about-after"></div>

@endsection