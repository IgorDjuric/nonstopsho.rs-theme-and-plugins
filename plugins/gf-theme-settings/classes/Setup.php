<?php

namespace GfThemeSettings;
class Setup
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        load_plugin_textdomain('gf-theme-settings', '', plugins_url() . '/gf-theme-settings/languages');

        //admin scripts & styles
        add_action('admin_enqueue_scripts', array($this, 'enqueueAdminStyleAndScripits'));

        //frontend scripts & styles
        add_action('wp_enqueue_scripts', array($this, 'enqueueFrontendStyleAndScripits'));


        add_action('admin_menu', array($this, 'gfThemeSettingsCreateMenu'));
    }

    public function gfThemeSettingsCreateMenu()
    {
        //create new top-level menu
        add_menu_page('GreenFriends Theme Settings', 'GreenFriends Theme Settings', 'administrator', 'gf_theme_settings', array($this, 'gfThemeSettingsOptionsPage'), null, 200);
        add_submenu_page('gf_theme_settings', 'GreenFriends Theme Settings', 'General', 'administrator', 'gf_theme_settings', array($this, 'gfThemeSettingsOptionsPage'));
    }

    public function gfThemeSettingsOptionsPage()
    {
        require(__DIR__ . "/../html/optionPageGeneral.phtml");

    }


    //admin scripts & styles
    public function enqueueAdminStyleAndScripits()
    {
        //Zove media uploader
        wp_enqueue_media();

        wp_enqueue_style('gf-theme-settings-admin-css', plugins_url() . '/gf-theme-settings/css/admin.css');
        wp_enqueue_script('gf-theme-settings-admin-js', plugins_url() . '/gf-theme-settings/js/admin.js', array('jquery'), '', true);
    }

    //frontend scripts & styles
    public function enqueueFrontendStyleAndScripits()
    {
        wp_enqueue_style('gf-theme-settings-front-end-css', plugins_url() . '/gf-image-slider/css/front.css');
    }
}