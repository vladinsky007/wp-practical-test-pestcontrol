<?php
namespace QuadLayers\AICP\Api\Entities\Chatbots;

use QuadLayers\AICP\Models\Chatbots as Models_Chatbots;
use QuadLayers\AICP\Api\Entities\Chatbots\Base;
use QuadLayers\AICP\Helpers;

/**
 * API_Rest_Chatbots_Edit Class
 */
class Edit extends Base {

	protected static $route_path = 'chatbots';

	public function callback( \WP_REST_Request $request ) {

		try {
			$data = Helpers::parse_body(
				json_decode( $request->get_body(), true ),
				array(
					'chatbot_id'           => 'intval',
					'chatbot_label'        => 'self::sanitize_label',
					'chatbot_description'  => 'wp_kses_post',
					'chatbot_assistant_id' => 'wp_kses_post',
					'chatbot_visibility'   => 'intval',
				)
			);

			$chatbots = Models_Chatbots::instance()->update( $data['chatbot_id'], $data );

			if ( ! $chatbots ) {
				throw new \Exception( esc_html__( 'Chatbot can not be updated', 'ai-copilot' ), 412 );
			}

			return $this->handle_response( $chatbots );
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
			'chatbot_label' => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( empty( $param ) ) {
						return new \WP_Error( 400, __( 'Label is empty.', 'ai-copilot' ) );
					}
					return true;
				},
			),
			'chatbot_id'    => array(
				'required'          => true,
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
		return \WP_REST_Server::EDITABLE;
	}

	public function get_rest_permission() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}
		return true;
	}
}
