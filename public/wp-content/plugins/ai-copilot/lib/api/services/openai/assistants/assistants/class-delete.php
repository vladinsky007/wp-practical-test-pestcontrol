<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Assistants\Assistants;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Services\OpenAI\Assistants\Assistants\Delete as API_Fetch_Delete_Assistant_OpenAi;
use QuadLayers\AICP\Models\Assistants as Models_Assistant;

class Delete extends Base {
	protected static $route_path = 'assistants';

	public function callback( \WP_REST_Request $request ) {
		try {
			$assistant_id = $request->get_param( 'assistant_id' );

			$assistant = Models_Assistant::instance()->get( $assistant_id );

			if ( ! $assistant ) {
				throw new \Exception( esc_html__( 'Assistant not found', 'ai-copilot' ), 404 );
			}

			// TODO: Delete safe verification
			$protected_assistants_ids = array(
				// 'asst_XeiwMya25SRABHNpheZxXD5L',
				'asst_RSkB0k4SlCSjSTUs6rw0XRmX',
				'asst_6h5V8m0ljKHSTAvQO86VTE6D',
				'asst_tzKYSGIhCKrqu6Gdwmkdb2Og',
				'asst_z5yhfeL4dEEOphnXBKnwvMcX',
			);

			$is_protected = in_array( $assistant['openai_id'], $protected_assistants_ids, true );

			if ( $is_protected ) {
				throw new \Exception( esc_html__( 'Cannot delete protected assistant', 'ai-copilot' ), 400 );
			}

			if ( ! empty( $assistant['openai_id'] ) ) {

				$response = ( new API_Fetch_Delete_Assistant_OpenAi() )->get_data(
					array(
						'openai_id' => $assistant['openai_id'],
					)
				);

				if ( isset( $response['code'] ) ) {
					throw new \Exception( $response['message'], $response['code'] );
				}
			}

			$success = Models_Assistant::instance()->delete( $assistant['assistant_id'] );

			if ( ! $success ) {
				throw new \Exception( esc_html__( 'Cannot delete assistant, openai_id not found', 'ai-copilot' ), 404 );
			}

			return $this->handle_response( $success );
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
		return \WP_REST_Server::DELETABLE;
	}

	public static function get_rest_args() {
		return array(
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
