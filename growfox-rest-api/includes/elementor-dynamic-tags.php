<?php
if (!defined('ABSPATH')) exit;

add_action('elementor/dynamic_tags/register', function($dynamic_tags) {
    $dynamic_tags->register_group('growfox', ['title' => 'Growfox']);
    
    require_once __DIR__ . '/dynamic-tag-exchange-rate.php';
    $dynamic_tags->register(new Growfox_Exchange_Rate_Tag());
    
    require_once __DIR__ . '/dynamic-tag-period.php';
    $dynamic_tags->register(new Growfox_Period_Tag());
});
