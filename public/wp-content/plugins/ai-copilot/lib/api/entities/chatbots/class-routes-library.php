<?php

namespace QuadLayers\AICP\Api\Entities\Chatbots;

use QuadLayers\AICP\Api\Route as Route_Interface;

use QuadLayers\AICP\Api\Entities\Chatbots\Get as Chatbot_Get;
use QuadLayers\AICP\Api\Entities\Chatbots\Create as Chatbot_Create;
use QuadLayers\AICP\Api\Entities\Chatbots\Edit as Chatbot_Edit;
use QuadLayers\AICP\Api\Entities\Chatbots\Delete as Chatbot_Delete;

class Routes_Library {
	protected $routes                = array();
	protected static $rest_namespace = 'quadlayers/ai-copilot';
	protected static $instance;

	private function __construct() {
		add_action( 'init', array( $this, '_rest_init' ) );
	}

	public static function get_namespace() {
		return self::$rest_namespace;
	}

	public function get_routes( $route_path = null ) {
		if ( ! $route_path ) {
			return $this->routes;
		}

		if ( isset( $this->routes[ $route_path ] ) ) {
			return $this->routes[ $route_path ];
		}
	}

	public function register( Route_Interface $instance ) {
		$this->routes[ $instance::get_name() ] = $instance;
	}

	public function _rest_init() {
		new Chatbot_Get();
		new Chatbot_Create();
		new Chatbot_Edit();
		new Chatbot_Delete();
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
