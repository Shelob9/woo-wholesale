<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 8/3/16
 * Time: 7:00 PM
 */

namespace josh\ww\order;


use josh\ww\data\collection;
use josh\ww\settings;
use josh\ww\user;

class form {



	protected $view_dir;

	protected $collection;

	/**
	 * @var user
	 */
	protected $user;

	/**
	 * @var settings
	 */
	protected $settings;

	public function __construct( $view_dir, collection $collection, user $user ) {
		$this->view_dir = $view_dir;
		$this->collection = $collection;
		$this->user = $user;
	}


	public function display(){
		WC()->cart->empty_cart();
		$products = $this->collection->get_products();

		$discount = $this->user->discount;
		$batch_size = $this->user->batch_size;
		ob_start();
		include $this->view_dir . '/order-form.php';
		return ob_get_clean();
	}









}