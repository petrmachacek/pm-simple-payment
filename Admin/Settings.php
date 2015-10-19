<?php

namespace PM\SimplePayment\Admin;

use PM\SimplePayment\Main;

class Settings extends Main {

    private $menu_slug;
    private $notify_url;
    

    function __construct() {
        parent::__construct();

        if (is_admin()) {

            $this->menu_slug = 'pm-simple-payment';
            $this->notify_url = home_url() . '/?akce_fakturace=gopay-notify';
            
            add_action('tf_create_options', array($this, 'create_options'));
        }
    }

    private function get_post_type_values() {
        $types = get_post_types(false, 'objects');
        $out = array();
        foreach ($types as $key => $item) {
            $out[$key] = $item->labels->name;
        }
        return $out;
    }

    function create_options() {


        // Initialize Titan & options here
        $titan = $this->get_titan_instance();



        $panel = $titan->createAdminPanel(array(
            'name' => $this->get_text('Simple Payments'),
            'id' => $this->menu_slug,
            'position' => 30
        ));
        $layoutPanel = $panel->createAdminPanel(array(
            'name' => 'My Layout Panel',
        ));

        // =====

        $tab_info = $panel->createTab(array(
            'name' => 'Informace',
            'desc' => 'Informace o pluginu'
        ));

        $tab_main = $panel->createTab(array(
            'name' => $this->get_text('Main Settings'),
            'desc' => $this->get_text('Main Plugin Settings')
        ));
        $tab_gopay = $panel->createTab(array(
            'name' => 'GoPay',
            'desc' => 'Nastavení přístupových údajů do platební brány GoPay'
        ));

        // -----

        $tab_info->createOption(array(
            'name' => 'Co Simple Payments umí?',
            'type' => 'heading',
        ));

        $tab_info->createOption(array(
            'type' => 'note',
            'desc' => "<p>Skvělé věci!</p>"
        ));



        $tab_main->createOption(array(
            'name' => 'Show meta box on post types',
            'id' => 'meta_box_post_types',
            'type' => 'multicheck',
            'desc' => $this->get_text('Check, in which post type show Meta Box with Simple Payments Possibilities.'),
            'options' => $this->get_post_type_values(),
            'default' => array('post'),
        ));
        
        $tab_main->createOption(array(
            'name' => 'Enabled by default?',
            'id' => 'meta_box_enabled_default',
            'type' => 'enable',
            'default' => true,
            'desc' => 'Enable or disable by default options in post meta box?',
        ));


        $tab_main->createOption(array(
            'type' => 'save',
            'save' => $this->get_text('Save'),
            'reset' => $this->get_text('Set Default Values')
        ));


        $tab_gopay->createOption(array(
            'name' => 'Přístupové údaje k platební bráně',
            'type' => 'heading',
        ));

        $tab_gopay->createOption(array(
            'name' => 'Gopay GOID',
            'id' => 'gopay-goid',
            'type' => 'text',
            'desc' => 'Identifikátor obchodníka v systému GoPay'
        ));

        $tab_gopay->createOption(array(
            'name' => 'Gopay Secure Key',
            'id' => 'gopay-seckey',
            'type' => 'text',
            'desc' => 'Tajný klíč obchodníka v systému GoPay'
        ));

        $tab_gopay->createOption(array(
            'name' => 'Testovací brána',
            'id' => 'gopay-test',
            'type' => 'checkbox',
            'desc' => 'Pracovat v testovacím (implementačním) režimu',
            'default' => true,
        ));

        $tab_gopay->createOption(array(
            'name' => 'Notifikační URL pro integraci platební brány GoPay',
            'type' => 'heading',
        ));

        $tab_gopay->createOption(array(
            'type' => 'note',
            'desc' => "<a href=\"{$this->notify_url}\" target=\"blank\">{$this->notify_url}</a>"
        ));

        $tab_gopay->createOption(array(
            'type' => 'save',
            'save' => 'Uložit',
            'reset' => 'Vrátit výchozí hodnoty'
        ));


        $tab_faktury = $panel->createTab(array(
            'name' => 'Faktury',
            'desc' => 'Nastavení číselných řad a fakturačních údajů'
        ));


        $tab_faktury->createOption(array(
            'name' => 'Číselné řady dokladů',
            'type' => 'heading',
        ));

        $tab_faktury->createOption(array(
            'name' => 'Vydané faktury',
            'id' => 'fakt-rada-fv',
            'type' => 'text',
            'default' => 'RRRR1CCCCC',
            'desc' => 'Číselná řada pro faktury vydané (R = rok, C = číslo faktury, např. RRRR1CCCCC)'
        ));

        $tab_faktury->createOption(array(
            'name' => 'Údaje o dodavateli',
            'type' => 'heading',
        ));

        $tab_faktury->createOption(array(
            'name' => 'Název',
            'id' => 'fakt-dodavatel-nazev',
            'type' => 'text',
            'desc' => 'Jméno firmy nebo dodavatele'
        ));

        $tab_faktury->createOption(array(
            'name' => 'Ulice a číslo',
            'id' => 'fakt-dodavatel-ulice',
            'type' => 'text',
            'desc' => 'Ulice a č.p.'
        ));

        $tab_faktury->createOption(array(
            'name' => 'Město',
            'id' => 'fakt-dodavatel-mesto',
            'type' => 'text',
            'desc' => 'Město nebo obec'
        ));

        $tab_faktury->createOption(array(
            'name' => 'PSČ',
            'id' => 'fakt-dodavatel-psc',
            'type' => 'text',
            'desc' => 'Poštovní směrovací číslo'
        ));

        $tab_faktury->createOption(array(
            'name' => 'IČ',
            'id' => 'fakt-dodavatel-ic',
            'type' => 'text',
            'desc' => 'Identifikační číslo (IČO)'
        ));

        $tab_faktury->createOption(array(
            'name' => 'DIČ',
            'id' => 'fakt-dodavatel-dic',
            'type' => 'text',
            'desc' => 'Daňové identifikační číslo'
        ));

        $tab_faktury->createOption(array(
            'name' => 'Účet',
            'id' => 'fakt-dodavatel-ucet',
            'type' => 'text',
            'desc' => 'Číslo účtu i s lomítkem a číselným kódem banky'
        ));

        $tab_faktury->createOption(array(
            'name' => 'Jsem plátce DPH',
            'id' => 'fakt-platce-dph',
            'type' => 'checkbox',
            'default' => true,
            'desc' => 'Ano'
        ));

        // -----

        $tab_faktury->createOption(array(
            'name' => 'Další údaje',
            'type' => 'heading',
        ));


        $tab_faktury->createOption(array(
            'name' => 'Splatnost faktury',
            'id' => 'fakt-splatnost',
            'type' => 'number',
            'desc' => 'Počet dnů splatnosti faktury',
            'default' => '14',
            'unit' => 'dnů',
//            'min' => '1',
//            'max' => '30',
        ));

        $tab_faktury->createOption(array(
            'name' => 'Podpis',
            'id' => 'fakt-podpis',
            'type' => 'text',
            'desc' => 'Text (jméno) podpisu na faktuře'
        ));


        $tab_faktury->createOption(array(
            'name' => 'Razítko a podpis',
            'id' => 'fakt-podpis-img',
            'type' => 'upload',
            'desc' => 'Obrázek s podpisem, případně i s razítkem'
        ));

        $tab_faktury->createOption(array(
            'type' => 'save',
            'save' => 'Uložit',
            'reset' => 'Vrátit výchozí hodnoty'
        ));
    }

    function members_roles() {
        global $wp_roles;

        if (!empty($wp_roles->role_names))
            return $wp_roles->role_names;

        return false;
    }

}

new Settings();
