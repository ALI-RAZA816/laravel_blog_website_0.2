@extends('layouts.app')

@section('title', 'SlowLiving - Home')
@push('publicpost')
    <script src="{{ asset('js/publicpost.js') }}"></script>
    <script src="{{ asset('js/subscriber.js') }}"></script>
@endpush
@section('content')

@php
    $posts = [
        ['cat' => 'Mindfulness',     'ago' => '2 days ago',  'date' => '12 Oct 2026', 'title' => 'The Art of the Slow Morning',      'img' => 'https://images.unsplash.com/photo-1470252649378-9c29740c9fa8?auto=format&fit=crop&w=832&h=420&q=80'],
        ['cat' => 'Home & Interiors','ago' => '6 days ago',  'date' => '08 Oct 2026', 'title' => 'Small Rituals for a Calmer Home',  'img' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=832&h=420&q=80'],
        ['cat' => 'Nature',          'ago' => '11 days ago', 'date' => '03 Oct 2026', 'title' => 'Walking Without a Destination',    'img' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=832&h=420&q=80'],
        ['cat' => 'Wellness',        'ago' => '2 weeks ago', 'date' => '27 Sep 2026', 'title' => 'Why I Stopped Counting My Steps',  'img' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=832&h=420&q=80'],
        ['cat' => 'Food & Rituals',  'ago' => '3 weeks ago', 'date' => '21 Sep 2026', 'title' => 'A Simple Bowl of Soup',            'img' => 'https://images.unsplash.com/photo-1476718406336-bb5a9690ee2a?auto=format&fit=crop&w=832&h=420&q=80'],
        ['cat' => 'Travel',          'ago' => '4 weeks ago', 'date' => '15 Sep 2026', 'title' => 'Three Days in the Northern Hills', 'img' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=832&h=420&q=80'],
    ];

    $categories = [
        'Mindfulness (14)', 'Home & Interiors (11)', 'Nature (9)',
        'Food & Rituals (7)', 'Travel (5)', 'Wellness (4)',
    ];

    $popular = [
        ['title' => 'The Art of the Slow Morning',      'date' => '12 Oct 2026', 'cat' => 'Mindfulness',      'img' => 'https://images.unsplash.com/photo-1470252649378-9c29740c9fa8?auto=format&fit=crop&w=128&h=128&q=80'],
        ['title' => 'Three Days in the Northern Hills', 'date' => '15 Sep 2026', 'cat' => 'Travel',          'img' => 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=128&h=128&q=80'],
        ['title' => 'Small Rituals for a Calmer Home',  'date' => '08 Oct 2026', 'cat' => 'Home & Interiors','img' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=128&h=128&q=80'],
        ['title' => 'Walking Without a Destination',    'date' => '03 Oct 2026', 'cat' => 'Nature',         'img' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=128&h=128&q=80'],
    ];
@endphp
@push('home')
<link rel="stylesheet" href="{{asset('css/home.css')}}">
@endpush
<main class="main">

    {{-- HERO --}}
    <section class="hero" style="background-image: url('https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=2000&q=80');">
        <div class="hero__overlay"></div>
        <div class="hero__content">
            <span class="hero__tag">Featured Story</span>
            <h1 class="hero__title">Finding Stillness in a Chaotic Digital World</h1>
            <p class="hero__text">How rediscovering analog rituals can transform your mental clarity and creativity.</p>
            <a href="#" class="hero__btn">Read More <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </section>

    <div class="content">

        {{-- LATEST STORIES --}}
        <section class="stories">
            <h2 class="stories__heading">Latest Stories</h2>

            <div class="stories__grid">
                @foreach ($posts as $post)
                    <article class="card">
                        <a href="{{url('/single-view')}}" class="card__image">
                            <img src="{{ $post['img'] }}" alt="{{ $post['title'] }}">
                        </a>
                        <div class="card__meta">
                            <span class="card__cat">{{ $post['cat'] }}</span>
                            <span class="card__info"><i class="fa-regular fa-clock"></i> {{ $post['ago'] }}</span>
                            <span class="card__info"><i class="fa-regular fa-calendar"></i> {{ $post['date'] }}</span>
                        </div>
                        <h3 class="card__title"><a href="#">{{ $post['title'] }}</a></h3>
                    </article>
                @endforeach
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
        </section>


        {{-- SIDEBAR --}}
        <aside class="sidebar">

            <div class="widget">
                <h4 class="widget__title">Search the Journal</h4>
                <form action="#" method="GET" class="search">
                    <input type="text" name="q" placeholder="Search post" class="search__input">
                    <button type="submit" class="search__btn"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>

            <div class="widget">
                <h4 class="widget__title">Categories</h4>
                <div class="tags">
                    @foreach ($categories as $category)
                        <a href="#" class="tags__item">{{ $category }}</a>
                    @endforeach
                </div>
            </div>

            <div class="widget">
                <h4 class="widget__title">Popular This Week</h4>
                <ul class="popular">
                    @foreach ($popular as $item)
                        <li class="popular__item">
                            <a href="#" class="popular__thumb"><img src="{{ $item['img'] }}" alt="{{ $item['title'] }}"></a>
                            <div class="popular__body">
                                <h5 class="popular__title"><a href="#">{{ $item['title'] }}</a></h5>
                                <div class="popular__meta">
                                    <span class="popular__date">{{ $item['date'] }}</span>
                                    <span class="popular__badge">{{ $item['cat'] }}</span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="newsletter">
                <span class="newsletter__icon"><i class="fa-regular fa-envelope"></i></span>
                <h4 class="newsletter__title">Weekly Musings</h4>
                <p class="newsletter__text">A curated collection of thoughts, inspiration, and slow-living tips delivered every Sunday morning.</p>
                <form>
                    <input type="email" name="email" placeholder="Your email address" class="newsletter__input">
                    <button type="button" class="newsletter__btn">Join the Circle</button>
                </form>
                <small class="newsletter__note">We respect your space. Unsubscribe anytime.</small>
            </div>

        </aside>
    </div>
</main>

@endsection
