$(document).ready(function () {
    
    // Global Header Settings Fetch
    function loadGlobalSettings() {
        // Agar header elements page par mojood hain
        if ($('#dynamicSiteLogo').length || $('#dynamicSiteTitle').length) {
            $.ajax({
                url: '/fetchsettings',
                type: 'GET',
                success: function (data) {
                    var s = data.setting || {};

                    if (s.site_logo) {
                        $('#dynamicSiteLogo').attr('src', `/posts-images/${s.site_logo}`).show();
                        $('#dynamicSiteTitle').hide();
                    } else {
                        $('#dynamicSiteLogo').hide();
                        $('#dynamicSiteTitle').text(s.site_title || '').show();
                    }

                    // --- FOOTER ---
                // 1. Title & Description
                if (s.site_title) {
                    $('#footerSiteTitle').text(s.site_title);
                }
                if (s.site_description) {
                    $('#footerSiteDesc').text(s.site_description);
                }

                // 2. Copyright
                if (s.site_copyright) {
                    $('#footerCopyright').text(s.site_copyright);
                } else {
                    var currentYear = new Date().getFullYear();
                    $('#footerCopyright').html(`&copy; ${currentYear} SlowLiving. All rights reserved.`);
                }

                // 3. Social Links
                $('#footerFbUrl').attr('href', s.f_url || '#');
                $('#footerTwUrl').attr('href', s.t_url || '#');
                $('#footerIgUrl').attr('href', s.i_url || '#');
                $('#footerLiUrl').attr('href', s.l_url || '#');

                // Hide empty links
                // if (!s.f_url) $('#footerFbUrl').hide(); else $('#footerFbUrl').show();
                // if (!s.t_url) $('#footerTwUrl').hide(); else $('#footerTwUrl').show();
                // if (!s.i_url) $('#footerIgUrl').hide(); else $('#footerIgUrl').show();
                // if (!s.l_url) $('#footerLiUrl').hide(); else $('#footerLiUrl').show();
                },
                error: function (xhr) {
                    console.log('Header settings error:', xhr);
                }
            });
        }
    }

    loadGlobalSettings();

    // Mobile / tablet hamburger menu
    $('#navToggle').on('click', function (e) {
        e.stopPropagation();
        var isOpen = $('#siteNav').toggleClass('is-open').hasClass('is-open');
        $(this).toggleClass('is-open', isOpen).attr('aria-expanded', isOpen);
    });

    // Close menu when clicking outside it, or when resizing up to desktop
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.site-header').length) {
            $('#siteNav').removeClass('is-open');
            $('#navToggle').removeClass('is-open').attr('aria-expanded', false);
        }
    });

    $(window).on('resize', function () {
        if (window.innerWidth >= 992) {
            $('#siteNav').removeClass('is-open');
            $('#navToggle').removeClass('is-open').attr('aria-expanded', false);
        }
    });
});
