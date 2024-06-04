<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Chat;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Services\OpenAI\Chat\Post as API_Fetch_Chat_OpenAi;
use QuadLayers\AICP\Helpers;

class Post extends Base {
	protected static $route_path = 'chat';

	public function callback( \WP_REST_Request $request ) {
		try {
			$data = Helpers::parse_body(
				json_decode( $request->get_body(), true ),
				array(
					'model'         => 'strval',
					'prompt_system' => function ( $value ) {
						return addslashes( urldecode( $value ) );
					},
					'prompt_user'   => function ( $value ) {
						return addslashes( urldecode( $value ) );
					},
					'max_tokens'    => 'absint',
					'temperature'   => 'floatval',
					'messages'      => 'json_decode',
					'stop'          => 'json_decode',
					'top_p'         => 'floatval',
				)
			);

			$response = ( new API_Fetch_Chat_OpenAi() )->get_data( $data );

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
