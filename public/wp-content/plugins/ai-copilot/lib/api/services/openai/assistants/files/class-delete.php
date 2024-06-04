<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Assistants\Files;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Services\OpenAI\Assistants\Files\Delete as API_Fetch_Delete_Assistant_File_OpenAi;
use QuadLayers\AICP\Models\Assistants_Files as Models_Assistants_Files;

class Delete extends Base {
	protected static $route_path = 'assistants/files';

	public function callback( \WP_REST_Request $request ) {
		try {
			$file_id = $request->get_param( 'file_id' );

			if ( ! is_numeric( $file_id ) ) {
				throw new \Exception( esc_html__( 'File id not set.', 'ai-copilot' ), 400 );
			}

			$file = Models_Assistants_Files::instance()->get( $file_id );

			if ( ! isset( $file['file_id'] ) ) {
				throw new \Exception( esc_html__( 'File not found.', 'ai-copilot' ), 404 );
			}

			$response = ( new API_Fetch_Delete_Assistant_File_OpenAi() )->get_data(
				array(
					'file_id' => $file['openai_id'],
				)
			);

			if ( isset( $response['code'] ) ) {
				throw new \Exception( $response['message'], $response['code'] );
			}

			$success = Models_Assistants_Files::instance()->delete( $file['file_id'] );

			if ( ! $success ) {
				throw new \Exception( esc_html__( 'Cannot delete file.', 'ai-copilot' ), 404 );
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
			'file_id' => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( ! is_numeric( $param ) ) {
						return new \WP_Error( 400, __( 'File id not found.', 'ai-copilot' ) );
					}
					return true;
				},
			),
		);
	}
}
