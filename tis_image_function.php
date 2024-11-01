<?php

/*
Plugin Name: Techworm Image Slider
Plugin URI: http://www.plugins.techwormsoftware.com/image-slider/
Description: This is a fully customized image slider plugin where the user modify the slider speed and
number of images shown in the slider.Image size of this slider should be width 400px and height 300px .
Version: 1.0
Author URI: http://techwormsoftware.com/com_web/
*/
if(!defined("ABSPATH"))
	exit;
if(!defined("TIS_IMAGE_PLUGIN_DIR_PATH"))
	define("TIS_IMAGE_PLUGIN_DIR_PATH",plugin_dir_path(__FILE__));
if(!defined("TIS_IMAGE_PLUGIN_URL"))
	define("TIS_IMAGE_PLUGIN_URL",plugins_url()."/techworm-image-slider");
function tis_image_plugin_main_js()

{
	wp_enqueue_style("tis-bootstrap",TIS_IMAGE_PLUGIN_URL.'/css/bootstrap.min.css');
	wp_enqueue_style('tis-owl.carousel',TIS_IMAGE_PLUGIN_URL.'/css/owl.carousel.css');
	wp_enqueue_style('tis-owl.theme',TIS_IMAGE_PLUGIN_URL.'/css/owl.theme.css');
	wp_enqueue_style('tis-owl.transitions',TIS_IMAGE_PLUGIN_URL.'/css/owl.transitions.css');
	wp_enqueue_script('tis-owl.carousel.min.js',TIS_IMAGE_PLUGIN_URL.'/js/owl.carousel.min.js',array('jquery'));
	
	 
}
add_action('init','tis_image_plugin_main_js');
function tis_local_js()
{
	wp_enqueue_script('jquery');
}
add_action('init','tis_local_js');

function tis_register_submenu_page() {
    add_submenu_page('edit.php?post_type=tisimage', 'carousel settings', 'Carousel Settings', "manage_options", 'tis_reading', 'tis_carousal_settings', '');
	
}

add_action('admin_menu', 'tis_register_submenu_page');



 
 function tis_settings_api_init() {
 	// Add the section to reading settings so we can add our
 	// fields to it
 	add_settings_section(
		'tis_setting_section',
		'Carousel settings',
		'tis_setting_section_callback_function',
		'tis_reading'
	);
 	
 	// Add the field with the names and function to use for our new
 	// settings, put it in our new section
 	add_settings_field(
		'tis_setting_name',
		'Carousal Speed :',
		'tis_setting_callback_function',
		'tis_reading',
		'tis_setting_section'
	);
	
	add_settings_field(
		'tis_item_setting_name',
		' No Image Show :',
		'tis_item_setting_callback_function',
		'tis_reading',
		'tis_setting_section'
	);
 	
 	// Register our setting so that $_POST handling is done for us and
 	// our callback function just has to echo the <input>
 	register_setting( 'tis-autoplay', 'tis_auto_play' );
	register_setting( 'tis-autoplay', 'tis_it_em' );
 } // eg_settings_api_init()
 
 add_action( 'admin_init', 'tis_settings_api_init' );
 
  
 // ------------------------------------------------------------------
 // Settings section callback function
 // ------------------------------------------------------------------
 //
 // This function is needed if we added a new section. This function 
 // will be run at the start of our section
 //
 
 function tis_setting_section_callback_function() {
 	echo '<p></p>';
 }
 
 // ------------------------------------------------------------------
 // Callback function for our example setting
 // ------------------------------------------------------------------
 //
 // creates a checkbox true/false option. Other types are surely possible
 //
 
 function tis_setting_callback_function() {
	 $tis_cr_speed= esc_attr(get_option('tis_auto_play'));
	  
 	echo ' <input type="number"   value="'.$tis_cr_speed.'" name="tis_auto_play" required placeholder="Enter Cursor Speed"> Recommended carousel speed should be 5000';
	
 }
function tis_item_setting_callback_function() {
	 $tis_item_no= esc_attr(get_option('tis_it_em'));
 	echo ' <input type="number"   value="'.$tis_item_no.'" name="tis_it_em" required placeholder="Enter No Of Image"> By default 4 images are there in every carousel';
	
 }





function tis_carousal_settings()
{
	include_once TIS_IMAGE_PLUGIN_DIR_PATH.'/views/carasoul_setting.php';
}



add_theme_support( 'post-thumbnails', array( 'tisimage') );
add_image_size( 'carouselthumbtech', 400,300 );
add_action('init','tis_image_carousal_techworm');
function tis_image_carousal_techworm()
{
	register_post_type('tisimage',
	array(
	'labels'=>array(
	'name'=>__('Manage Slider'),
	'singular_name'=>__('Image Carousel Slider'),
	'add_new_item'=>__('Add New Image')
	),
	'public'=>true,
	'supports'=>array('thumbnail','title','editor','custom-fields'),
	'has_archive'=>true,
	'rewrite'=>array('slug'=>'image-item'),
	
	)
	
	);
}

function tis_techworm_carousal_taxonomy()
{
	 register_taxonomy(  
        'Carousel_categories',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces). 
        'tisimage',        //post type name
        array(  
            'hierarchical' => true,  
            'label' => 'Add Carousel Category',  //Display name
            'query_var' => true,
			'show_admin_column'     => true,
            'rewrite' => array(
                'slug' => 'carousel-category', // This controls the base slug that will display before each term
                'with_front' => true // Don't display the category base before 
            )
        )  
    );  
}
add_action('init','tis_techworm_carousal_taxonomy');





function tis_techworm_carousal_shortcode($atts){
	$tis_res_item=esc_attr(get_option('tis_it_em'));
	$tis_res_item_speed=esc_attr(get_option('tis_auto_play'));
	$tis_re_item='';
	$tis_re_item_speed='';
	if(	$tis_re_item==null && $tis_res_item_speed==null)
	{
	$tis_re_item=4;	
	$tis_re_item_speed=5000;
	}
	else
	{
	$tis_re_item=$tis_res_item;	
	$tis_re_item_speed=$tis_res_item_speed;
	}
	extract(shortcode_atts(array(
	
	'tis_speed'=>$tis_re_item_speed,
	'tis_noitem'=>$tis_re_item,
	
	),$atts,'projects'));
	$tis_techwormcarousal='
	<script type="text/javascript">
	jQuery(document).ready(function() {
  jQuery("#owl-demo").owlCarousel({
    autoPlay:'.$tis_speed.',
	items:'.$tis_noitem.',
  });
 
});
	</script>
	<div id="owl-demo" class="owl-carousel">';
	$tis_efs_query= "post_type=tisimage&posts_per_page=-1";
	query_posts($tis_efs_query);
	if (have_posts()) : while (have_posts()) : the_post(); 
	$tis_post_id=get_the_id();
		$img_tech_blg= get_the_post_thumbnail( $tis_post_id, 'carouselthumbtech' );	
		$tis_techwormcarousal.='<div class="item">'.$img_tech_blg.'</div>';		
	endwhile; endif; wp_reset_query();
	$tis_techwormcarousal.= '</div>';
	return $tis_techwormcarousal;
}

/**add the shortcode for the slider- for use in editor**/

add_shortcode('techworm-carousal-image','tis_techworm_carousal_shortcode');
?>