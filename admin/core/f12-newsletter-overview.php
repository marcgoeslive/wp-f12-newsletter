<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Change the view of the overview table
 */
class F12NewsletterOverview {
	public function __construct() {
		//add_filter( 'manage_f12_contact_columns', array( &$this, "set_columns" ) );
		add_filter( 'manage_f12_newsletter_posts_columns', array( &$this, "posts_columns" ) );
		add_action( 'manage_f12_newsletter_posts_custom_column', array( &$this, "custom_columns" ), 10, 2);
	}

	public function custom_columns( $column, $post_id ) {
		$stored_meta_data = get_post_meta( $post_id );
		switch ( $column ) {
			case "cp":
				echo "<input type=\"checkbox\" />";
				break;
			case 'name':
				echo F12NewsletterUtils::get_field( $stored_meta_data, "f12-newsletter-name" );
				break;
			case 'email':
				echo F12NewsletterUtils::get_field( $stored_meta_data, "f12-newsletter-email" );
				break;
		}
	}

	public function posts_columns( $columns ) {
		unset($columns["title"]);

		$args = array(
			"name"   => "Name",
			"email"  => "E-Mail",
			"date"   => $columns["date"]
		);
		;
		unset($columns["date"]);

		$columns = array_merge($columns,$args);

		return $columns;
	}
}