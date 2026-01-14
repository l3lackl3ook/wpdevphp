/**
 * Siam Blog Layouts Scripts
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        // Smooth scroll for pagination
        $('.pagination a').on('click', function(e) {
            var target = $('.category-wrapper, .column-wrapper');
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 500);
            }
        });
        
        // Lazy load images
        if ('loading' in HTMLImageElement.prototype) {
            const images = document.querySelectorAll('img[loading="lazy"]');
            images.forEach(img => {
                img.src = img.dataset.src || img.src;
            });
        }
    });
    
})(jQuery);
