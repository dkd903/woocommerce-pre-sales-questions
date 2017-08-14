<?php
/**
 * Pre-Sales Questions tab template
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>

<div class="wcpsq-container">
	<form name="wcpsq_form" id="wcpsq_form" action="<?php echo admin_url( 'admin-ajax.php' ) ?>" onSubmit="return false;">
		<div class="row">
			<div class="left"><label>Name:</label></div>
			<div class="right"><input name="name" type="text" /></div>
		</div>
		<div class="row">
			<div class="left"><label>E-mail:</label></div>
			<div class="right"><input name="email" type="text" /></div>
		</div>
		<div class="row">
			<div class="left"><label>Message:</label></div>
			<div class="right"><textarea name="message"></textarea></div>
		</div>
		<div class="row">
			<?php if ( ! empty( $product ) ): ?>
			<input type="hidden" name="product_id" value="<?php echo esc_attr( $product->id ) ?>" />
			<?php endif; ?>
			<input type="hidden" name="nonce" id="psq_nonce" value="<?php echo esc_attr( $formdata['nonce'] ); ?>" />
			<input type="hidden" name="action" value="<?php echo esc_attr( WCPSQ_SLUG . '_psq_submissions' ); ?>" />
			<button type="submit">Submit</button>
		</div>
	</form>
</div>