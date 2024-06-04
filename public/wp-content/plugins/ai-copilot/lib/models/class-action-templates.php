<?php

namespace QuadLayers\AICP\Models;

use QuadLayers\AICP\Entities\Action_Template;
use QuadLayers\WP_Orm\Builder\CollectionRepositoryBuilder;

/**
 * Models_Action Class
 */
class Action_Templates {

	protected static $instance;
	protected $repository;

	private function __construct() {

		$builder = ( new CollectionRepositoryBuilder() )
		->setTable( 'aicp_action_templates' )
		->setEntity( Action_Template::class )
		->setAutoIncrement( true );

		$this->repository = $builder->getRepository();
	}

	public function get_table() {
		return $this->repository->getTable();
	}

	public function get_args() {
		$entity   = new Action_Template();
		$defaults = $entity->getDefaults();
		return $defaults;
	}

	public function get( int $id ) {
		$entity = $this->repository->find( $id );
		if ( $entity ) {
			return $entity->getProperties();
		}
	}

	public function delete( int $id ) {
		return $this->repository->delete( $id );
	}

	public function update( int $id, array $data ) {
		$entity = $this->repository->update( $id, $data );
		if ( $entity ) {
			return $entity->getProperties();
		}
	}

	public function create( array $data ) {
		if ( isset( $data['action_id'] ) ) {
			unset( $data['action_id'] );
		}
		$entity = $this->repository->create( $data );
		if ( $entity ) {
			return $entity->getProperties();
		}
	}

	public function get_all() {
		$entities = $this->repository->findAll();
		if ( ! $entities ) {
			return;
		}
		$actions = array();
		foreach ( $entities as $entity ) {
			$actions[] = $entity->getProperties();
		}
		return $actions;
	}

	public function delete_all() {
		return $this->repository->deleteAll();
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
