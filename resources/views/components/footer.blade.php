<footer class="site-footer">
    <div class="site-footer__inner">

        <div class="site-footer__top">
            <div class="site-footer__brand">
                <a href="{{ url('/') }}" class="site-footer__logo">
                    <span id="footerSiteTitle">SlowLiving</span>
                </a>
                <p class="site-footer__desc" id="footerSiteDesc">A quiet corner of the internet for slower mornings, simpler homes and more intentional days.</p>
            </div>

            <div class="site-footer__col">
                <h4 class="site-footer__title">Navigation</h4>
                <ul class="site-footer__links">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ url('/about') }}">About</a></li>
                    <li><a href="{{ url('/contact') }}">Contact</a></li>
                </ul>
            </div>

            <div class="site-footer__col">
                <h4 class="site-footer__title">Connect</h4>
                <div class="site-footer__social">
                    <a href="#" id="footerFbUrl" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" id="footerTwUrl" aria-label="X"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="#" id="footerIgUrl" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" id="footerLiUrl" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>

        <div class="site-footer__bottom">
            <p id="footerCopyright">&copy; {{ date('Y') }} SlowLiving. All rights reserved.</p>
            <div class="site-footer__legal">
                <a href="#">Terms of Service</a>
                <a href="#">Privacy Policy</a>
            </div>
        </div>

    </div>
</footer>