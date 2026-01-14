<?php
/**
 * Custom Single Post Layout
 * แสดงบทความแบบสวยงามเหมือน aommoney.com
 */

if (!defined('ABSPATH')) exit;

function siam_custom_single_post_content($content) {
    // ใช้เฉพาะหน้า single post
    if (!is_single()) {
        return $content;
    }
    
    global $post;
    
    // ดึงข้อมูล
    $author_id = $post->post_author;
    $author_name = get_the_author_meta('display_name', $author_id);
    $author_url = get_author_posts_url($author_id);
    $author_avatar = get_avatar_url($author_id, ['size' => 150]);
    $post_date = get_the_date('j F Y');
    $categories = get_the_category();
    $tags = get_the_tags();
    $featured_image = get_the_post_thumbnail_url($post->ID, 'full');
    
    // สีสำหรับหมวดหมู่
    $colors = [
        'rgba(26,116,168, 0.05)' => 'rgb(26,116,168)',
        'rgba(247,103,157, 0.05)' => 'rgb(247,103,157)',
        'rgba(106,121,200, 0.05)' => 'rgb(106,121,200)',
        'rgba(26,188,156, 0.05)' => 'rgb(26,188,156)',
        'rgba(241,196,15, 0.05)' => 'rgb(241,196,15)',
        'rgba(231,76,60, 0.05)' => 'rgb(231,76,60)',
    ];
    
    ob_start();
    ?>
    <div class="single-post-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Author Info -->
                    <div class="author-wrapper">
                        <div class="author-inner">
                            <div class="avatar">
                                <a href="<?php echo esc_url($author_url); ?>">
                                    <img src="<?php echo esc_url($author_avatar); ?>" 
                                         alt="<?php echo esc_attr($author_name); ?>">
                                </a>
                            </div>
                            <div class="info">
                                <h2><a href="<?php echo esc_url($author_url); ?>"><?php echo esc_html($author_name); ?></a></h2>
                                <p class="date"><?php echo esc_html($post_date); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Categories -->
                    <?php if (!empty($categories)): ?>
                        <ul class="cate-wrapper">
                            <?php foreach ($categories as $cat): ?>
                                <?php
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
                    
                    <!-- Title -->
                    <h1><?php the_title(); ?></h1>
                    
                    <!-- Featured Image -->
                    <?php if ($featured_image): ?>
                        <figure class="feature-image">
                            <img src="<?php echo esc_url($featured_image); ?>" 
                                 alt="<?php the_title(); ?>">
                        </figure>
                    <?php endif; ?>
                    
                    <!-- Content -->
                    <div class="content-wrapper">
                        <div class="content-inner">
                            <?php echo $content; ?>
                        </div>
                        
                        <!-- Tags -->
                        <?php if (!empty($tags)): ?>
                            <div class="tag-wrapper">
                                <h3 class="title-tag">Tagged in</h3>
                                <div class="tag-list">
                                    <?php foreach ($tags as $tag): ?>
                                        <a href="<?php echo get_tag_link($tag->term_id); ?>" rel="tag">
                                            <?php echo esc_html($tag->name); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Share Buttons -->
                        <div class="share-wrapper">
                            <h3 class="title-share">Share</h3>
                            <ul class="icon-share">
                                <li class="share-facebook">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                                       target="_blank" 
                                       aria-label="Share on Facebook">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </a>
                                </li>
                                <li class="share-twitter">
                                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                                       target="_blank" 
                                       aria-label="Share on Twitter">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                        </svg>
                                    </a>
                                </li>
                                <li class="share-linkedin">
                                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>" 
                                       target="_blank" 
                                       aria-label="Share on LinkedIn">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                        </svg>
                                    </a>
                                </li>
                                <li class="share-line">
                                    <a href="https://social-plugins.line.me/lineit/share?url=<?php echo urlencode(get_permalink()); ?>" 
                                       target="_blank" 
                                       aria-label="Share on LINE">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/>
                                        </svg>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Posts -->
    <?php
    $related_posts = get_posts([
        'category__in' => wp_get_post_categories($post->ID),
        'numberposts' => 3,
        'post__not_in' => [$post->ID],
        'orderby' => 'rand',
    ]);
    
    if (!empty($related_posts)):
    ?>
        <div class="relate-post-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="relate-post-title">You might also like</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <ul class="article-list">
                            <?php foreach ($related_posts as $related): ?>
                                <?php
                                $related_cats = get_the_category($related->ID);
                                ?>
                                <li>
                                    <a href="<?php echo get_permalink($related->ID); ?>" aria-label="Post link">
                                        <div class="img-post">
                                            <?php if (has_post_thumbnail($related->ID)): ?>
                                                <?php echo get_the_post_thumbnail($related->ID, 'medium'); ?>
                                            <?php else: ?>
                                                <img src="<?php echo SIAM_BLOG_URL . 'assets/images/default-post.jpg'; ?>" 
                                                     alt="<?php echo get_the_title($related->ID); ?>">
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                    <div class="block-details">
                                        <div class="author-date-wrapper">
                                            <a href="<?php echo get_author_posts_url(get_post_field('post_author', $related->ID)); ?>" 
                                               class="author">
                                                <?php echo get_the_author_meta('display_name', get_post_field('post_author', $related->ID)); ?>
                                            </a>
                                            <p class="date"><?php echo get_the_date('F j, Y', $related->ID); ?></p>
                                        </div>
                                        <h2 class="title-post">
                                            <a href="<?php echo get_permalink($related->ID); ?>" 
                                               class="permalink" 
                                               aria-label="Post link">
                                                <?php echo wp_trim_words(get_the_title($related->ID), 15, '...'); ?>
                                            </a>
                                        </h2>
                                        <p class="excerpt">
                                            <?php echo wp_trim_words(get_the_excerpt($related->ID), 25, '...'); ?>
                                        </p>
                                        <?php if (!empty($related_cats)): ?>
                                            <ul class="cate-wrapper">
                                                <?php foreach ($related_cats as $cat): ?>
                                                    <?php
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
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php
    
    return ob_get_clean();
}
