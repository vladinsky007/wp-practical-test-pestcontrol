<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Assistants\Files;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Models\Assistants_Files as Models_Assistants_Files;
use QuadLayers\AICP\Services\OpenAI\Assistants\Files\Get as API_Fetch_Get_Assistant_File_OpenAi;

class Get extends Base {
	protected static $route_path = 'assistants/files';

	public function callback( \WP_REST_Request $request ) {
		try {

			$file_id = $request->get_param( 'file_id' );
			$sync    = $request->get_param( 'sync' );

			$response = array();

			// If file_id is not set, get all files.
			if ( ! isset( $file_id ) ) {
				// If sync is set, update all files.
				if ( $sync ) {
					$old_files = Models_Assistants_Files::instance()->get_all();
					$files     = ( new API_Fetch_Get_Assistant_File_OpenAi() )->get_data();
					// Update all current files.
					if ( ! empty( $old_files ) ) {
						foreach ( $old_files as $old_file_data ) {
							$new_file_data = array_filter(
								$files,
								function ( $file ) use ( $old_file_data ) {
									return $file['openai_id'] === $old_file_data['openai_id'];
								}
							);

							// If file is in wpdb but not in openai.
							if ( empty( $new_file_data ) ) {
								$old_file_data['openai_id'] = '';
								$new_file_data              = $old_file_data;
							} else {
								$new_file_data = reset( $new_file_data );
							}

							Models_Assistants_Files::instance()->update( $old_file_data['file_id'], $new_file_data );
						}
					}
					// Create new files.
					foreach ( $files as $file ) {
						if ( ! isset( $old_files ) || ! in_array( $file['openai_id'], array_column( $old_files, 'openai_id' ), true ) ) {
							Models_Assistants_Files::instance()->create( $file );
						}
					}
				}
				$response = Models_Assistants_Files::instance()->get_all();
			} else {
				// If file_id is set, get file by id.
				// If sync is set, update file by id.
				if ( $sync ) {
					$old_file_data = Models_Assistants_Files::instance()->get( $file_id );
					if ( empty( $old_file_data ) ) {
						throw new \Exception( esc_html__( 'File not found.', 'ai-copilot' ), 404 );
					}

					$args = array(
						'openai_id' => $old_file_data['openai_id'],
					);

					$new_file_data = ( new API_Fetch_Get_Assistant_File_OpenAi() )->get_data( $args );

					if ( empty( $new_file_data ) ) {
						$old_file_data['openai_id'] = null;
						$new_file_data              = $old_file_data;
					}
					Models_Assistants_Files::instance()->update( $file_id, $new_file_data );

				}
				$response = Models_Assistants_Files::instance()->get( $file_id );
			}

			return $this->handle_response( $response );
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
		return \WP_REST_Server::READABLE;
	}

	public static function get_rest_args() {
		return array(
			'file_id' => array(
				'required'          => false,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( ! is_numeric( $param ) ) {
						return new \WP_Error( 400, __( 'File id not found.', 'ai-copilot' ) );
					}
					return true;
				},
			),
			'sync'    => array(
				'required' => false,
			),
		);
	}
}
