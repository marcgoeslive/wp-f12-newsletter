<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Includes
require_once( plugin_dir_path( __FILE__ ) . "/core/f12-newsletter-cpt.php" );
require_once( plugin_dir_path( __FILE__ ) . "/core/f12-newsletter-metabox.php" );
require_once( plugin_dir_path( __FILE__ ) . "/core/f12-newsletter-options.php" );
require_once( plugin_dir_path( __FILE__ ) . "/core/f12-newsletter-overview.php" );

/**
 * Class F12NewsletterAdmin
 */
class F12NewsletterAdmin {
	// Custom Post Types
	private $F12CPT;
	private $F12MetaBox;
	private $F12Options;
	private $F12Overview;

	public function __construct() {
		$this->F12CPT      = new F12NewsletterCPT();
		$this->F12MetaBox  = new F12NewsletterMetaBox();
		$this->F12Options  = new F12NewsletterOptions();
		$this->F12Overview = new F12NewsletterOverview();

		// Actions
		add_action( "admin_menu", array( &$this, "admin_menu" ) );
	}

	public function admin_menu() {
		add_submenu_page( "edit.php?post_type=f12_newsletter", "Einstellungen", "Einstellungen", "manage_options", "f12_newsletter_settings", array(
			&$this->F12Options,
			"render"
		) );
	}
}

new F12NewsletterAdmin();