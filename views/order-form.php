<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 8/3/16
 * Time: 5:32 PM
 */
if( ! defined( 'ABSPATH' ) ){
	exit;
}

if( ! isset( $products ) || ! isset( $discount ) || ! isset( $batch_size ) ){
	if (  ! headers_sent() ) {
		$url = remove_query_arg( 'wholesale' );
		wp_safe_redirect( $url );
		exit;
	}
	return;
}

$url =  wc_get_checkout_url();

?>


<form id="jww-order-form" method="post" action="<?php echo esc_url( $url ); ?>">
	<table>


		<thead>
			<tr>

				<th><?php esc_html_e( 'Product', 'jww' ); ?></th>
				<th><?php esc_html_e( 'Wholesale Price', 'jww' ); ?></th>
				<th>
					<?php esc_html_e( 'SKU', 'jww' ); ?>
				</th>
				<th>
					<?php esc_html_e( 'Number To Order', 'jww' ); ?>
				</th>
			</tr>
		</thead>
		<tbody>
	<?php
	$contents = WC()->cart->cart_contents;
	$i= 0;
	/** @var \josh\ww\data\product $product */
	foreach( $products as $product ) :
		$product->set();
		$product->apply_discount( $discount );
		$id_attr = 'jpww-product-' . $product->id;
		$quantity = $product->get_quantity_in_cart( $contents );

	?>

			<tr>

				<td>
					<?php printf( '<a href="%s" title="%s" target="_blank">%s</a>', esc_url( $product->link ), esc_attr__( 'View Product Page', 'jww' ), esc_html( $product->name )  ); ?>
				</td>
				<td>
					<?php echo esc_html( $product->price ); ?>
				</td>
				<td>
					<?php echo esc_html( $product->sku ); ?>
				</td>
				<td>
					<input type="number" name="<?php echo esc_attr( 'jww-amount[' . $i .']' ); ?>" min="0" step="<?php echo intval( $batch_size ); ?>" id="<?php echo esc_attr( $id_attr ); ?>" value="<?php echo esc_attr( $quantity ); ?>" />
					<input type="hidden" name="<?php echo esc_attr( 'jww-product[' . $i .']' ); ?>" value="<?php echo esc_attr( $product->id ); ?>" />
				</td>

			</tr>


	<?php
		$i++;
		endforeach;
		wp_nonce_field( 'jww-order-form', 'jww-add-cart' );
	?>
		</tbody>
	</table>

	<button type="submit" class="pure-button pure-button-primary">
		<?php esc_html_e( 'Add To Cart', 'jpww'  ); ?>
	</button>

</form>
