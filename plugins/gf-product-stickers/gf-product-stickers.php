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
 * Plugin Name: GF Product Stickers
 * Plugin URI:  https://example.com/plugin-name
 * Description: Custom product stickers
 * Version:     1.0.0
 * Author:      Green Friends
 * Author URI:  https://example.com
 * Text Domain: gf-product-stickers
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */


load_plugin_textdomain('gf-product-stickers', '', plugins_url() . '/gf-product-stickers/languages');

function gf_product_stickers_admin_scripts()
{
    if (is_admin()) {
        wp_enqueue_style('gf-product-stickers-admin-css', plugins_url() . '/gf-product-stickers/css/admin.css');
        wp_register_script('product-stickers-admin-js', plugins_url() . '/gf-product-stickers/js/product-stickers-admin.js', array('jquery'), '', true);
        wp_enqueue_script('product-stickers-admin-js');

    }
}
//add_action('admin_enqueue_scripts', 'gf_product_stickers_admin_scripts');

function gf_public_css()
{
    wp_enqueue_style('gf-product-stickers-public-css', plugins_url() . '/gf-product-stickers/css/public.css');
}
add_action('wp_enqueue_scripts', 'gf_public_css');

function gf_product_stickers_options_create_menu()
{
    //create new top-level menu
    add_menu_page('Product stickers', 'Product stickers', 'administrator', 'product_stickers_options', 'gf_product_stickers_options_page', null, 99);

    //call register settings function
    add_action('admin_init', 'register_gf_product_stickers_options');
}

add_action('admin_menu', 'gf_product_stickers_options_create_menu');

function register_gf_product_stickers_options()
{
    register_setting('gf-product-stickers-settings-group', 'enable_stickers_select_new');
    register_setting('gf-product-stickers-settings-group', 'image_select_new');
    register_setting('gf-product-stickers-settings-group', 'image_position_new');
    register_setting('gf-product-stickers-settings-group', 'new_product_time');

    register_setting('gf-product-stickers-settings-group', 'enable_stickers_select_soldout');
    register_setting('gf-product-stickers-settings-group', 'image_select_soldout');
    register_setting('gf-product-stickers-settings-group', 'image_position_soldout');

    register_setting('gf-product-stickers-settings-group', 'enable_stickers_select_sale');
    register_setting('gf-product-stickers-settings-group', 'image_select_sale');
    register_setting('gf-product-stickers-settings-group', 'image_position_sale');
}

