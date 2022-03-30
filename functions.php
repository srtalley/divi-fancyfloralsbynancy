<?php
// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

/* Add custom functions below */

add_action( 'wp_enqueue_scripts', 'ds_enqueue_assets', 15 );
function ds_enqueue_assets() {

  wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array(), et_get_theme_version() );
  wp_dequeue_style( 'divi-style' );
  wp_enqueue_style( 'child-theme', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));

  wp_register_style('dst-stacktable-style', get_stylesheet_directory_uri() . '/lib/stacktable/stacktable.css');
  wp_enqueue_style('dst-stacktable-style');

  wp_enqueue_script('dst-stacktable-script', get_stylesheet_directory_uri() . '/lib/stacktable/stacktable.js', '', '', true);
  
  wp_enqueue_script( 'main', get_stylesheet_directory_uri() . '/js/main.js', '', '1.1', true );

}//end function ds_enqueue_assets

function ds_conditional_style_enqueue($ds_handle='', $ds_path = '', $ds_version = '', $ds_dependencies = 'array()', $ds_media = 'all'){
  if(wp_style_is($ds_handle, $ds_list = 'enqueued')){
    return;
  } else {
    wp_enqueue_style($ds_handle, $ds_path, $ds_dependencies, $ds_version, $ds_media);
  }
} //end function ds_conditional_enqueue


// Remove default styling for WP galleries
add_filter('use_default_gallery_style', function ($html5) {
	return false; // suppress default inline styles
});
add_filter('shortcode_atts_gallery', function ($atts) {
	$atts['columns'] = 0; // allow lines to wrap as required
	return $atts;
});

/////////////////////////////////////////////////////
// NEW MENU LOCATIONS
////////////////////////////////////////////////////
add_action( 'init', 'register_dst_footer_copyright_menu' );
function register_dst_footer_copyright_menu() {
  register_nav_menu('footer-copyright-menu',__( 'Footer Copyright Menu' ));
}

////////////////////////////////////////////////////
// CHILD THEME CUSTOMIZER OPTIONS
////////////////////////////////////////////////////
require_once('includes/theme_customizer.php');

////////////////////////////////////////////////////
// OUTPUT CUSTOM CSS
////////////////////////////////////////////////////
function dst_theme_customizer_css() {

  ?>
  <style type="text/css">
      #main-header {
        background-image: url( '<?php echo get_theme_mod('dst_header_background_img');?>') !important;
        background-position: top center;
        background-repeat: no-repeat;
      }
  </style>

  <?php

} //end dst_theme_customizer_css tds_custom_css_header
add_action('wp_head','dst_theme_customizer_css');
////////////////////////////////////////////////////
// CUSTOM WIDGET AREAS
////////////////////////////////////////////////////
function dst_widgets_init() {

  register_sidebar( array(
    'name'          => 'Below Posts',
    'id'            => 'below-posts-01',
    'before_widget' => '<div class="clear"></div><div id="below-posts-01" class="widget-area">',
    'after_widget'  => '</div>',
    'before_title'  => '<h1>',
    'after_title'   => '</h1>',
  ));

  register_sidebar( array(
    'name'          => 'Above Footer Fullwidth',
    'id'            => 'footer-fullwidth',
    'before_widget' => '<div class="clear"></div><div id="footer-fullwidth" class="widget-area">',
    'after_widget'  => '</div>',
    'before_title'  => '<h1>',
    'after_title'   => '</h1>',
  ));

} //end function ds_widgets_init
add_action( 'widgets_init', 'dst_widgets_init');
// Add the widget area below the posts
function dst_add_post_widgets($content) {
  if( is_singular( 'post' ) ):
    if ( is_active_sidebar('below-posts-01') ) :
      ob_start();
      dynamic_sidebar('below-posts-01');
      $content .= ob_get_contents();
      ob_end_clean();
   endif;
 endif; //function dst_add_post_widgets($content)
  return $content;
} //end function dst_add_facebook_comments_div()
add_filter('the_content', 'dst_add_post_widgets', 999999999);



// // Add the widget area below the posts
// function dst_add_post_widgets($content) {
//   if( is_singular( 'post' ) ):
//     if ( is_active_sidebar('footer-fullwidth') ) :
//       ob_start();
//       dynamic_sidebar('footer-fullwidth');
//       $content .= ob_get_contents();
//       ob_end_clean();
//    endif;
//  endif; //function dst_add_post_widgets($content)
//   return $content;
// } //end function dst_add_facebook_comments_div()
// add_filter('the_content', 'dst_add_post_widgets', 999999999);

////////////////////////////////////////////////////
// ADMIN AREA BRANDING
////////////////////////////////////////////////////
//http://www.wpbeginner.com/wp-tutorials/25-extremely-useful-tricks-for-the-wordpress-functions-file/

function ds_remove_footer_admin () {

echo 'Designed by <a href="https://dustysun.com" target="_blank">Dusty Sun</a></p>';

}

add_filter('admin_footer_text', 'ds_remove_footer_admin');


function ds_help_dashboard_widgets() {
global $wp_meta_boxes;

  wp_add_dashboard_widget('custom_help_widget', 'Website Support', 'ds_custom_dashboard_help');
}

function ds_custom_dashboard_help() {
  echo '<p>Need help? <a href="mailto:support@dustysun.com">Contact</a> the friendly folks at Dusty Sun <a href="mailto:support@dustysun.com">here</a>. For WordPress Help visit: <a href="https://dustysun.com/help-center" target="_blank">Dusty Sun\'s Help Center</a></p>';
}
add_action('wp_dashboard_setup', 'ds_help_dashboard_widgets');

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
