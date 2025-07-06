<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://jawisoftbcd.com
 * @since      1.0.0
 *
 * @package    MFA_SEC_WP
 * @subpackage MFA_SEC_WP/public/partials
 */

// We need to load the login header and footer to make the page look like the standard WP login page
require_once ABSPATH . 'wp-login.php';

login_header();
?>

<div id="login">
    <div id="login_error">
        <?php
        // Display any login errors
        if (isset($GLOBALS['wp_login_errors'])) {
            echo $GLOBALS['wp_login_errors']->get_error_message();
        }
        ?>
    </div>
    <form name="loginform" id="loginform" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">
        <p>
            <label for="mfa_sec_wp_code"><?php _e( 'Verification Code', 'mfa-sec-wp' ); ?></label>
            <input type="text" name="mfa_sec_wp_code" id="mfa_sec_wp_code" class="input" value="" size="20" pattern="\d*" maxlength="6" autofocus />
        </p>
        <p class="description"><?php _e( 'Please check your email for the 6-digit verification code.', 'mfa-sec-wp' ); ?></p>
        
        <?php
        // We need to pass the username and password through so WordPress knows who is logging in
        if (isset($_POST['log'])) {
            echo '<input type="hidden" name="log" value="' . esc_attr($_POST['log']) . '" />';
        }
        if (isset($_POST['pwd'])) {
            // Note: We are not re-displaying the password, just passing it along in the form submission.
            // This is a standard part of how WordPress multi-step login flows work.
            echo '<input type="hidden" name="pwd" value="' . esc_attr($_POST['pwd']) . '" />';
        }
        if (isset($_POST['rememberme'])) {
            echo '<input type="hidden" name="rememberme" value="' . esc_attr($_POST['rememberme']) . '" />';
        }
        if (isset($_POST['redirect_to'])) {
            echo '<input type="hidden" name="redirect_to" value="' . esc_attr($_POST['redirect_to']) . '" />';
        }
        ?>

        <p class="submit">
            <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Verify', 'mfa-sec-wp' ); ?>" />
        </p>
    </form>
</div>

<?php login_footer(); ?>
