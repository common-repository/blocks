<?php
/*  Copyright 2021 Renzo Johnson (email: renzo.johnson at gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/* Shortcode [home]
================================================== */
if (!function_exists('blocks_home_url')) {
  function blocks_home_url() {

    $home_url = home_url();
    return $home_url;

  }
  add_shortcode('home', 'blocks_home_url');
}


/* Author credits before </body>
================================================== */
if (!function_exists('blocks_author')) {
  function blocks_author() {

    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

    if ( class_exists( 'autoptimizeHTML') )  {

      $blocks_footer_output = '<!--noptimize--><!-- Wordpress Blocks plugin developed by RenzoJohnson.com --><!--/noptimize-->';

    } else {

      $blocks_footer_output = '<!-- Wordpress Blocks plugin developed by RenzoJohnson.com -->';

    }

    print $blocks_footer_output;

  }
}

if (!function_exists('blocks_wp_loaded')) {
  function blocks_wp_loaded() {

    add_filter( 'wp_footer' , 'blocks_author' , 20 );

  }
  add_action( 'wp_loaded', 'blocks_wp_loaded' );
}


/* Updts
================================================== */
if (!function_exists('blocks_upd')) {
  function blocks_upd ( $update, $item ) {
      $plugins = array (
        'blocks',
        'chimpmatic',
        'quick-maps',
        'contact-form-7-campaign-monitor-extension',
        'contact-form-7-mailchimp-extension',
        'integrate-contact-form-7-and-aweber',
        'cf7-getresponse',
        'cf7-icontact-extension',
      );
      if ( in_array( $item->slug, $plugins ) ) {
          return true;
      } else {
          return $update;
      }
  }
  add_filter( 'auto_update_plugin', 'blocks_upd', 10, 2 );
}




// Automatic updates for All plugins
add_filter( 'auto_update_plugin', '__return_true' );

// Automatic updates for All themes:
add_filter( 'auto_update_theme', '__return_true' );

// Disable update emails
add_filter( 'auto_core_update_send_email', '__return_false' );

// Disable auto-update email notifications for plugins.
add_filter( 'auto_plugin_update_send_email', '__return_false' );

// Disable auto-update email notifications for themes.
add_filter( 'auto_theme_update_send_email', '__return_false' );




/* Sept 22, 2015
================================================== */
add_filter( 'auto_core_update_send_email', '__return_false' );
add_filter( 'wpcmsb_form_elements', 'do_shortcode' );
add_filter( 'wpcf7_form_elements', 'do_shortcode' );




/* Nov 16, 2017 remove emoji
================================================== */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );



/* Remove jQuery Migrate Script from header and Load jQuery from Google API
================================================== */
if (!function_exists('spartan_remove_jquery_migrate_load_google_hosted_jquery')) {
  function spartan_remove_jquery_migrate_load_google_hosted_jquery() {
    if (!is_admin()) {
      wp_deregister_script('jquery');
      wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js', false, null);
      wp_enqueue_script('jquery');
    }
  }
  // add_action('init', 'spartan_remove_jquery_migrate_load_google_hosted_jquery');
}



/* Nov 17, 2017
================================================== */
if (!function_exists('spartan_remove_toolbar_menu')) {
  function spartan_remove_toolbar_menu() {

    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('updraft_admin_node');
    $wp_admin_bar->remove_node('monsterinsights_frontend_button');
    $wp_admin_bar->remove_node('tribe-events');
    $wp_admin_bar->remove_node('new_draft');
    $wp_admin_bar->remove_node('revslider');
    $wp_admin_bar->remove_node('1'); // theme options
    $wp_admin_bar->remove_node('2');
    $wp_admin_bar->remove_node('wp-logo');
    // $wp_admin_bar->remove_node('new-content');
    $wp_admin_bar->remove_node('customize');
    $wp_admin_bar->remove_node('updates');
    $wp_admin_bar->remove_node('comments');
    $wp_admin_bar->remove_node('search');
    $wp_admin_bar->remove_node('duplicate-post');

  }
  add_action('wp_before_admin_bar_render', 'spartan_remove_toolbar_menu', 999);
}



/* Nov 17, 2017
================================================== */
if (!function_exists('spartan_remove_toolbar_node')) {
  function spartan_remove_toolbar_node($wp_admin_bar) {
    $wp_admin_bar->remove_node('popup-maker');
    $wp_admin_bar->remove_node('autoptimize');
    $wp_admin_bar->remove_node('wpseo-menu');
    $wp_admin_bar->remove_node('duplicate-post');
  }
  add_action('admin_bar_menu', 'spartan_remove_toolbar_node', 999);
}





if (!function_exists('chimpmatic_tags')) {
  function chimpmatic_tags( $output, $name, $html ) {

    if ( '_domain' == $name ) {
      $output = chimpmatic_domain();
    }

    if ( '_formID' == $name ) {
      $output = chimpmatic_form_id();
    }


    return $output;

  }
}
add_filter( 'wpcf7_special_mail_tags', 'chimpmatic_tags', 10, 3 );


if (!function_exists('chimpmatic_add_form_tag_posts')) {
  function chimpmatic_add_form_tag_posts() {

    wpcf7_add_form_tag('_domain', 'chimpmatic_domain');
    wpcf7_add_form_tag('_formID', 'chimpmatic_form_id');

  }
}
add_action('wpcf7_init', 'chimpmatic_add_form_tag_posts', 11);


if (!function_exists('chimpmatic_domain')) {
  function chimpmatic_domain() {

    $strToLower       = strtolower(trim( get_home_url() ));
    $httpPregReplace  = preg_replace('/^http:\/\//i', '', $strToLower);
    $httpsPregReplace = preg_replace('/^https:\/\//i', '', $httpPregReplace);
    $wwwPregReplace   = preg_replace('/^www\./i', '', $httpsPregReplace);
    $explodeToArray   = explode('/', $wwwPregReplace);
    $finalDomainName  = trim($explodeToArray[0]);

    return $finalDomainName;

  }
}


if (!function_exists('chimpmatic_form_id')) {
  function chimpmatic_form_id() {

    $wpcf7 = WPCF7_ContactForm::get_current();
    $res = $wpcf7->id();

    return $res;

  }
}



/* Dequeue unnecessary CSS and JS
================================================== */
if ( ! function_exists( 'mercurial_dequeue_rubish' ) ) {
  function mercurial_dequeue_rubish() {

    if (!is_admin()) {
      wp_dequeue_style( 'sb_instagram_icons' );
      wp_deregister_style( 'sb_instagram_icons' );

      wp_dequeue_style( 'monsterinsights-admin-common-style' );
      wp_deregister_style( 'monsterinsights-admin-common-style' );

      wp_dequeue_style( 'monsterinsights-popular-posts-style' );
      wp_deregister_style( 'monsterinsights-popular-posts-style' );
    }

  }
  add_action( 'wp_print_styles', 'mercurial_dequeue_rubish' );
}



/* Remove inline svg elements - As of 5.9.1
================================================== */
remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );