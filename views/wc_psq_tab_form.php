<?php
/**
 * Pre-Sales Questions tab template
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

if ( ! empty( $product ) ): ?>

<form action="">
	<div>
		<label>Name:</label>
		<input type="text" />
	</div>
	<div>
		<label>E-mail:</label>
		<input type="text" />
	</div>
	<div>
		<label>Message:</label>
		<textarea></textarea>
	</div>
	<input type="hidden" name="product_id" value="<?php echo esc_attr( $product->id ) ?>" />
	<button type="submit"></button>
</form>

<?php endif;