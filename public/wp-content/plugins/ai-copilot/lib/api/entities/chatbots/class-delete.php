<?php
namespace QuadLayers\AICP\Api\Entities\Chatbots;

use QuadLayers\AICP\Models\Chatbots as Models_Chatbots;
use QuadLayers\AICP\Api\Entities\Chatbots\Base;

/**
 * API_Rest_Chatbots_Delete Class
 */
class Delete extends Base {

	protected static $route_path = 'chatbots';

	public function callback( \WP_REST_Request $request ) {

		try {
			$chatbot_id = $request->get_param( 'chatbot_id' );

			$success = Models_Chatbots::instance()->delete( $chatbot_id );

			if ( ! $success ) {
				throw new \Exception( esc_html__( 'Cannot delete the chatbot: chatbot_id not found.', 'ai-copilot' ), 404 );
			}

			return $this->handle_response( $success );
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
				'required'          => true,
				'type'              => 'integer',
				'sanitize_callback' => 'absint',
				'validate_callback' => function ( $param, $request, $key ) {
					if ( ! is_numeric( $param ) ) {
						return new \WP_Error( 400, __( 'Chatbot id not found.', 'ai-copilot' ) );
					}
					return true;
				},
			),
		);
	}

	public static function get_rest_method() {
		return \WP_REST_Server::DELETABLE;
	}

	public function get_rest_permission() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}
		return true;
	}
}
