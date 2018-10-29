<?php
function gf_add_frontend_scripts(){
    // Font awesome
    wp_enqueue_style('font-awesome', 'https://use.fontawesome.com/releases/v5.1.1/css/all.css');
    // Widget specific css

    // jQuerry UI
    wp_enqueue_style('jQuery-ui' ,'//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    wp_enqueue_style('gf-social-icons-css', plugins_url() . '/gf-widgets/css/gf-social-icons-widget.css');
    wp_enqueue_style('slick-css', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css');
    wp_enqueue_style('slick-theme-css', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css');
    wp_enqueue_style('gf-product-slider-css', plugins_url() . '/gf-widgets/css/gf-product-slider-widget.css');
    wp_enqueue_style('gf-product-box-css', plugins_url() . '/gf-widgets/css/gf-product-box-widget.css');
    wp_enqueue_style('gf-image-banner-css', plugins_url() . '/gf-widgets/css/gf-banners-widget.css');

    wp_enqueue_script('jquery-ui-tabs', '', [], false, true);
    wp_enqueue_script('slick-js', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js', array('jquery'), 'true');
//    wp_enqueue_script('gf-product-slider-js', plugins_url() . '/gf-widgets/js/gf-product-slider.js', array('jquery'), '1.0.0', 'true');
}
add_action('wp_enqueue_scripts', 'gf_add_frontend_scripts');

function gf_add_backend_scripts(){
    wp_enqueue_media();
//    wp_enqueue_style('gf-image-slider-admin', plugins_url() . '/gf-widgets/css/gf-image-slider.css');
//    wp_enqueue_style('gf-product-slider-admin', plugins_url() . '/gf-widgets/css/gf-product-slider-admin.css');
//    wp_enqueue_style('gf-social-icons-admin-css', plugins_url() . '/gf-widgets/css/gf-social-icons-admin.css');
//    wp_enqueue_style('gf-image-banner-css', plugins_url() . '/gf-widgets/css/gf-image-banner-admin.css');
//    wp_enqueue_script('gf-main-js', plugins_url() . '/gf-widgets/js/gf-widget-main.js', array('jquery'), '1.0.0', 'true');
}
add_action('admin_enqueue_scripts', 'gf_add_backend_scripts');
