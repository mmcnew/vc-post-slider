<?php
/*
  Plugin Name: VC Post Slider
  Plugin URI: http://www.visceralconcepts.com
  Description: A shortcode to add an Owl Slider featuring any custom post type.
  Version: 1.00
  Author: Visceral Concepts
  Author URI: http://www.visceralconcepts.com
  License: GPLv3 or Later
 */
 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*
  Before the plugin does anything, we check for updates.
*/

require_once( 'inc/vc-plugin-updater.php' );
if ( is_admin() ) {
    new VCSlide_Plugin_Updater( __FILE__, 'mmcnew', 'vc-post-slider' );
}


//Load CSS & Scripts

add_action( 'wp_enqueue_scripts', 'vc_owl_scripts', 16 );
function vc_owl_scripts() {

	wp_register_style( 'owl-css', plugin_dir_url(__FILE__) . 'css/owl.carousel.css' );
	wp_register_script( 'owl-script', plugin_dir_url(__FILE__) . 'js/owl.carousel.min.js' );
	wp_enqueue_style( 'owl-css' );
	wp_enqueue_script( 'owl-script' );

}

// Shortcode Construct
function vc_post_scroller( $atts ) {
	$a = shortcode_atts ( array (
		'type' =>  'posts',
		'nav' => 'false',
		'margin' => '10',
		), $atts );
		
    $q = new WP_Query( array(
		'orderby' => 'date',
		'posts_per_page' => '6'
		)
		
 );

return '<div class="owl-carousel">';

while($q->have_posts()) : $q->the_post();
	$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
	$list = '<div class="item"><a href="' . get_permalink() . '"><img src="' . $feat_image . '" alt="' . get_the_title() . '"/><p class="name">' . get_the_title() . '</p></a></div>';
endwhile;

return  '</div>';

return '<script type="text/javascript">
			$(document).ready(function(){
				$(".owl-carousel").owlCarousel({
					loop:true,
					margin:' . "{$a['margin']}" . ',
					nav:' . "{$a['nav']}" . ',
					dots:' . "{$a['nav']}" . ',
					lazyLoad:true,
					responsive:{
						0:{
							items:1
							},
						600:{
							items:3
							},
						1000:{
							items:5
							}
						}
					});
				});
			</script>';

wp_reset_query();
}
add_shortcode( 'scroller', 'vc_post_scroller' );




















?>