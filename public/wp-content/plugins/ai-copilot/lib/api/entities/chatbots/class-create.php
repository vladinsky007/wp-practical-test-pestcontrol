<?php

namespace QuadLayers\AICP\Api\Entities\Chatbots;

use QuadLayers\AICP\Models\Chatbots as Models_Chatbots;
use QuadLayers\AICP\Api\Entities\Chatbots\Base;
use QuadLayers\AICP\Helpers;
use WP_REST_Server;

class Create extends Base {

	protected static $route_path = 'chatbots';

	public function callback( \WP_REST_Request $request ) {
		try {

			$data = Helpers::parse_body(
				json_decode( $request->get_body(), true ),
				array(
					'chatbot_label'        => 'self::sanitize_label',
					'chatbot_description'  => 'wp_kses_post',
					'chatbot_assistant_id' => 'intval',
					'chatbot_visibility'   => 'intval',
				)
			);

			$chatbot = Models_Chatbots::instance()->create( $data );

			if ( ! $chatbot ) {
				throw new \Exception( esc_html__( 'Unknown error.', 'ai-copilot' ), 500 );
			}

			return $this->handle_response( $chatbot );

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
		);
	}

	public static function get_rest_method() {
		return WP_REST_Server::CREATABLE;
	}

	public function get_rest_permission() {
		return current_user_can( 'manage_options' );
	}
}
