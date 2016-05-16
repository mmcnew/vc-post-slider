<?php
/*
  Plugin Name: VC Post Slider
  Plugin URI: http://www.visceralconcepts.com
  Description: A shortcode to add an Owl Slider featuring any custom post type.
  Version: 1.02
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

	wp_register_style( 'vc-css', plugin_dir_url(__FILE__) . 'css/vc.carousel.css', false, '2.0.0', false );
	wp_register_style( 'vc-theme-css', plugin_dir_url(__FILE__) . 'css/vc.theme.default.css', false, '2.0.0', false );
	wp_register_style( 'vc-animate-css', plugin_dir_url(__FILE__) . 'css/vc.animate.css', false, '2.0.0', false );
	wp_register_style( 'vc-autoheight-css', plugin_dir_url(__FILE__) . 'css/vc.autoheight.css', false, '2.0.0', false );
	wp_register_style( 'vc-lazyload-css', plugin_dir_url(__FILE__) . 'css/vc.lazyload.css', false, '2.0.0', false );
	wp_register_script( 'vc-jquery' , plugin_dir_url(__FILE__) . 'js/jquery.min.js', false, '2.1.1', false );
	wp_register_script( 'vc-script', plugin_dir_url(__FILE__) . 'js/vc.carousel.min.js', false, '2.0.0', false );
	wp_enqueue_style( 'vc-css' );
	wp_enqueue_style( 'vc-theme-css' );
	wp_enqueue_style( 'vc-animate-css' );
	wp_enqueue_style( 'vc-autoheight-css' );
	wp_enqueue_style( 'vc-lazyload-css' );
	wp_enqueue_script( 'vc-jquery' );
	wp_enqueue_script( 'vc-script' );

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
		'columns' => '3',
		'center' => 'false',
		'autoplay' => 'true',
		'excerpt' => 'false',
		'classes' => ''
		), $atts );
		
	$args = array(
		'post_type' => $a['type'],
		);
		
	$q = new WP_Query( $args  );
	
	$list = '<div class="vc-carousel">';
	while($q->have_posts()) : $q->the_post();
		$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
		$excerpt =  get_the_excerpt();
		$list .= "<div class='item " . $a['type'];
		if( $a['classes'] != '' ) {
			$list .= $a['classes'];
		}
		$list .= "'><img class='";
		if( $a['lazyload'] != 'false' ) {
			$list .= "vc-lazy";
		}
		$list .= "' src='" . $feat_image . "' alt='" . get_the_title() . "'";
		if( $a['lazyload'] != 'false' ) {
			$list .= " data-src='" . $feat_image . "'";
		}
		$list .= "/><p ";
		if( $a['excerpt'] != 'false' && '' != $excerpt ) {
			$list .= "class='quote'>" . $excerpt . "</p><p";
		}
		$list .= " class='name'>";
		$list .= get_the_title() . "</p>";
		$list .= "</div>";
	endwhile;
	wp_reset_query();
	
	$list .= '</div>
				</div>
			</div>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			$(' . "'.vc-carousel'" . ').vcCarousel({
				loop: ' . "{$a['loop']}" . ',
				margin: ' . "{$a['margin']}" . ',
				nav: ' . "{$a['nav']}" . ',
				dots: ' . "{$a['dots']}" . ',
				lazyLoad: ' . "{$a['lazyload']}" . ',
				responsive: '; 
				if ( $a['columns'] == 1 ) {
					$list .= '{
						0:{
							items:1
						}
					}';
				} elseif ( $a['columns'] == 3 ) {
					$list .= '{
						0:{
							items:1
						},
						600:{
							items:3
						}
					}';
				} elseif ( $a['columns'] == 5 ) {
					$list .= '{
						0:{
							items:1
						},
						600:{
							items:3
						},
						1000:{
							items:5,
							margin:10
						}
					}';
				} else {
					$list .= 'false';
				}
				$list .= ',
				center: ' . "{$a['center']}" . ',
				autoplay: ' . "{$a['autoplay']}" . ',
				autoplayTimeout: 2000,
				autoplaySpeed: 3000,
				autoplayHoverPause:true
				});
			});
		</script>';
		
		return $list;
}
add_shortcode( 'scroller', 'vc_post_scroller' );




















?>