<?php
/**
 * Plugin Name: Sticky Header Script
 * Description: เพิ่ม JavaScript สำหรับ Sticky Header ที่ซ่อนเมื่อเลื่อนลง และแสดงเมื่อเลื่อนขึ้น
 * Version: 1.0.0
 * Author: BlogeverydayTH
 */

// ป้องกันการเข้าถึงโดยตรง
if (!defined('ABSPATH')) {
    exit;
}

// เพิ่ม JavaScript ใน footer
function sticky_header_add_script() {
    ?>
    <script>
    (function() {
        'use strict';
        
        let lastScroll = 0;
        
        function handleScroll() {
            const header = document.querySelector('.elementor-location-header');
            if (!header) return;
            
            const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
            
            if (currentScroll < 0) return;
            
            if (currentScroll > 100) {
                if (currentScroll > lastScroll) {
                    // เลื่อนลง - ซ่อน header
                    header.classList.remove('scrolling-up');
                    header.classList.add('scrolling-down');
                } else {
                    // เลื่อนขึ้น - แสดง header
                    header.classList.remove('scrolling-down');
                    header.classList.add('scrolling-up');
                }
            } else {
                // อยู่ด้านบนสุด
                header.classList.remove('scrolling-down', 'scrolling-up');
            }
            
            lastScroll = currentScroll;
        }
        
        // ใช้ requestAnimationFrame สำหรับ performance
        let ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    handleScroll();
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
        
        // Initial check
        handleScroll();
    })();
    </script>
    <?php
}
add_action('wp_footer', 'sticky_header_add_script', 999);
