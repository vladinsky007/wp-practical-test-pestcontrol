<?php

namespace QuadLayers\AICP\Models;

use Symlink\ORM\Manager;
use Symlink\ORM\Mapping;
use QuadLayers\AICP\Entities\Transaction as Transaction_Entity;

/**
 * Models_Stat Class
 */
class Transactions {

	protected static $instance;
	protected $orm;
	protected $repository;

	private function __construct() {

		$orm       = Manager::getManager();
		$this->orm = $orm;

		$repository       = $this->orm->getRepository( Transaction_Entity::class );
		$this->repository = $repository;
	}

	public static function create_table() {
		$mapper = Mapping::getMapper();
		$mapper->updateSchema( Transaction_Entity::class );
	}

	public function get_all( int $page = 0, int $limit = 0 ) {
		$offset = ( $page - 1 ) * $limit;
		$query  = $this->repository->createQueryBuilder()
			->orderBy( 'ID', 'DESC' )
			->limit( $limit, $offset )
			->buildQuery();

		return $query->getResults( true );
	}

	public function get_by_grouped( $order, $order_by, $limit, $offset, $where, $group_by ) {
		global $wpdb;

		$table_name = $this->get_table_name();

		$order       = 'ASC';
		$order_by    = 'date';
		$where       = isset( $where ) ? $this->parse_where( $where ) : '';
		$date_format = '';
		switch ( $group_by ) {
			case 'day':
				$date_format = "DATE_FORMAT(date, '%Y/%m/%d')";
				break;
			case 'month':
				$date_format = "DATE_FORMAT(date, '%Y/%m')";
				break;
			case 'year':
				$date_format = "DATE_FORMAT(date, '%Y')";
				break;
			default:
				$date_format = '';
				break;
		}

		$group_by = "GROUP BY $group_by";

		$query = "SELECT $date_format AS date, SUM(tokens_qty_input) AS tokens_qty_input, SUM(tokens_qty_output) AS tokens_qty_output, SUM(tokens_qty_total) AS tokens_qty_total, COUNT(*) AS transaction_qty, ROUND(SUM(transaction_cost_input),2) AS transaction_cost_input, ROUND(SUM(transaction_cost_output),2) AS transaction_cost_output, ROUND(SUM(transaction_cost_total),2) AS transaction_cost_total FROM $table_name $where GROUP BY $date_format ORDER BY $order_by $order;";

		$results = $wpdb->get_results( $query ); // phpcs:ignore.WordPress.DB.PreparedSQL.NotPrepared

		return $results;
	}


	public function get_by( $order_by = 'ID', $order = 'DESC', $limit = 0, $offset = 0, $where = array() ) {
		$query = $this->repository->createQueryBuilder();

		$query->orderBy( $order_by, $order );

		$query->limit( $limit, $offset );

		if ( ! empty( $where ) ) {
			foreach ( $where as $key => $value ) {
				$query->where( $value[0], $value[1], $value[2] );
			}
		}
		$query->buildQuery();

		return $query->getResults( true );
	}

	public function parse_where( $where_array ) {
		$where_query = '';
		if ( 0 === count( $where_array ) ) {
			return $where_query;
		}
		$where_query .= 'WHERE ';

		$total_conditions = count( $where_array );
		for ( $i = 0; $i < $total_conditions; $i++ ) {
			$value        = $where_array[ $i ];
			$where_query .= "$value[0] $value[2] '$value[1]'";

			if ( $i < $total_conditions - 1 ) {
				$where_query .= ' AND ';
			}
		}

		return $where_query;
	}

	public function get_table_name() {
		global $wpdb;
		return $wpdb->prefix . $this->repository->getDBTable();
	}

	public function create( array $data ) {
		$transaction = new Transaction_Entity();
		$transaction->set( 'date', current_time( 'mysql' ) );
		$transaction->set( 'consumer_module', $data['consumer_module'] );
		$transaction->set( 'api_type', $data['api_type'] );
		$transaction->set( 'api_service', $data['api_service'] );
		$transaction->set( 'api_service_model', $data['api_service_model'] );
		$transaction->set( 'consumer_user', $data['consumer_user'] );
		$transaction->set( 'consumer_user_role', $data['consumer_user_role'] );
		$transaction->set( 'tokens_qty_input', $data['tokens_qty_input'] );
		$transaction->set( 'tokens_qty_output', $data['tokens_qty_output'] );
		$transaction->set( 'tokens_qty_total', $data['tokens_qty_input'] + $data['tokens_qty_output'] );
		$transaction->set( 'transaction_cost_input', $data['transaction_cost_input'] );
		$transaction->set( 'transaction_cost_output', $data['transaction_cost_output'] );
		$transaction->set( 'transaction_cost_total', $data['transaction_cost_input'] + $data['transaction_cost_output'] );
		if ( ! $this->save( $transaction ) ) {
			return false;
		}
		return $transaction;
	}

	public function save( Transaction_Entity $transaction ) {
		try {
			$this->orm->persist( $transaction );
			$this->orm->flush();
			return true;

		} catch ( \Throwable  $error ) {
			return false;
		}
	}

	public function delete( $transaction ) {
		try {
			if ( ! $transaction instanceof Transaction_Entity ) {
				$transaction = $this->find( $transaction );
			}
			if ( ! $transaction instanceof Transaction_Entity ) {
				return false;
			}
			$this->orm->remove( $transaction );
			$this->orm->flush();
			return true;
		} catch ( \Throwable  $error ) {
			return false;
		}
	}

	public function find( $id ) {
		try {
			$transaction_entity_stat = $this->repository->find( $id );
			return $transaction_entity_stat;
		} catch ( \Throwable  $error ) {
			return false;
		}
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
