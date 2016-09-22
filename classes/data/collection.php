<?php

namespace josh\ww\data;


class collection {

	/**
	 * @var products
	 */
	protected $_products;

	protected $products;

	protected $args;

	public function __construct( array  $args ) {
		$this->args = $args;

	}


	public function get_products(){
		if( null == $this->_products ){
			$this->find_products();

		}

		if( null == $this->products ){
			$this->make_collection();
		}
		return $this->products;
	}

	protected function make_collection(){
		if( ! empty( $this->_products ) ){
			foreach( $this->_products->get_products() as $product ){
				$this->products[] = new product( $product );
			}

		}
	}


	protected function find_products( ){
		$this->_products = new products( $this->args );
		$this->_products->get_products();
	}
}