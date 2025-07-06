<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://jawisoftbcd.com
 * @since      1.0.0
 *
 * @package    MFA_SEC_WP
 * @subpackage MFA_SEC_WP/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks for the login screen.
 *
 * @package    MFA_SEC_WP
 * @subpackage MFA_SEC_WP/public
 * @author     JaWiSoft BCD (Pty) Ltd <info@jawisoftbcd.com>
 */
class MFA_SEC_WP_Public {

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
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Enqueue the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        // The 'login_enqueue_scripts' hook ensures this only runs on the login page,
        // so no conditional check is necessary.
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mfa-sec-wp-public.css', array(), $this->version, 'all' );
    }

    /**
     * Intercept the authentication process.
     *
     * @since    1.0.0
     * @param    WP_User|WP_Error|null $user     WP_User object if authentication is successful, WP_Error or null otherwise.
     * @param    string                $username The username.
     * @param    string                $password The password.
     * @return   WP_User|WP_Error               The user object or an error.
     */
    public function intercept_authentication( $user, $username, $password ) {
        // Pass through errors or already authenticated users
        if ( is_wp_error( $user ) || ! $password ) {
            return $user;
        }
        
        // If MFA code is submitted, verify it. We need to re-authenticate the user with the password
        // to ensure the user object is correctly populated before verification.
        if ( isset( $_POST['mfa_sec_wp_code'] ) ) {
            $user = wp_authenticate_username_password( null, $username, $password );
            return $this->verify_mfa_code( $user );
        }

        // Generate and send MFA code
        $this->generate_and_send_mfa_code( $user );

        // Show the MFA form
        // We need to pass the username and password to the display form
        $reauth_user = $user;
        require_once 'partials/mfa-sec-wp-public-display.php';
        
        // We exit here to prevent the default login flow
        exit;
    }

    /**
     * Generate, store, and send the MFA code.
     *
     * @since    1.0.0
     * @param    WP_User $user The user object.
     */
    private function generate_and_send_mfa_code( $user ) {
        // Use cryptographically secure random number generation
        try {
            $code = sprintf( '%06d', random_int( 0, 999999 ) );
        } catch ( Exception $e ) {
            // Fallback for environments where random_int is not available
            $code = sprintf( '%06d', mt_rand( 0, 999999 ) );
        }
        
        $expiration = time() + ( 15 * 60 ); // Code is valid for 15 minutes

        // Hash the code before storing it for better security
        $hashed_code = wp_hash_password($code);

        update_user_meta( $user->ID, 'mfa_sec_wp_code_hashed', $hashed_code );
        update_user_meta( $user->ID, 'mfa_sec_wp_code_expiration', $expiration );

        $to = get_option( 'mfa_sec_wp_email' );
        $subject = 'Your Login Verification Code for ' . get_bloginfo('name');
        $body = "Your verification code is: " . $code;
        $headers = array('Content-Type: text/plain; charset=UTF-8');

        wp_mail( $to, $subject, $body, $headers );
    }

    /**
     * Verify the submitted MFA code.
     *
     * @since    1.0.0
     * @param    WP_User|WP_Error $user The user object or a WP_Error.
     * @return   WP_User|WP_Error The user object if the code is valid, otherwise a WP_Error.
     */
    private function verify_mfa_code( $user ) {
        // Check if user authentication failed
        if ( is_wp_error( $user ) ) {
            return $user;
        }

        $submitted_code = isset($_POST['mfa_sec_wp_code']) ? sanitize_text_field( $_POST['mfa_sec_wp_code'] ) : '';
        $stored_hashed_code = get_user_meta( $user->ID, 'mfa_sec_wp_code_hashed', true );
        $expiration = get_user_meta( $user->ID, 'mfa_sec_wp_code_expiration', true );

        // Clean up user meta immediately
        delete_user_meta( $user->ID, 'mfa_sec_wp_code_hashed' );
        delete_user_meta( $user->ID, 'mfa_sec_wp_code_expiration' );

        if ( empty($stored_hashed_code) || time() > $expiration ) {
            return new WP_Error( 'mfa_code_expired', '<strong>ERROR</strong>: The verification code has expired or is invalid. Please try logging in again.' );
        }

        // Use wp_check_password to securely compare the submitted code with the stored hash
        if ( ! wp_check_password( $submitted_code, $stored_hashed_code ) ) {
            return new WP_Error( 'mfa_invalid_code', '<strong>ERROR</strong>: The verification code is incorrect.' );
        }

        // Code is valid, allow login
        return $user;
    }
}
