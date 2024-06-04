<?php

namespace QuadLayers\AICP\Models;

use QuadLayers\AICP\Entities\Chatbot;
use QuadLayers\WP_Orm\Builder\CollectionRepositoryBuilder;

/**
 * Models_Chatbots Class
 */
class Chatbots {

	protected static $instance;
	protected $repository;

	private function __construct() {

		$builder = ( new CollectionRepositoryBuilder() )
		->setTable( 'aicp_chatbots' )
		->setEntity( Chatbot::class )
		->setAutoIncrement( true );

		$this->repository = $builder->getRepository();
	}

	public function get_table() {
		return $this->repository->getTable();
	}

	public function get_args() {
		$entity   = new Chatbot();
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
		if ( isset( $data['chatbot_id'] ) ) {
			unset( $data['chatbot_id'] );
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
		$chatbots = array();
		foreach ( $entities as $entity ) {
			$chatbots[] = $entity->getProperties();
		}
		return $chatbots;
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
