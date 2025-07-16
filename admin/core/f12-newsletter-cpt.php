<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class F12NewsletterCPT {
	/**
	 * Constructor
	 */
	public function __construct() {

		// Add actions
		add_action( "init", array( &$this, "add_custom_post_types" ) );
	}

	/**
	 * Add custom post types to wordpress
	 */
	public function add_custom_post_types() {
		register_post_type( "f12_newsletter", array(
			'labels'          => array(
				'name'          => __( 'Newsletter' ),
				'singular_name' => __( 'Newsletter' ),
				'menu_name'     => __( 'Newsletteranfragen' )
			),
			'menu_position'   => 42,
			'menu_icon'       => 'dashicons-email-alt',
			'public'          => true,
			'has_archive'     => true,
			'rewrite'         => array( 'slug' => 'f12_newsletter' ),
			'capability_type' => 'page',
			'supports'        => array(
				"title",
				"revisions"
			)
		) );
	}
}