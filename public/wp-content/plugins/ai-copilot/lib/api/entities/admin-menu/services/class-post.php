<?php

namespace QuadLayers\AICP\Api\Entities\Admin_Menu\Services;

use QuadLayers\AICP\Api\Entities\Admin_Menu\Base;
use QuadLayers\AICP\Models\Admin_Menu_Services;

class Post extends Base {
	protected static $route_path = 'services';

	public function callback( \WP_REST_Request $request ) {
		try {

			$body = json_decode( $request->get_body(), true );

			$status = Admin_Menu_Services::instance()->save( $body );

			return $this->handle_response( $status );
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
