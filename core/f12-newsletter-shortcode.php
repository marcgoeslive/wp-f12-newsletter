<?php
if ( ! defined( "ABSPATH" ) ) {
	exit;
}

/**
 * Class F12NewsletterShortcode
 */
class F12NewsletterShortcode {
	/**
	 * Error Messages
	 */
	private $error = array();

	/**
	 * Constructor
	 */
	public function __construct() {
		add_shortcode( "f12_newsletter_form", array( &$this, "add_shortcode" ) );
		add_action( "wp_loaded", array( &$this, "validate_form" ) );
	}

	/**
	 * Validate form
	 */
	public function validate_form() {
		if ( ! isset( $_POST["f12-newsletter-submit"] ) ) {
			return;
		}

		// clean input
		$name       = isset( $_POST["f12-newsletter-name"] ) ? sanitize_text_field( $_POST["f12-newsletter-name"] ) : "";
		$email      = isset( $_POST["f12-newsletter-email"] ) ? $_POST["f12-newsletter-email"] : "";

		// Check fields
		$is_valid_name    = ! empty( $name ) ? true : false;
		$is_valid_email   = is_email( $email ) ? true : false;
		$is_valid_nonce   = ( isset( $_POST['f12_newsletter_nonce'] ) && wp_verify_nonce( $_POST['f12_newsletter_nonce'], basename( __FILE__ ) ) ) ? true : false;

		if ( $is_valid_name && $is_valid_email && $is_valid_nonce ) {
			$link = add_query_arg( array(
				"hash"      => $email
			), get_permalink(get_option( "f12-newsletter-settings" )["f12-newsletter-page-doubleoptin"]));

			// array data
			$data = array(
				"name"       => $name,
				"email"      => $email,
				"link" => $link
			);

			$admin_mail    = get_option( "f12-newsletter-settings" )["f12-newsletter-email"];

			$header   = array();
			$header[] = "MIME-Version: 1.0";
			$header[] = "Content-type: text/html; charset=utf-8";
			$header[] = "From: " . $admin_mail;
			$header[] = "Return-Path: " .$admin_mail;
			$header[] = "X-Mailer: PHP/" . phpversion();
			$header[] = "Reply-To: " . $admin_mail;
			$header   = implode( "\r\n", $header );

			// Send the external e-mail
			$email_message = $this->parse_email_message( wpautop(get_option( "f12-newsletter-settings" )["f12-newsletter-extern-message"]), $data );
			mail( $email, get_option( "f12-newsletter-settings" )["f12-newsletter-extern-subject"], $email_message, $header );

			$post = array(
				'post_title'  => "Newsletter Abo",
				'post_status' => 'publish',
				'post_author' => 1,
				'post_type'   => 'f12_newsletter'
			);

			// Add to posts
			$post_id = wp_insert_post( $post );

			update_post_meta( $post_id, "f12-newsletter-name", $name );
			update_post_meta( $post_id, "f12-newsletter-email", $email );
			update_post_meta($post_id, "f12-newsletter-ip", $_SERVER["REMOTE_ADDR"]);

			wp_redirect( get_permalink( get_option( "f12-newsletter-settings" )["f12-newsletter-page-send"] ) );
			exit;
		} else {
			if ( ! $is_valid_name ) {
				$this->error["error-name"] = true;
			}
			if ( ! $is_valid_email ) {
				$this->error["error-email"] = true;
			}

		}
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
	 * Shortcode galerie output
	 */
	public function add_shortcode( $atts ) {
		if(!is_array($atts)){
			$atts = array();
		}

		$atts["wp_nonce_field"] = wp_nonce_field( basename( __FILE__ ), "f12_newsletter_nonce" );

		$atts = array_merge($atts,$this->error);
		$atts["f12-newsletter-email"] = "";
		$atts["f12-newsletter-name"] = "";

		if(isset($_POST["f12-newsletter-submit"])){
			$atts["f12-newsletter-email"] = $_POST["f12-newsletter-email"];
			$atts["f12-newsletter-name"] = $_POST["f12-newsletter-name"];
		}

		echo F12NewsletterUtils::loadTemplate( "shortcode-newsletter-form.php", $atts );
	}
}

new F12NewsletterShortcode();