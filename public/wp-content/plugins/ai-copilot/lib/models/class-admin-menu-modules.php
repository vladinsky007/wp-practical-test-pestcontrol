<?php
namespace QuadLayers\AICP\Models;

use QuadLayers\AICP\Entities\Admin_Menu_Modules as Admin_Menu_Modules_Entity;

use QuadLayers\WP_Orm\Builder\SingleRepositoryBuilder;

class Admin_Menu_Modules {

	protected static $instance;
	protected $repository;

	private function __construct() {
		$builder = ( new SingleRepositoryBuilder() )
		->setTable( 'aicp_features' )
		->setEntity( Admin_Menu_Modules_Entity::class );

		$this->repository = $builder->getRepository();
	}

	public function get_table() {
		return $this->repository->getTable();
	}

	public function get() {
		$entity = $this->repository->find();

		if ( $entity ) {
			return $entity->getProperties();
		} else {
			$admin = new Admin_Menu_Modules_Entity();
			return $admin->getProperties();
		}
	}

	public function delete_all() {
		return $this->repository->delete();
	}

	public function save( $data ) {
		$entity = $this->repository->create( $data );

		if ( $entity ) {
			return true;
		}
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
