/**
 * Custom JavaScript for Hello Elementor Child Theme
 * เพิ่ม animations และ interactions
 */

(function($) {
    'use strict';

    // Smooth scroll สำหรับ anchor links
    $('a[href*="#"]').not('[href="#"]').not('[href="#0"]').click(function(event) {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && 
            location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                event.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 800);
            }
        }
    });

    // Scroll to top button
    var scrollToTopBtn = $('<button>', {
        class: 'scroll-to-top',
        html: '↑',
        css: {
            position: 'fixed',
            bottom: '30px',
            right: '30px',
            width: '50px',
            height: '50px',
            background: 'linear-gradient(135deg, #003d7a, #0056a8)',
            color: 'white',
            border: 'none',
            borderRadius: '50%',
            fontSize: '24px',
            cursor: 'pointer',
            opacity: '0',
            visibility: 'hidden',
            transition: 'all 0.3s',
            zIndex: '9999',
            boxShadow: '0 4px 12px rgba(0,0,0,0.15)'
        }
    });

    $('body').append(scrollToTopBtn);

    $(window).scroll(function() {
        if ($(this).scrollTop() > 300) {
            scrollToTopBtn.css({
                opacity: '1',
                visibility: 'visible'
            });
        } else {
            scrollToTopBtn.css({
                opacity: '0',
                visibility: 'hidden'
            });
        }
    });

    scrollToTopBtn.click(function() {
        $('html, body').animate({scrollTop: 0}, 600);
    });

    scrollToTopBtn.hover(
        function() {
            $(this).css({
                background: 'linear-gradient(135deg, #0056a8, #00a8e8)',
                transform: 'translateY(-4px)',
                boxShadow: '0 6px 20px rgba(0,0,0,0.2)'
            });
        },
        function() {
            $(this).css({
                background: 'linear-gradient(135deg, #003d7a, #0056a8)',
                transform: 'translateY(0)',
                boxShadow: '0 4px 12px rgba(0,0,0,0.15)'
            });
        }
    );

    // Animate elements on scroll
    function animateOnScroll() {
        $('.animate-on-scroll').each(function() {
            var elementTop = $(this).offset().top;
            var elementBottom = elementTop + $(this).outerHeight();
            var viewportTop = $(window).scrollTop();
            var viewportBottom = viewportTop + $(window).height();

            if (elementBottom > viewportTop && elementTop < viewportBottom) {
                $(this).addClass('animated');
            }
        });
    }

    // เพิ่ม class animate-on-scroll ให้กับ elements
    if ($(window).width() > 768) {
        $('article.post, .widget, .entry-content > *').addClass('animate-on-scroll');
        
        // เพิ่ม CSS สำหรับ animation
        $('<style>')
            .text(`
                .animate-on-scroll {
                    opacity: 0;
                    transform: translateY(30px);
                    transition: all 0.6s ease-out;
                }
                .animate-on-scroll.animated {
                    opacity: 1;
                    transform: translateY(0);
                }
            `)
            .appendTo('head');

        $(window).on('scroll', animateOnScroll);
        animateOnScroll(); // Run on page load
    }

    // Image lightbox effect
    $('.entry-content img').each(function() {
        var $img = $(this);
        if (!$img.parent('a').length) {
            $img.css('cursor', 'zoom-in').click(function() {
                var lightbox = $('<div>', {
                    class: 'image-lightbox',
                    css: {
                        position: 'fixed',
                        top: '0',
                        left: '0',
                        width: '100%',
                        height: '100%',
                        background: 'rgba(0,0,0,0.9)',
                        zIndex: '99999',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        cursor: 'zoom-out',
                        animation: 'fadeIn 0.3s'
                    }
                });

                var imgClone = $img.clone().css({
                    maxWidth: '90%',
                    maxHeight: '90%',
                    objectFit: 'contain',
                    boxShadow: '0 10px 40px rgba(0,0,0,0.5)',
                    borderRadius: '8px'
                });

                lightbox.append(imgClone);
                $('body').append(lightbox);

                lightbox.click(function() {
                    $(this).fadeOut(300, function() {
                        $(this).remove();
                    });
                });
            });
        }
    });

    // Copy code button for code blocks
    $('pre code').each(function() {
        var $code = $(this);
        var $pre = $code.parent();
        
        var $copyBtn = $('<button>', {
            class: 'copy-code-btn',
            text: 'คัดลอก',
            css: {
                position: 'absolute',
                top: '10px',
                right: '10px',
                padding: '0.5rem 1rem',
                background: '#003d7a',
                color: 'white',
                border: 'none',
                borderRadius: '6px',
                fontSize: '0.85rem',
                cursor: 'pointer',
                transition: 'all 0.3s',
                zIndex: '10'
            }
        });

        $pre.css('position', 'relative');
        $pre.append($copyBtn);

        $copyBtn.click(function() {
            var text = $code.text();
            navigator.clipboard.writeText(text).then(function() {
                $copyBtn.text('คัดลอกแล้ว!').css('background', '#00a8e8');
                setTimeout(function() {
                    $copyBtn.text('คัดลอก').css('background', '#003d7a');
                }, 2000);
            });
        });

        $copyBtn.hover(
            function() {
                $(this).css('background', '#0056a8');
            },
            function() {
                if ($(this).text() === 'คัดลอก') {
                    $(this).css('background', '#003d7a');
                }
            }
        );
    });

    // Table responsive wrapper
    $('.entry-content table').each(function() {
        if (!$(this).parent('.table-wrapper').length) {
            $(this).wrap('<div class="table-wrapper" style="overflow-x: auto; margin: 2rem 0;"></div>');
        }
    });

    // Add animation to page load
    $('body').css('opacity', '0');
    $(window).on('load', function() {
        $('body').animate({opacity: '1'}, 400);
    });

    // Progress bar for reading
    var progressBar = $('<div>', {
        class: 'reading-progress',
        css: {
            position: 'fixed',
            top: '0',
            left: '0',
            width: '0%',
            height: '4px',
            background: 'linear-gradient(90deg, #003d7a, #00a8e8)',
            zIndex: '99999',
            transition: 'width 0.1s'
        }
    });

    if ($('body').hasClass('single-post')) {
        $('body').prepend(progressBar);

        $(window).scroll(function() {
            var scrollTop = $(window).scrollTop();
            var docHeight = $(document).height();
            var winHeight = $(window).height();
            var scrollPercent = (scrollTop / (docHeight - winHeight)) * 100;
            progressBar.css('width', scrollPercent + '%');
        });
    }

})(jQuery);
