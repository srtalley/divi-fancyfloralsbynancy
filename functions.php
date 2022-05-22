<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;
require_once( dirname( __FILE__ ) . '/includes/class-projects.php');

/* Add custom functions below */

add_action( 'wp_enqueue_scripts', 'ds_enqueue_assets', 15 );
function ds_enqueue_assets() {

  wp_enqueue_style( 'divi-style-parent', get_template_directory_uri() . '/style.css', array(), et_get_theme_version() );
  wp_enqueue_style( 'divi-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));

  wp_register_style('dst-stacktable-style', get_stylesheet_directory_uri() . '/lib/stacktable/stacktable.css');
  wp_enqueue_style('dst-stacktable-style');

  wp_enqueue_script('dst-stacktable-script', get_stylesheet_directory_uri() . '/lib/stacktable/stacktable.js', '', '', true);
  
  wp_enqueue_script( 'main', get_stylesheet_directory_uri() . '/js/main.js', array('jquery'), wp_get_theme()->get('Version'), true );

}//end function ds_enqueue_assets


/**
 * Enqueue Magnific Popup
 */
function popup_enqueue_scripts(){
  wp_enqueue_script( 'magnific-popup', ET_BUILDER_URI . '/feature/dynamic-assets/assets/js/magnific-popup.js', array( 'jquery' ), '1.3.0', true );
  wp_enqueue_style('et_jquery_magnific_popup', ET_BUILDER_URI . "/feature/dynamic-assets/assets/css/magnific_popup.css", [], '1.3.0');
}
add_action('wp_enqueue_scripts', 'popup_enqueue_scripts', 20);




// Remove default styling for WP galleries
// add_filter('use_default_gallery_style', function ($html5) {
// 	return false; // suppress default inline styles
// });
// add_filter('shortcode_atts_gallery', function ($atts) {
// 	$atts['columns'] = 0; // allow lines to wrap as required
// 	return $atts;
// });


////////////////////////////////////////////////////
// FEATURED IMAGES IN RSS (for MailChimp)
////////////////////////////////////////////////////
function rss_post_thumbnail($content) {
  global $post;
  if(has_post_thumbnail($post->ID)) {
  $content = '<p>' . get_the_post_thumbnail($post->ID, 'large') .
  '</p>' . get_the_content();
}
return $content;
}
add_filter('the_excerpt_rss', 'rss_post_thumbnail');
add_filter('the_content_feed', 'rss_post_thumbnail');


// Begin custom image size for Blog Module & Porfolio module
add_filter( 'et_pb_blog_image_height', 'blog_size_h' );
add_filter( 'et_pb_portfolio_image_height', 'blog_size_h' );
// add_filter( 'et_pb_blog_image_width', 'blog_size_w' );
 
function blog_size_h($height) {
	return '600';
}
 
// function blog_size_w($width) {
// 	return '400';
// }
function wl ( $log ) {
	if ( is_array( $log ) || is_object( $log ) ) {
	error_log( print_r( $log, true ) );
	} else {
	error_log( $log );
	}
}

// change the archive titles
function dst_archive_title( $title ) {
  wl($title);
  if ( is_category() ) {
      $title = single_cat_title( 'Category: ', false );
  } elseif ( is_tag() ) {
      $title = single_tag_title( '', false );
  } elseif ( is_author() ) {
      $title = '<span class="vcard">' . get_the_author() . '</span>';
  } elseif ( is_post_type_archive() ) {
      $title = post_type_archive_title( '', false );
  } elseif ( is_tax() ) {
      $title = single_term_title( '', false );
  }

  return $title;
}
add_filter( 'get_the_archive_title', 'dst_archive_title', 1000 );


/**
 * SG Optimizer Exclude
 */

add_filter( 'sgo_js_combine_exclude', 'js_combine_exclude' );
function js_combine_exclude( $exclude_list ) {
    // Add the style handle to exclude list.
    $exclude_list[] = 'convertkit-js-js';

    return $exclude_list;
}

add_filter( 'sgo_js_async_exclude', 'js_async_exclude' );
function js_async_exclude( $exclude_list ) {
  $exclude_list[] = 'convertkit-js-js';

  return $exclude_list;
}
// add_filter( 'sgo_javascript_combine_excluded_external_paths', 'js_combine_exclude_external_script' );
// function js_combine_exclude_external_script( $exclude_list ) {
//   $exclude_list[] = 'script-host.com';
//   $exclude_list[] = 'script-host-2.com';

//   return $exclude_list;
// }