<?php

namespace PM\SimplePayment\Admin;

use PM\SimplePayment\Main;

class Settings extends Main {

    private $menu_slug;
    private $settings;
    private $settings_group;

    function __construct() {
        parent::__construct();

        if (is_admin()) {

            $this->menu_slug = 'pm-simple-payment';
            $this->settings_group = 'pm-payment-settings';
            
            $this->settings = $this->get_default_settings();
            

            add_action('admin_menu', array($this, 'register_custom_menu'));
            add_action('admin_init', array($this, 'register_settings'));
        }
    }
    
    public function get_settings_group() {
        return $this->settings_group;
    }

    private function get_default_settings() {
        $settings = array(
            array(
                'title' => $this->get_text('Enable to post types'),
                'name' => $this->get_settings_group().'_post-types',
                'type' => 'checkbox_group',
                'values' =>  $this->get_post_type_values()
            )
        );

        return $settings;
    }
    
    private function get_settings() {
        return $this->get_default_settings();
    }
    
    private function get_post_type_values() {
        $types = get_post_types(false, 'objects');
        $out = array();
        foreach ($types as $key => $item) {
            $out[$key] = $item->labels->name;
        }
        return $out;
    }

    function register_custom_menu() {
        add_menu_page($this->get_text('Simple Payments'), $this->get_text('Simple Payments'), 'manage_options', $this->menu_slug, array($this, 'page_dashboard_callback'), false, 30);
        add_submenu_page($this->menu_slug, $this->get_text('Simple Payments Settings'), $this->get_text('Settings'), 'manage_options', 'pm_sp_settings', array($this, 'page_settings_callback'), false, 30);
    }

    function page_dashboard_callback() {
        $vars = array();
        echo $this->load_template('Admin/dashboard.php', $vars);
    }

    function page_settings_callback() {
        $vars = array();
        $vars['image_loading'] = $this->get_loading_image();
        $vars['settings'] = $this->get_settings();
        echo $this->load_template('Admin/settings.php', $vars);
    }

    function register_settings() {
        foreach ($this->settings as $key => $item) {
            register_setting($this->get_settings_group(), $item['name']);
        }
    }

}

new Settings();
