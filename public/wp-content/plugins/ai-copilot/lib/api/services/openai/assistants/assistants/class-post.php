<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Assistants\Assistants;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Services\OpenAI\Assistants\Assistants\Post as API_Fetch_Post_Assistant_OpenAi;
use QuadLayers\AICP\Models\Assistants as Models_Assistant;
use QuadLayers\AICP\Helpers;

class Post extends Base {
	protected static $route_path = 'assistants';

	public function callback( \WP_REST_Request $request ) {
		try {

			$data = Helpers::parse_body(
				json_decode( $request->get_body(), true ),
				array(
					'model'                 => 'self::sanitize_label',
					'assistant_label'       => 'self::sanitize_label',
					'assistant_description' => 'self::sanitize_label',
					'prompt_system'         => 'self::sanitize_label',
					'tools'                 => 'self::check_if_array',
					'tools_file_ids'        => 'self::check_if_array',
				)
			);

			$response = ( new API_Fetch_Post_Assistant_OpenAi() )->get_data( $data );

			if ( isset( $response['code'] ) ) {
				throw new \Exception( $response['message'], $response['code'] );
			}

			$assistant = Models_Assistant::instance()->create( $response );

			if ( ! $assistant ) {
				throw new \Exception( esc_html__( 'Unknown error.', 'ai-copilot' ), 500 );
			}

			return $this->handle_response( $assistant );
		} catch ( \Throwable $error ) {
			return $this->handle_response(
				array(
					'code'    => $error->getCode(),
					'message' => $error->getMessage(),
				)
			);
		}
	}

	public static function get_rest_method() {
		return \WP_REST_Server::CREATABLE;
	}

	public static function get_rest_args() {
		return array(
			'model' => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( empty( $param ) ) {
						return new \WP_Error( 400, __( 'Model tags is empty.', 'ai-copilot' ) );
					}
					return true;
				},
			),
		);
	}
}
