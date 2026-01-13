/**
 * Growfox Shortcodes JavaScript
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // ปรับความเร็วของ ticker ตาม data-speed attribute
        $('.growfox-ticker').each(function() {
            var $ticker = $(this);
            var speed = $ticker.data('speed') || 50;
            var $content = $ticker.find('.growfox-ticker-content').first();
            
            if ($content.length) {
                var contentWidth = $content.outerWidth();
                var duration = contentWidth / speed;
                
                $ticker.find('.growfox-ticker-content').css({
                    'animation-duration': duration + 's'
                });
            }
        });
        
    });
    
})(jQuery);
