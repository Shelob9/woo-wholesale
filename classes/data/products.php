<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 8/3/16
 * Time: 4:57 PM
 */

namespace josh\ww\data;


class products {


	protected $args;

	/**
	 * @var \WP_Query
	 */
	protected $query;


	public function __construct( array  $args ) {
		$this->set_args( $args );
	}

	protected function do_query(){
		$this->query = new \WP_Query( $this->args );
	}

	public function get_products(){
		if(  empty( $this->query ) ){
			$this->do_query();
		}

		return $this->query->posts;
	}

	protected function set_args( $args ){
		$this->args = wp_parse_args( $args, [
			'post_type'      => 'product',
			'posts_per_page' => - 1,
			'fields' => 'ids'
		] );
	}
}