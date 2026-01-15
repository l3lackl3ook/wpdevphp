<?php
/**
 * Plugin Name: Synergy Sidebar Widgets
 * Description: Sidebar widgets สำหรับเว็บไซต์บทความทางการเงิน - synergycrafted.com
 * Version: 1.0.0
 * Author: Synergy Crafted
 */

if (!defined('ABSPATH')) exit;

define('SYNERGY_SIDEBAR_VERSION', '1.0.0');
define('SYNERGY_SIDEBAR_PATH', plugin_dir_path(__FILE__));

class Synergy_Sidebar_Widgets {
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        
        // Register shortcodes
        add_shortcode('synergy_popular_posts', [$this, 'popular_posts']);
        add_shortcode('synergy_recent_posts', [$this, 'recent_posts']);
        add_shortcode('synergy_categories', [$this, 'categories_widget']);
        add_shortcode('synergy_exchange_mini', [$this, 'exchange_mini']);
        add_shortcode('synergy_gold_mini', [$this, 'gold_mini']);
        add_shortcode('synergy_newsletter', [$this, 'newsletter_form']);
    }
    
    public function enqueue_assets() {
        wp_enqueue_style('synergy-sidebar', plugin_dir_url(__FILE__) . 'assets/css/sidebar.css', [], SYNERGY_SIDEBAR_VERSION);
        wp_enqueue_script('synergy-sidebar', plugin_dir_url(__FILE__) . 'assets/js/sidebar.js', ['jquery'], SYNERGY_SIDEBAR_VERSION, true);
    }
    
    /**
     * Shortcode: [synergy_popular_posts limit="5"]
     * แสดงบทความยอดนิยม
     */
    public function popular_posts($atts) {
        $atts = shortcode_atts([
            'limit' => 5,
            'show_thumbnail' => 'yes',
            'show_date' => 'yes',
            'show_views' => 'no'
        ], $atts);
        
        $args = [
            'post_type' => 'post',
            'posts_per_page' => $atts['limit'],
            'orderby' => 'comment_count',
            'order' => 'DESC',
            'post_status' => 'publish'
        ];
        
        $popular = new WP_Query($args);
        
        if (!$popular->have_posts()) {
            return '<p class="synergy-no-posts">ยังไม่มีบทความ</p>';
        }
        
        ob_start();
        ?>
        <div class="synergy-widget synergy-popular-posts">
            <h3 class="synergy-widget-title">บทความยอดนิยม</h3>
            <ul class="synergy-post-list">
                <?php while ($popular->have_posts()): $popular->the_post(); ?>
                    <li class="synergy-post-item">
                        <?php if ($atts['show_thumbnail'] === 'yes' && has_post_thumbnail()): ?>
                            <div class="synergy-post-thumb">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('thumbnail'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="synergy-post-content">
                            <h4 class="synergy-post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h4>
                            <?php if ($atts['show_date'] === 'yes'): ?>
                                <span class="synergy-post-date"><?php echo get_the_date('d M Y'); ?></span>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endwhile; wp_reset_postdata(); ?>
            </ul>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Shortcode: [synergy_recent_posts limit="5"]
     * แสดงบทความล่าสุด
     */
    public function recent_posts($atts) {
        $atts = shortcode_atts([
            'limit' => 5,
            'show_thumbnail' => 'yes',
            'show_date' => 'yes'
        ], $atts);
        
        $args = [
            'post_type' => 'post',
            'posts_per_page' => $atts['limit'],
            'orderby' => 'date',
            'order' => 'DESC',
            'post_status' => 'publish'
        ];
        
        $recent = new WP_Query($args);
        
        if (!$recent->have_posts()) {
            return '<p class="synergy-no-posts">ยังไม่มีบทความ</p>';
        }
        
        ob_start();
        ?>
        <div class="synergy-widget synergy-recent-posts">
            <h3 class="synergy-widget-title">บทความล่าสุด</h3>
            <ul class="synergy-post-list">
                <?php while ($recent->have_posts()): $recent->the_post(); ?>
                    <li class="synergy-post-item">
                        <?php if ($atts['show_thumbnail'] === 'yes' && has_post_thumbnail()): ?>
                            <div class="synergy-post-thumb">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('thumbnail'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="synergy-post-content">
                            <h4 class="synergy-post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h4>
                            <?php if ($atts['show_date'] === 'yes'): ?>
                                <span class="synergy-post-date"><?php echo get_the_date('d M Y'); ?></span>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endwhile; wp_reset_postdata(); ?>
            </ul>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Shortcode: [synergy_categories]
     * แสดงหมวดหมู่
     */
    public function categories_widget($atts) {
        $atts = shortcode_atts([
            'show_count' => 'yes',
            'orderby' => 'count',
            'order' => 'DESC',
            'limit' => 10
        ], $atts);
        
        $categories = get_categories([
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
            'number' => $atts['limit'],
            'hide_empty' => true
        ]);
        
        if (empty($categories)) {
            return '<p class="synergy-no-cats">ยังไม่มีหมวดหมู่</p>';
        }
        
        ob_start();
        ?>
        <div class="synergy-widget synergy-categories">
            <h3 class="synergy-widget-title">หมวดหมู่</h3>
            <ul class="synergy-cat-list">
                <?php foreach ($categories as $cat): ?>
                    <li class="synergy-cat-item">
                        <a href="<?php echo get_category_link($cat->term_id); ?>">
                            <span class="synergy-cat-name"><?php echo esc_html($cat->name); ?></span>
                            <?php if ($atts['show_count'] === 'yes'): ?>
                                <span class="synergy-cat-count">(<?php echo $cat->count; ?>)</span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Shortcode: [synergy_exchange_mini currencies="USD,EUR,GBP"]
     * แสดงอัตราแลกเปลี่ยนแบบกระชับ
     */
    public function exchange_mini($atts) {
        $atts = shortcode_atts([
            'currencies' => 'USD,EUR,GBP,JPY,CNY',
            'show_flags' => 'yes'
        ], $atts);
        
        $data = get_option('options_exchange_data');
        $period = get_option('options_period');
        
        if (!$data) {
            return '<div class="synergy-no-data">ไม่มีข้อมูลอัตราแลกเปลี่ยน</div>';
        }
        
        $rates = json_decode($data, true);
        $currencies_array = array_map('trim', explode(',', strtoupper($atts['currencies'])));
        
        $flags = [
            'USD' => '🇺🇸', 'EUR' => '🇪🇺', 'GBP' => '🇬🇧', 'JPY' => '🇯🇵',
            'CNY' => '🇨🇳', 'HKD' => '🇭🇰', 'SGD' => '🇸🇬', 'AUD' => '🇦🇺',
            'CHF' => '🇨🇭', 'CAD' => '🇨🇦', 'NZD' => '🇳🇿', 'MYR' => '🇲🇾'
        ];
        
        ob_start();
        ?>
        <div class="synergy-widget synergy-exchange-mini">
            <h3 class="synergy-widget-title">อัตราแลกเปลี่ยน</h3>
            <?php if ($period): ?>
                <div class="synergy-widget-meta">
                    <span class="synergy-meta-date">ประจำวันที่ <?php echo esc_html($period); ?></span>
                    <span class="synergy-meta-source">ข้อมูลจากธนาคารแห่งประเทศไทย</span>
                </div>
            <?php endif; ?>
            <div class="synergy-rates-list">
                <?php foreach ($rates as $rate): 
                    $currency = strtoupper($rate['currency_id'] ?? '');
                    if (!in_array($currency, $currencies_array)) continue;
                    $sell = $rate['selling'] ?? '-';
                ?>
                    <div class="synergy-rate-item">
                        <div class="synergy-rate-currency">
                            <?php if ($atts['show_flags'] === 'yes' && isset($flags[$currency])): ?>
                                <span class="synergy-flag"><?php echo $flags[$currency]; ?></span>
                            <?php endif; ?>
                            <span class="synergy-currency-code"><?php echo $currency; ?></span>
                        </div>
                        <div class="synergy-rate-value">
                            <?php echo $sell !== '-' ? number_format((float)$sell, 2) : '-'; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Shortcode: [synergy_gold_mini]
     * แสดงราคาทองแบบย่อ
     */
    public function gold_mini($atts) {
        $data = get_option('options_gold_data');
        $last_update = get_option('options_gold_last_update');
        
        if (!$data) {
            return '<div class="synergy-no-data">ไม่มีข้อมูลราคาทอง</div>';
        }
        
        $gold = json_decode($data, true);
        $bar_buy = str_replace(',', '', $gold['bar_buy'] ?? '0');
        $bar_sell = str_replace(',', '', $gold['bar_sell'] ?? '0');
        $change = str_replace(',', '', $gold['change'] ?? '0');
        $date = $gold['date'] ?? '';
        $time = $gold['time'] ?? '';
        
        $change_class = $change > 0 ? 'up' : ($change < 0 ? 'down' : 'neutral');
        $change_icon = $change > 0 ? '▲' : ($change < 0 ? '▼' : '');
        
        ob_start();
        ?>
        <div class="synergy-widget synergy-gold-mini">
            <h3 class="synergy-widget-title">ราคาทองคำ</h3>
            <?php if ($date || $time): ?>
                <div class="synergy-widget-meta">
                    <?php if ($date): ?>
                        <span class="synergy-meta-date"><?php echo esc_html($date); ?></span>
                    <?php endif; ?>
                    <?php if ($time): ?>
                        <span class="synergy-meta-time"><?php echo esc_html($time); ?></span>
                    <?php endif; ?>
                    <span class="synergy-meta-source">ข้อมูลจากสมาคมค้าทองคำ</span>
                </div>
            <?php endif; ?>
            <div class="synergy-gold-prices">
                <div class="synergy-gold-row">
                    <span class="synergy-gold-label">รับซื้อ</span>
                    <span class="synergy-gold-price"><?php echo number_format((float)$bar_buy, 0); ?></span>
                </div>
                <div class="synergy-gold-row">
                    <span class="synergy-gold-label">ขายออก</span>
                    <span class="synergy-gold-price"><?php echo number_format((float)$bar_sell, 0); ?></span>
                </div>
                <div class="synergy-gold-change <?php echo $change_class; ?>">
                    <span class="synergy-change-icon"><?php echo $change_icon; ?></span>
                    <span class="synergy-change-value"><?php echo ($change > 0 ? '+' : '') . number_format((float)$change, 0); ?></span>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Shortcode: [synergy_newsletter]
     * ฟอร์มสมัครรับข่าวสาร
     */
    public function newsletter_form($atts) {
        $atts = shortcode_atts([
            'title' => 'รับข่าวสารล่าสุด',
            'description' => 'สมัครรับบทความและข้อมูลการเงินทุกวัน'
        ], $atts);
        
        ob_start();
        ?>
        <div class="synergy-widget synergy-newsletter">
            <h3 class="synergy-widget-title"><?php echo esc_html($atts['title']); ?></h3>
            <p class="synergy-newsletter-desc"><?php echo esc_html($atts['description']); ?></p>
            <form class="synergy-newsletter-form" method="post" action="">
                <input type="email" 
                       name="synergy_email" 
                       placeholder="อีเมลของคุณ" 
                       required 
                       class="synergy-email-input">
                <button type="submit" class="synergy-submit-btn">สมัครเลย</button>
            </form>
        </div>
        <?php
        return ob_get_clean();
    }
}

// Initialize
Synergy_Sidebar_Widgets::instance();
