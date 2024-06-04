<?php

namespace QuadLayers\AICP;

final class Helpers {

	public static function get_admin_screen_post_type() {
		$screen = get_current_screen();
		if ( ! isset( $screen->post_type ) ) {
			return;
		}
		$post_type        = $screen->post_type;
		$post_type_object = get_post_type_object( $post_type );
		if ( ! $post_type_object ) {
			return;
		}
		return $post_type_object;
	}

	public static function is_admin_post_type( $post_types = array() ) {
		global $pagenow;
		// Check that we're in the admin area and on a post type edit page.
		if ( ! is_admin() || ! ( 'post.php' === $pagenow || 'post-new.php' === $pagenow || 'edit.php' === $pagenow ) ) {
			return false;
		}
		$screen = get_current_screen();
		// Check that we're editing one of the specified post types or all post types.
		if ( ! empty( $post_types ) && ! in_array( $screen->post_type, $post_types, true ) ) {
			return false;
		}
		return true;
	}

	public static function get_valid_post_types() {
		$post_types = get_post_types();
		$filtered   = array();
		foreach ( $post_types as $post_type ) {
			$post_type_object   = get_post_type_object( $post_type );
			$has_editor_support = post_type_supports( $post_type, 'editor' );
			if ( $has_editor_support && $post_type_object->show_ui && 'wp_block' !== $post_type && 'wp_navigation' !== $post_type ) {
				$filtered[] = $post_type_object;
			}
		}
		return $filtered;
	}

	public static function parse_body( $body, $valid_args ) {
		$args = array();
		foreach ( $valid_args as $field => $sanitize_callback ) {
			if ( isset( $body[ $field ] ) ) {
				$args[ $field ] = call_user_func( $sanitize_callback, $body[ $field ] );
			}
		}
		return $args;
	}

	public static function check_if_array( $value ) {
		if ( is_array( $value ) ) {
			return $value;
		}
	}

	public static function sanitize_number( $value ) {
		if ( is_numeric( $value ) ) {
			// Check if the value contains a decimal point to decide between float and int.
			if ( strpos( $value, '.' ) !== false ) {
				return floatval( $value );
			} else {
				return intval( $value );
			}
		}
	}

	public static function sanitize_label( $value ) {
		if ( ! strlen( trim( $value ) ) ) {
			return '';
		}
		$value = wp_unslash( $value );
		return sanitize_text_field( $value );
	}

	public static function get_max_execution_time() {
		return ini_get( 'max_execution_time' );
	}

	public static function get_timeout() {
		$max_execution_time = self::get_max_execution_time();
		$timeout            = min( $max_execution_time - 5, 240 );
		return $timeout;
	}
}
