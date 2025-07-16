<?php
/**
 * Plugin Name: Forge12 Newsletter abonnieren
 * Plugin URI: https://www.forge12.com
 * Description: Create a simple newsletter abo system
 * Version: v1.02
 * Author: Forge12 Interactive GmbH
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( plugin_dir_path( __FILE__ ) . "core/f12-newsletter-utils.php" );
require_once( plugin_dir_path( __FILE__ ) . "core/f12-newsletter-shortcode.php" );
require_once( plugin_dir_path( __FILE__ ) . "core/f12-newsletter-doubleoptin.php" );

if ( is_admin() ) {
	require_once( plugin_dir_path( __FILE__ ) . "admin/f12-newsletter-admin.php" );
}