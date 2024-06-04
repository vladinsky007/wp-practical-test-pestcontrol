<?php

namespace QuadLayers\AICP\Api\Entities\Actions_Templates;

use QuadLayers\AICP\Models\Action_Templates as Models_Action_Templates;
use QuadLayers\AICP\Api\Entities\Actions_Templates\Base;
use QuadLayers\AICP\Helpers;
use WP_REST_Server;

class Create extends Base {

	protected static $route_path = 'actions';

	public function callback( \WP_REST_Request $request ) {
		try {
			$data = Helpers::parse_body(
				json_decode( $request->get_body(), true ),
				array(
					'action_label'       => 'self::sanitize_label',
					'action_origin'      => 'sanitize_title',
					'action_description' => 'wp_kses_post',
					'action_post_type'   => 'self::check_if_array',
					'action_type'        => 'sanitize_title',
					'variables_language' => 'sanitize_title',
					'variables_style'    => 'sanitize_title',
					'variables_tone'     => 'sanitize_title',
					'prompt_system'      => 'wp_kses_post',
					'prompt_user'        => 'wp_kses_post',
					'model'              => 'wp_kses_post',
					'model_temperature'  => 'self::sanitize_number',
					'model_max_tokens'   => 'intval',
				)
			);

			$action = Models_Action_Templates::instance()->create( $data );

			if ( ! $action ) {
				throw new \Exception( esc_html__( 'Unknown error.', 'ai-copilot' ), 500 );
			}

			return $this->handle_response( $action );

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
			'prompt_system' => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( empty( strlen( trim( $param ) ) ) ) {
						return new \WP_Error( 400, __( 'Prompt system is empty.', 'ai-copilot' ) );
					}
					return true;
				},
			),
			'prompt_user'   => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( empty( strlen( trim( $param ) ) ) ) {
						return new \WP_Error( 400, __( 'Prompt user is empty.', 'ai-copilot' ) );
					}
					return true;
				},
			),
			'action_label'  => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( empty( strlen( trim( $param ) ) ) ) {
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
