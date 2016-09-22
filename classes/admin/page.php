<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 8/3/16
 * Time: 5:25 PM
 */

namespace josh\ww\admin;


use josh\ww\settings;
use josh\ww\role\roles;


class page {

	/**
	 * @var settings
	 */
	private $settings;

	protected $view_dir;

	protected $roles;

	public function __construct( settings $settings, roles $roles, $view_dir ) {
		$this->settings = $settings;
		$this->roles = $roles;
		$this->view_dir = $view_dir;

	}

	public function display() {
		add_submenu_page(
			'edit.php?post_type=product',
			__( 'Wholesale Pricing', 'jww'),
			__( 'Wholesale Pricing', 'jww'),
			'manage_options',
			'jww',
			[ $this, 'render' ]
		);
	}


	public function render() {
		$roles = $this->roles->get_roles();
		ob_start();
		include  $this->view_dir . '/admin.php';
		echo ob_get_clean();

	}

}