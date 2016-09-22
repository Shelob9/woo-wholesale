<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 8/11/16
 * Time: 7:59 PM
 */

namespace josh\ww\data;


class cart_product {

	public $qty;
	public $id;

	public function __construct( $id, $qty ) {
		$this->id = $id;
		$this->qty = $qty;
	}
}