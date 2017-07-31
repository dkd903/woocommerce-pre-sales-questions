<?php

namespace WcPreSalesQuestions\Plugin;

/**
 * The settings plugin class.
 *
 * @since      0.1
 * @author     Debjit Saha
 */
class WcPreSalesQuestionsSettings {

	public $extensions;

	/**
	 * Constructor
	 *
	 * @return   none
	 */
	function __construct( $extensions = array() ) {
		if ( !empty( $extensions ) ) {
			$this->extensions = $extensions;
		}
		$this->init();
	}

	/**
	 * Add the necessary hooks and filters for admin
	 */
	function init() {
		// settings page
		add_action( 'admin_menu', array( $this, 'wpfomo_menu' ) );

		// admin notices
		add_action( 'admin_notices', array( $this, 'dashboard_notices' ) );

		// dismiss welcome notice ajax
		add_action( 'wp_ajax_' . WPFOMO_SLUG . '_dismiss_dashboard_notices', array( $this, 'dismiss_dashboard_notices' ) );

		// Add settings link in plugin list
		add_filter( 'plugin_action_links_' . plugin_basename( WPFOMO_PATH ), array( $this, 'settings_link' ) );
	}

	/**
	 * Add the options page
	 */
	function wpfomo_menu() {
		add_options_page(
			WPFOMO_PLUGIN_NAME,
			WPFOMO_PLUGIN_NAME,
			'manage_options',
			WPFOMO_SLUG,
			array(
				$this,
				'settings_page'
			)
		);
	}

	function settings_page() {
		// check nonce to process form data
		if ( isset( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'wpfomo-settings-nonce' ) ) {
			// check if form submitted
			if ( isset( $_REQUEST['submit'] ) ) {
				// check if all fields filled
				if ( isset( $_REQUEST['_sfa'] ) && !empty( $_REQUEST['_sfa'] ) &&
					 isset( $_REQUEST['_ha'] ) && !empty( $_REQUEST['_ha'] ) &&
					  isset( $_REQUEST['_ii'] ) && !empty( $_REQUEST['_ii'] ) &&
					   isset( $_REQUEST['_ps'] ) && !empty( $_REQUEST['_ps'] ) &&
					    isset( $_REQUEST['_ph'] ) && !empty( $_REQUEST['_ph'] ) &&
					     isset( $_REQUEST['_ph'] ) && !empty( $_REQUEST['_ph'] ) &&
					     isset( $_REQUEST['_rn'] ) && !empty( $_REQUEST['_rn'] ) ) {

					// process the select2 values
					$_REQUEST['_view_optin_not_on'] = implode( ',', array_unique( explode( ',', $_REQUEST['_view_optin_not_on'] ) ) );
					$_REQUEST['_view_optin_on'] = implode( ',', array_unique( explode( ',', $_REQUEST['_view_optin_on'] ) ) );

					//update_option
					$settings = array(
							'_sfa' => sanitize_text_field( $_REQUEST['_sfa'] ),
							'_ha' => sanitize_text_field( $_REQUEST['_ha'] ),
							'_ii' => sanitize_text_field( $_REQUEST['_ii'] ),
							'_ps' => sanitize_text_field( $_REQUEST['_ps'] ),
							'_ph' => sanitize_text_field( $_REQUEST['_ph'] ),
							'_pv' => sanitize_text_field( $_REQUEST['_pv'] ),
							'_rn' => sanitize_text_field( $_REQUEST['_rn'] ),
							'_view_optin_not_on' => sanitize_text_field( $_REQUEST['_view_optin_not_on'] ),
							'_view_optin_on' => sanitize_text_field( $_REQUEST['_view_optin_on'] ),
							'_view_optin_user' => sanitize_text_field( $_REQUEST['_view_optin_user'] )
					);
					update_option( WPFOMO_SLUG, $settings );

					// update actions for extensions
					foreach ( $this->extensions as $extension ) {
						do_action( 'wp_fomo_magic_update_settings_' . $extension->name );
					}

				}
			}
		}

		// get the settings
		$settings = get_option( WPFOMO_SLUG );

		$all_posts = array();
		// get the posts and pages
		$posts = get_posts( array( 'numberposts' => -1, 'post_status' => 'publish', 'post_type' => array( 'post', 'page' ) ) );
		foreach( $posts as $post ) {
			$all_posts[$post->ID] = $post->post_title;
		}

		// load the template
		require WPFOMO_DIR_PATH . 'views/wp_fomo_settings.php';
	}

	/**
	 * Show relevant notices for the plugin
	 */
	function dashboard_notices() {
		global $pagenow;

		if ( !get_option( WPFOMO_SLUG . '_welcome' ) ) {
			if ( ! ( $pagenow == 'options-general.php' && isset( $_GET['page'] ) && $_GET['page'] == WPFOMO_SLUG ) ) {
				$setting_page = admin_url( 'options-general.php?page=' . WPFOMO_SLUG );
				$ajax_url = admin_url( 'admin-ajax.php' );
				// load the notices view
				include WPFOMO_DIR_PATH . 'views/wp_fomo_activate_plugin_welcome.php';
			}
		}
	}

    /**
     * Dismiss the welcome notice for the plugin
     */
    function dismiss_dashboard_notices() {
    	check_ajax_referer( WPFOMO_SLUG . '-nonce', 'nonce' );
        // user has dismissed the welcome notice
        update_option( WPFOMO_SLUG . '_welcome', 1 );
        exit;
    }

	/**
	 * Adds a settings link to the WP PLugin listing page
	 */
	function settings_link( $links ) {
		$settings_link = '<a href="' . esc_url( admin_url( 'options-general.php?page=' . WPFOMO_SLUG ) ) . '">' . esc_html__( 'Settings' ) . '</a>';
		array_push( $links, $settings_link );
		return $links;
	}

}