<?php
if( ! defined( 'ABSPATH' ) ){
	return;
}

if( ! isset( $roles ) ){
	return;
}

?>
<form id="jww-admin" action="<?php echo esc_url( admin_url() ); ?>" method="POST">

	<table>
		<thead>
			<th>
				<?php esc_html_e( 'Level', 'jww' ); ?>
			</th>
			<th>
				<label id="jww-discount-amount">
					<?php esc_html_e( 'Discount', 'jww' ); ?>
				</label>
			</th>
			<th>
				<label id="jww-discount-batch-size">
					<?php esc_html_e( 'Batch size', 'jww' ); ?>
				</label>
			</th>
		</thead>
		<tbody>
			<?php
			$i = 1;
			/** @var \josh\ww\role\role $role */
			foreach ( $roles as $role ) :

				?>
			<tr>
				<td>
					<?php echo esc_html( $role->label ); ?>
				</td>
				<td>
					<input value="<?php echo esc_attr( $role->discount ); ?>" type="number" min="0" max="100" step="1" aria-labelledby="jww-discount-amount" name="<?php echo esc_attr( 'discount[' . $i . ']' ); ?>" title="<?php esc_attr_e( sprintf( 'Discount for level %s', $role->label ), 'jww' ); ?>" />
				</td>
				<td>
					<input value="<?php echo esc_attr( $role->batch_size ); ?>" type="number" min="0" max="100" step="1" aria-labelledby="jww-discount-amount" name="<?php echo esc_attr( 'batch_size[' . $i . ']' ); ?>" title="<?php esc_attr_e( sprintf( 'Batch size for level %s', $role->label ), 'jww' ); ?>" />
				</td>

			</tr>
			<?php	$i++;
			endforeach;

			?>

		</tbody>

	</table>
	<?php
	wp_nonce_field( 'jww-admin-form', 'jww-admin-save' );
	submit_button( __( 'Save', 'jww' ) );
	?>


</form>
