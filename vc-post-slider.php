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

	wp_register_style( 'owl-css', plugin_dir_url(__FILE__) . 'css/owl.carousel.css', false, '2.0.0', false );
	wp_register_style( 'owl-theme-css', plugin_dir_url(__FILE__) . 'css/owl.theme.default.css', false, '2.0.0', false );
	wp_register_style( 'owl-animate-css', plugin_dir_url(__FILE__) . 'css/owl.animate.css', false, '2.0.0', false );
	wp_register_style( 'owl-autoheight-css', plugin_dir_url(__FILE__) . 'css/owl.autoheight.css', false, '2.0.0', false );
	wp_register_style( 'owl-lazyload-css', plugin_dir_url(__FILE__) . 'css/owl.lazyload.css', false, '2.0.0', false );
	wp_register_script( 'owl-jquery' , plugin_dir_url(__FILE__) . 'js/jquery.min.js', false, '2.1.1', false );
	wp_register_script( 'owl-script', plugin_dir_url(__FILE__) . 'js/owl.carousel.min.js', false, '2.0.0', false );
	wp_enqueue_style( 'owl-css' );
	wp_enqueue_style( 'owl-theme-css' );
	wp_enqueue_style( 'owl-animate-css' );
	wp_enqueue_style( 'owl-autoheight-css' );
	wp_enqueue_style( 'owl-lazyload-css' );
	wp_enqueue_script( 'owl-jquery' );
	wp_enqueue_script( 'owl-script' );

}

// Shortcode Construct
function vc_post_scroller( $atts ) {
	$a = shortcode_atts ( array (
		'type' =>  'posts',
		'nav' => 'false',
		'dots' => 'false',
		'margin' => '10',
		'loop' => 'true',
		'lazyload' => 'true',
		'responsive' => 'true',
		'center' => 'false',
		'autoplay' => 'true'
		), $atts );
		
	$args = array(
		'post_type' => $a['type'],
		);
		
	$q = new WP_Query( $args  );
	
	function lazy_class() {
		if( $a['lazyload'] != 'false' ) {
			 return 'owl-lazy';		
		} else {
			return '';
		};
	};
	
	$class = lazy_class();
		
	$list = '<div class="owl-carousel">';
	while($q->have_posts()) : $q->the_post();
		$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
		$list .= "<div class='item " . $a['type'] . "'><img class='" . $class . "' src='" . $feat_image . "' alt='" . get_the_title() . "' data-src='" . $feat_image . "' /><p class='name'>" . get_the_title() . "</p></div>";
	endwhile;
	wp_reset_query();
	
	return $list . '</div>
				</div>
			</div>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			$(' . "'.owl-carousel'" . ').owlCarousel({
				loop: ' . "{$a['loop']}" . ',
				margin: ' . "{$a['margin']}" . ',
				nav: ' . "{$a['nav']}" . ',
				dots: ' . "{$a['dots']}" . ',
				lazyLoad: ' . "{$a['lazyload']}" . ',
				responsive: ' . "{$a['responsive']}" . ',
				center: ' . "{$a['center']}" . ',
				autoplay: ' . "{$a['autoplay']}" . ',
				autoplayTimeout: 2000,
				autoplaySpeed: 3000,
				autoplayHoverPause:true
				});
			});
		</script>';
}
add_shortcode( 'scroller', 'vc_post_scroller' );




















?>