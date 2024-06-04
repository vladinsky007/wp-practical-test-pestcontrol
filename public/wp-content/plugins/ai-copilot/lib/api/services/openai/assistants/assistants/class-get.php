<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Assistants\Assistants;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Models\Assistants as Models_Assistant;
use QuadLayers\AICP\Services\OpenAI\Assistants\Assistants\Get as API_Fetch_Get_Assistant_OpenAi;

class Get extends Base {
	protected static $route_path = 'assistants';

	public function callback( \WP_REST_Request $request ) {
		try {

			// Query Params.
			$assistant_id = $request->get_param( 'assistant_id' );
			$sync         = $request->get_param( 'sync' );

			// If assistant_id is not set, get all assistants.
			if ( ! is_numeric( $assistant_id ) ) {
				// If sync is set, update all assistants.
				if ( $sync ) {
					$old_assistants = Models_Assistant::instance()->get_all();
					$assistants     = ( new API_Fetch_Get_Assistant_OpenAi() )->get_data();
					// Update all current assistants.
					if ( ! empty( $old_assistants ) ) {
						foreach ( $old_assistants as $old_assistant_data ) {
							$new_assistant_data = array_filter(
								$assistants,
								function ( $assistant ) use ( $old_assistant_data ) {
									return $assistant['openai_id'] === $old_assistant_data['openai_id'];
								}
							);

							// If assistant is in wpdb but not in openai.
							if ( empty( $new_assistant_data ) ) {
								$old_assistant_data['openai_id'] = '';
								$new_assistant_data              = $old_assistant_data;
							} else {
								$new_assistant_data = reset( $new_assistant_data );
							}

							Models_Assistant::instance()->update( $old_assistant_data['assistant_id'], $new_assistant_data );
						}
					}
					// Create new assistants.
					foreach ( $assistants as $assistant ) {
						if ( ! isset( $old_assistants ) || ! in_array( $assistant['openai_id'], array_column( $old_assistants, 'openai_id' ), true ) ) {
							Models_Assistant::instance()->create( $assistant );
						}
					}
				}

				$response = Models_Assistant::instance()->get_all();

				return null !== $response ? $this->handle_response( $response ) : $this->handle_response( array() );
			}

			// If assistant_id is set, get assistant by id.
			// If sync is set, update assistant by id.
			if ( $sync ) {
				$old_assistant_data = Models_Assistant::instance()->get( $assistant_id );
				if ( empty( $old_assistant_data ) ) {
					throw new \Exception( esc_html__( 'Assistant not found.', 'ai-copilot' ), 404 );
				}

				$args = array(
					'openai_id' => $old_assistant_data['openai_id'],
				);

				$new_assistant_data = ( new API_Fetch_Get_Assistant_OpenAi() )->get_data( $args );

				Models_Assistant::instance()->update( $assistant_id, $new_assistant_data );

			}

			$response = Models_Assistant::instance()->get( $assistant_id );

			return null !== $response ? $this->handle_response( $response ) : $this->handle_response( array() );
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
			'assistant_id' => array(
				'required'          => false,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( ! is_numeric( $param ) ) {
						return new \WP_Error( 400, __( 'Assistant id not found.', 'ai-copilot' ) );
					}
					return true;
				},
			),
			'sync'         => array(
				'required' => false,
			),
		);
	}
}
