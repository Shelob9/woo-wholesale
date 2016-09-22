<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 8/4/16
 * Time: 6:31 PM
 */

namespace josh\ww\role;


class role {


	protected $name;

	protected $label;

	protected $discount;

	protected $batch_size;

	public function __construct( $name, $label ) {
		$this->name = $name;
		$this->label = $label;
	}


	public function __get( $prop ) {

		if( 0 === $this->$prop || ! empty( $this->$prop ) ){
			return $this->$prop;
		}

		return false;
	}

	public function set_batch_size( $size ){
		$this->batch_size = $size;
	}

	public function set_discount( $discount ){
		$this->discount = $discount;
	}


}