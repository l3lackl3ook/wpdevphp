<?php
/**
 * Plugin Name: Siam Blog Layouts
 * Description: แสดงหน้า Blog, Category, Single Post, Contact และ About แบบสวยงามเหมือน aommoney.com
 * Version: 2.0.0
 * Author: Siam Financial
 * Text Domain: siam-blog
 */

if (!defined('ABSPATH')) exit;

define('SIAM_BLOG_VERSION', '2.0.0');
define('SIAM_BLOG_PATH', plugin_dir_path(__FILE__));
define('SIAM_BLOG_URL', plugin_dir_url(__FILE__));

class Siam_Blog_Layouts {
    private static $instance = null;
    
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_shortcode('siam_categories', [$this, 'categories_grid']);
        add_shortcode('siam_posts', [$this, 'posts_list']);
        add_shortcode('siam_contact', [$this, 'contact_page']);
        add_shortcode('siam_about', [$this, 'about_page']);
        
        // Custom single post template
        add_filter('the_content', [$this, 'custom_single_post'], 20);
    }
    
    public function enqueue_assets() {
        wp_enqueue_style('siam-blog-styles', SIAM_BLOG_URL . 'assets/css/styles.css', [], SIAM_BLOG_VERSION);
        wp_enqueue_script('siam-blog-scripts', SIAM_BLOG_URL . 'assets/js/scripts.js', ['jquery'], SIAM_BLOG_VERSION, true);
    }
    
    /**
     * Shortcode: [siam_categories]
     * แสดงหมวดหมู่ทั้งหมดแบบ Grid
     */
    public function categories_grid($atts) {
        $atts = shortcode_atts([
            'title' => 'Column',
            'description' => 'เพิ่มความรู้และขยายมุมมองผ่านคอลัมน์สุดพิเศษ<br>ที่เขียนโดยนักเขียนมากประสบการณ์ในหลากหลายประเด็น',
            'exclude' => '', // ID หมวดหมู่ที่ไม่ต้องการแสดง
            'orderby' => 'count', // count, name, id
            'order' => 'DESC',
        ], $atts);
        
        // ดึงหมวดหมู่ทั้งหมด
        $args = [
            'taxonomy' => 'category',
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
            'hide_empty' => true,
        ];
        
        if (!empty($atts['exclude'])) {
            $args['exclude'] = $atts['exclude'];
        }
        
        $categories = get_categories($args);
        
        if (empty($categories)) {
            return '<p>ไม่พบหมวดหมู่</p>';
        }
        
        ob_start();
        ?>
        <div class="column-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="block-title">
                            <h1 class="title"><?php echo esc_html($atts['title']); ?></h1>
                            <div class="desc">
                                <?php echo wp_kses_post($atts['description']); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <ul class="column-list">
                            <?php foreach ($categories as $category): ?>
                                <?php
                                // ดึงรูปภาพจาก category (ถ้ามี plugin category image)
                                $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                                $image_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'medium') : SIAM_BLOG_URL . 'assets/images/default-category.jpg';
                                
                                // ถ้าไม่มีรูป ใช้รูปจากโพสต์ล่าสุดในหมวดนั้น
                                if (!$thumbnail_id) {
                                    $recent_post = get_posts([
                                        'category' => $category->term_id,
                                        'posts_per_page' => 1,
                                        'post_status' => 'publish',
                                    ]);
                                    
                                    if (!empty($recent_post) && has_post_thumbnail($recent_post[0]->ID)) {
                                        $image_url = get_the_post_thumbnail_url($recent_post[0]->ID, 'medium');
                                    }
                                }
                                ?>
                                <li>
                                    <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" aria-label="Category link">
                                        <div class="img-post">
                                            <img src="<?php echo esc_url($image_url); ?>" 
                                                 alt="<?php echo esc_attr($category->name); ?>"
                                                 loading="lazy">
                                        </div>
                                    </a>
                                    <div class="block-details">
                                        <p class="count"><?php echo $category->count; ?> Articles</p>
                                        <h3 class="title-post">
                                            <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                                               aria-label="Category link" 
                                               class="permalink">
                                                <?php echo esc_html($category->name); ?>
                                            </a>
                                        </h3>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Shortcode: [siam_posts category="การลงทุน" posts_per_page="12"]
     * แสดงบทความในหมวดหมู่ พร้อม Latest/Popular tabs
     */
    public function posts_list($atts) {
        $atts = shortcode_atts([
            'category' => '', // slug หรือ ID ของหมวดหมู่
            'posts_per_page' => 12,
            'show_tabs' => 'yes', // แสดง Latest/Popular tabs
            'show_excerpt' => 'yes',
            'show_author' => 'yes',
            'show_date' => 'yes',
            'show_categories' => 'yes',
            'orderby' => 'date', // date, comment_count (popular)
        ], $atts);
        
        // ดึงข้อมูลหมวดหมู่
        $category_obj = null;
        if (!empty($atts['category'])) {
            if (is_numeric($atts['category'])) {
                $category_obj = get_category($atts['category']);
            } else {
                $category_obj = get_category_by_slug($atts['category']);
            }
        }
        
        // Query posts
        $paged = get_query_var('paged') ? get_query_var('paged') : 1;
        $args = [
            'posts_per_page' => $atts['posts_per_page'],
            'paged' => $paged,
            'post_status' => 'publish',
            'orderby' => $atts['orderby'],
            'order' => 'DESC',
        ];
        
        if ($category_obj) {
            $args['cat'] = $category_obj->term_id;
        }
        
        // ถ้าเป็น Popular ให้เรียงตาม comment_count
        $sort_type = isset($_GET['options']) && $_GET['options'] === 'popular' ? 'popular' : 'latest';
        if ($sort_type === 'popular') {
            $args['orderby'] = 'comment_count';
        }
        
        $query = new WP_Query($args);
        
        if (!$query->have_posts()) {
            return '<p>ไม่พบบทความ</p>';
        }
        
        ob_start();
        ?>
        <div class="category-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="block-title">
                            <h1 class="title">
                                <?php echo $category_obj ? esc_html($category_obj->name) : 'บทความทั้งหมด'; ?>
                            </h1>
                            <?php if ($atts['show_tabs'] === 'yes'): ?>
                                <div class="tab-button-wrapper">
                                    <?php
                                    $current_url = remove_query_arg('options');
                                    $popular_url = add_query_arg('options', 'popular', $current_url);
                                    ?>
                                    <a href="<?php echo esc_url($current_url); ?>" 
                                       class="tab-button <?php echo $sort_type === 'latest' ? 'active' : ''; ?>">
                                        Lastest
                                    </a>
                                    <a href="<?php echo esc_url($popular_url); ?>" 
                                       class="tab-button <?php echo $sort_type === 'popular' ? 'active' : ''; ?>">
                                        Popular
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <ul class="article-list">
                            <?php while ($query->have_posts()): $query->the_post(); ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>" aria-label="Post link">
                                        <div class="img-post">
                                            <?php if (has_post_thumbnail()): ?>
                                                <?php the_post_thumbnail('medium'); ?>
                                            <?php else: ?>
                                                <img src="<?php echo SIAM_BLOG_URL . 'assets/images/default-post.jpg'; ?>" 
                                                     alt="<?php the_title(); ?>">
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                    <div class="block-details">
                                        <?php if ($atts['show_author'] === 'yes' || $atts['show_date'] === 'yes'): ?>
                                            <div class="author-date-wrapper">
                                                <?php if ($atts['show_author'] === 'yes'): ?>
                                                    <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" 
                                                       class="author">
                                                        <?php the_author(); ?>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if ($atts['show_date'] === 'yes'): ?>
                                                    <p class="date"><?php echo get_the_date('F j, Y'); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <h2 class="title-post">
                                            <a href="<?php the_permalink(); ?>" class="permalink" aria-label="Post link">
                                                <?php echo wp_trim_words(get_the_title(), 15, '...'); ?>
                                            </a>
                                        </h2>
                                        
                                        <?php if ($atts['show_excerpt'] === 'yes'): ?>
                                            <p class="excerpt">
                                                <?php echo wp_trim_words(get_the_excerpt(), 25, '...'); ?>
                                            </p>
                                        <?php endif; ?>
                                        
                                        <?php if ($atts['show_categories'] === 'yes'): ?>
                                            <?php
                                            $post_categories = get_the_category();
                                            if (!empty($post_categories)):
                                            ?>
                                                <ul class="cate-wrapper">
                                                    <?php foreach ($post_categories as $cat): ?>
                                                        <?php
                                                        // สุ่มสีสำหรับแต่ละหมวดหมู่
                                                        $colors = [
                                                            'rgba(26,116,168, 0.05)' => 'rgb(26,116,168)',
                                                            'rgba(247,103,157, 0.05)' => 'rgb(247,103,157)',
                                                            'rgba(106,121,200, 0.05)' => 'rgb(106,121,200)',
                                                            'rgba(26,188,156, 0.05)' => 'rgb(26,188,156)',
                                                            'rgba(241,196,15, 0.05)' => 'rgb(241,196,15)',
                                                        ];
                                                        $color_keys = array_keys($colors);
                                                        $bg_color = $color_keys[array_rand($color_keys)];
                                                        $text_color = $colors[$bg_color];
                                                        ?>
                                                        <li>
                                                            <a href="<?php echo get_category_link($cat->term_id); ?>" 
                                                               aria-label="Link category"
                                                               style="background-color: <?php echo $bg_color; ?>; 
                                                                      color: <?php echo $text_color; ?>; 
                                                                      border: 1px solid <?php echo str_replace('0.05', '.5', $bg_color); ?>; 
                                                                      --hover-cate-background-color: <?php echo $text_color; ?>;">
                                                                <?php echo esc_html($cat->name); ?>
                                                            </a>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                        
                        <?php if ($query->max_num_pages > 1): ?>
                            <div class="pagination-wrapper">
                                <div class="pagination">
                                    <?php
                                    echo paginate_links([
                                        'total' => $query->max_num_pages,
                                        'current' => $paged,
                                        'prev_text' => '<span>Previous</span>',
                                        'next_text' => '<span>Next</span>',
                                        'type' => 'list',
                                    ]);
                                    ?>
                                </div>
                                <p class="showing">
                                    Showing <?php echo (($paged - 1) * $atts['posts_per_page']) + 1; ?> - 
                                    <?php echo min($paged * $atts['posts_per_page'], $query->found_posts); ?> 
                                    of <?php echo $query->found_posts; ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        wp_reset_postdata();
        return ob_get_clean();
    }
    
    /**
     * Shortcode: [siam_contact]
     */
    public function contact_page($atts) {
        $atts = shortcode_atts([
            'email' => 'nevermind2629@gmail.com',
            'phone' => '02-XXX-XXXX',
        ], $atts);
        
        return '<div class="contact-wrapper"><div class="container"><div class="contact-header"><h1>ติดต่อเรา</h1><p class="subtitle">มีคำถามหรือข้อเสนอแนะ? เราพร้อมรับฟัง</p></div><div class="contact-info"><h3>ข้อมูลติดต่อ</h3><div class="info-item"><div class="icon">📧</div><div class="details"><h4>อีเมล</h4><a href="mailto:' . esc_attr($atts['email']) . '">' . esc_html($atts['email']) . '</a></div></div></div></div></div>';
    }
    
    /**
     * Shortcode: [siam_about]
     */
    public function about_page($atts) {
        return '<div class="about-wrapper"><div class="container"><h1>เกี่ยวกับเรา</h1><p>Siam Financial - แหล่งข้อมูลข่าวสารและความรู้ทางการเงินที่คุณไว้วางใจ</p></div></div>';
    }
    
    /**
     * Custom Single Post
     */
    public function custom_single_post($content) {
        if (!is_single() || !in_the_loop() || !is_main_query()) {
            return $content;
        }
        
        $author_id = get_the_author_meta('ID');
        $author_name = get_the_author();
        $author_url = get_author_posts_url($author_id);
        $author_avatar = get_avatar_url($author_id, ['size' => 60]);
        $categories = get_the_category();
        $tags = get_the_tags();
        
        $colors = [
            'rgba(26,116,168, 0.05)' => 'rgb(26,116,168)',
            'rgba(247,103,157, 0.05)' => 'rgb(247,103,157)',
            'rgba(106,121,200, 0.05)' => 'rgb(106,121,200)',
        ];
        
        ob_start();
        ?>
        <div class="author-wrapper">
            <div class="author-inner">
                <div class="avatar">
                    <a href="<?php echo esc_url($author_url); ?>">
                        <img src="<?php echo esc_url($author_avatar); ?>" alt="<?php echo esc_attr($author_name); ?>">
                    </a>
                </div>
                <div class="info">
                    <h2><a href="<?php echo esc_url($author_url); ?>"><?php echo esc_html($author_name); ?></a></h2>
                    <p class="date"><?php echo get_the_date('j F Y'); ?></p>
                </div>
            </div>
        </div>
        
        <?php if (!empty($categories)): ?>
            <ul class="cate-wrapper">
                <?php foreach ($categories as $cat): 
                    $color_keys = array_keys($colors);
                    $bg_color = $color_keys[array_rand($color_keys)];
                    $text_color = $colors[$bg_color];
                ?>
                    <li>
                        <a href="<?php echo get_category_link($cat->term_id); ?>" 
                           style="background-color: <?php echo $bg_color; ?>; color: <?php echo $text_color; ?>; border: 1px solid <?php echo str_replace('0.05', '.5', $bg_color); ?>; --hover-cate-background-color: <?php echo $text_color; ?>;">
                            <?php echo esc_html($cat->name); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        
        <div class="content-wrapper">
            <div class="content-inner">
                <?php echo $content; ?>
            </div>
            
            <?php if (!empty($tags)): ?>
                <div class="tag-wrapper">
                    <h3 class="title-tag">Tagged in</h3>
                    <div class="tag-list">
                        <?php foreach ($tags as $tag): ?>
                            <a href="<?php echo get_tag_link($tag->term_id); ?>"><?php echo esc_html($tag->name); ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="share-wrapper">
                <h3 class="title-share">Share</h3>
                <ul class="icon-share">
                    <li class="share-facebook">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" aria-label="Facebook">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                    </li>
                    <li class="share-twitter">
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" aria-label="Twitter">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                    </li>
                    <li class="share-linkedin">
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink()); ?>" target="_blank" aria-label="LinkedIn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                        </a>
                    </li>
                    <li class="share-line">
                        <a href="https://social-plugins.line.me/lineit/share?url=<?php echo urlencode(get_permalink()); ?>" target="_blank" aria-label="LINE">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/></svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <?php
        
        return ob_get_clean();
    }
}

// Initialize
function siam_blog_layouts() {
    return Siam_Blog_Layouts::instance();
}
siam_blog_layouts();
