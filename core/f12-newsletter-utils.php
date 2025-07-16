<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Utils functionalities for the Plugin
 */
class F12NewsletterUtils {
	/**
	 * Loads and returns the content of an admin template
	 *
	 * @param $template - The Template that should be loaded
	 * @param array $args - parameter that should be loaded
	 *
	 * @return string the output of the template
	 */
	public static function loadAdminTemplate( $template, $args = array() ) {
		include( plugin_dir_path( __FILE__ ) . "../admin/templates/" . $template );
	}

	/**
	 * Loads and returns the content of the frontend template
	 *
	 * @param $template - The Template that should be loaded
	 * @param array $args - parameter that should be loaded
	 *
	 * @return string the output of the template
	 */
	public static function loadTemplate( $template, $args = array() ) {
		$output = "";
		if ( file_exists( get_stylesheet_directory() . "/f12-newsletter/" . $template ) ) {
			ob_start();
			include( get_stylesheet_directory() . "/f12-newsletter/" . $template );
			$output = ob_get_contents();
		} else {
			ob_start();
			include( plugin_dir_path( __FILE__ ) . "../templates/" . $template );
			$output = ob_get_clean();
		}

		return $output;
	}

	/**
	 * Get field from Stored Meta Data and return value if it exists,
	 * otherwise return the default value
	 */
	public static function get_field( $stored_meta_data = array(), $key, $default = "" ) {
		if ( isset( $stored_meta_data[ $key ] ) && ! empty( $stored_meta_data[ $key ] ) ) {
			return $stored_meta_data[ $key ][0];
		}

		return $default;
	}

	/**
	 * Returns a Option list with all pages and the given $selected as selected.
	 *
	 * @param $selected_id int
	 *
	 * @return string
	 */
	public static function get_option_list_pages( $selected_id ) {

		$option = "";
		$pages  = get_pages();
		$option .= "<option value='-1'>Bitte wählen</option>";
		foreach ( $pages as $page ) {
			if ( $selected_id == $page->ID ) {
				$option .= "<option value=\"" . $page->ID . "\" selected='selected'>" . $page->post_title . "</option>";
			} else {
				$option .= "<option value=\"" . $page->ID . "\">" . $page->post_title . "</option>";
			}
		}

		return $option;
	}
}
