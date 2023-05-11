<?php

$slug = WC_PD_SLUG;

CSF_Setup::createOptions( $slug, array(
  'framework_title' => __( 'WooCommerce Pakcik Din', 'wc_pd' ),

  'menu_title'  => __( 'Pakcik Din Settings', 'wc_pd' ),
  'menu_slug'   => 'wc_pd',
  'menu_type'   => 'submenu',
  'menu_parent' => 'woocommerce',
  'footer_text' => sprintf( __( 'WooCommerce Pakcik Din %s', 'wc_pd' ) , WC_PD_VERSION ),
  'theme'       => 'light',
) );

$credentials_global_fields = array(
  array(
    'type'    => 'subheading',
    'content' => 'Enter product ids',
  ),
  array(
    'id'    => 'product_ids',
    'type'  => 'text',
    'title' => __( 'Product IDs', 'wc_pd' ),
    'desc'  => __( 'Enter your Product IDs, separated by comma.', 'wc_pd' ),
    'help'  => __( 'Any product id set here will become the only product available in cart and directly goes to checkout.', 'wc_pd' ),
  ));

CSF_Setup::createSection( $slug, array(
  'id'    => 'global-configuration',
  'title' => __( 'Global Configuration', 'wc_pd' ),
  'icon'  => 'fa fa-home',
) );

CSF_Setup::createSection( $slug, array(
  'parent'      => 'global-configuration',
  'id'          => 'woocommerce',
  'title'       => __( 'WooCommerce', 'wc_pd' ),
  'description' => __( 'Configure the product you want to skip cart.', 'wc_pd' ),
  'fields'      => $credentials_global_fields,
) );
