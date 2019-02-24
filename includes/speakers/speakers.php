<?php

namespace CeylonConferences\Speakers;

use Carbon_Fields\Container;
use Carbon_Fields\Field;


const SPEAKER_POST_TYPE = 'cp_speakers';

add_action( 'init', __NAMESPACE__ . '\\register_speakers_post_type' );
add_action( 'carbon_fields_register_fields', __NAMESPACE__ . '\\speakers_post_meta_fields' );
//add_action( 'admin_menu' ,__NAMESPACE__. '\\add_speaker_sub_menu');
add_action( 'manage_'.SPEAKER_POST_TYPE.'_posts_custom_column' , __NAMESPACE__.'\\speaker_thumbnail_column' , 2 , 10 );
add_filter( 'manage_'.SPEAKER_POST_TYPE.'_posts_columns' ,__NAMESPACE__.'\\add_speaker_thumbnail_to_column' , 1 , 10 );
add_filter( 'manage_'.SPEAKER_POST_TYPE.'_posts_columns' ,__NAMESPACE__.'\\order_of_the_columns' , 1, 20 );

function register_speakers_post_type() {

	$labels = array(
		'name'                  => _x( 'Speakers', 'Post Type General Name', CEYLON_CONF_TEXT_DOMAIN ),
		'singular_name'         => _x( 'Speaker', 'Post Type Singular Name', CEYLON_CONF_TEXT_DOMAIN ),
		'menu_name'             => __( 'Speakers', CEYLON_CONF_TEXT_DOMAIN ),
		'name_admin_bar'        => __( 'Speakers', CEYLON_CONF_TEXT_DOMAIN ),
		'archives'              => __( 'Speakers Archives', CEYLON_CONF_TEXT_DOMAIN ),
		'attributes'            => __( 'Speakers Attributes', CEYLON_CONF_TEXT_DOMAIN ),
		'parent_item_colon'     => __( 'Parent Item:', CEYLON_CONF_TEXT_DOMAIN ),
		'all_items'             => __( 'All Speakers', CEYLON_CONF_TEXT_DOMAIN ),
		'add_new_item'          => __( 'Add New Speaker', CEYLON_CONF_TEXT_DOMAIN ),
		'add_new'               => __( 'Add New', CEYLON_CONF_TEXT_DOMAIN ),
		'new_item'              => __( 'New Speaker', CEYLON_CONF_TEXT_DOMAIN ),
		'edit_item'             => __( 'Edit Speaker', CEYLON_CONF_TEXT_DOMAIN ),
		'update_item'           => __( 'Update Speaker', CEYLON_CONF_TEXT_DOMAIN ),
		'view_item'             => __( 'View Speaker', CEYLON_CONF_TEXT_DOMAIN ),
		'view_items'            => __( 'View Speakers', CEYLON_CONF_TEXT_DOMAIN ),
		'search_items'          => __( 'Search Speakers', CEYLON_CONF_TEXT_DOMAIN ),
		'not_found'             => __( 'Not found', CEYLON_CONF_TEXT_DOMAIN ),
		'not_found_in_trash'    => __( 'Not found in Trash', CEYLON_CONF_TEXT_DOMAIN ),
		'featured_image'        => __( 'Speaker Image', CEYLON_CONF_TEXT_DOMAIN ),
		'set_featured_image'    => __( 'Set speaker image', CEYLON_CONF_TEXT_DOMAIN ),
		'remove_featured_image' => __( 'Remove featured image', CEYLON_CONF_TEXT_DOMAIN ),
		'use_featured_image'    => __( 'Use as speaker image', CEYLON_CONF_TEXT_DOMAIN ),
		'uploaded_to_this_item' => __( 'Uploaded to this speaker', CEYLON_CONF_TEXT_DOMAIN ),
		'items_list'            => __( 'Speakers list', CEYLON_CONF_TEXT_DOMAIN ),
		'items_list_navigation' => __( 'Speakers list navigation', CEYLON_CONF_TEXT_DOMAIN ),
		'filter_items_list'     => __( 'Filter speakers list', CEYLON_CONF_TEXT_DOMAIN ),
	);

	$rewrite = array(
		'slug'       => 'speakers',
		'with_front' => true,
		'pages'      => true,
		'feeds'      => true,
	);

	$args = array(
		'label'               => __( 'Speaker', CEYLON_CONF_TEXT_DOMAIN ),
		'description'         => __( 'Conference Manager', CEYLON_CONF_TEXT_DOMAIN ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-megaphone',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => 'speakers',
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite,
		'capability_type'     => 'post',
		'show_in_rest'        => true,
	);

	register_post_type( SPEAKER_POST_TYPE, $args );

}

function add_speaker_thumbnail_to_column( $columns ){
	return array_merge( $columns ,
		array( 'thumbnail' => __('Speaker Avatar' , CEYLON_CONF_TEXT_DOMAIN ) ) );
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

function speaker_thumbnail_column( $column , $post_id  ){
	if( $column === 'thumbnail'){
		echo the_post_thumbnail( array(72,72) );
	}
}

function speakers_post_meta_fields() {

	Container::make( 'post_meta', __( 'Speaker Information', CEYLON_CONF_TEXT_DOMAIN ) )
	         ->where( 'post_type', '=', SPEAKER_POST_TYPE )
			 ->set_context( 'side' )
	         ->add_fields( array(
		         Field::make( 'complex', 'ccm_social_links', __( 'Social Links', CEYLON_CONF_TEXT_DOMAIN ) )
		              ->add_fields( 'ccm_links', array(
			              Field::make( 'select', 'ccm_social_network_type', __( 'Select a network' ) )
			                   ->set_options( array(
				                   'facebook' => 'Facebook',
				                   'twitter' => 'Twitter',
				                   'whatsapp' => 'WhatsApp',
				                   'linkdin' => 'LinkdIn',
				                   'youtube' => 'YouTube',
			                   ) )
							   ->set_default_value('facebook'),
			              Field::make( 'text', 'ccm_social_network_link', __( 'Social Profile Link' ) )
			              ->set_attribute('type','url')
		              ) )

	         ) );

}

function add_speaker_sub_menu(){
	add_submenu_page(
		'edit.php?post_type=cp_conferences',
		__('Speakers',CEYLON_CONF_TEXT_DOMAIN),
		__('Speakers',CEYLON_CONF_TEXT_DOMAIN),
		'manage_options',
		'edit.php?post_type='.SPEAKER_POST_TYPE
	);
}
