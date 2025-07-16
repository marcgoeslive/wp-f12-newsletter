<?php
/**
 * adds the flag for the user to confirm the
 * newsletter entry
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class F12NewsletterDoubleOptIn {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Actions add
		add_action( "wp_head", array( &$this, "fire" ) );

		// Filter add
		add_filter( "query_vars", array( &$this, "add_query_vars_filter" ) );
	}

	public function add_query_vars_filter( $vars ) {
		$vars[] = "hash";

		return $vars;
	}

	/**
	 * Checglobal $wp_query;
	$wp_query->set_404();
	status_header( 404 );
	get_template_part( 404 );
	exit();k if the page is the defined double opt in page
	 */
	private function is_double_opt_in_page() {
		global $post;

		if ( ! $post ) {
			return false;
		}

		if ( ! isset( get_option( "f12-newsletter-settings" )["f12-newsletter-page-doubleoptin"] ) ) {
			return false;
		}

		$defined_page = get_option( "f12-newsletter-settings" )["f12-newsletter-page-doubleoptin"];

		if ( $defined_page != $post->ID ) {
			return false;
		}
		return true;
	}

	private function is_hash() {
		$hash = get_query_var( "hash" );
		if ( empty( $hash ) || ! is_email( $hash ) ) {
			return false;
		}

		return true;
	}

	private function confirm() {
		$hash = get_query_var( "hash" );

		$args = array(
			"post_type"  => "f12_newsletter",
			"meta_query" => array(
				"relation"=>"AND",
				array(
					"key"   => "f12-newsletter-email",
					"value" => $hash
				),
				array(
					"relation" => "OR",
					array(
						"key"   => "f12-newsletter-doubleoptin",
						"value" => 0
					),
					array(
						"key"   => "f12-newsletter-doubleoptin",
						"compare" => 'NOT EXISTS'
					)
				)
			)
		);

		$query = new WP_Query( $args );
		$items = $query->get_posts();

		if ( ! isset( $items[0] )) {
			global $wp_query;
			status_header(404);
			$wp_query->set_404();
			wp_redirect( get_home_url()."/404" );
			exit;
		} 

		update_post_meta( $items[0]->ID, "f12-newsletter-doubleoptin", "1" );

		// array data
		$data = array(
			"name"       => get_post_meta($items[0]->ID, "f12-newsletter-name",true),
			"email"       => get_post_meta($items[0]->ID, "f12-newsletter-email",true),
		);


		// Send mail
		$admin_mail    = get_option( "f12-newsletter-settings" )["f12-newsletter-email"];
		$email_message = $this->parse_email_message( wpautop(get_option( "f12-newsletter-settings" )["f12-newsletter-intern-message"]), $data );

		$header   = array();
		$header[] = "MIME-Version: 1.0";
		$header[] = "Content-type: text/html; charset=utf-8";
		$header[] = "From: " . $admin_mail;
		$header[] = "Return-Path: " .$admin_mail;
		$header[] = "X-Mailer: PHP/" . phpversion();
		$header[] = "Reply-To: " . $admin_mail;
		$header   = implode( "\r\n", $header );

		// send the internal email
		mail( $admin_mail, get_option( "f12-newsletter-settings" )["f12-newsletter-intern-subject"], $email_message, $header );

		return true;
	}

	/**
	 * Parse the email message content with the given user data
	 */
	private function parse_email_message( $message, $placeholder ) {
		foreach ( $placeholder as $key => $value ) {
			$message = str_replace( "{" . $key . "}", $value, $message );
		}

		return $message;
	}

	/**
	 * Runs the procedure after wordpress has loaded
	 */
	public function fire() {
		global $post;

		if ( $this->is_double_opt_in_page() ) {
			if ( $this->is_hash() ) {
				$this->confirm();
			} else {
				global $wp_query;
				status_header(404);
				$wp_query->set_404();
				wp_redirect( get_home_url()."/404" );
				exit;
			}
		}
	}
}

new F12NewsletterDoubleOptIn();