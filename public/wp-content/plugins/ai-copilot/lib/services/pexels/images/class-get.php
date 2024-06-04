<?php

namespace QuadLayers\AICP\Services\Pexels\Images;

use QuadLayers\AICP\Services\Pexels\Base;
/**
 * API_Fetch_Text_OpenAi Class extends Base
 */
class Get extends Base {

	/**
	 * Function to build query url.
	 *
	 * @return string
	 */
	public function get_url( $args = null ) {
		$url = $this->fetch_url . '/search';

		$params = array(
			'query'       => isset( $args['query'] ) ? $args['query'] : '',
			'orientation' => isset( $args['orientation'] ) ? $args['orientation'] : 'landscape',
			'page'        => isset( $args['page'] ) ? $args['page'] : 0,
			'per_page'    => isset( $args['per_page'] ) ? $args['per_page'] : 4,
			'color'       => isset( $args['color'] ) ? $args['color'] : '',
			'locale'      => isset( $args['locale'] ) ? $args['locale'] : '',
			'size'        => isset( $args['size'] ) ? $args['size'] : 'medium',
		);

		$url = $url . '?' . http_build_query( $params );

		return $url;
	}
}
