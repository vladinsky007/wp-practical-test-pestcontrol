<?php
namespace QuadLayers\AICP\Entities;

use QuadLayers\WP_Orm\Entity\CollectionEntity;

class Assistant extends CollectionEntity {
	public static $primaryKey     = 'assistant_id'; // phpcs:ignore.WordPress.NamingConventions.ValidVariableName.PropertyNotSnakeCase
	public $assistant_id          = 0;
	public $assistant_description = '';
	public $assistant_label       = '';
	public $assistant_origin      = 'user';
	public $model                 = 'gpt-3.5-turbo';
	public $prompt_system         = '';
	public $tools                 = array();
	public $tools_file_ids        = array();
	public $openai_id             = '';
}
