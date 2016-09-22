<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 8/4/16
 * Time: 9:10 PM
 */

namespace josh\ww\admin;


class menu {

	private $page;

	public function __construct( page $page ) {
		$this->page = $page;
	}


	public function init(){
		add_action( 'admin_menu', [ $this->page, 'display' ] );
	}
}