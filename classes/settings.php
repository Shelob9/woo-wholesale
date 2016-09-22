<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 8/4/16
 * Time: 6:59 PM
 */

namespace josh\ww;


use josh\ww\role\roles;

class settings implements \JsonSerializable{


	protected $discounts = [];

	protected $batch_size = [];


	protected $roles;

	public function __construct( roles $roles ) {
		$this->roles = $roles;
		$this->setup_vars();
	}


	public function get_discount( $level ){
		if( isset( $this->discounts[ $level ] ) ){
			return $this->discounts[ $level ];
		}
	}

	public function get_batch_size( $level ){
		if( isset( $this->batch_size[ $level ] ) ){
			return $this->batch_size[ $level ];
		}
	}

	public function set_discount( $level, $size  ){
		if( isset( $this->discounts[ $level ] ) && 0 < floatval( $size ) && 100 > floatval( $size ) ){
			$this->discounts[ $level ] = $size;
			return true;
		}

		return false;
	}

	public function set_batch_size( $level, $size  ){
		if( isset( $this->batch_size[ $level ] ) && 0 < absint( $size )  ){
			$this->batch_size[ $level ] = $size;
			return true;
		}

		return false;

	}


	protected function setup_vars(){
		foreach( $this->roles->get_roles() as $role ){
			$this->discounts[ $role->name ] = 0;
			$this->batch_size[ $role->name ] = 1;
		}

		return false;
	}


	public function jsonSerialize() {
		return [
			'discounts'  => $this->discounts,
			'batch_size' => $this->batch_size
		];
	}

}