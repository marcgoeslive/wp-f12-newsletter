<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Options
 */
class F12NewsletterOptions {
	/**
	 * F12NewsletterOptions constructor.
	 */
	public function __construct() {
		// Add actions
		add_action( "admin_init", array( &$this, "register_settings" ) );
		add_action( "admin_post_f12_newsletter_settings_save", array( &$this, "save" ) );
		add_action( "f12_newsletter_after_settings_save", array( $this, "testmail" ) );
	}

	public function testmail() {
		$is_valid_nonce = ( isset( $_POST['f12-newsletter-nonce'] ) && wp_verify_nonce( $_POST['f12-newsletter-nonce'], basename( __FILE__ ) ) ) ? true : false;
		$is_valid_email = is_email( $_POST["f12_newsletter_testmail_email"] ) ? true : false;

		if ( $is_valid_nonce && $is_valid_email ) {
			$args                   = array();
			$args["email-receiver"] = $_POST["f12_newsletter_testmail_email"];
			$args["email-sender"]   = $_POST["f12-newsletter-email"];
			$args["email-subject"]  = $_POST["f12-newsletter-extern-subject"];
			$args["email-message"]  = $_POST["f12-newsletter-extern-message"];

			$header   = array();
			$header[] = "MIME-Version: 1.0";
			$header[] = "Content-type: text/html; charset=utf-8";
			$header[] = "From: " . $args["email-sender"];
			$header[] = "Return-Path: " . $args["email-sender"];
			$header[] = "X-Mailer: PHP/" . phpversion();
			$header[] = "Reply-To: " . $args["email-sender"];
			$header   = implode( "\r\n", $header );

			if ( ! wp_mail( $args["email-receiver"], $args["email-subject"], wpautop( $args["email-message"] ), $header ) ) {
				error_log( "couldn't send testmail" );
			}
		}
	}

	public function save() {
		// Check save status
		$is_valid_nonce = ( isset( $_POST['f12-newsletter-nonce'] ) && wp_verify_nonce( $_POST['f12-newsletter-nonce'], basename( __FILE__ ) ) ) ? true : false;

		if ( $is_valid_nonce && $_POST && isset( $_POST["action"] ) == "f12_newsletter_page_save" ) {
			// Data
			$f12_newsletter_email            = isset( $_POST["f12-newsletter-email"] ) ? $_POST["f12-newsletter-email"] : "";
			$f12_newsletter_intern_subject   = isset( $_POST["f12-newsletter-intern-subject"] ) ? $_POST["f12-newsletter-intern-subject"] : "";
			$f12_newsletter_intern_message   = isset( $_POST["f12-newsletter-intern-message"] ) ? $_POST["f12-newsletter-intern-message"] : "";
			$f12_newsletter_extern_subject   = isset( $_POST["f12-newsletter-extern-subject"] ) ? $_POST["f12-newsletter-extern-subject"] : "";
			$f12_newsletter_extern_message   = isset( $_POST["f12-newsletter-extern-message"] ) ? $_POST["f12-newsletter-extern-message"] : "";
			$f12_newsletter_page_send        = isset( $_POST["f12-newsletter-page-send"] ) ? $_POST["f12-newsletter-page-send"] : - 1;
			$f12_newsletter_page_doubleoptin = isset( $_POST["f12-newsletter-page-doubleoptin"] ) ? $_POST["f12-newsletter-page-doubleoptin"] : - 1;

			// Data to save
			$args = array(
				"f12-newsletter-intern-subject"   => $f12_newsletter_intern_subject,
				"f12-newsletter-intern-message"   => $f12_newsletter_intern_message,
				"f12-newsletter-extern-subject"   => $f12_newsletter_extern_subject,
				"f12-newsletter-extern-message"   => $f12_newsletter_extern_message,
				"f12-newsletter-page-send"        => $f12_newsletter_page_send,
				"f12-newsletter-page-doubleoptin" => $f12_newsletter_page_doubleoptin
			);

			// validate data
			if ( is_email( $f12_newsletter_email ) ) {
				$args["f12-newsletter-email"] = $f12_newsletter_email;
			}

			// Update Data
			update_option( "f12-newsletter-settings", $args );

			do_action( "f12_newsletter_after_settings_save" );
		}
		wp_redirect( add_query_arg( array(
			"page"      => "f12_newsletter_settings",
			"post_type" => "f12_newsletter"
		), "edit.php" ) );
	}

	public function register_settings() {
		// Default settings
		add_option( "f12-newsletter-settings", array(
			"f12-newsletter-email"            => "",
			"f12-newsletter-intern-subject"   => "",
			"f12-newsletter-intern-message"   => "",
			"f12-newsletter-extern-subject"   => "",
			"f12-newsletter-extern-message"   => "",
			"f12-newsletter-page-send"        => - 1,
			"f12-newsletter-page-doubleoptin" => - 1
		) );
	}

	/**
	 * Output the Settings page
	 */
	public function render() {
		$args = array(
			"f12-newsletter-email"            => get_option( "f12-newsletter-settings" )["f12-newsletter-email"],
			"f12-newsletter-intern-subject"   => get_option( "f12-newsletter-settings" )["f12-newsletter-intern-subject"],
			"f12-newsletter-intern-message"   => get_option( "f12-newsletter-settings" )["f12-newsletter-intern-message"],
			"f12-newsletter-extern-subject"   => get_option( "f12-newsletter-settings" )["f12-newsletter-extern-subject"],
			"f12-newsletter-extern-message"   => get_option( "f12-newsletter-settings" )["f12-newsletter-extern-message"],
			"f12-newsletter-page-doubleoptin" => F12NewsletterUtils::get_option_list_pages( get_option( "f12-newsletter-settings" )["f12-newsletter-page-doubleoptin"] ),
			"f12-newsletter-page-send"        => F12NewsletterUtils::get_option_list_pages( get_option( "f12-newsletter-settings" )["f12-newsletter-page-send"] ),
			"f12-newsletter-nonce"            => wp_nonce_field( basename( __FILE__ ), "f12-newsletter-nonce", true, false )
		);

		echo F12NewsletterUtils::loadAdminTemplate( "admin.php", $args );
	}
}