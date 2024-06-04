<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Assistants\Threads;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Services\OpenAI\Assistants\Threads\Post as API_Fetch_Post_Assistant_Thread_OpenAi;
use QuadLayers\AICP\Models\Assistants_Threads as Models_Assistant_Thread;
use QuadLayers\AICP\Models\Assistants as Models_Assistant;
use QuadLayers\AICP\Helpers;

class Post extends Base {
	protected static $route_path = 'assistants/threads';

	public function callback( \WP_REST_Request $request ) {
		try {

			$data = Helpers::parse_body(
				json_decode( $request->get_body(), true ),
				array(
					'assistant_id' => 'self::sanitize_number', // TODO: replace with assistant_openai_id
				)
			);

			$assistant = Models_Assistant::instance()->get( $data['assistant_id'] );

			if ( empty( $assistant['openai_id'] ) ) {// TODO: send openai_id directly to prevent query
				throw new \Exception( esc_html__( 'Assistant OpenAI ID not found.', 'ai-copilot' ), 404 );
			}

			$response = ( new API_Fetch_Post_Assistant_Thread_OpenAi() )->get_data(
				array_merge(
					$data,
					array(
						'assistant_openai_id' => $assistant['openai_id'],
					)
				)
			);

			if ( isset( $response['code'] ) ) {
				throw new \Exception( $response['message'], $response['code'] );
			}

			$assistant_thread = Models_Assistant_Thread::instance()->create( array_merge( $data, $response ) );

			if ( ! $assistant_thread ) {
				throw new \Exception( esc_html__( 'Unknown error.', 'ai-copilot' ), 500 );
			}
			return $this->handle_response( (array) $assistant_thread );
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
