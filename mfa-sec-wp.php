<?php
/**
 * Plugin Name:       MFA SEC WP
 * Plugin URI:        https://jawisoftbcd.com
 * Description:       A simple and secure Multi-Factor Authentication plugin for WordPress.
 * Version:           1.0.0
 * Author:            JaWiSoft BCD (Pty) Ltd
 * Author URI:        https://jawisoftbcd.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       mfa-sec-wp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * Define constants
 */
define( 'MFA_SEC_WP_VERSION', '1.0.0' );
define( 'MFA_SEC_WP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MFA_SEC_WP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 */
function activate_mfa_sec_wp() {
    require_once MFA_SEC_WP_PLUGIN_DIR . 'includes/class-mfa-sec-wp-activator.php';
    MFA_SEC_WP_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_mfa_sec_wp() {
    require_once MFA_SEC_WP_PLUGIN_DIR . 'includes/class-mfa-sec-wp-deactivator.php';
    MFA_SEC_WP_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mfa_sec_wp' );
register_deactivation_hook( __FILE__, 'deactivate_mfa_sec_wp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require MFA_SEC_WP_PLUGIN_DIR . 'includes/class-mfa-sec-wp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mfa_sec_wp() {

    $plugin = new MFA_SEC_WP();
    $plugin->run();

}
run_mfa_sec_wp();
