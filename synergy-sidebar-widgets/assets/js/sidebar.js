/**
 * Synergy Sidebar Widgets - JavaScript
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Newsletter form submission
        $('.synergy-newsletter-form').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $input = $form.find('.synergy-email-input');
            var $button = $form.find('.synergy-submit-btn');
            var email = $input.val();
            
            // Validate email
            if (!isValidEmail(email)) {
                alert('กรุณากรอกอีเมลที่ถูกต้อง');
                return;
            }
            
            // Disable button
            $button.prop('disabled', true).text('กำลังส่ง...');
            
            // Send AJAX request
            $.ajax({
                url: ajaxurl || '/wp-admin/admin-ajax.php',
                type: 'POST',
                data: {
                    action: 'synergy_newsletter_subscribe',
                    email: email,
                    nonce: '<?php echo wp_create_nonce("synergy_newsletter"); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        alert('สมัครรับข่าวสารเรียบร้อยแล้ว!');
                        $input.val('');
                    } else {
                        alert('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง');
                    }
                },
                error: function() {
                    alert('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง');
                },
                complete: function() {
                    $button.prop('disabled', false).text('สมัครเลย');
                }
            });
        });
        
        // Smooth scroll for view more links
        $('.synergy-view-more').on('click', function(e) {
            var href = $(this).attr('href');
            if (href && href.startsWith('/')) {
                // Let it navigate normally
                return true;
            }
        });
        
        // Add loading animation to post thumbnails
        $('.synergy-post-thumb img').on('load', function() {
            $(this).addClass('loaded');
        });
        
    });
    
    // Email validation helper
    function isValidEmail(email) {
        var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
    
})(jQuery);
