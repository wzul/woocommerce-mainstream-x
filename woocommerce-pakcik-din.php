<?php

/**
 *
 * Plugin Name: WooCommerce Pakcik Din
 * Plugin URI: https://github.com/wzul/woocommerce-pakcik-din/
 * Description: Customize WooCommerce Shopping Experience
 * Version: 1.0.0
 * Author: Wan Zulkarnain
 * Author URI: https://www.wanzul.net
 *
 * Copyright: © 2023 Wan Zulkarnain
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 */

if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access directly.

class Woocommerce_Pakcik_Din {

  private static $_instance;

  public static function get_instance() {
    if ( self::$_instance == null ) {
      self::$_instance = new self();
    }

    return self::$_instance;
  }

  public function __construct() {
    $this->define();
    $this->includes();
    $this->add_filters();
    // $this->add_actions();
  }

  public function define() {
    define( 'WC_PD_VERSION' , 'v1.0.0' );
    define( 'WC_PD_FILE', __FILE__ );
    define( 'WC_PD_BASENAME', plugin_basename( WC_PD_FILE ) );
    define( 'WC_PD_SLUG', 'wc_pd' );
  }

  public function includes() {
    $includes_dir = plugin_dir_path( WC_PD_FILE ) . 'includes/';
    include $includes_dir . 'codestar-framework/classes/setup.class.php';

    if ( is_admin() ){
      include $includes_dir . 'admin/global-settings.php';
    }
  }

  public function add_filters() {
    add_filter( 'plugin_action_links_' . WC_PD_BASENAME, array( $this, 'setting_link' ) );

    // https://stackoverflow.com/questions/32962653/how-to-skip-cart-page-on-woocomerce-for-certain-products-only
    add_filter( 'woocommerce_add_to_cart_redirect', array( $this, 'redirect_checkout' ) );
    // https://www.businessbloomer.com/woocommerce-allow-1-product-cart/
    add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'only_one_in_cart' ), 10, 3 );
  }

  public function setting_link( $links ) {
    $new_links = array(
      'settings' => sprintf(
        '<a href="%1$s">%2$s</a>', admin_url('admin.php?page=wc_pd'), esc_html__('Settings', 'wc_pd')
      )
    );

    return array_merge( $new_links, $links );
  }

  public function redirect_checkout() {
    if ( !isset( $_POST['add-to-cart'] ) ) {
      return;
    }

    $product_ids = $this->get_product_ids();


    $product_id = (int) apply_filters( 'woocommerce_add_to_cart_product_id', $_POST['add-to-cart'] );

    if ( in_array( $product_id, $product_ids ) ) {
        return wc_get_checkout_url();
        exit;
    } else {
        return wc_get_cart_url();
        exit;
    }
  }

  public function only_one_in_cart( $passed, $product_id, $quantity ) {

    $product_ids = $this->get_product_ids();

    if ( in_array( $product_id, $product_ids ) ) {
      wc_empty_cart();
    }

    $carts = WC()->cart->get_cart();

    foreach ( $carts as $cart ) {
      if ( in_array( $cart['product_id'], $product_ids ) ) {
        wc_empty_cart();
        break;
      }
    }

    return $passed;
  }

  public function get_product_ids() {
    $options  = get_option( WC_PD_SLUG, array() );

    if ( empty( $options ) || !isset($options['product_ids'] ) || empty( $options['product_ids'] ) ) {
      exit('aik');
      return $options;
    }

    $options_array = explode( ',', $options['product_ids'] );

    for( $i=0; $i < sizeof($options_array); $i++ ) {
      $options_array[$i] = trim( $options_array[$i] );
    }

    return $options_array;
  }
}

Woocommerce_Pakcik_Din::get_instance();