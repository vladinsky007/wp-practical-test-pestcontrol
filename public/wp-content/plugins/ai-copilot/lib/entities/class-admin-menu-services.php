<?php
namespace QuadLayers\AICP\Entities;

use QuadLayers\WP_Orm\Entity\SingleEntity;

class Admin_Menu_Services extends SingleEntity {
	public $openai_api_key = '';
	public $pexels_api_key = '';
}
