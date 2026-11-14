@extends('layouts.app')

@section('title', 'SlowLiving - Contact')

@push('contact')
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endpush
@push('contactjs')
    <script src="{{ asset('js/contact.js') }}"></script>
@endpush

@section('content')

<section class="contact">
    <div class="container">

        {{-- ============ HEADING ============ --}}
        <div class="contact-head">
            <span class="contact-head__label">Contact</span>
            <h1 class="contact-head__title">Get in Touch</h1>
            <p class="contact-head__text">Have a question about our blog, want to write as a guest author, suggest a topic, or report an issue? Send us a message and our team will get back to you as soon as possible.</p>
        </div>

        <div class="row gx-4">

            {{-- ============ FORM ============ --}}
            <div class="col-lg-6">
                <div class="contact-form">

                    <form>
                        
                        <div class="row gx-4">
                            <div class="col-md-6">
                                <div class="contact-form__group">
                                    <label for="name" class="contact-form__label">Name</label>
                                    <input type="text" id="name" name="name" value=""
                                           class="contact-form__control "
                                           placeholder="John Carter">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="contact-form__group">
                                    <label for="email" class="contact-form__label">Email</label>
                                    <input type="email" id="email" name="email" value=""
                                           class="contact-form__control "
                                           placeholder="john@example.com">
                                </div>
                            </div>
                        </div>

                        <div class="contact-form__group">
                            <label for="subject" class="contact-form__label">Subject</label>
                            <input type="text" id="subject" name="subject" value=""
                                   class="contact-form__control"
                                   placeholder="What is this regarding?">
                        </div>

                        <div class="contact-form__group contact-form__group--last">
                            <label for="message" class="contact-form__label">Message</label>
                            <textarea id="message" name="message"
                                      class="contact-form__control contact-form__control--area"
                                      placeholder="Write your message here..."></textarea>
                        </div>

                        <button class="contact-form__btn " id="sendMessage">Send Message</button>
                    </form>
                </div>
            </div>

            {{-- ============ INFO ============ --}}
            <div class="col-lg-6 mt-5 mt-lg-0">
                <div class="contact-info">

                    <div class="contact-info__image">
                        <img src="https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1272&h=480&q=80" alt="Contact SlowLiving">
                    </div>

                    <h2 class="contact-info__title">Other ways to reach us</h2>
                    <p class="contact-info__quote">"Every great blog starts with a conversation. We'd love to hear yours."</p>

                    <ul class="contact-info__list">
                        <li class="contact-info__item">
                            <span class="contact-info__icon"><i class="fa-regular fa-envelope"></i></span>
                            <div>
                                <span class="contact-info__label">Email</span>
                                <p class="contact-info__value">support@yourblog.com</p>
                            </div>
                        </li>
                        <li class="contact-info__item">
                            <span class="contact-info__icon"><i class="fa-solid fa-location-dot"></i></span>
                            <div>
                                <span class="contact-info__label">Editorial Office</span>
                                <p class="contact-info__value">Content Team, 25 Main Boulevard<br>Lahore, Punjab 54000</p>
                            </div>
                        </li>
                    </ul>

                    <div class="contact-info__follow">
                        <span class="contact-info__follow-title">Follow Us</span>
                        <div class="contact-info__social">
                            <a href="#" aria-label="Instagram"><i class="fa-solid fa-camera"></i></a>
                            <a href="#" aria-label="Blog"><i class="fa-regular fa-file-lines"></i></a>
                            <a href="#" aria-label="Telegram"><i class="fa-regular fa-paper-plane"></i></a>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

@endsection