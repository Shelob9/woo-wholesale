<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 8/11/16
 * Time: 7:27 PM
 */

namespace josh\ww;


class session {

	protected static $discount_key = 'jww-discount';

	protected static $products_key = 'jww-products';

	/**
	 * @param array $products Optional. Products to store. If empty, the default, function does nothing
	 * @param int $discount Discount amount
	 */
	public static  function set( array  $products = [], $discount  ){
		if (  ! empty( $products ) ) {
			$session                        = self::get_session();
			$session[ self::$discount_key ] = $discount;
			$session[ self::$products_key ] = wp_json_encode( $products );
		}

	}

	public static function get_discount(){
		$session = self::get_session();
		return $session[ self::$discount_key ];

	}


	public static function get_products(){
		$session                        = self::get_session();
		$products = $session[ self::$products_key ];
		$products = json_decode( $products );
		if( is_array( $products )  ){
			return $products;
		}elseif ( is_object( $products ) ){
			return (array) $products;
		}

		return [];
	}

	/**
	 * @return bool|\WP_Session
	 */
	protected static function get_session() {
		$session = \WP_Session::get_instance();

		return $session;
	}

}