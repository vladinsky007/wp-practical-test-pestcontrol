<?php

namespace QuadLayers\AICP\Api\Services\Pexels\Images;

use QuadLayers\AICP\Api\Services\Pexels\Base;
use QuadLayers\AICP\Services\Pexels\Images\Get as API_Fetch_Image_Pexels;
use QuadLayers\AICP\Helpers;

class Post extends Base {
	protected static $route_path = 'images';

	public function callback( \WP_REST_Request $request ) {
		try {
			$data = Helpers::parse_body(
				json_decode( $request->get_body(), true ),
				array(
					'query'       => function ( $value ) {
						return addslashes( urldecode( $value ) );
					},
					'orientation' => 'strval',
					'page'        => 'absint',
					'per_page'    => 'absint',
					'color'       => 'strval',
					'locale'      => 'strval',
					'size'        => 'strval',
				)
			);

			$response = ( new API_Fetch_Image_Pexels() )->get_data( $data );

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
