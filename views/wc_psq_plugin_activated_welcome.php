<div class="notice notice-success is-dismissible <?php echo WCPSQ_SLUG; ?>-notice-welcome">
	<p>
		<?php
			printf( __( 'Thanks for installing %1$s. <a href="%2$s">Click here</a> to configure the plugin.', WCPSQ_DOMAIN ), WCPSQ_PLUGIN_NAME, esc_url( $setting_page ) ); 
		?>
	</p>
</div>
<script type="text/javascript">
	jQuery(document).ready( function($) {
		$(document).on( 'click', '.<?php echo WCPSQ_SLUG; ?>-notice-welcome button.notice-dismiss', function( event ) {
			event.preventDefault();
			$.post( '<?php echo esc_url( $ajax_url ); ?>', {
				action: '<?php echo WCPSQ_SLUG . '_dismiss_dashboard_notices'; ?>',
				nonce: '<?php echo wp_create_nonce( WCPSQ_SLUG . '-nonce' ); ?>'
			});
			$('.<?php echo WCPSQ_SLUG; ?>-notice-welcome').remove();
		});
	});
</script>