<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    MFA_SEC_WP
 * @subpackage MFA_SEC_WP/includes
 * @author     JaWiSoft BCD (Pty) Ltd <info@jawisoftbcd.com>
 */
class MFA_SEC_WP_Deactivator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function deactivate() {
        // Optional: You could remove the option on deactivation if desired.
        // delete_option('mfa_sec_wp_email');
    }

}
