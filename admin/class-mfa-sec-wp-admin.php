<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://jawisoftbcd.com
 * @since      1.0.0
 *
 * @package    MFA_SEC_WP
 * @subpackage MFA_SEC_WP/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    MFA_SEC_WP
 * @subpackage MFA_SEC_WP/admin
 * @author     JaWiSoft BCD (Pty) Ltd <info@jawisoftbcd.com>
 */
class MFA_SEC_WP_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }
    
    /**
     * Add the options page to the admin menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu() {
        add_options_page(
            'MFA SEC WP Settings',
            'MFA SEC WP',
            'manage_options',
            $this->plugin_name,
            array($this, 'display_plugin_setup_page')
        );
    }

    /**
     * Display the plugin setup page.
     *
     * @since    1.0.0
     */
    public function display_plugin_setup_page() {
        require_once 'partials/mfa-sec-wp-admin-display.php';
    }

    /**
     * Register the settings for the plugin.
     *
     * @since    1.0.0
     */
    public function register_settings() {
        register_setting(
            $this->plugin_name,
            'mfa_sec_wp_email',
            array($this, 'validate_email_address')
        );

        add_settings_section(
            'mfa_sec_wp_section_main',
            __('Main Settings', 'mfa-sec-wp'),
            null,
            $this->plugin_name
        );

        add_settings_field(
            'mfa_sec_wp_email',
            __('MFA Notification Email', 'mfa-sec-wp'),
            array($this, 'render_email_field'),
            $this->plugin_name,
            'mfa_sec_wp_section_main'
        );
    }

    /**
     * Render the email input field.
     *
     * @since    1.0.0
     */
    public function render_email_field() {
        $email = get_option('mfa_sec_wp_email');
        echo "<input type='email' name='mfa_sec_wp_email' value='" . esc_attr($email) . "' class='regular-text'>";
        echo "<p class='description'>" . __('The email address where the 6-digit MFA code will be sent.', 'mfa-sec-wp') . "</p>";
    }

    /**
     * Validate the email address.
     *
     * @since    1.0.0
     * @param    string    $input    The email address to validate.
     * @return   string             The sanitized email address.
     */
    public function validate_email_address($input) {
        $new_input = sanitize_email($input);
        if (!is_email($new_input)) {
            add_settings_error(
                'mfa_sec_wp_email',
                'mfa_sec_wp_email_error',
                __('Please enter a valid email address.', 'mfa-sec-wp'),
                'error'
            );
        }
        return $new_input;
    }
}
