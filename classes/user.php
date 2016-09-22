<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 8/4/16
 * Time: 10:00 PM
 */

namespace josh\ww;


class user {

	public $batch_size;

	public $discount;

	/**
	 * @var settings
	 */
	protected $settings;

	public function __construct( settings $settings ) {
		$this->settings = $settings;
		$this->find_levels();
	}

	protected function find_level(){

		for( $i = 1; $i <= 3; $i++ ) {
			$level = 'jww_level_' . $i;
			if ( current_user_can( $level ) ) {
				return $level;
			}
		}
		if( current_user_can( 'manage_options' ) ){
			return 'jww_level_1';
		}

		return '';
	}

	protected function find_levels() {
		$level      = $this->find_level();
		$this->batch_size = $this->settings->get_batch_size( $level );
		$this->discount   = $this->settings->get_discount( $level );

	}


}