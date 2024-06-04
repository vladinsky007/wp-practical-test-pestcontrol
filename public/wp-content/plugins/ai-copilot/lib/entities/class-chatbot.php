<?php
namespace QuadLayers\AICP\Entities;

use QuadLayers\WP_Orm\Entity\CollectionEntity;

class Chatbot extends CollectionEntity {
	public static $primaryKey    = 'chatbot_id'; // phpcs:ignore.WordPress.NamingConventions.ValidVariableName.PropertyNotSnakeCase
	public $chatbot_id           = 0;
	public $chatbot_label        = '';
	public $chatbot_description  = '';
	public $chatbot_origin       = 'user';
	public $chatbot_assistant_id = 0;
	public $chatbot_visibility   = 1;
}
