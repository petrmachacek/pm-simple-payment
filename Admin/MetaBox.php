<?php

namespace PM\SimplePayment\Admin;

use PM\SimplePayment\Main;

class MetaBox extends Main {

    private $titan_instance;

    function __construct() {
        parent::__construct();

        if (is_admin()) {

            $this->titan_instance = $this->get_plugin_dir();

            add_action('tf_create_options', array($this, 'create_metabox'));
            add_action('save_post', array($this, 'save_price_item'), 10, 3);
        }
    }

    function create_metabox() {


        // Initialize Titan & options here
        $titan = $this->get_titan_instance();


        $meta_box = $titan->createMetaBox(array(
            'name' => $this->get_text('Simple Payments'),
            'desc' => $this->get_text('You can set Simple Payments form added to this post here.'),
            'post_type' => $this->get_option('meta_box_post_types'),
            'priority' => 'high',
            'hide_custom_fields' => false
        ));


        $meta_box->createOption(array(
            'name' => $this->get_text('Display Simple Payment Form?'),
            'id' => 'form_enable',
            'type' => 'enable',
            'default' => $this->get_option('meta_box_enabled_default'),
            'desc' => $this->get_text('Enable or disable Simple Payment Form in this post?'),
            'enabled' => 'Show',
            'disabled' => 'Hide'
        ));

        $default_time = new \DateTime();
        $default_time_format = $default_time->format('Y-m-d H:i');

        $meta_box->createOption(array(
            'name' => $this->get_text('Show until'),
            'id' => 'form_show_until',
            'type' => 'date',
            'desc' => $this->get_text('Choose date and time, until show the form'),
            //'default' => $default_time_format,
            'time' => true
        ));



        $meta_box->createOption(array(
            'name' => $this->get_text('Notification email'),
            'id' => 'notification_email',
            'type' => 'text',
            'desc' => $this->get_text('Email of user, who will be inform when order from Simple Payment Form will be created. Comma separated, if you need more then one.')
        ));

        $post_id = (isset($_GET['post'])) ? $_GET['post'] : 0;
        $meta = get_post_meta($post_id, 'price_item', true);
        $vars = array(
            'post_id' => $post_id,
            'price_items' => $meta           
        );
        $meta_box->createOption(array(
            'type' => 'custom',
            'name' => $this->get_text('Form Order Items'),
            'id' => 'price_item',
            'custom' => $this->load_template('Admin/metabox_order_item.php', $vars),
        ));
        
       

    }

    function save_price_item($post_id, $post, $update) {
        if (isset($_POST['price_item'])) {
            update_post_meta($post_id, 'price_item', $_POST['price_item']);
        }
    }

}

new MetaBox();
