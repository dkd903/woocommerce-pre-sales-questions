<?php

namespace WcPreSalesQuestions\Plugin;

use WcPreSalesQuestions\Plugin\WcPreSalesQuestionsSettings;

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
		// get all the saved settings from the database
		$this->settings = new WcPreSalesQuestionsSettings();

		// add new Pre sales questions tab based on options
		if ( $this->wc_psq_show_here( 'tab' ) ) {
			add_filter( 'woocommerce_product_tabs', array( $this, 'wc_psq_form_tabs' ) );
		}

		/**
		 * add pre sales question form to the product description area
		 * NOTE: meta and excerpt hook into woocommerce_single_product_summary
		 * between 20 and 40, so we will hook the press sales question form in order: 25
		 */
		if ( $this->wc_psq_show_here( 'summary' ) ) {
			add_action( 'woocommerce_single_product_summary', array( $this, 'wc_psq_form_summary' ), 25 );
		}

		// add pre sales questions exit intent form to the cart page
		if ( $this->wc_psq_show_here( 'cart' ) ) {
			add_action( 'woocommerce_after_cart', array( $this, 'wc_psq_exit_form' ) );
		}
		
		// add pre sales questions exit intent form to the checkout page
		if ( $this->wc_psq_show_here( 'checkout' ) ) {
			add_action( 'woocommerce_after_checkout_form', array( $this, 'wc_psq_exit_form' ) );
		}

		// called just before the woocommerce template functions are included
		add_action( 'init', array( $this, 'include_template_functions' ), 20 );
		
		// Ajax endpoints for Storing question form submissions
		add_action( 'wp_ajax_' . WCPSQ_SLUG . '_psq_submissions', array( $this, 'psq_form_submit' ) );		
		add_action( 'wp_ajax_nopriv_' . WCPSQ_SLUG . '_psq_submissions', array( $this, 'psq_form_submit' ) );		

	}

	/**
	 * Override any of the template functions from woocommerce/woocommerce-template.php
	 * with our own template functions file
	 */
	public function include_template_functions() {
		//include( 'woocommerce-template.php' );
	}
	
	/**
	 * Handle Ajax requests for Pre-Sales questions form submissions 
	 */
	public function psq_form_submit() {
		check_ajax_referer( WCPSQ_SLUG . '-nonce-psq-form', 'nonce' );
	}

	/**
	 * Add Pre-Sales questions tabs to product pages.
	 *
	 * @param array $tabs
	 * @return array
	 */
	function wc_psq_form_tabs( $tabs = array() ) {
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
	 * Add Pre-Sales questions form below the product summary
	 */
	function wc_psq_form_summary() {
		global $product, $post;

		// render Presales questions form
		if ( $product ) {
			$this->wc_psq_render_form();
		}
	}


	/**
	 * Add Pre-Sales questions form to the cart page or the checkout page
	 */
	function wc_psq_exit_form() {

		// get the current cart contents
		$cart = WC()->cart->get_cart();
		
		// print the form
		$this->wc_psq_render_form();

	}

	/**
	 * Render the Pre-Sales questions form
	 */
	function wc_psq_render_form() {
		$formdata = array(
			'nonce' => wp_create_nonce( WCPSQ_SLUG . '-nonce-psq-form' )
		);
		require( WCPSQ_DIR_PATH . 'views/wc_psq_form.php' );
	}
	
	/**
	 * Checks whether or not the form should be rendered in the given area
	 * 
	 * @param $area String Name of the area
	 * @return Boolean
	 */
	function wc_psq_show_here( $area = '' ) {
		
		if ( empty( $area ) ) {
			return false;
		}
		
		// get the settings
		$settings = $this->settings->getSettings();
		
		// check if it is the product page
		if ( 'tab' == $area || 'summary' == $area ) {
			
			// check if the 'show on product page' setting is set to yes
			if ( ! empty( $settings['_sop'] ) ) {
			
				// check if the 'show on product page' setting is set to yes
				if ( 'yes' == $settings['_sop'] ) {
				
					// check if the setting is not empty
					if ( ! empty( $settings['_sopw'] ) ) {
					
						// check if the area queried for and the area stored in settings 'show on product page where' are same	
						if ( 'psf' . $area == $settings['_sopw'] ) {
							return true;
						}
					} 
					else {
						
						// if the setting is not available, show psq form in tab area (defaults)
						if ( 'tab' == $area ) {
							return true;
						}
					}
				}
			}	
			
		} else if ( 'cart' == $area  || 'checkout' == $area ) {
			
			// return true if setting 'show_on_cart_checkout' is not available and area is cart
			if ( empty( $settings['_socc'] ) && 'cart' == $area ) {
				return true;
			}
			
			// check if the setting 'show_on_cart_checkout' is not empty
			if ( ! empty( $settings['_socc'] ) ) {
				
				// get the areas from setting
				$areas_from_setting = explode( '::', $settings['_socc'] );
				
				// check if passed area exists in the setting
				if ( in_array( $area, $areas_from_setting ) ) {
					return true;
				}
			}
			
		}
		
		// return false by default
		return false;
		
	}
}