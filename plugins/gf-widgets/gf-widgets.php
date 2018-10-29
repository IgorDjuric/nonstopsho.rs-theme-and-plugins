<?php
/**
 * Plugin Name
 *
 * @package     PluginPackage
 * @author      Green Friends
 * @copyright   2016 Your Name or Company Name
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: GF widgets
 * Plugin URI:  https://example.com/plugin-name
 * Description: GF custom widgets
 * Version:     1.0.0
 * Author:      Green Friends
 * Author URI:  https://example.com
 * Text Domain: gf-widgets
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

load_plugin_textdomain( 'gf-widgets', '', plugins_url().'/gf-widgets/languages' );


require_once(plugin_dir_path(__FILE__) . '/includes/gf-scripts.php');
require_once(plugin_dir_path(__FILE__) . '/includes/gf-newsletter-widget-class.php');
require_once(plugin_dir_path(__FILE__) . '/includes/gf-social-icons-widget-class.php');
require_once(plugin_dir_path(__FILE__) . '/includes/gf-image-slider-class.php');
require_once(plugin_dir_path(__FILE__) . '/includes/gf-logo-class.php');
require_once(plugin_dir_path(__FILE__) . '/includes/gf-contact-phone-class.php');
require_once(plugin_dir_path(__FILE__) . '/includes/gf-product-slider-class.php');
require_once(plugin_dir_path(__FILE__) . '/includes/gf-product-slider-without-tabs-class.php');
require_once(plugin_dir_path(__FILE__) . '/includes/gf-product-box-class.php');
require_once(plugin_dir_path(__FILE__) . '/includes/gf-image-banners-class.php');
require_once(plugin_dir_path(__FILE__) . '/includes/GF_Cache.php');


function gf_register_widgets(){
    register_widget('gf_newsletter_widget');
    register_widget('gf_social_icons_widget');
    register_widget('gf_image_slider_widget');
    register_widget('gf_custom_logo_widget');
    register_widget('gf_contact_phone_widget');
    register_widget('gf_product_slider_widget');
    register_widget('gf_product_slider_without_tabs_widget');
    register_widget('gf_product_box_widget');
    register_widget('gf_image_banners_widget');


}
add_action('widgets_init', 'gf_register_widgets');
