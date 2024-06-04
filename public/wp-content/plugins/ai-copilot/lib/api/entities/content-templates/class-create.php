<?php

namespace QuadLayers\AICP\Api\Entities\Content_Templates;

use QuadLayers\AICP\Models\Content_Templates as Models_Templates;
use QuadLayers\AICP\Api\Entities\Content_Templates\Base;
use QuadLayers\AICP\Helpers;
use WP_REST_Server;

class Create extends Base {

	protected static $route_path = 'templates';

	public function callback( \WP_REST_Request $request ) {
		try {
			$data = Helpers::parse_body(
				json_decode( $request->get_body(), true ),
				array(
					'template_label'            => 'self::sanitize_label',
					'template_description'      => 'wp_kses_post',
					'template_type'             => 'sanitize_title',
					'template_post_type'        => 'self::check_if_array',
					'prompt_system'             => 'wp_kses_post',
					'prompt_title'              => 'wp_kses_post',
					'prompt_sections'           => 'wp_kses_post',
					'prompt_content'            => 'wp_kses_post',
					'prompt_excerpt'            => 'wp_kses_post',
					'prompt_tags'               => 'wp_kses_post',
					'variables_section_count'   => 'intval',
					'variables_paragraph_count' => 'intval',
					'variables_word_count'      => 'intval',
					'variables_language'        => 'sanitize_title',
					'variables_style'           => 'sanitize_title',
					'variables_tone'            => 'sanitize_title',
					'image_n'                   => 'intval',
					'image_size'                => 'wp_kses_post',
					'image_position'            => 'wp_kses_post',
					'image_source'              => 'sanitize_title',
					'image_orientation'         => 'sanitize_title',
					'image_color'               => 'sanitize_title',
					'image_locale'              => 'sanitize_title',
					'image_quality'             => 'sanitize_title',
					'image_style'               => 'sanitize_title',
					'model'                     => 'wp_kses_post',
					'model_temperature'         => 'self::sanitize_number',
					'model_max_tokens'          => 'intval',
					'seo'                       => 'sanitize_title',
					'seo_bold_keywords'         => 'wp_kses_post',
					'seo_positive_keywords'     => 'wp_kses_post',
					'seo_negative_keywords'     => 'wp_kses_post',
				)
			);

			$template = Models_Templates::instance()->create( $data );

			if ( ! $template ) {
				throw new \Exception( esc_html__( 'Unknown error.', 'ai-copilot' ), 500 );
			}

			return $this->handle_response( $template );

		} catch ( \Throwable  $error ) {
			return $this->handle_response(
				array(
					'code'    => $error->getCode(),
					'message' => $error->getMessage(),
				)
			);
		}
	}

	public static function get_rest_args() {
		return array(
			'template_label'  => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( empty( strlen( trim( $param ) ) ) ) {
						return new \WP_Error( 400, __( 'Label is empty.', 'ai-copilot' ) );
					}
					return true;
				},
			),
			'prompt_title'    => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( empty( strlen( trim( $param ) ) ) ) {
						return new \WP_Error( 400, __( 'Prompt title is empty.', 'ai-copilot' ) );
					}
					return true;
				},
			),
			'prompt_content'  => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( empty( strlen( trim( $param ) ) ) ) {
						return new \WP_Error( 400, __( 'Prompt content is empty.', 'ai-copilot' ) );
					}
					return true;
				},
			),
			'prompt_sections' => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( empty( strlen( trim( $param ) ) ) ) {
						return new \WP_Error( 400, __( 'Prompt sections is empty.', 'ai-copilot' ) );
					}
					return true;
				},
			),
			'prompt_excerpt'  => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( empty( strlen( trim( $param ) ) ) ) {
						return new \WP_Error( 400, __( 'Prompt excerpt is empty.', 'ai-copilot' ) );
					}
					return true;
				},
			),
			'prompt_tags'     => array(
				'required'          => true,
				'validate_callback' => function ( $param, $request, $key ) {
					if ( empty( strlen( trim( $param ) ) ) ) {
						return new \WP_Error( 400, __( 'Prompt tags is empty.', 'ai-copilot' ) );
					}
					return true;
				},
			),
		);
	}

	public static function get_rest_method() {
		return WP_REST_Server::CREATABLE;
	}

	public function get_rest_permission() {
		return current_user_can( 'manage_options' );
	}
}