function gf_product_stickers_options_page()
{

    ?>
    <div class="wrap">
        <h2><?= __('Opcije podešavanja nalepnica proizvoda') ?></h2>
        <br/>

        <?php settings_errors(); ?>

        <form method="post" action="options.php" id="theme-options-form">
            <?php settings_fields('gf-product-stickers-settings-group'); ?>
            <?php do_settings_sections('gf-product-stickers-settings-group'); ?>

            <div class="admin-module">
                <div class="row gf-stickers-wrapper">
                    <h3>Novi proizvodi</h3>
                    <div class="row">
                        <div class="row">
                            <label for="new_product_time">Vreme trajanja novog proizvoda</label>
                            <input type="number" name="new_product_time">
                        </div>
                        <label for="enable_stickers_select_new">Ukljuci nalepnice:</label>
                        <select name="enable_stickers_select_new">
                            <option value="0" <?php if (get_option('enable_stickers_select_new') == 0) {
                                echo 'selected';
                            } ?>>Ne
                            </option>
                            <option value="1" <?php if (get_option('enable_stickers_select_new') == 1) {
                                echo 'selected';
                            } ?>>Da
                            </option>
                        </select>
                    </div>
                    <div class="row">
                        <label for="image_position_new">Pozicija nalepnice</label>
                        <select name="image_position_new">
                            <option value="left" <?php if (get_option('image_position_new') == 'left') {
                                echo 'selected';
                            } ?>>Levo
                            </option>
                            <option value="right" <?php if (get_option('image_position_new') == 'right') {
                                echo 'selected';
                            } ?>>Desno
                            </option>
                            <option value="center" <?php if (get_option('image_position_new') == 'center') {
                                echo 'selected';
                            } ?>>Sredina
                            </option>
                        </select>
                    </div>
                    <div class="row">
                        <?php $get_option_new = get_option('image_select_new')?>
                        <?php list($width_new, $height_new) = getimagesize(get_option('image_select_new')); ?>
                        <div><img src="<?=$get_option_new?>" alt="" width="<?=$width_new?>" height="<?=$height_new?>"></div>
                        <input class="gf-upload-sticker-image-new"
                               id="upload-sticker-image-new"
                               name="image_select_new_button"
                               type="button"
                               value="Izaberite sliku">
                        <input type="hidden" class="image_select_new" name="image_select_new"
                               value="<?= $get_option_new ?>">
                    </div>
                </div>
                <div class="row gf-stickers-wrapper">
                    <div class="row">
                        <h3>Rasprodati proizvodi</h3>
                        <div class="row">
                            <label for="enable_stickers_select_soldout">Ukljuci nalepnice:</label>
                            <select name="enable_stickers_select_soldout">
                                <option value="0" <?php if (get_option('enable_stickers_select_soldout') == 0) {
                                    echo 'selected';
                                } ?>>Ne
                                </option>
                                <option value="1" <?php if (get_option('enable_stickers_select_soldout') == 1) {
                                    echo 'selected';
                                } ?>>Da
                                </option>
                            </select>
                        </div>
                        <div class="row">
                            <label for="image_position_soldout">Pozicija nalepnice</label>
                            <select name="image_position_soldout">
                                <option value="left" <?php if (get_option('image_position_soldout') == 'left') {
                                    echo 'selected';
                                } ?>>Levo
                                </option>
                                <option value="right" <?php if (get_option('image_position_soldout') == 'right') {
                                    echo 'selected';
                                } ?>>Desno
                                </option>
                                <option value="center" <?php if (get_option('image_position_soldout') == 'center') {
                                    echo 'selected';
                                } ?>>Sredina
                                </option>
                            </select>
                        </div>
                        <div class="row">
                            <?php $get_option_soldout = get_option('image_select_soldout')?>
                            <?php list($width_soldout, $height_soldout) = getimagesize($get_option_soldout); ?>
                            <div><img src="<?= $get_option_soldout ?>" alt="" width="<?=$width_soldout?>" height="<?=$height_soldout?>"></div>
                            <input class="gf-upload-sticker-image-soldout"
                                   id="upload-sticker-image-soldout"
                                   name="image_select_soldout_button"
                                   type="button"
                                   value="Izaberite sliku">
                            <input type="hidden" class="image_select_soldout" name="image_select_soldout"
                                   value="<?=$get_option_soldout?>">
                        </div>
                    </div>
                </div>
                <div class="row gf-stickers-wrapper">
                    <div class="row">
                        <h3>Proizvodi na akciji</h3>
                        <div class="row">
                            <label for="enable_stickers_select_sale">Ukljuci nalepnice:</label>
                            <select name="enable_stickers_select_sale">
                                <option value="0" <?php if (get_option('enable_stickers_select_sale') == 0) {
                                    echo 'selected';
                                } ?>>Ne
                                </option>
                                <option value="1" <?php if (get_option('enable_stickers_select_sale') == 1) {
                                    echo 'selected';
                                } ?>>Da
                                </option>
                            </select>
                        </div>
                        <div class="row">
                            <label for="image_position_sale">Pozicija nalepnice</label>
                            <select name="image_position_sale">
                                <option value="left" <?php if (get_option('image_position_sale') == 'left') {
                                    echo 'selected';
                                } ?>>Levo
                                </option>
                                <option value="right" <?php if (get_option('image_position_sale') == 'right') {
                                    echo 'selected';
                                } ?>>Desno
                                </option>
                                <option value="center" <?php if (get_option('image_position_sale') == 'center') {
                                    echo 'selected';
                                } ?>>Sredina
                                </option>
                            </select>
                        </div>
                        <div class="row">
                            <?php $get_option_sale = get_option('image_select_sale')?>
                            <?php list($width_sale, $height_sale) = getimagesize($get_option_sale); ?>
                            <div><img src="<?=$get_option_sale?>" alt="" width="<?=$width_sale?>" height="<?=$height_sale?>"></div>
                            <input class="gf-upload-sticker-image-sale"
                                   id="upload-sticker-image-sale"
                                   name="image_select_sale_button"
                                   type="button"
                                   value="Izaberite sliku">
                            <input type="hidden" class="image_select_sale" name="image_select_sale"
                                   value="<?=$get_option_sale?>">
                        </div>
                    </div>
                </div>
            </div><!--admin-module-->

            <?php submit_button('', 'primary', 'sticker_submit'); ?>
        </form>
    </div><!--WRAP-->
    <?php
}

