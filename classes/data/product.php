<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 8/3/16
 * Time: 5:10 PM
 */

namespace josh\ww\data;


class product {

	protected $cache_group = 'jww-product-cache';
	public $id;

	public $price;

	public $sku;

	public $name;

	public $link;

	public function __construct( $id ) {
		$this->id = $id;
		$this->link = get_permalink( $id );

	}

	public function set(){
		$this->set_from_cache();
		if( empty( $this->sku ) || empty( $this->price ) ){
			$this->set_by_woo();
		}

	}

	public function apply_discount( $discount ){
		$this->price =  $this->price - ( $this->price * ( $discount / 100 ) );
	}

	public function get_quantity_in_cart( $cart_contents ){
		$quantity = 0;
		if( ! empty( $cart_contents ) ){
			foreach( $cart_contents as $cart_content ) {
				if( isset( $cart_content ['product_id' ] ) && $this->id == $cart_content[ 'product_id' ] ){
					$quantity = $quantity + $cart_content[ 'quantity' ];
				}
			}

		}

		return $quantity;
	}

	protected function set_by_woo(){
		$product =  new \WC_Product( $this->id );
		$this->price = $product->get_price();
		$this->sku = $product->get_sku();
		$this->name = $product->get_formatted_name();
		if( ! empty( $this->sku ) && ! empty( $this->price ) ){
			$this->cache();
		}

	}

	protected function set_from_cache(){
		if( ! empty( $cached  = wp_cache_get( $this->cache_key(), $this->cache_group ) ) ){
			$this->sku = $cached[ 'sku' ];
			$this->price = $cached[ 'price' ];
			$this->name = $cached[ 'name' ];
		}
	}

	protected function cache(){
		wp_cache_set( $this->cache_key(), $this->toArray(), $this->cache_group, HOUR_IN_SECONDS );
	}

	public function toArray(){
		return [
			'id' => $this->id,
			'price' => $this->price,
			'sku' => $this->sku,
			'name' => $this->name,
		];
	}


	protected function cache_key(){
		return md5( get_class( $this ) . $this->id );
	}
}