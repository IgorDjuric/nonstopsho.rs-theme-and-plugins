<?php

namespace GfImageSlider;
class Setup
{
    /**
     * Setup constructor.
     */
    private $width;
    private $height;
    private $imageSlider;

    /**
     * Setup constructor.
     * @param $width
     * @param $height
     */
    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
        $this->imageSlider = new ImageSlider($width, $height);
        $this->init();
    }


    private function init()
    {
        load_plugin_textdomain('gf-image-slider', '', plugins_url() . '/gf-image-slider/languages');
        add_action(
            'admin_enqueue_scripts',
            array($this, 'enqueueAdminStyleAndScripits')
        );
        add_action('admin_menu', array($this, 'optionPageRegister'));
        add_action('widgets_init', array($this, 'registerWidget'));
        add_action('test',array($this, 'test'));
    }
    public function test() {
        return $imageSize = array($this->width,$this->height);
    }
    public function enqueueAdminStyleAndScripits()
    {
        //Zove media uploader
        wp_enqueue_media();

        wp_enqueue_style('gf-image-slider-admin-css', plugins_url() . '/gf-image-slider/css/admin.css');
        wp_enqueue_script('gf-image-slider-admin-js', plugins_url() . '/gf-image-slider/js/admin.js', array('jquery'), '', true);
    }

    public function enqueueFrontendStyleAndScripits()
    {
        wp_enqueue_style('gf-image-slider-front-end-css', plugins_url() . '/gf-image-slider/css/front.css');
    }

    public function optionPageRegister()
    {

        add_menu_page('Image Sliders', 'Image slider', 'administrator', 'image_slider_options', array($this, 'optionPage'), null, 100);


        add_action('admin_init', array($this, 'registerOptions'));
    }

    public function registerOptions()
    {
        register_setting('gf-image-slider-settings-group', 'gf-image-values');
        register_setting('gf-image-slider-settings-group', 'gf-image-links');

    }

    public function optionPage()
    {
        require(__DIR__ . "/../html/optionPage.phtml");
    }

    public function registerWidget()
    {
        register_widget('ImageSliderWidget');
    }

}