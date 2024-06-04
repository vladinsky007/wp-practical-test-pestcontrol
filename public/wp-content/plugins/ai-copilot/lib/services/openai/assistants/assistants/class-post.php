<?php

namespace QuadLayers\AICP\Services\OpenAI\Assistants\Assistants;

use QuadLayers\AICP\Services\OpenAI\Base;
use QuadLayers\AICP\Helpers;
use QuadLayers\AICP\Models\Admin_Menu_Services;

/**
 * API_Fetch_Assistant_OpenAi Class extends Base
 */
class Post extends Base {

	/**
	 * Function to build query url.
	 *
	 * @return string
	 */
	public function get_url( $args = null ) {
		return $this->fetch_url . '/assistants';
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

		if ( empty( $args['model'] ) ) {
			throw new \Exception( esc_html__( 'Model not found.', 'ai-copilot' ), 404 );
		}

		$api_key = $settings['openai_api_key'];

		$headers = array(
			'Content-Type'  => 'application/json',
			'Authorization' => 'Bearer ' . $api_key,
			'OpenAI-Beta'   => 'assistants=v1',
		);

		$name = isset( $args['assistant_label'] ) ? $args['assistant_label'] : '';

		if ( strpos( $name, $this->prefix ) !== 0 ) {
			$name = $this->prefix . $name;
		}

		$body = array(
			'model'        => $args['model'],
			'name'         => $name,
			'description'  => isset( $args['assistant_description'] ) ? $args['assistant_description'] : '',
			'instructions' => isset( $args['prompt_system'] ) ? $args['prompt_system'] : '',
			'tools'        => isset( $args['tools'] ) ? $args['tools'] : array(
				array( 'type' => 'retrieval' ),
			),
			'file_ids'     => isset( $args['tools_file_ids'] ) ? $args['tools_file_ids'] : array(),
			'metadata'     => array(
				'origin' => 'aicp',
			),
		);

		$url     = $this->get_url( $args );
		$timeout = Helpers::get_timeout();

		$response = wp_remote_post(
			$url,
			array(
				'method'  => 'POST',
				'timeout' => $timeout,
				'headers' => $headers,
				'body'    => wp_json_encode( $body ),
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

		if ( isset( $response['code'] ) && isset( $response['message'] ) ) {
			return $response;
		}

		if ( ! isset( $response['object'] ) || 'list' !== $response['object'] ) {
			$assistant_label = isset( $response['name'] ) ? $response['name'] : '';

			if ( strpos( $assistant_label, $this->prefix ) === 0 ) {
				$assistant_label = substr( $assistant_label, strlen( $this->prefix ) );
			}

			$metadata         = isset( $response['metadata'] ) ? $response['metadata'] : array();
			$assistant_origin = isset( $metadata['origin'] ) && 'aicp' === $metadata['origin']
				? 'user'
				: 'external';

			return array(
				'assistant_description' => isset( $response['description'] ) ? $response['description'] : '',
				'assistant_label'       => $assistant_label,
				'model'                 => isset( $response['model'] ) ? $response['model'] : '',
				'prompt_system'         => isset( $response['instructions'] ) ? $response['instructions'] : '',
				'tools'                 => isset( $response['tools'] ) ? $response['tools'] : array(),
				'tools_file_ids'        => isset( $response['file_ids'] ) ? $response['file_ids'] : array(),
				'openai_id'             => isset( $response['id'] ) ? $response['id'] : '',
				'assistant_origin'      => $assistant_origin,
			);
		}

		$parsed_response = array();
		foreach ( $response['data'] as $data ) {
			$assistant_label = isset( $data['name'] ) ? $data['name'] : '';

			if ( strpos( $assistant_label, $this->prefix ) === 0 ) {
				$assistant_label = substr( $assistant_label, strlen( $this->prefix ) );
			}

			$metadata         = isset( $data['metadata'] ) ? $data['metadata'] : array();
			$assistant_origin = isset( $metadata['origin'] ) && 'aicp' === $metadata['origin']
				? 'user'
				: 'external';

			$parsed_response[] = array(
				'assistant_description' => isset( $data['description'] ) ? $data['description'] : '',
				'assistant_label'       => $assistant_label,
				'model'                 => isset( $data['model'] ) ? $data['model'] : '',
				'prompt_system'         => isset( $data['instructions'] ) ? $data['instructions'] : '',
				'tools'                 => isset( $data['tools'] ) ? $data['tools'] : array(),
				'tools_file_ids'        => isset( $data['file_ids'] ) ? $data['file_ids'] : array(),
				'openai_id'             => isset( $data['id'] ) ? $data['id'] : '',
				'assistant_origin'      => $assistant_origin,
			);
		}

		return $parsed_response;
	}
}
