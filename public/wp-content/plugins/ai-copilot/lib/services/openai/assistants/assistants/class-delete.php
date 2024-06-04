<?php

namespace QuadLayers\AICP\Services\OpenAI\Assistants\Assistants;

use QuadLayers\AICP\Services\OpenAI\Base;
use QuadLayers\AICP\Helpers;
use QuadLayers\AICP\Models\Admin_Menu_Services;

/**
 * API_Fetch_Assistant_OpenAi Class extends Base
 */
class Delete extends Base {

	/**
	 * Function to build query url.
	 *
	 * @return string
	 */
	public function get_url( $args = null ) {
		return $this->fetch_url . '/assistants/' . $args['openai_id'];
	}

	/**
	 * Function to query Open AI data.
	 *
	 * @param string $args Args to set query.
	 * @return array
	 *
	 * @throws \Exception If API Key is not found.
	 */
	public function get_response( $args = null ) {

		$admin_menu_services = Admin_Menu_Services::instance();
		$settings            = $admin_menu_services->get();

		if ( empty( $settings['openai_api_key'] ) ) {
			throw new \Exception( esc_html__( 'API Key not found.', 'ai-copilot' ), 404 );
		}

		$api_key = $settings['openai_api_key'];

		if ( empty( $args['openai_id'] ) ) {
			throw new \Exception( esc_html__( 'Assistant OpenAI ID not found.', 'ai-copilot' ), 404 );
		}

		$api_key = $settings['openai_api_key'];

		$headers = array(
			'Content-Type'  => 'application/json',
			'Authorization' => 'Bearer ' . $api_key,
			'OpenAI-Beta'   => 'assistants=v1',
		);

		$url     = $this->get_url( $args );
		$timeout = Helpers::get_timeout();

		$response = wp_remote_post(
			$url,
			array(
				'method'  => 'DELETE',
				'timeout' => $timeout,
				'headers' => $headers,
			)
		);

		$response = $this->handle_response( $response );
		return $response;
	}

	/**
	 * Function to parse response to usable data.
	 *
	 * @param array $response Raw response from openai.
	 * @return array
	 */
	public function response_to_data( $response = null ) {
		return $response;
	}
}
