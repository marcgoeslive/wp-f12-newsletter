<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class F12NewsletterMetaBox {
	/**
	 * Constructor
	 */
	public function __construct() {
		// actions
		add_action( "add_meta_boxes", array( &$this, "add_meta_box" ) );
		add_action( 'save_post', array( &$this, "save_meta_box" ) );
	}

	/**
	 * Hooked into add_meta_boxes to create it
	 */
	public function add_meta_box() {
		add_meta_box(
			"f12_newsletter_meta_box",
			"Informationen",
			array( &$this, "add_meta_box_html" ),
			"f12_newsletter"
		);
	}


	/**
	 * Save the content of the metabox slider
	 */
	public function save_meta_box() {
		global $post;

		if ( isset( $post ) ) {
			$post_id = $post->ID;

			$is_autosave    = wp_is_post_autosave( $post_id );
			$is_revision    = wp_is_post_revision( $post_id );
			$is_valid_nonce = ( isset( $_POST['f12_newsletter_nonce'] ) && wp_verify_nonce( $_POST['f12_newsletter_nonce'], basename( __FILE__ ) ) ) ? true : false;

			// Exit script depending on status
			if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
				return;
			}

			$f12_newsletter_name        = isset( $_POST['f12-newsletter-name'] ) ? $_POST['f12-newsletter-name'] : "";
			$f12_newsletter_email       = isset( $_POST['f12-newsletter-email'] ) ? $_POST['f12-newsletter-email'] : "";
			$f12_newsletter_doubleoptin = isset( $_POST['f12-newsletter-doubleoptin'] ) ? $_POST['f12-newsletter-doubleoptin'] : 0;
			$f12_newsletter_ip          = $_SERVER["REMOTE_ADDR"];

			update_post_meta( $post_id, "f12-newsletter-name", $f12_newsletter_name );
			update_post_meta( $post_id, "f12-newsletter-email", $f12_newsletter_email );
			update_post_meta( $post_id, "f12-newsletter-ip", $f12_newsletter_ip );
			update_post_meta( $post_id, "f12-newsletter-doubleoptin", $f12_newsletter_doubleoptin );
		}
	}

	/**
	 * The output for the Metabox as HTML
	 */
	public function add_meta_box_html() {
		global $post;

		$stored_meta_data = get_post_meta( $post->ID );

		$args = array(
			"wp_nonce_field"       => wp_nonce_field( basename( __FILE__ ), "f12_newsletter_nonce" ),
			"f12-newsletter-name"  => F12NewsletterUtils::get_field( $stored_meta_data, "f12-newsletter-name" ),
			"f12-newsletter-email" => F12NewsletterUtils::get_field( $stored_meta_data, "f12-newsletter-email" ),
			"f12-newsletter-ip"    => F12NewsletterUtils::get_field( $stored_meta_data, "f12-newsletter-ip" ),
			"f12-newsletter-doubleoptin"    => F12NewsletterUtils::get_field( $stored_meta_data, "f12-newsletter-doubleoptin" ),
		);

		F12NewsletterUtils::loadAdminTemplate( "meta-box-newsletter.php", $args );
	}
}