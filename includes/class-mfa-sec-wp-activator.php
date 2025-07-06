<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    MFA_SEC_WP
 * @subpackage MFA_SEC_WP/includes
 * @author     JaWiSoft BCD (Pty) Ltd <info@jawisoftbcd.com>
 */
class MFA_SEC_WP_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate() {
        // Add a default option for the MFA email if it doesn't exist.
        if ( ! get_option( 'mfa_sec_wp_email' ) ) {
            add_option( 'mfa_sec_wp_email', get_option( 'admin_email' ) );
        }
    }

}
