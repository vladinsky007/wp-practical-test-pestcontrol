<?php

namespace QuadLayers\AICP;

use QuadLayers\AICP\Api\Services\Pexels\Routes_Library as Services_Pexels_Routes_Library;
use QuadLayers\AICP\Api\Services\OpenAI\Routes_Library as Services_OpenAI_Routes_Library;
use QuadLayers\AICP\Api\Entities\Actions_Templates\Routes_Library as Actions_Templates_Routes_Library;
use QuadLayers\AICP\Api\Entities\Admin_Menu\Routes_Library as Admin_Menu_Routes_Library;
use QuadLayers\AICP\Api\Entities\Chatbots\Routes_Library as Chatbots_Routes_Library;
use QuadLayers\AICP\Api\Entities\Content_Templates\Routes_Library as Content_Templates_Routes_Library;
use QuadLayers\AICP\Api\Entities\Transactions\Routes_Library as Transactions_Routes_Library;

final class Plugin {

	private static $instance;

	private function __construct() {
		/**
		 * Load plugin textdomain.
		 */
		load_plugin_textdomain( 'ai-copilot', false, QUADLAYERS_AICP_PLUGIN_DIR . '/languages/' );
		/**
		 * Add premium CSS
		 */
		add_action( 'admin_footer', array( __CLASS__, 'add_premium_css' ) );

		Setup::instance();

		Services_OpenAI_Routes_Library::instance();
		Services_Pexels_Routes_Library::instance();
		Transactions_Routes_Library::instance();
		Admin_Menu_Routes_Library::instance();
		Actions_Templates_Routes_Library::instance();
		Content_Templates_Routes_Library::instance();
		Chatbots_Routes_Library::instance();

		Controllers\Icons::instance();
		Controllers\Helpers::instance();
		Controllers\Hooks::instance();
		Controllers\Components::instance();
		Controllers\Api_Services::instance();
		Controllers\Api_Transactions::instance();
		Controllers\Api_Admin_Menu::instance();

		Controllers\Api_Actions_Templates::instance();
		Controllers\Api_Content_Templates::instance();
		Controllers\Api_Assistant_Messages::instance();
		Controllers\Api_Assistant_Threads::instance();
		Controllers\Api_Chatbots::instance(); // TODO: remover
		Controllers\Api_Assistants::instance(); // TODO: remover

		Controllers\Admin_Menu::instance();
		Controllers\Admin_Actions::instance();
		// Controllers\Frontend_Chatbot::instance();
		Controllers\Admin_Content::instance();
		Controllers\Admin_Bulk::instance();
		Controllers\Admin_Playground::instance();

		Hooks\Content_Classic_Editor::instance();
		Hooks\File_Upload_Permissions::instance();

		do_action( 'quadlayers_aicp_init' );
	}

	public static function add_premium_css() {
		?>
			<style>
				.aicp_premium-field {
					opacity: 0.5;
					pointer-events: none;
				}
				.aicp_premium-field input,
				.aicp_premium-field textarea,
				.aicp_premium-field select {
					background-color: #eee;
				}
				.aicp_premium-badge::before {
					content: "PRO";
					display: inline-block;
					font-size: 10px;
					color: #ffffff;
					background-color: #f57c00;
					border-radius: 3px;
					width: 30px;
					height: 15px;
					line-height: 15px;
					text-align: center;
					margin-right: 5px;
					vertical-align: middle;
				}
				.aicp_premium-hide {
					display: none;
				}
				.aicp_premium-field .description {
					display: inline-block !important;
					vertical-align: middle;
				}
			</style>
		<?php
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}

Plugin::instance();
