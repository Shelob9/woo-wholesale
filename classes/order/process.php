<?php

namespace josh\ww\order;


use josh\ww\data\cart_product;
use josh\ww\session;


class process extends order {

	protected function hooks(){
		add_action( 'wp_loaded', [ $this, 'add_to_cart' ] );
	}

	public function add_to_cart(  ){
		if ( ! empty( $this->products ) ) {
			add_filter( 'woocommerce_add_cart_item', [ $this, 'set_price' ], 50, 2  );
			/** @var cart_product $product */
			foreach ( $this->products as $product ) {
				\WooCommerce::instance()->cart->add_to_cart( $product->id, $product->qty );
			}
			$this->set_session();
		}
	}

	public function set_price( $cart_item_data, $product_id ){
		if ( $this->should_change( $cart_item_data['product_id'] ) ) {
				$new_price = $this->change_price( $cart_item_data['data']->price );
				$cart_item_data['data']->price = $new_price;
		}

		return $cart_item_data;
	}

	protected function set_session(  ) {
		session::set( $this->products, $this->discount );
	}




}