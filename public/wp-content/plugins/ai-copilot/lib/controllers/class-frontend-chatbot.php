<?php

namespace QuadLayers\AICP\Controllers;

class Frontend_Chatbot {

	protected static $instance;
	protected static $menu_slug = 'ai-copilot';

	private function __construct() {
		/**
		 * Admin Menu
		 */
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_footer', array( $this, 'chat_app_container' ) );
	}

	public function register_scripts() {

		$frontend_chatbot = include QUADLAYERS_AICP_PLUGIN_DIR . 'build/frontend-chatbot/js/index.asset.php';

		// wp_deregister_script( 'wp-element' );

		// wp_register_script( 'wp-element', 'http://wp-copilot.local/wp-includes/js/wp-element.min.js', array(), '1.0', true );

		// wp_enqueue_script( 'wp-element' );

		wp_register_script(
			'aicp-frontend-chatbot',
			plugins_url( '/build/frontend-chatbot/js/index.js', QUADLAYERS_AICP_PLUGIN_FILE ),
			$frontend_chatbot['dependencies'],
			$frontend_chatbot['version'],
			true
		);

		wp_register_style(
			'aicp-frontend-chatbot',
			plugins_url( '/build/frontend-chatbot/css/style.css', QUADLAYERS_AICP_PLUGIN_FILE ),
			array(),
			QUADLAYERS_AICP_PLUGIN_VERSION
		);
	}

	public function chat_app_container() {
		echo '<div id="aicp-chat-app"></div>';
	}

	public function enqueue_scripts() {
		wp_enqueue_media();
		wp_enqueue_script( 'aicp-frontend-chatbot' );
		wp_enqueue_style( 'aicp-frontend-chatbot' );
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
