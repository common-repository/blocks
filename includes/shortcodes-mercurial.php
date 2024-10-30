<?php

if ( ! function_exists( 'blocks_current_year' ) ) {
  function blocks_current_year() { // current year

    $year = date_i18n('Y');
    return $year;

  }
  add_shortcode('year', 'blocks_current_year');
}



/* Mercurial Shortcode to show a module
==================================================== */
if ( ! function_exists( 'mercurial_module_shortcode' ) ) {
  function mercurial_module_shortcode($moduleid) {

    extract(shortcode_atts(array('id' =>'*'),$moduleid));

    return do_shortcode('[et_pb_section global_module="'.$id.'"][/et_pb_section]');

  }
  add_shortcode('mercurial-module', 'mercurial_module_shortcode');
}



function blocks_site_url() { // Site URL

  $url = get_site_url();
  $domain = parse_url( $url, PHP_URL_HOST );
  $site = '<a href="'. $url .'" title="'. $domain .'">'. $domain .'</a>';

  return $site;

}
add_shortcode('site-url', 'blocks_site_url');



function blocks_site_domain() { // Site Domain

  $url = get_site_url();
  $domain = parse_url( $url, PHP_URL_HOST );

  return $domain;

}
add_shortcode('site-domain', 'blocks_site_domain');
