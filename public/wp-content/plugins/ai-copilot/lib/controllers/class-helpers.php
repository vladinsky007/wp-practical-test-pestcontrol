<?php

namespace QuadLayers\AICP\Controllers;

use QuadLayers\AICP\Helpers as QUADLAYERS_AICP_Helpers;

class Helpers {

	protected static $instance;

	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
	}

	public function register_scripts() {
		$helpers = include QUADLAYERS_AICP_PLUGIN_DIR . 'build/helpers/js/index.asset.php';

		/**
		 * Register helpers assets
		 */
		wp_register_script(
			'aicp-helpers',
			plugins_url( '/build/helpers/js/index.js', QUADLAYERS_AICP_PLUGIN_FILE ),
			$helpers['dependencies'],
			$helpers['version'],
			true
		);

		require_once ABSPATH . 'wp-admin/includes/translation-install.php';
		$available_languages = wp_get_available_translations();
		$languages           = array();

		foreach ( $available_languages as $key => $value ) {
			$languages[ $key ] = array(
				'language'     => $value['language'],
				'english_name' => $value['english_name'],
			);
		}

		$languages['en_US'] = array(
			'language'     => 'en_US',
			'english_name' => 'English (USA)',
		);

		$post_types = QUADLAYERS_AICP_Helpers::get_valid_post_types();

		wp_localize_script(
			'aicp-helpers',
			'aicpHelpers',
			array(
				'WP_LANGUAGES'                      => $languages,
				'WP_LANGUAGE'                       => get_locale(),
				'WP_STATUSES'                       => get_post_statuses(),
				'QUADLAYERS_AICP_PLUGIN_URL'        => plugins_url( '/', QUADLAYERS_AICP_PLUGIN_FILE ),
				'QUADLAYERS_AICP_PLUGIN_NAME'       => QUADLAYERS_AICP_PLUGIN_NAME,
				'QUADLAYERS_AICP_PLUGIN_VERSION'    => QUADLAYERS_AICP_PLUGIN_VERSION,
				'QUADLAYERS_AICP_WORDPRESS_URL'     => QUADLAYERS_AICP_WORDPRESS_URL,
				'QUADLAYERS_AICP_REVIEW_URL'        => QUADLAYERS_AICP_REVIEW_URL,
				'QUADLAYERS_AICP_DEMO_URL'          => QUADLAYERS_AICP_DEMO_URL,
				'QUADLAYERS_AICP_PREMIUM_SELL_URL'  => QUADLAYERS_AICP_PREMIUM_SELL_URL,
				'QUADLAYERS_AICP_SUPPORT_URL'       => QUADLAYERS_AICP_SUPPORT_URL,
				'QUADLAYERS_AICP_DOCUMENTATION_URL' => QUADLAYERS_AICP_DOCUMENTATION_URL,
				'QUADLAYERS_AICP_GROUP_URL'         => QUADLAYERS_AICP_GROUP_URL,
				'QUADLAYERS_AICP_DEVELOPER'         => QUADLAYERS_AICP_DEVELOPER,
				'QUADLAYERS_AICP_VALID_POST_TYPES'  => $post_types,
			)
		);
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
