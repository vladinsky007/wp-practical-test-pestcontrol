<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Assistants\Messages;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Services\OpenAI\Assistants\Messages\Post as API_Fetch_Post_Assistant_Message_OpenAi;
use QuadLayers\AICP\Services\OpenAI\Assistants\Runs\Get as API_Fetch_Get_Assistant_Run_OpenAi;
use QuadLayers\AICP\Services\OpenAI\Assistants\Runs\Post as API_Fetch_Post_Assistant_Run_OpenAi;
use QuadLayers\AICP\Models\Assistants as Models_Assistant;
use QuadLayers\AICP\Helpers;

class Post extends Base {
	protected static $route_path = 'assistants/messages';

	public function callback( \WP_REST_Request $request ) {
		try {

			$data = Helpers::parse_body(
				json_decode( $request->get_body(), true ),
				array(
					'message_content'  => 'self::sanitize_label',
					'thread_openai_id' => 'self::sanitize_label',
					'assistant_id'     => 'self::sanitize_number',
				)
			);

			// Add message to given thread in OpenAI.
			$response = ( new API_Fetch_Post_Assistant_Message_OpenAi() )->get_data( $data );

			if ( isset( $response['code'] ) ) {
				throw new \Exception( $response['message'], $response['code'] );
			}

			// Retrieve assistant_openai_id.
			// TODO: enviar directamente assistant_id, asi eviamos una consulta a la db con cada mensaje
			$assistant = Models_Assistant::instance()->get( $data['assistant_id'] );

			if ( ! isset( $assistant['assistant_id'] ) ) {
				throw new \Exception( esc_html__( 'Assistant Id not found.', 'ai-copilot' ), 404 );
			}

			// Max time/attempts to get response from OpenAI.
			$timeout      = Helpers::get_timeout();
			$sleep        = 0.3;
			$iteration    = $sleep + 3;
			$max_attempts = ( $timeout - $iteration ) / $iteration;

			// Loop to make polling awaiting for [status] = completed.
			$post_run_current_attemps = 0;
			do {
				++$post_run_current_attemps;

				if ( $post_run_current_attemps > $max_attempts ) {
					$error = array(
						'code'    => 500,
						'message' => esc_html__( 'Max post run attempts reached.', 'ai-copilot' ),
					);

					throw new \Exception( $error['message'], $error['code'] );
				}

				// Create Run.
				$post_run = ( new API_Fetch_Post_Assistant_Run_OpenAi() )->get_data(
					array_merge(
						$data,
						array(
							'assistant_openai_id' => $assistant['openai_id'],
						)
					)
				);

				sleep( $sleep );

				$completed = isset( $post_run['status'] );

				// Exit condition: status has to be set and completed else keep polling, if response is and error exit and throw exception.
			} while ( ! $completed );

			if ( isset( $post_run['code'] ) ) {
				throw new \Exception( $post_run['message'], $post_run['code'] );
			}

			// Loop to make polling awaiting for $[status] = completed.
			$get_run_current_attemps = 0;
			do {
				++$get_run_current_attemps;

				if ( $get_run_current_attemps > $max_attempts ) {
					$error = array(
						'code'    => 500,
						'message' => esc_html__( 'Max attempts reached.', 'ai-copilot' ),
					);

					throw new \Exception( $error['message'], $error['code'] );
				}

				// Get Run.
				$get_run = ( new API_Fetch_Get_Assistant_Run_OpenAi() )->get_data(
					array_merge(
						$data,
						array( 'run_openai_id' => $post_run['openai_id'] )
					)
				);

				sleep( $sleep );

				$completed = isset( $get_run['status'] ) && 'completed' === $get_run['status'];

				// Exit condition: status has to be set and completed else keep polling, if response is and error exit and throw exception.
			} while ( ! $completed );

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
		return \WP_REST_Server::CREATABLE;
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

	public function get_rest_permission() {
		return true;
	}
}
