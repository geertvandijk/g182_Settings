<?php
include_once(ABSPATH . 'wp-config.php');
include_once(ABSPATH . 'wp-includes/wp-db.php');
include_once(ABSPATH . 'wp-includes/pluggable.php');

/*
Plugin Name: Extra instellingen voor 7x7
Description: Instellingenpagina voor 7x7.
Author: Geert van Dijk
Version: 1.0.0
*/

// Main plugin file, core logic and loading classes/files only


class g182_Settings
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Extra instellingen', 
            'Extra instellingen voor 7x7', 
            'manage_options', 
            '7x7-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( '7x7_settings' );
        ?>
        <div class="wrap">
            <h2>Extra instellingen voor 7x7</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( '7x7_option_group' );   
                do_settings_sections( '7x7-setting-admin' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            '7x7_option_group', // Option group
            '7x7_settings', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Instellingen voor de 7x7 Waardenscan-site', // Title
            array( $this, 'print_section_info' ), // Callback
            '7x7-setting-admin' // Page
        );  

        add_settings_field(
            '7x7_miniscan_url', 
            'URL voor miniscan-logo (inclusief http://, of # voor geen verwijzing)', 
            array( $this, 'miniscan_callback' ), 
            '7x7-setting-admin', 
            'setting_section_id'
        );      

        add_settings_field(
            '7x7_miniscan_text', 
            'Tekst bij miniscan-logo', 
            array( $this, 'miniscan_text_callback' ), 
            '7x7-setting-admin', 
            'setting_section_id'
        );      

        add_settings_field(
            '7x7_bloginmenu', 
            'Blog weergeven in menu', 
            array( $this, 'blogmenu_callback' ), 
            '7x7-setting-admin', 
            'setting_section_id'
        );      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        return $input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print '';
    }

    public function miniscan_callback()
    {
        printf(
            '<input type="text" id="7x7_miniscan_url" name="7x7_settings[7x7_miniscan_url]" value="%s" />',
            isset( $this->options['7x7_miniscan_url'] ) ? esc_attr( $this->options['7x7_miniscan_url']) : ''
        );
    }

    public function miniscan_text_callback()
    {
        printf(
            '<input type="text" id="7x7_miniscan_text" name="7x7_settings[7x7_miniscan_text]" value="%s" />',
            isset( $this->options['7x7_miniscan_text'] ) ? esc_attr( $this->options['7x7_miniscan_text']) : ''
        );
    }

    public function blogmenu_callback()
    {
    	$options = get_option('7x7_settings');

	    $html = '<input type="checkbox" id="7x7_bloginmenu" name="7x7_settings[7x7_bloginmenu]" value="1"' . checked( 1, $options['7x7_bloginmenu'], false ) . '/>';
	    

	    echo $html;
    }
}

if( is_admin() ) {
$my_settings_page = new g182_Settings();
}
?>