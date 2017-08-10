<?php
/**
 * Pre-Sales Questions tab template
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

if ( ! empty( $product ) ): ?>

<div class="wcpsq-container">
	<form action="">
		<div class="row">
			<div class="left"><label>Name:</label></div>
			<div class="right"><input type="text" /></div>
		</div>
		<div>
			<div class="left"><label>E-mail:</label></div>
			<div class="right"><input type="text" /></div>
		</div>
		<div>
			<div class="left"><label>Message:</label></div>
			<div class="right"><textarea></textarea></div>
		</div>
		<input type="hidden" name="product_id" value="<?php echo esc_attr( $product->id ) ?>" />
		<button type="submit">Submit</button>
	</form>
</div>
<?php endif;