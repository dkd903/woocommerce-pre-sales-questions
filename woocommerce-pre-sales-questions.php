<?php
/*
Plugin Name: WooCommerce Pre-Sales Questions
Plugin URI:
Description: WooCommerce Pre-Sales Questions
Author: Debjit
Author URI: https://digitizor.com
Version: 1.0.1

Copyright: © 2017
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

require 'autoload-php-fig-psr4.php';

use WcPreSalesQuestions\Plugin\WcPreSalesQuestions;

/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	// common plugin defines
	define( 'WCPSQ_DIR', dirname( plugin_basename( __FILE__ ) ) );
	define( 'WCPSQ_DOMAIN', 'wc_psq' );
	define( 'WCPSQ_DIR_PATH', plugin_dir_path( __FILE__ ) );
	define( 'WCPSQ_PATH', __FILE__ );
	define( 'WCPSQ_PLUGIN_NAME' , 'WooCommerce Pre-Sales Questions' );
	define( 'WCPSQ_SLUG' , 'wcpsq' );
	define( 'WCPSQ_VERSION' , '1.0.1' );

	// Localization
	load_plugin_textdomain( WCPSQ_DOMAIN, false, WCPSQ_DIR . '/languages' );

	// finally instantiate our plugin class and add it to the set of globals
	$GLOBALS['wc_pre_sales_questions'] = new WcPreSalesQuestions();
}