<?php
/**
 * @package   WP_Brand_Identity_Lite
 * @author    Circlewaves Team <support@circlewaves.com>
 * @license   GPL-2.0+
 * @link      http://circlewaves.com
 * @copyright 2014 Circlewaves Team <support@circlewaves.com>
 *
 * @wordpress-plugin
 * Plugin Name:       WP Brand Identity Lite
 * Plugin URI:        http://circlewaves.com/products/plugins/backend-brand-identity
 * Description:       Customize WordPress admin, set your own logo, customize login form, hide admin bar, restrict access to wp-admin for subscribers. Lite version.
 * Version:           1.0.0
 * Author:            Circlewaves Team
 * Author URI:        http://circlewaves.com
 * Text Domain:       backend-brand-identity
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-wp-brand-identity-lite.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'WP_Brand_Identity_Lite', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'WP_Brand_Identity_Lite', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'WP_Brand_Identity_Lite', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-wp-brand-identity-lite-admin.php' );
	add_action( 'plugins_loaded', array( 'WP_Brand_Identity_Lite_Admin', 'get_instance' ) );

}
