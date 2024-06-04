<?php

namespace QuadLayers\AICP\Api\Services\OpenAI\Images;

use QuadLayers\AICP\Api\Services\OpenAI\Base;
use QuadLayers\AICP\Services\OpenAI\Images\Get as API_Fetch_Image_OpenAi;
use QuadLayers\AICP\Helpers;

class Post extends Base {
	protected static $route_path = 'images';

	public function callback( \WP_REST_Request $request ) {
		try {
			$data = Helpers::parse_body(
				json_decode( $request->get_body(), true ),
				array(
					'model'           => 'sanitize_title',
					'prompt'          => function ( $value ) {
						return addslashes( urldecode( $value ) );
					},
					'n'               => 'absint',
					'response_format' => 'strval',
					'size'            => 'wp_kses_post',
					'style'           => 'sanitize_title',
					'quality'         => 'sanitize_title',
				)
			);

			error_log( 'data: ' . json_encode( $data, JSON_PRETTY_PRINT ) );

			if ( empty( $data['model'] ) ) {
				throw new \Exception( esc_html__( 'Model is empty', 'ai-copilot' ), 400 );
			}

			$response = ( new API_Fetch_Image_OpenAi() )->get_data( $data );

			error_log( 'response: ' . json_encode( $response, JSON_PRETTY_PRINT ) );

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
