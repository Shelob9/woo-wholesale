<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 8/4/16
 * Time: 9:25 PM
 */

namespace josh\ww\admin;


use josh\ww\settings;

class save {


	protected $settings;

	protected $slug;

	public function __construct( settings $settings, array  $new_data, $slug ) {
		$this->settings = $settings;
		$this->slug = $slug;
		$this->prepare_new_data( $new_data );
	}

	protected function prepare_new_data( array  $new_data ){
		for( $i = 1; $i <= 3; $i++ ){
			if( isset( $new_data[ $i ] ) ){
				$level = 'jww_level_' . $i;
				$this->settings->set_batch_size( $level, $new_data[$i]->id );
				$this->settings->set_discount( $level, $new_data[$i]->qty );
			}
		}


	}

	public function save(){
		if( update_option( $this->slug, wp_json_encode( $this->settings ) ) ){
			return $this->settings;
		}
	}


}