<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 8/3/16
 * Time: 7:43 PM
 */

namespace josh\ww\order;


use josh\ww\data\cart_product;

class validate {

	public static function collect ( array $amounts, array  $products ) {
		$collected = [];
		foreach ( $amounts as $i => $amount ){
			$i = absint( $i );
			$amount = absint( $amount );
			if( isset( $products[ $i ] ) && 0 < absint( $products[ $i ] ) ){
				$collected[ $i ] = new cart_product( (int) $products[ $i ], (int) $amount );
			}
		}

		return $collected;
	}



	public static function check_nonce( $nonce ){
		return wp_verify_nonce( $nonce, 'jww-order-form' );
	}
}