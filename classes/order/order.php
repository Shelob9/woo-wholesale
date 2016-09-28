<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 8/11/16
 * Time: 7:37 PM
 */

namespace josh\ww\order;


use josh\ww\user;

abstract  class order {

	/**
	 * @var array
	 */
	protected $products;

	/**
	 * @var int
	 */
	protected $discount;

	/**
	 * @var user
	 */
	protected $user;

	public function __construct( array $products, user $user  ) {
		$this->hooks();
		$this->products = $products;
		$this->user = $user;
		$this->set_discount();
	}

	abstract protected function hooks();


	protected function set_discount(){
		$this->discount = $this->user->discount;
	}

	protected function change_price( $price ){
		return $price - (  $price * ( $this->discount / 100 ) );
	}

	protected function should_change( $id ){
		foreach( $this->products as $product ){
			if( $id === $product->id ){
				return true;
			}
		}

		return false;
	}
}