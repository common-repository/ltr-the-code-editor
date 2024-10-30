<?php
/**
 * LTR-The-Code-Editor
 *
 *
 *
 * @package LTR_The_Editor
 * @author Automattic
 * @author Yoav Farhi
 * @version 0.2
 *
 * @wordpress
 * Plugin Name: Force LTR for the Code Editor
 * Plugin URI: http://wordpress.org/extend/plugins/ltr-the-editor/
 * Description: Temporarily fix a regression in WordPress 3.8, and set the theme/plugin editor to LTR. Safe to delete if you've updated to WP 3.8.1 or newer.
 * Author: <a href="http://blog.yoavfarhi.com">Yoav Farhi</a>, <a href="http://automattic.com">Automattic</a>
 * Version: 0.2
 */

/**
 * Plugin class for RTL Tester plugin
 *
 * @package RTL_Tester
 */
class LTREditor {

	/**
	 * Hook
	 */
	function __construct() {
		add_action( 'plugins_loaded', array( $this, 'maybe_deactivate') );
		add_action( 'admin_print_styles', array( $this, 'set_direction' ) );
	}

	/**
	 * Adds required CSS
	 *
	 */
	function set_direction() {
		$_screen = get_current_screen();
		if ( ! in_array( $_screen->base , array( 'plugin-editor', 'theme-editor') ) ) {
			return;
		}

		if ( ! is_rtl() ) {
			return;
		}

		?>
		<style>
			.rtl #template textarea, .rtl #docs-list { direction:ltr; }
		</style>
		<?php
	}

	function maybe_deactivate() {
		if ( version_compare( get_bloginfo( 'version' ), '3.8.1', '>=' ) ) {
			if ( current_user_can( 'activate_plugins' ) ) {
 			 	add_action( 'admin_init', array( $this, 'deactivate' ) );
			  	add_action( 'admin_notices', array( $this, 'admin_notice' ) );
	 		}
		}
	}

	function deactivate() {
		  deactivate_plugins( plugin_basename( __FILE__ ) );
	  }

	function admin_notice() {
		echo '<div class="updated"><p><strong>LTR The Code Editor</strong> is no longer necessary with WordPress 3.8.1. The plug-in has been <strong>deactivated</strong>.</p></div>';
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}
	  }


}

new LTREditor;