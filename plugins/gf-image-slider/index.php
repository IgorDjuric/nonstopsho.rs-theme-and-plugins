<?php
/**
* Plugin Name
*
* @package     PluginPackage
* @author      Green Friends
* @copyright   2018 Green Friends
* @license     GPL-2.0+
*
* @wordpress-plugin
* Plugin Name: GF Image Slider
* Plugin URI:  https://example.com/plugin-name
* Description: Image Slider
* Version:     1.0.0
* Author:      Green Friends
* Author URI:  https://example.com
* Text Domain: gf-image-slider
* Domain Path: /languages
* License:     GPL-2.0+
* License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

require (__DIR__ . "/classes/Setup.php");
require (__DIR__ . "/classes/ImageSlider.php");
require (__DIR__ . "/classes/ImageSliderWidget.php");
$setup = new GfImageSlider\Setup(842,184);