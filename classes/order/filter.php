<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 8/3/16
 * Time: 8:01 PM
 */

namespace josh\ww\order;


class filter  extends order {

	protected function hooks(){
		add_action( 'woocommerce_before_calculate_totals', [ $this, 'cart_prices' ] );
	}

	public function cart_prices( $cart_object ){
		foreach ( $cart_object->cart_contents as $key => $value ) {
			$value['data']->price = $this->set_price( $value['data']->price, $value['data'] );
		}

	}


	/**
	 * @param $price
	 * @param \WC_Product $product
	 *
	 * @return mixed
	 */
	public function set_price( $price, $product ){
		if( $this->should_change(  $product->id ) ){
			$base_price = $product->price;
			$price = $this->change_price( $base_price );
		}


		return  $price;

	}






}