$stickerConfig = [
    'image_position_new_option' => get_option('image_position_new'),
    'image_select_new' => get_option('image_select_new'),
    'image_position_soldout_option' => get_option('image_position_soldout'),
    'image_select_soldout' => get_option('image_select_soldout'),
    'image_position_sale_option' => get_option('image_position_sale'),
    'image_select_sale' => get_option('image_select_sale')
];

add_action('woocommerce_before_shop_loop_item_title', 'add_stickers_to_products_new', 10);
add_action('woocommerce_before_single_product_summary', 'add_stickers_to_products_new', 10);
function add_stickers_to_products_new($product = null)
{
    global $product, $stickerConfig;

    if (!is_object($product)) $product = wc_get_product(get_the_ID());

    if ($stickerConfig['image_position_new_option'] === 'left') {
        $class = 'gf-sticker--left';
    } elseif ($stickerConfig['image_position_new_option'] === 'center') {
        $class = 'gf-sticker--center';
    } else {
        $class = 'gf-sticker--right';
    }

    $postdatestamp = strtotime(get_the_time('Y-m-d'));
    $newness = 15;
    if ((time() - (60 * 60 * 24 * $newness)) < $postdatestamp && !$product->is_on_sale() && !gf_is_product_sold_out($product)) {
        //// If the product was published within the newness time frame display the new badge /////
        echo '<span class="gf-sticker gf-sticker--new ' . $class . '"><img src="' . $stickerConfig['image_select_new'] . '" alt="New Product Sticker" width="54" height="54"></span>';
    }
}

add_action('woocommerce_before_shop_loop_item_title', 'add_stickers_to_products_soldout', 10);
add_action('woocommerce_before_single_product_summary', 'add_stickers_to_products_soldout', 10);
function add_stickers_to_products_soldout($classes)
{
    global $stickerConfig;

    if ($stickerConfig['image_position_soldout_option'] === 'right') {
        $class = 'gf-sticker--right';
    } elseif ($stickerConfig['image_position_soldout_option'] === 'left') {
        $class = 'gf-sticker--left';
    } else {
        $class = 'gf-sticker--center';
    }
    if (!$classes || strstr($classes, 'span')) {
        ob_start();
        wc_product_class();
        $classes = ob_get_clean();
    }

    if (strstr($classes, 'outofstock')) {
        echo '<span class="gf-sticker gf-sticker--soldout ' . $class . '"><img src="' . $stickerConfig['image_select_soldout'] . '" alt="" width="200" height="47"></span>';
    }
}

add_filter('woocommerce_sale_flash', 'add_stickers_to_products_on_sale', 10, 3);
function add_stickers_to_products_on_sale($classes = null)
{
    global $stickerConfig;
    if ($stickerConfig['image_position_sale_option'] === 'right') {
        $class = 'gf-sticker--right';
    } elseif ($stickerConfig['image_position_sale_option'] === 'center') {
        $class = 'gf-sticker--center';
    } else {
        $class = 'gf-sticker--left';
    }
    if (!$classes || strstr($classes, 'span')) {
        ob_start();
        wc_product_class();
        $classes = ob_get_clean();
    }

    if (strstr($classes, 'sale') && !strstr($classes, 'outofstock')) {
        return '<span class="gf-sticker gf-sticker--sale ' . $class . '"><img src="' . $stickerConfig['image_select_sale'] . '" alt="" height="64" width="64"></span>';
    }
    return '';
}

function gf_is_product_sold_out($product)
{
    if ($product->get_type() === 'variable') {
        $inStock = true;
        if ($product->get_stock_status() !== 'instock') {
            $inStock = false;
        }

//        foreach ($product->get_available_variations() as $variation) {
//            if ($variation['is_in_stock']) {
//                $inStock = true;
//                break;
//            }
//        }
        if (!$inStock) {
            return true;
        }
    } else {
        if (!$product->is_in_stock()) {
            return true;
        }
    }

    return false;
}