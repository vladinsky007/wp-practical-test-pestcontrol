<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Completions;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Services\OpenAI\Completions\Post as API_Fetch_Completions_OpenAi;
use QuadLayers\AICP\Helpers;

class Post extends Base {
	protected static $route_path = 'completion';

	public function callback( \WP_REST_Request $request ) {
		try {
			$data = Helpers::parse_body(
				json_decode( $request->get_body(), true ),
				array(
					'model'       => 'strval',
					'prompt'      => function ( $value ) {
						return addslashes( urldecode( $value ) );
					},
					'max_tokens'  => 'absint',
					'temperature' => 'floatval',
					'best_of'     => 'floatval',
					'stop'        => 'json_decode',
					'top_p'       => 'floatval',
				)
			);

			$data['stop']  = isset( $data['stop'] ) ? $data['stop'] : null;
			$data['top_p'] = isset( $data['top_p'] ) ? $data['top_p'] : 1;

			$response = ( new API_Fetch_Completions_OpenAi() )->get_data( $data );

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
		return array();
	}
}
