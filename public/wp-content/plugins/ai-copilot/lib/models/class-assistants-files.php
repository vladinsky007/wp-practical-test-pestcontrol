<?php

namespace QuadLayers\AICP\Models;

use QuadLayers\AICP\Entities\Assistant_File;
use QuadLayers\WP_Orm\Builder\CollectionRepositoryBuilder;

/**
 * Models_Assistants_Files Class
 */
class Assistants_Files {

	protected static $instance;
	protected $repository;

	private function __construct() {

		$builder = ( new CollectionRepositoryBuilder() )
		->setTable( 'aicp_assistant_files' )
		->setEntity( Assistant_File::class )
		->setAutoIncrement( true );

		$this->repository = $builder->getRepository();
	}

	public function get_table() {
		return $this->repository->getTable();
	}

	public function get_args() {
		$entity   = new Assistant_File();
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
		if ( isset( $data['file_id'] ) ) {
			unset( $data['file_id'] );
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
		$assistant_files = array();
		foreach ( $entities as $entity ) {
			$assistant_files[] = $entity->getProperties();
		}
		return $assistant_files;
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
