<?php
/**
 * Hello Elementor Child Theme Functions
 * ระบบดึงข้อมูลอัตราแลกเปลี่ยนจากธนาคารแห่งประเทศไทย (BoT)
 */

// โหลด stylesheet และ scripts
add_action('wp_enqueue_scripts', 'hello_child_enqueue_styles');
function hello_child_enqueue_styles() {
    // Parent theme style
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    
    // Child theme style
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'), '1.0.0');
    
    // Custom JavaScript
    wp_enqueue_script('child-custom-js', get_stylesheet_directory_uri() . '/custom.js', array('jquery'), '1.0.0', true);
    
    // Google Fonts
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap', array(), null);
}

// 1. สร้าง Custom Post Type
function create_exchange_rate_cpt() {
    register_post_type('exchange_rate', array(
        'labels' => array(
            'name' => 'อัตราแลกเปลี่ยน',
            'singular_name' => 'อัตราแลกเปลี่ยน',
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'custom-fields'),
        'menu_icon' => 'dashicons-money-alt',
    ));
}
add_action('init', 'create_exchange_rate_cpt');

// 2. ดึงข้อมูลจาก BoT API (แก้ไขแล้ว - เพิ่ม User-Agent และ error logging)
function fetch_bot_exchange_rates() {
    $api_url = 'https://www.bot.or.th/content/bot/th/statistics/exchange-rate/jcr:content/root/container/statisticstable2.results.level1cache.json';
    
    // เพิ่ม headers และ timeout ที่เหมาะสม
    $response = wp_remote_get($api_url, array(
        'timeout' => 45,
        'sslverify' => false, // ปิด SSL verify ถ้า server มีปัญหา
        'headers' => array(
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Accept' => 'application/json, text/javascript, */*; q=0.01',
            'Accept-Language' => 'th-TH,th;q=0.9,en-US;q=0.8,en;q=0.7',
            'Referer' => 'https://www.bot.or.th/th/statistics/exchange-rate.html',
            'Cache-Control' => 'no-cache'
        )
    ));
    
    // ตรวจสอบ error
    if (is_wp_error($response)) {
        error_log('BoT API Error: ' . $response->get_error_message());
        update_option('bot_exchange_last_error', $response->get_error_message());
        return false;
    }
    
    // ตรวจสอบ HTTP status code
    $status_code = wp_remote_retrieve_response_code($response);
    if ($status_code !== 200) {
        error_log('BoT API HTTP Error: Status ' . $status_code);
        update_option('bot_exchange_last_error', 'HTTP Error: ' . $status_code);
        return false;
    }
    
    $body = wp_remote_retrieve_body($response);
    
    // ตรวจสอบว่ามีข้อมูล
    if (empty($body)) {
        error_log('BoT API: Empty response body');
        update_option('bot_exchange_last_error', 'Empty response from API');
        return false;
    }
    
    $data = json_decode($body, true);
    
    // ตรวจสอบ JSON decode error
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log('BoT API JSON Error: ' . json_last_error_msg());
        update_option('bot_exchange_last_error', 'JSON decode error: ' . json_last_error_msg());
        return false;
    }
    
    // ตรวจสอบโครงสร้างข้อมูล
    if (!isset($data['data']['responseContent'])) {
        error_log('BoT API: Invalid data structure');
        update_option('bot_exchange_last_error', 'Invalid data structure from API');
        return false;
    }
    
    // ลบ error message เมื่อสำเร็จ
    delete_option('bot_exchange_last_error');
    
    return $data['data']['responseContent'];
}

// 3. บันทึกข้อมูล
function save_exchange_rates_to_wp() {
    $rates = fetch_bot_exchange_rates();
    if (!$rates) return false;
    $count = 0;
    foreach ($rates as $rate) {
        $existing = get_posts(array(
            'post_type' => 'exchange_rate',
            'meta_key' => 'currency_id',
            'meta_value' => $rate['currency_id'],
            'posts_per_page' => 1
        ));
        $post_data = array(
            'post_title' => $rate['currency_id'] . ' - ' . $rate['period'],
            'post_type' => 'exchange_rate',
            'post_status' => 'publish'
        );
        if ($existing) {
            $post_data['ID'] = $existing[0]->ID;
            $post_id = wp_update_post($post_data);
        } else {
            $post_id = wp_insert_post($post_data);
        }
        if ($post_id) {
            update_post_meta($post_id, 'period', $rate['period']);
            update_post_meta($post_id, 'currency_id', $rate['currency_id']);
            update_post_meta($post_id, 'currency_name_th', $rate['currency_name_th']);
            update_post_meta($post_id, 'buying_sight', $rate['buying_sight']);
            update_post_meta($post_id, 'buying_transfer', $rate['buying_transfer']);
            update_post_meta($post_id, 'selling', $rate['selling']);
            update_post_meta($post_id, 'flag_path', 'https://www.bot.or.th' . $rate['flagPath']);
            $count++;
        }
    }
    update_option('bot_exchange_last_update', current_time('mysql'));
    return $count;
}

// 4. ตั้งค่าอัปเดตอัตโนมัติ
function schedule_exchange_rate_update() {
    if (!wp_next_scheduled('update_exchange_rates_hook')) {
        wp_schedule_event(time(), 'daily', 'update_exchange_rates_hook');
    }
}
add_action('wp', 'schedule_exchange_rate_update');
add_action('update_exchange_rates_hook', 'save_exchange_rates_to_wp');

// 5. เมนูอัปเดต
function add_exchange_rate_update_button() {
    add_submenu_page(
        'edit.php?post_type=exchange_rate',
        'อัปเดตข้อมูล',
        'อัปเดตข้อมูล',
        'manage_options',
        'update-exchange-rates',
        'render_update_exchange_rates_page'
    );
}
add_action('admin_menu', 'add_exchange_rate_update_button');

function render_update_exchange_rates_page() {
    $last_update = get_option('bot_exchange_last_update', 'ยังไม่เคยอัปเดต');
    $last_error = get_option('bot_exchange_last_error', '');
    
    if (isset($_POST['update_rates']) && check_admin_referer('update_exchange_rates')) {
        $count = save_exchange_rates_to_wp();
        if ($count !== false && $count > 0) {
            echo '<div class="notice notice-success is-dismissible"><p>✅ <strong>อัปเดตข้อมูลเรียบร้อยแล้ว!</strong> ดึงข้อมูล ' . $count . ' สกุลเงิน</p></div>';
            $last_update = get_option('bot_exchange_last_update');
            $last_error = ''; // ล้าง error
        } else {
            $error_msg = get_option('bot_exchange_last_error', 'ไม่สามารถเชื่อมต่อ API ได้');
            echo '<div class="notice notice-error is-dismissible">';
            echo '<p>❌ <strong>เกิดข้อผิดพลาดในการดึงข้อมูล</strong></p>';
            echo '<p><strong>สาเหตุ:</strong> ' . esc_html($error_msg) . '</p>';
            echo '<p><strong>แนะนำ:</strong></p>';
            echo '<ul style="list-style: disc; margin-left: 20px;">';
            echo '<li>ตรวจสอบว่าเซิร์ฟเวอร์สามารถเชื่อมต่ออินเทอร์เน็ตได้</li>';
            echo '<li>ตรวจสอบว่า PHP function <code>wp_remote_get()</code> ทำงานได้</li>';
            echo '<li>ลองอัปเดตอีกครั้งในอีกสักครู่</li>';
            echo '<li>ติดต่อผู้ให้บริการโฮสติ้งหากปัญหายังคงอยู่</li>';
            echo '</ul>';
            echo '</div>';
        }
    }
    
    // นับจำนวนข้อมูลที่มีอยู่
    $existing_count = wp_count_posts('exchange_rate')->publish;
    ?>
    <div class="wrap">
        <h1>🔄 อัปเดตอัตราแลกเปลี่ยน</h1>
        
        <div class="card" style="max-width: 800px; margin: 20px 0;">
            <h2 style="margin-top: 0;">📊 สถานะข้อมูล</h2>
            <table class="widefat" style="margin-top: 10px;">
                <tr>
                    <td style="width: 200px;"><strong>อัปเดตล่าสุด:</strong></td>
                    <td><?php echo esc_html($last_update); ?></td>
                </tr>
                <tr>
                    <td><strong>จำนวนสกุลเงิน:</strong></td>
                    <td><?php echo $existing_count; ?> สกุลเงิน</td>
                </tr>
                <tr>
                    <td><strong>ระบบอัปเดตอัตโนมัติ:</strong></td>
                    <td>✅ เปิดใช้งาน (ทุกวัน)</td>
                </tr>
                <?php if ($last_error): ?>
                <tr style="background: #fff3cd;">
                    <td><strong>⚠️ Error ล่าสุด:</strong></td>
                    <td style="color: #856404;"><?php echo esc_html($last_error); ?></td>
                </tr>
                <?php endif; ?>
            </table>
        </div>
        
        <form method="post" style="margin: 20px 0;">
            <?php wp_nonce_field('update_exchange_rates'); ?>
            <p>
                <input type="submit" name="update_rates" class="button button-primary button-hero" value="🔄 อัปเดตข้อมูลทันที">
            </p>
            <p class="description">กดปุ่มนี้เพื่อดึงข้อมูลอัตราแลกเปลี่ยนล่าสุดจาก ธปท. ทันที</p>
        </form>
        
        <hr>
        
        <div class="card" style="max-width: 800px;">
            <h3>📖 วิธีใช้งาน</h3>
            <ol>
                <li><strong>อัปเดตข้อมูล:</strong> กดปุ่ม "อัปเดตข้อมูลทันที" ด้านบน</li>
                <li><strong>สร้างหน้าใหม่:</strong> ไปที่ Pages → Add New หรือแก้ไขหน้าที่มีอยู่</li>
                <li><strong>เปิด Elementor:</strong> คลิก "Edit with Elementor"</li>
                <li><strong>เพิ่ม Widget:</strong> ลาก Widget "Shortcode" มาวางในหน้า</li>
                <li><strong>ใส่ Shortcode:</strong> ใส่โค้ด <code>[bot_exchange_rates]</code></li>
                <li><strong>บันทึก:</strong> คลิก Update/Publish</li>
            </ol>
            
            <h3>🔧 การแก้ปัญหา</h3>
            <div style="background: #f0f0f1; padding: 15px; border-left: 4px solid #2271b1; margin: 10px 0;">
                <p><strong>ถ้าขึ้น "เกิดข้อผิดพลาด":</strong></p>
                <ul style="list-style: disc; margin-left: 20px;">
                    <li>ตรวจสอบว่าเซิร์ฟเวอร์เชื่อมต่ออินเทอร์เน็ตได้</li>
                    <li>ตรวจสอบ PHP error log ที่ <code>wp-content/debug.log</code></li>
                    <li>ลองปิด SSL verification ใน code (มีอยู่แล้ว)</li>
                    <li>ติดต่อผู้ให้บริการโฮสติ้งเพื่อเปิดใช้งาน <code>allow_url_fopen</code></li>
                </ul>
            </div>
            
            <div style="background: #d1ecf1; padding: 15px; border-left: 4px solid #0c5460; margin: 10px 0;">
                <p><strong>💡 เคล็ดลับ:</strong></p>
                <ul style="list-style: disc; margin-left: 20px;">
                    <li>ข้อมูลจะอัปเดตอัตโนมัติทุกวัน</li>
                    <li>คุณสามารถอัปเดตด้วยตนเองได้ตลอดเวลา</li>
                    <li>ใช้ shortcode <code>[bot_exchange_rates]</code> ได้ในหลายหน้า</li>
                </ul>
            </div>
        </div>
        
        <hr>
        
        <div style="background: #fff; padding: 20px; border: 1px solid #ccc; border-radius: 4px; max-width: 800px;">
            <h3>🧪 ทดสอบการเชื่อมต่อ API</h3>
            <p>URL: <code style="background: #f0f0f1; padding: 2px 6px;">https://www.bot.or.th/content/bot/th/statistics/exchange-rate/jcr:content/root/container/statisticstable2.results.level1cache.json</code></p>
            <p><a href="https://www.bot.or.th/content/bot/th/statistics/exchange-rate/jcr:content/root/container/statisticstable2.results.level1cache.json" target="_blank" class="button">เปิด API ในแท็บใหม่</a></p>
            <p class="description">ถ้าเปิดได้และเห็นข้อมูล JSON แสดงว่า API ทำงานปกติ</p>
        </div>
    </div>
    <?php
}

// 6. Shortcode แสดงตาราง
function display_exchange_rates_table() {
    $rates = get_posts(array(
        'post_type' => 'exchange_rate',
        'posts_per_page' => -1,
        'orderby' => 'meta_value',
        'meta_key' => 'currency_id',
        'order' => 'ASC'
    ));
    if (empty($rates)) {
        return '<p>⚠️ ไม่พบข้อมูล กรุณาอัปเดตข้อมูลก่อน</p>';
    }
    $period = get_post_meta($rates[0]->ID, 'period', true);
    $period_thai = date('d/m/Y', strtotime($period));
    ob_start();
    ?>
    <div class="bot-exchange-rates">
        <h3>อัตราแลกเปลี่ยนถัวเฉลี่ยที่ธนาคารพาณิชย์ซื้อขายกับลูกค้า</h3>
        <p>ประจำวันที่ <?php echo $period_thai; ?></p>
        <div class="table-responsive">
            <table class="exchange-table">
                <thead>
                    <tr>
                        <th rowspan="2">สกุลเงิน</th>
                        <th colspan="2">อัตราซื้อถัวเฉลี่ย</th>
                        <th rowspan="2">อัตราขายถัวเฉลี่ย</th>
                    </tr>
                    <tr>
                        <th>ซื้อตั๋วเงิน</th>
                        <th>ซื้อเงินโอน</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rates as $rate): 
                        $currency_id = get_post_meta($rate->ID, 'currency_id', true);
                        $currency_name = get_post_meta($rate->ID, 'currency_name_th', true);
                        $flag_path = get_post_meta($rate->ID, 'flag_path', true);
                        $buying_sight = get_post_meta($rate->ID, 'buying_sight', true);
                        $buying_transfer = get_post_meta($rate->ID, 'buying_transfer', true);
                        $selling = get_post_meta($rate->ID, 'selling', true);
                    ?>
                    <tr>
                        <td>
                            <div class="currency-cell">
                                <img src="<?php echo esc_url($flag_path); ?>" alt="<?php echo esc_attr($currency_id); ?>">
                                <div>
                                    <strong><?php echo esc_html($currency_id); ?></strong>
                                    <br><small><?php echo esc_html($currency_name); ?></small>
                                </div>
                            </div>
                        </td>
                        <td class="text-center"><?php echo esc_html($buying_sight); ?></td>
                        <td class="text-center"><?php echo esc_html($buying_transfer); ?></td>
                        <td class="text-center"><?php echo esc_html($selling); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <style>
            .bot-exchange-rates { margin: 20px 0; }
            .table-responsive { overflow-x: auto; }
            .exchange-table { width: 100%; border-collapse: collapse; min-width: 600px; }
            .exchange-table th, .exchange-table td { padding: 12px; border: 1px solid #ddd; }
            .exchange-table th { background: #003d7a; color: white; font-weight: bold; text-align: center; }
            .exchange-table tbody tr:nth-child(even) { background: #f9f9f9; }
            .exchange-table tbody tr:hover { background: #e8f4f8; }
            .currency-cell { display: flex; align-items: center; gap: 10px; }
            .currency-cell img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
            .text-center { text-align: center; font-weight: 500; }
        </style>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('bot_exchange_rates', 'display_exchange_rates_table');


// ========================================
// 7. ปรับปรุงหน้าโพสต์และ Archive
// ========================================

// เพิ่ม excerpt length
function custom_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'custom_excerpt_length');

// เปลี่ยน excerpt more
function custom_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'custom_excerpt_more');

// เพิ่ม Read More button
function add_read_more_link($excerpt) {
    if (!is_single()) {
        $excerpt .= '<a href="' . get_permalink() . '" class="read-more">อ่านต่อ →</a>';
    }
    return $excerpt;
}
add_filter('the_excerpt', 'add_read_more_link');

// เพิ่ม Featured Image support
add_theme_support('post-thumbnails');
set_post_thumbnail_size(800, 450, true);

// เพิ่ม Custom Image Sizes
add_image_size('blog-thumb', 400, 300, true);
add_image_size('blog-large', 1200, 675, true);

// ========================================
// 8. เพิ่ม Meta Tags สำหรับ SEO
// ========================================

function add_meta_tags() {
    if (is_single()) {
        global $post;
        $description = wp_trim_words(strip_tags($post->post_content), 30);
        $image = get_the_post_thumbnail_url($post->ID, 'full');
        ?>
        <meta name="description" content="<?php echo esc_attr($description); ?>">
        <meta property="og:title" content="<?php echo esc_attr(get_the_title()); ?>">
        <meta property="og:description" content="<?php echo esc_attr($description); ?>">
        <?php if ($image): ?>
        <meta property="og:image" content="<?php echo esc_url($image); ?>">
        <?php endif; ?>
        <meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>">
        <meta property="og:type" content="article">
        <meta name="twitter:card" content="summary_large_image">
        <?php
    }
}
add_action('wp_head', 'add_meta_tags');

// ========================================
// 9. เพิ่ม Reading Time
// ========================================

function get_reading_time() {
    $content = get_post_field('post_content', get_the_ID());
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // 200 words per minute
    return $reading_time . ' นาที';
}

function add_reading_time_to_content($content) {
    if (is_single() && !is_admin()) {
        $reading_time = get_reading_time();
        $reading_time_html = '<div class="reading-time" style="
            display: inline-block;
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #003d7a, #0056a8);
            color: white;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        ">⏱️ เวลาอ่าน: ' . $reading_time . '</div>';
        $content = $reading_time_html . $content;
    }
    return $content;
}
add_filter('the_content', 'add_reading_time_to_content');

// ========================================
// 10. เพิ่ม Share Buttons
// ========================================

function add_social_share_buttons($content) {
    if (is_single()) {
        $url = urlencode(get_permalink());
        $title = urlencode(get_the_title());
        
        $share_buttons = '
        <div class="social-share-buttons" style="
            margin: 3rem 0;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 12px;
            text-align: center;
        ">
            <h4 style="margin-bottom: 1.5rem; color: #1a1a1a;">แชร์บทความนี้</h4>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="https://www.facebook.com/sharer/sharer.php?u=' . $url . '" target="_blank" rel="noopener" style="
                    padding: 0.75rem 1.5rem;
                    background: #1877f2;
                    color: white;
                    border-radius: 8px;
                    font-weight: 500;
                    transition: all 0.3s;
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                " onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 12px rgba(24,119,242,0.3)\'" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'none\'">
                    📘 Facebook
                </a>
                <a href="https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title . '" target="_blank" rel="noopener" style="
                    padding: 0.75rem 1.5rem;
                    background: #1da1f2;
                    color: white;
                    border-radius: 8px;
                    font-weight: 500;
                    transition: all 0.3s;
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                " onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 12px rgba(29,161,242,0.3)\'" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'none\'">
                    🐦 Twitter
                </a>
                <a href="https://line.me/R/msg/text/?' . $title . '%20' . $url . '" target="_blank" rel="noopener" style="
                    padding: 0.75rem 1.5rem;
                    background: #00b900;
                    color: white;
                    border-radius: 8px;
                    font-weight: 500;
                    transition: all 0.3s;
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                " onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 4px 12px rgba(0,185,0,0.3)\'" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'none\'">
                    💬 LINE
                </a>
            </div>
        </div>';
        
        $content .= $share_buttons;
    }
    return $content;
}
add_filter('the_content', 'add_social_share_buttons', 20);

// ========================================
// 11. เพิ่ม Related Posts
// ========================================

function add_related_posts($content) {
    if (is_single()) {
        global $post;
        $categories = get_the_category($post->ID);
        
        if ($categories) {
            $category_ids = array();
            foreach($categories as $category) {
                $category_ids[] = $category->term_id;
            }
            
            $args = array(
                'category__in' => $category_ids,
                'post__not_in' => array($post->ID),
                'posts_per_page' => 3,
                'orderby' => 'rand'
            );
            
            $related_posts = new WP_Query($args);
            
            if ($related_posts->have_posts()) {
                $related_html = '
                <div class="related-posts" style="
                    margin: 4rem 0;
                    padding: 3rem 2rem;
                    background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
                    border-radius: 12px;
                ">
                    <h3 style="
                        text-align: center;
                        margin-bottom: 2rem;
                        font-size: 2rem;
                        background: linear-gradient(135deg, #003d7a, #00a8e8);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        background-clip: text;
                    ">บทความที่เกี่ยวข้อง</h3>
                    <div style="
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
                        gap: 2rem;
                    ">';
                
                while ($related_posts->have_posts()) {
                    $related_posts->the_post();
                    $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                    $thumbnail = $thumbnail ? $thumbnail : 'https://via.placeholder.com/400x300?text=No+Image';
                    
                    $related_html .= '
                    <article style="
                        background: white;
                        border-radius: 12px;
                        overflow: hidden;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                        transition: all 0.3s;
                    " onmouseover="this.style.transform=\'translateY(-8px)\'; this.style.boxShadow=\'0 8px 24px rgba(0,0,0,0.15)\'" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'0 2px 8px rgba(0,0,0,0.08)\'">
                        <div style="
                            aspect-ratio: 16/9;
                            overflow: hidden;
                            background: #f0f0f0;
                        ">
                            <img src="' . esc_url($thumbnail) . '" alt="' . esc_attr(get_the_title()) . '" style="
                                width: 100%;
                                height: 100%;
                                object-fit: cover;
                            ">
                        </div>
                        <div style="padding: 1.5rem;">
                            <h4 style="
                                font-size: 1.25rem;
                                margin-bottom: 0.75rem;
                                line-height: 1.4;
                            ">
                                <a href="' . get_permalink() . '" style="
                                    color: #1a1a1a;
                                    transition: color 0.3s;
                                " onmouseover="this.style.color=\'#00a8e8\'" onmouseout="this.style.color=\'#1a1a1a\'">' . get_the_title() . '</a>
                            </h4>
                            <p style="
                                color: #666;
                                font-size: 0.9rem;
                                margin-bottom: 1rem;
                            ">' . wp_trim_words(get_the_excerpt(), 15) . '</p>
                            <a href="' . get_permalink() . '" style="
                                display: inline-block;
                                padding: 0.5rem 1rem;
                                background: linear-gradient(135deg, #003d7a, #0056a8);
                                color: white;
                                border-radius: 6px;
                                font-size: 0.9rem;
                                font-weight: 500;
                                transition: all 0.3s;
                            " onmouseover="this.style.background=\'linear-gradient(135deg, #0056a8, #00a8e8)\'; this.style.transform=\'translateX(4px)\'" onmouseout="this.style.background=\'linear-gradient(135deg, #003d7a, #0056a8)\'; this.style.transform=\'translateX(0)\'">อ่านเพิ่มเติม →</a>
                        </div>
                    </article>';
                }
                
                $related_html .= '</div></div>';
                wp_reset_postdata();
                
                $content .= $related_html;
            }
        }
    }
    return $content;
}
add_filter('the_content', 'add_related_posts', 30);

// ========================================
// 12. เพิ่ม Breadcrumbs
// ========================================

function custom_breadcrumbs() {
    if (!is_front_page()) {
        echo '<div class="breadcrumbs" style="
            padding: 1rem 0;
            margin-bottom: 2rem;
            font-size: 0.9rem;
            color: #666;
        ">';
        echo '<a href="' . home_url() . '" style="color: #003d7a;">หน้าแรก</a> <span style="margin: 0 0.5rem;">›</span> ';
        
        if (is_category() || is_single()) {
            $categories = get_the_category();
            if ($categories) {
                $category = $categories[0];
                echo '<a href="' . get_category_link($category->term_id) . '" style="color: #003d7a;">' . $category->name . '</a>';
                if (is_single()) {
                    echo ' <span style="margin: 0 0.5rem;">›</span> ';
                    echo '<span>' . get_the_title() . '</span>';
                }
            }
        } elseif (is_page()) {
            echo '<span>' . get_the_title() . '</span>';
        } elseif (is_search()) {
            echo '<span>ผลการค้นหา: ' . get_search_query() . '</span>';
        }
        
        echo '</div>';
    }
}

// ========================================
// 13. เพิ่ม Performance Optimization
// ========================================

// Lazy load images
function add_lazy_load_to_images($content) {
    if (is_single() || is_page()) {
        $content = preg_replace('/<img(.*?)src=/i', '<img$1loading="lazy" src=', $content);
    }
    return $content;
}
add_filter('the_content', 'add_lazy_load_to_images');

// Remove jQuery Migrate
function remove_jquery_migrate($scripts) {
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        if ($script->deps) {
            $script->deps = array_diff($script->deps, array('jquery-migrate'));
        }
    }
}
add_action('wp_default_scripts', 'remove_jquery_migrate');

// ========================================
// 14. Sticky Header with Scroll Detection
// ========================================

function add_sticky_header_script() {
    ?>
    <script>
    (function() {
        'use strict';
        
        let lastScroll = 0;
        const header = document.querySelector('.elementor-location-header');
        
        if (!header) return;
        
        // Throttle function สำหรับ performance
        function throttle(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
        
        function handleScroll() {
            const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
            
            // ป้องกัน negative scroll
            if (currentScroll < 0) return;
            
            if (currentScroll > 100) {
                if (currentScroll > lastScroll && !header.classList.contains('scrolling-down')) {
                    // เลื่อนลง - ซ่อน header
                    header.classList.remove('scrolling-up');
                    header.classList.add('scrolling-down');
                } else if (currentScroll < lastScroll && !header.classList.contains('scrolling-up')) {
                    // เลื่อนขึ้น - แสดง header
                    header.classList.remove('scrolling-down');
                    header.classList.add('scrolling-up');
                }
            } else {
                // อยู่ด้านบนสุด - ลบทุกคลาส
                header.classList.remove('scrolling-down', 'scrolling-up');
            }
            
            lastScroll = currentScroll;
        }
        
        // ใช้ throttle เพื่อลด event firing
        const throttledScroll = throttle(handleScroll, 100);
        
        // เพิ่ม event listener
        window.addEventListener('scroll', throttledScroll, { passive: true });
        
        // Initial check
        handleScroll();
    })();
    </script>
    <?php
}
add_action('wp_footer', 'add_sticky_header_script', 999);
