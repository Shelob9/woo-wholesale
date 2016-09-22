<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 8/4/16
 * Time: 6:29 PM
 */

namespace josh\ww\role;


use josh\jww\settings;

class create {

	/**
	 * @var \WP_Roles
	 */
	protected $wp_roles;

	public function __construct( \WP_Roles &$wp_roles ) {
		$this->wp_roles = $wp_roles;

	}

	public function init( roles $roles ){
		foreach( $roles->get_roles() as $role ){
			$this->wp_roles->remove_role( $role->name );
			$this->clone_role( $role, 'customer' );
		}

	}


	public function clone_role( role $role, $clone ){
		/** @var \WP_Role $clone_role */
		$clone_role = $this->wp_roles->get_role( $clone );
		if( null != $clone_role ){
			$this->wp_roles->role_names[ $role->name ] = $role->label;
			$this->wp_roles->add_role( $role->name, $role->label, $clone_role->capabilities );
			$this->wp_roles->add_cap( $role->name, 'jww_wholesale' );
			$this->wp_roles->add_cap( $role->name, $role->name );

		}

	}

}