<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 8/4/16
 * Time: 6:29 PM
 */

namespace josh\ww\role;


use josh\ww\settings;

class roles {


	protected $roles;


	protected $wp_roles;

	protected $settings;

	public function __construct( \WP_Roles $wp_roles ) {
		$this->wp_roles = $wp_roles;


		$this->create_role_objects();
	}

	public function get_roles(){
		return $this->roles;
	}


	protected function create_role_objects(){
		for( $i = 1; $i <= 3; $i++ ){
			$level = 'jww_level_' . $i;
			$this->roles[ $i ] = new role(
				$level,
				__( sprintf( 'Wholesale Level %d', $i ), 'jww' )
			);
		}
	}

	public function add_from_settings( settings $settings ){
		for( $i = 1; $i <= 3; $i++ ){
			/** @var role $role */
			$role = $this->roles[ $i ];
			$role->set_discount( $settings->get_discount( $role->name ));
			$role->set_batch_size( $settings->get_batch_size( $role->name ) );
		}
	}


}