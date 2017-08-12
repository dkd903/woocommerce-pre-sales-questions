<div class="wrap">
	<h1><?php echo WCPSQ_PLUGIN_NAME; ?></h1>
	<div><?php echo esc_html__( '', WCPSQ_DOMAIN ); ?></div>
	<form method="post" action="admin.php?page=<?php echo WCPSQ_SLUG; ?>" novalidate="novalidate">
		<input type="hidden" name="action" value="update">
		<?php wp_nonce_field( WCPSQ_SLUG . '-settings-nonce' ); ?>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label for="show_on_product"><?php echo __( 'Show the form on individual product page?', WCPSQ_DOMAIN ); ?></label></th>
					<td>
						<select name="_sop" id="show_on_product">
							<option value="yes" <?php if ( $this->settings['_sop'] == 'yes' ) { echo 'selected'; } ?>><?php echo __( 'Yes', WCPSQ_DOMAIN ); ?></option>
							<option value="no" <?php if ( $this->settings['_sop'] == 'no' || empty( $this->settings['_sop'] ) ) { echo 'selected'; } ?>><?php echo __( 'No', WCPSQ_DOMAIN ); ?></option>
						</select>
						<p class="description" id="tagline-description"><?php echo __( 'Whether or not the pre-sales form should be shown on individual product pages?', WCPSQ_DOMAIN ); ?></p>
					</td>
				</tr>
				<tr <?php if ( $this->settings['_sop'] != 'yes' ) { echo 'id="row-show_on_product_where"'; } ?>>
					<th scope="row"><label for="show_on_product_where"><?php echo __( 'Where in the product page should the form be shown?', WCPSQ_DOMAIN ); ?></label></th>
					<td>
						<select name="_sopw" id="show_on_product_where">
							<option value="psftab" <?php if ( $this->settings['_sopw'] == 'psftab' || empty( $this->settings['_sopw'] ) ) { echo 'selected'; } ?>><?php echo __( 'In a new Product Tab', WCPSQ_DOMAIN ); ?></option>
							<option value="psfsummary" <?php if ( $this->settings['_sopw'] == 'psfsummary' ) { echo 'selected'; } ?>><?php echo __( 'Below Product Description', WCPSQ_DOMAIN ); ?></option>
						</select>
						<p class="description" id="tagline-description"><?php echo __( 'Where should the pre-sales questions form be shown on the products page?', WCPSQ_DOMAIN ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="show_on_cart_checkout"><?php echo __( 'Show form on Cart or Checkout Page?', WCPSQ_DOMAIN ); ?></label></th>
					<td>
						<select name="_socc" id="show_on_cart_checkout">
							<option value="cart::checkout" <?php if ( $this->settings['_socc'] == 'cart::checkout' ) { echo 'selected'; } ?>><?php echo __( 'Show on both Cart and Checkout Pages', WCPSQ_DOMAIN ); ?></option>
							<option value="cart" <?php if ( $this->settings['_socc'] == 'cart' || empty( $this->settings['_socc'] ) ) { echo 'selected'; } ?>><?php echo __( 'Show only on Cart Page', WCPSQ_DOMAIN ); ?></option>
							<option value="checkout" <?php if ( $this->settings['_socc'] == 'checkout' ) { echo 'selected'; } ?>><?php echo __( 'Show only on Checkout Page', WCPSQ_DOMAIN ); ?></option>
							<option value="none" <?php if ( $this->settings['_socc'] == 'none' ) { echo 'selected'; } ?>><?php echo __( 'Hide on both Cart and Checkout Pages', WCPSQ_DOMAIN ); ?></option>
						</select>
						<p class="description" id="tagline-description"><?php echo __( 'The pre-sales form is added to the Cart or Checkout page based on ', WCPSQ_DOMAIN ); ?></p>
					</td>
				</tr>	
				<tr>
					<th scope="row"><label for="psq_form_title"><?php echo __( 'Enter form title', WCPSQ_DOMAIN ); ?></label></th>
					<td>
						<input name="_psq_form_title" type="text" id="psq_form_title" value="<?php echo esc_attr( $this->settings['_psq_form_title'] ); ?>" class="" />
						<p class="description" id="tagline-description"><?php echo __( 'What should the form title read? Leave empty to show a default title', WCPSQ_DOMAIN ); ?></p>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
		</p>
	</form>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($){
		$( 'select#show_on_product' ).on( 'change', function(){
			$optRow = $( '#row-show_on_product_where' );
			if ( this.value == 'yes' ) { 
				$optRow.show( 'slow' );
			} else { 
				$optRow.hide( 'slow' );
			}
		} );
	});
</script>