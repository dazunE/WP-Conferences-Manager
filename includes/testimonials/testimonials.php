<?php

namespace Conferences\Testimonials;

const TESTIMONIALS_POST_TYPE = 'ccm_testimonials';

add_action( 'init', __NAMESPACE__ . '\\register_testimonials_post_type' );
//add_action( 'carbon_fields_register_fields', __NAMESPACE__ . '\\testimonials_post_meta_fields' );
//add_action( 'admin_menu' ,__NAMESPACE__. '\\add_testimonial_sub_menu');
add_action( 'manage_'.TESTIMONIALS_POST_TYPE.'_posts_custom_column' , __NAMESPACE__.'\\testimonial_thumbnail_column' , 2 , 10 );
add_filter( 'manage_'.TESTIMONIALS_POST_TYPE.'_posts_columns' ,__NAMESPACE__.'\\add_testimonial_thumbnail_to_column' , 1 , 10 );
add_filter( 'manage_'.TESTIMONIALS_POST_TYPE.'_posts_columns' ,__NAMESPACE__.'\\order_of_the_columns' , 1, 20 );

function register_testimonials_post_type() {

	$labels = array(
		'name'                  => _x( 'Testimonials', 'Post Type General Name', CEYLON_CONF_TEXT_DOMAIN ),
		'singular_name'         => _x( 'Testimonial', 'Post Type Singular Name', CEYLON_CONF_TEXT_DOMAIN ),
		'menu_name'             => __( 'Testimonials', CEYLON_CONF_TEXT_DOMAIN ),
		'name_admin_bar'        => __( 'Testimonials', CEYLON_CONF_TEXT_DOMAIN ),
		'archives'              => __( 'Testimonials Archives', CEYLON_CONF_TEXT_DOMAIN ),
		'attributes'            => __( 'Testimonials Attributes', CEYLON_CONF_TEXT_DOMAIN ),
		'parent_item_colon'     => __( 'Parent Item:', CEYLON_CONF_TEXT_DOMAIN ),
		'all_items'             => __( 'All Testimonials', CEYLON_CONF_TEXT_DOMAIN ),
		'add_new_item'          => __( 'Add New Testimonial', CEYLON_CONF_TEXT_DOMAIN ),
		'add_new'               => __( 'Add New', CEYLON_CONF_TEXT_DOMAIN ),
		'new_item'              => __( 'New Testimonial', CEYLON_CONF_TEXT_DOMAIN ),
		'edit_item'             => __( 'Edit Testimonial', CEYLON_CONF_TEXT_DOMAIN ),
		'update_item'           => __( 'Update Testimonial', CEYLON_CONF_TEXT_DOMAIN ),
		'view_item'             => __( 'View Testimonial', CEYLON_CONF_TEXT_DOMAIN ),
		'view_items'            => __( 'View Testimonials', CEYLON_CONF_TEXT_DOMAIN ),
		'search_items'          => __( 'Search Testimonials', CEYLON_CONF_TEXT_DOMAIN ),
		'not_found'             => __( 'Not found', CEYLON_CONF_TEXT_DOMAIN ),
		'not_found_in_trash'    => __( 'Not found in Trash', CEYLON_CONF_TEXT_DOMAIN ),
		'featured_image'        => __( 'Testimonial Image', CEYLON_CONF_TEXT_DOMAIN ),
		'set_featured_image'    => __( 'Set testimonial image', CEYLON_CONF_TEXT_DOMAIN ),
		'remove_featured_image' => __( 'Remove featured image', CEYLON_CONF_TEXT_DOMAIN ),
		'use_featured_image'    => __( 'Use as testimonial image', CEYLON_CONF_TEXT_DOMAIN ),
		'uploaded_to_this_item' => __( 'Uploaded to this testimonial', CEYLON_CONF_TEXT_DOMAIN ),
		'items_list'            => __( 'Testimonials list', CEYLON_CONF_TEXT_DOMAIN ),
		'items_list_navigation' => __( 'Testimonials list navigation', CEYLON_CONF_TEXT_DOMAIN ),
		'filter_items_list'     => __( 'Filter testimonials list', CEYLON_CONF_TEXT_DOMAIN ),
	);

	$rewrite = array(
		'slug'       => 'testimonials',
		'with_front' => true,
		'pages'      => true,
		'feeds'      => true,
	);

	$args = array(
		'label'               => __( 'Testimonial', CEYLON_CONF_TEXT_DOMAIN ),
		'description'         => __( 'Conference Manager', CEYLON_CONF_TEXT_DOMAIN ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-format-quote',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => 'testimonials',
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite,
		'capability_type'     => 'post',
		'show_in_rest'        => true,
	);

	register_post_type( TESTIMONIALS_POST_TYPE, $args );

}

function add_testimonial_thumbnail_to_column( $columns ){
	return array_merge( $columns ,
		array( 'thumbnail' => __('User Avatar' , CEYLON_CONF_TEXT_DOMAIN ) ) );
}

function order_of_the_columns($defaults) {

	$new = array();
	$tags = $defaults['thumbnail'];  // save the tags column
	unset($defaults['thumbnail']);   // remove it from the columns list

	foreach($defaults as $key=>$value) {
		if($key=='date') {  // when we find the date column
			$new['thumbnail'] = $tags;  // put the tags column before it
		}
		$new[$key]=$value;
	}

	return $new;
}

function testimonial_thumbnail_column( $column , $post_id  ){
	if( $column === 'thumbnail'){
		echo the_post_thumbnail( array(72,72) );
	}
}

