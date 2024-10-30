<?php

/**
 *
 * @link              https://miniup.gl
 * @since             1.0.0
 * @package           Lisa
 *
 * @wordpress-plugin
 * Plugin Name:       Lisa Templates
 * Plugin URI:        https://templates.lisa.gl
 * Description:       Easily write templates filled with custom data that can be loaded through a shortcode.
 * Version:           1.1.1
 * Author:            Pierre Minik Lynge
 * Author URI:        https://miniup.gl
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lisa
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function lisa_deactivate() {
  deactivate_plugins( plugin_basename( __FILE__ ) );
}

function lisa_admin_notice() {
  printf( '<div class="updated"><p>%1$s</p></div>', __( '<strong>Lisa Templates</strong> requires the plugin Timber to be activated; the plug-in has been <strong>deactivated</strong>.', 'lisa' ) );
  if ( isset( $_GET['activate'] ) )
    unset( $_GET['activate'] );
}

function lisa_check_dependencies() {
  if ( ! class_exists( '\Timber\Timber' ) ) {
    add_action( 'admin_init', 'lisa_deactivate' );
    add_action( 'admin_notices', 'lisa_admin_notice' );
  } else {
  	/**
  	 * The core plugin class that is used to define internationalization,
  	 * admin-specific hooks, and public-facing site hooks.
  	 */
  	require plugin_dir_path( __FILE__ ) . 'includes/class-lisa.php';

  	/**
  	 * Begins execution of the plugin.
  	 *
  	 * Since everything within the plugin is registered via hooks,
  	 * then kicking off the plugin from this point in the file does
  	 * not affect the page life cycle.
  	 *
  	 * @since    1.0.0
  	 */
  	function run_lisa() {

  		$plugin = new Lisa();
  		$plugin->run();

  	}
  	run_lisa();
  }
}
add_action( 'plugins_loaded', 'lisa_check_dependencies', 1 );

// register_activation_hook( __FILE__, 'activate_lisa' );
// register_deactivation_hook( __FILE__, 'deactivate_lisa' );
