<?php

namespace WcPreSalesQuestions\Plugin;

/**
 * The settings plugin class.
 *
 * @since      0.1
 * @author     Debjit Saha
 */
class WcPreSalesQuestionsSettings {

	// TODO:you should add this exit intent to your pricing page as well
	// choose your pricing page

	public $settings;

	/**
	 * Constructor
	 *
	 * @return   none
	 */
	function __construct( $extensions = array() ) {
		$this->init();
	}
	
	/**
	 * Getters and setters for settings
	 */
	 function getSettings() {
	 	return $this->settings;
	 } 
	 
	 function setSettings( $options ) {
	 	$this->settings = $options;
	 } 	 

	/**
	 * Add the necessary hooks and filters for admin
	 */
	function init() {
		
		$this->setSettings( get_option( WCPSQ_SLUG ) );

		// admin notices
		add_action( 'admin_notices', array( $this, 'dashboard_notices' ) );

		// dismiss welcome notice ajax
		add_action( 'wp_ajax_' . WCPSQ_SLUG . '_dismiss_dashboard_notices', array( $this, 'dismiss_dashboard_notices' ) );

		// Add settings link in plugin list
		add_filter( 'plugin_action_links_' . plugin_basename( WCPSQ_DIR ), array( $this, 'settings_link' ) );

		// settings page
		add_action( 'admin_menu', array( $this, 'menu' ) );
		
		// enqueue styles
		add_action( 'admin_enqueue_scripts', array( $this, 'custom_admin_style' ) );		
	}

	/**
	 * Enqueue custom stylesheets in the WordPress admin.
	 */
	function custom_admin_style() {
        wp_enqueue_style( WCPSQ_SLUG . '_admin', WCPSQ_DIR_URL . 'assets/css/admin-style.css', array(), WCPSQ_VERSION );
	}
	
	/**
	 * Add the options page
	 */
	function menu() {
		add_submenu_page( 
			'woocommerce', 
			WCPSQ_PLUGIN_NAME_BASE, 
			WCPSQ_PLUGIN_NAME_BASE, 
			'manage_options', 
			WCPSQ_SLUG, 
			array(
				$this,
				'settings_page'
			) 
		);
	}

	function settings_page() {
		// check nonce to process form data
		if ( isset( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], WCPSQ_SLUG . '-settings-nonce' ) ) {
			// check if form submitted
			if ( isset( $_REQUEST['submit'] ) && ! empty( $_REQUEST['action'] ) ) {
				// check if all fields filled
				if ( isset( $_REQUEST['_sop'] ) && !empty( $_REQUEST['_sop'] ) &&
					 isset( $_REQUEST['_sopw'] ) && !empty( $_REQUEST['_sopw'] ) &&
					  isset( $_REQUEST['_socc'] ) && !empty( $_REQUEST['_socc'] ) ) {

					//update_option
					$settings = array(
							'_sop' => sanitize_text_field( $_REQUEST['_sop'] ),
							'_sopw' => sanitize_text_field( $_REQUEST['_sopw'] ),
							'_socc' => sanitize_text_field( $_REQUEST['_socc'] ),
							'_psq_form_title' => sanitize_text_field( $_REQUEST['_psq_form_title'] )
					);
					update_option( WCPSQ_SLUG, $settings );

				}
			}
		}

		// set the settings
		$this->setSettings( get_option( WCPSQ_SLUG ) );

		$all_posts = array();
		// get the posts and pages
		$posts = get_posts( array( 'numberposts' => -1, 'post_status' => 'publish', 'post_type' => 'page' ) );
		foreach( $posts as $post ) {
			if ( 'checkout' == strtolower( $post->post_title ) || 'cart' == strtolower( $post->post_title ) ) {
				continue;	
			}
			// if ( $post->content ) contains [woocommerce_cart] or [woocommerce_checkout]
			$all_posts[$post->ID] = $post->post_title;
		}

		// load the template
		require WCPSQ_DIR_PATH . 'views/wc_psq_settings.php';
	}

	/**
	 * Show relevant notices for the plugin
	 */
	function dashboard_notices() {
		global $pagenow;

		if ( !get_option( WCPSQ_SLUG . '_welcome' ) ) {
			if ( ! ( $pagenow == 'admin.php' && isset( $_GET['page'] ) && $_GET['page'] == WCPSQ_SLUG ) ) {
				$setting_page = admin_url( 'admin.php?page=' . WCPSQ_SLUG );
				$ajax_url = admin_url( 'admin-ajax.php' );
				// load the notices view
				include WCPSQ_DIR_PATH . 'views/wc_psq_plugin_activated_welcome.php';
			}
		}
	}

    /**
     * Dismiss the settings nod welcome notice for this plugin
     */
    function dismiss_dashboard_notices() {
    	check_ajax_referer( WCPSQ_SLUG . '-nonce', 'nonce' );
        // user has dismissed the welcome notice
        update_option( WCPSQ_SLUG . '_welcome', 1 );
        exit;
    }

	/**
	 * Add a link to the settings page to the WP PLugin listing page
	 */
	function settings_link( $links ) {
		$settings_link = '<a href="' . esc_url( admin_url( 'options-general.php?page=' . WCPSQ_SLUG ) ) . '">' . esc_html__( 'Settings' ) . '</a>';
		array_push( $links, $settings_link );
		return $links;
	}

}