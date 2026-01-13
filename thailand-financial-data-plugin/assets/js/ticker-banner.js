/**
 * Thailand Financial Data - Ticker Banner Script
 * JavaScript สำหรับ ticker banner
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // ปรับความเร็วของ ticker ตาม data-speed attribute
        $('.tfd-ticker-banner').each(function() {
            var $ticker = $(this);
            var speed = $ticker.data('speed') || 50;
            var $content = $ticker.find('.tfd-ticker-content').first();
            
            if ($content.length) {
                var contentWidth = $content.outerWidth();
                var duration = contentWidth / speed;
                
                $ticker.find('.tfd-ticker-content').css({
                    'animation-duration': duration + 's'
                });
            }
        });
        
        // Auto-refresh ข้อมูลทุก 5 นาที (ถ้าต้องการ)
        // setInterval(function() {
        //     location.reload();
        // }, 300000);
        
    });
    
})(jQuery);
