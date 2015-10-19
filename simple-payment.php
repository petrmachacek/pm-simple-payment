<?php

/*
  Plugin Name: PM Simple Payment
  Plugin URI:  http://petrmachacek.net
  Description: Simple Payments...
  Version:     1.0
  Author:      Petr Macháček
  Author URI:  http://petrmachacek.net
  Text Domain: PetrMachacek
  Domain Path: /lang
 */

namespace PM\SimplePayment;

include('Admin/Settings.php');

class Main {

    private $plugin_path;
    private $plugin_dir;
    private $plugin_url;
    private $textdomain;

    public function __construct() {
        $this->basic_init();
    }

    private function basic_init() {
        $this->plugin_path = dirname(__FILE__);
        $this->plugin_dir = basename(dirname(__FILE__));
        $this->plugin_url = plugins_url($this->plugin_dir);
        $this->textdomain = 'PetrMachacek';
    }

    public function get_plugin_path($file_name = '') {
        return $this->plugin_path . $file_name;
    }

    public function get_plugin_url($path = '') {
        return $this->plugin_url . $path;
    }

    public function get_textdomain() {
        return $this->textdomain;
    }

    function load_template($file_path, $variables = array()) {

        if (is_object($variables)) {
            $to_extract = get_object_vars($variables);
        } else {
            $to_extract = $variables;
        }

        ob_start();

        extract($to_extract);
        if (file_exists($file_path)) {
            include($file_path);
        } else if (file_exists($this->get_plugin_path('/View/'.$file_path))) {
            include($this->get_plugin_path('/View/'.$file_path));
        } else {
            die('Could not load file ' . $file_path);
        }
        $out = ob_get_contents();
        ob_clean();
        return $out;
    }

    public function get_image($image_name = '') {
        return $this->get_plugin_url('View/images' . $image_name);
    }

    public function get_loading_image() {
        return $this->get_image('loading.gif');
    }
    
    public function get_text($string) {
        return __($string, $this->get_textdomain());
    }
    
    public function echo_text($string) {
        echo $this->get_text($string);
    }

}

new Main();
