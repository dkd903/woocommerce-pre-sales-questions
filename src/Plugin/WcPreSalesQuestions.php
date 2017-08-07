<?php

namespace WcPreSalesQuestions\Plugin;

use WcPreSalesQuestions\Settings\WcPreSalesQuestionsSettings;

/**
 * The main plugin class.
 *
 * @since      0.1
 * @author     Debjit Saha
 */
class WcPreSalesQuestions {

	private $settings;

	public function __construct() {

		// init the settings page for the plugin
		// new WcPreSalesQuestionsSettings();

		// get all the saved settings from the database
		// $this->settings =

		// add new Pre sales questions tab based on options
		add_filter( 'woocommerce_product_tabs', array( $this, 'wc_psq_tabs' ) );

		/**
		 * add pre sales question form to the product description area
		 * NOTE: meta and excerpt hook into woocommerce_single_product_summary
		 * between 20 and 40, so we will hook the press sales question form in order: 25
		 */
		add_action( 'woocommerce_single_product_summary', array( $this, 'wc_psq_form' ), 25 );

		// add pre sales questions exit intent forms to checkout and cart pages
		add_action( 'woocommerce_after_cart', array( $this, 'wc_psq_exit_form' ) );
		add_action( 'woocommerce_after_checkout_form', array( $this, 'wc_psq_exit_form' ) );

		// called only after woocommerce has finished loading
		add_action( 'woocommerce_init', array( $this, 'woocommerce_loaded' ) );

		// called after all plugins have loaded
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );

		// called just before the woocommerce template functions are included
		add_action( 'init', array( $this, 'include_template_functions' ), 20 );

	}

	/**
	 * Take care of anything that needs woocommerce to be loaded.
	 * For instance, if you need access to the $woocommerce global
	 */
	public function woocommerce_loaded() {
		// ...
	}

	/**
	 * Take care of anything that needs all plugins to be loaded
	 */
	public function plugins_loaded() {
		// ...
	}

	/**
	 * Override any of the template functions from woocommerce/woocommerce-template.php
	 * with our own template functions file
	 */
	public function include_template_functions() {
		//include( 'woocommerce-template.php' );
	}

	/**
	 * Add Pre-Sales questions tabs to product pages.
	 *
	 * @param array $tabs
	 * @return array
	 */
	function wc_psq_tabs( $tabs = array() ) {
		global $product, $post;

		// Presales questions tab
		if ( $product ) {
			$tabs['pre_sales_questions'] = array(
				'title'    => __( 'Pre-Sales Questions', WCPSQ_DOMAIN ),
				'priority' => 120,
				'callback' => array( $this, 'wc_psq_render_form' ),
			);
		}

		return $tabs;
	}

	/**
	 * Add Pre-Sales questions form below the product description
	 */
	function wc_psq_form() {
		global $product, $post;

		// render Presales questions form
		if ( $product ) {
			$this->wc_psq_render_form();
		}
	}

	function wc_psq_exit_form() {

		// get the current cart
		$cart = WC()->cart->get_cart();


	}

	/**
	 * Render the Pre-Sales questions form
	 */
	function wc_psq_render_form() {
		require( WCPSQ_DIR_PATH . 'views/wc_psq_tab_form.php' );
	}
}