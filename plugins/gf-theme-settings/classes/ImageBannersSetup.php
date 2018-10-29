<?php

namespace GfThemeSettings;
class ImageBannersSetup
{
    private $imageBanners;

    public function __construct()
    {
        $this->imageBanners = new ImageBanners();
        $this->init();
    }

    public function init(){
        add_action('admin_menu', array($this, 'optionPageRegister'));
        add_action('widgets_init', array($this, 'registerWidget'));
    }

    public function optionPageRegister()
    {

        add_submenu_page('gf_theme_settings','Image Banners', 'Image banners', 'administrator', 'image_benners_options', array($this, 'optionPage'));

        add_action('admin_init', array($this, 'registerOptions'));
    }

    public function registerOptions()
    {
        register_setting('gf-image-banners-settings-group', 'gf-image-banners-values');

    }
    public function optionPage()
    {
        require(__DIR__ . "/../html/optionPageImageBanners.phtml");
    }

    public function registerWidget()
    {
        register_widget('ImageBannersWidget');
    }

}