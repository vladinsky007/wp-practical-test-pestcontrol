<?php

namespace QuadLayers\AICP\Services;

interface Services_Interface {

	/**
	 * Function to get response and parse to data
	 *
	 * @return array
	 */
	public function get_data();

	/**
	 * Function to query Facebook/Instagram data.
	 *
	 * @return array
	 */
	public function get_response();

	/**
	 * Function to parse response to usable data.
	 *
	 * @return array
	 */
	public function response_to_data();

	/**
	 * Function to build query url.
	 *
	 * @return string
	 */
	public function get_url( $args );

	/**
	 * Function to handle query response
	 *
	 * @return array
	 */
	public function handle_response();

	/**
	 * Function to handle error on query response
	 *
	 * @return array
	 */
	public function handle_error();
}
