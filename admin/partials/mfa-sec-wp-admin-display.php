<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://jawisoftbcd.com
 * @since      1.0.0
 *
 * @package    MFA_SEC_WP
 * @subpackage MFA_SEC_WP/admin/partials
 */
?>

<div class="wrap">

    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

    <form method="post" action="options.php">
        
        <?php
            settings_fields( $this->plugin_name );
            do_settings_sections( $this->plugin_name );
            submit_button();
        ?>

    </form>

</div>
