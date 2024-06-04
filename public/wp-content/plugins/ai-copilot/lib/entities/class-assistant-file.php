<?php
namespace QuadLayers\AICP\Entities;

use QuadLayers\WP_Orm\Entity\CollectionEntity;

class Assistant_File extends CollectionEntity {
	public static $primaryKey = 'file_id'; // phpcs:ignore.WordPress.NamingConventions.ValidVariableName.PropertyNotSnakeCase
	public $file_id           = 0;
	public $file_label        = '';
	public $openai_id         = '';
	public $post_type_id      = 0;
}
