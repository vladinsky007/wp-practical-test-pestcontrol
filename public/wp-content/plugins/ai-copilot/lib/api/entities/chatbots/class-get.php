<?php
namespace QuadLayers\AICP\Api\Entities\Chatbots;

use QuadLayers\AICP\Models\Chatbots as Models_Chatbots;
use QuadLayers\AICP\Api\Entities\Chatbots\Base;

/**
 * API_Rest_Chatbots_Get Class
 */
class Get extends Base {

	protected static $route_path = 'chatbots';

	public function callback( \WP_REST_Request $request ) {
		try {
			$chatbots = Models_Chatbots::instance()->get_all();

			if ( null !== $chatbots && 0 !== count( $chatbots ) ) {
				return $this->handle_response( $chatbots );
			}

			return $this->handle_response( array() );
		} catch ( \Throwable  $error ) {
			return $this->handle_response(
				array(
					'code'    => $error->getCode(),
					'message' => $error->getMessage(),
				)
			);
		}
	}

	public static function get_rest_args() {
		return array(
			'chatbot_id' => array(
				'validate_callback' => function ( $param, $request, $key ) {
					return is_numeric( $param );
				},
			),
		);
	}

	public static function get_rest_method() {
		return \WP_REST_Server::READABLE;
	}

	public function get_rest_permission() {
		// Removed to use route in frontend
		// if ( ! current_user_can( 'manage_options' ) ) {
		// return false;
		// }

		return true;
	}
}
