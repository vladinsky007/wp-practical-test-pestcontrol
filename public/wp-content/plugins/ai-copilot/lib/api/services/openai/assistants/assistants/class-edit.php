<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Assistants\Assistants;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Helpers;
use QuadLayers\AICP\Services\OpenAI\Assistants\Assistants\Edit as API_Fetch_Edit_Assistant_OpenAi;
use QuadLayers\AICP\Models\Assistants as Models_Assistant;

class Edit extends Base {
	protected static $route_path = 'assistants';

	public function callback( \WP_REST_Request $request ) {
		try {

			$data = Helpers::parse_body(
				json_decode( $request->get_body(), true ),
				array(
					'assistant_id'          => 'self::sanitize_number',
					'openai_id'             => 'self::sanitize_label',
					'model'                 => 'self::sanitize_label',
					'assistant_label'       => 'self::sanitize_label',
					'assistant_description' => 'self::sanitize_label',
					'prompt_system'         => 'self::sanitize_label',
					'tools'                 => 'self::check_if_array',
					'tools_file_ids'        => 'self::check_if_array',
					'assistant_origin'      => 'self::sanitize_label',
				)
			);

			// TODO: Delete safe verification
			$protected_assistants_ids = array(
				'asst_RSkB0k4SlCSjSTUs6rw0XRmX',
				'asst_6h5V8m0ljKHSTAvQO86VTE6D',
				'asst_tzKYSGIhCKrqu6Gdwmkdb2Og',
				'asst_z5yhfeL4dEEOphnXBKnwvMcX',
			);

			$is_protected = in_array( $data['openai_id'], $protected_assistants_ids, true );

			if ( $is_protected ) {
				throw new \Exception( esc_html__( 'Cannot delete protected assistant', 'ai-copilot' ), 400 );
			}

			$response = ( new API_Fetch_Edit_Assistant_OpenAi() )->get_data( $data );

			if ( isset( $response['code'] ) ) {
				throw new \Exception( $response['message'], $response['code'] );
			}

			$assistant = Models_Assistant::instance()->update( $data['assistant_id'], $response );

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
		return \WP_REST_Server::EDITABLE;
	}

	public static function get_rest_args() {
		return array(
			'model'        => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( empty( $param ) ) {
						return new \WP_Error( 400, __( 'Model tags is empty.', 'ai-copilot' ) );
					}
					return true;
				},
			),
			'assistant_id' => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( ! is_numeric( $param ) ) {
						return new \WP_Error( 400, __( 'Assistant id not found.', 'ai-copilot' ) );
					}
					return true;
				},
			),
		);
	}
}
