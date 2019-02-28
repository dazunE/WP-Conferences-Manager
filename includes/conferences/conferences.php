<?php

namespace CeylonConferences\Conferences;

use Carbon_Fields\Container;
use Carbon_Fields\Field;


const CONFERENCE_POST_TYPE = 'ccm_conferences';

add_action( 'init', __NAMESPACE__ . '\\register_conferences_post_type' );
add_action( 'carbon_fields_register_fields', __NAMESPACE__ . '\\conferences_post_meta_fields' );
add_action( 'manage_cp_conferences_posts_custom_column', __NAMESPACE__ . '\\sepaker_thumbnail_column', 2, 10 );
add_filter( 'manage_cp_conferences_posts_columns', __NAMESPACE__ . '\\add_Conference_thumbnail_to_column', 1, 10 );
add_filter( 'single_template', __NAMESPACE__ . '\\conference_single_page_template' );
add_filter( 'body_class', __NAMESPACE__ . '\\hide_header_body_class' );
add_filter( 'carbon_fields_association_field_title' , __NAMESPACE__ .'\\fix_the_title' , 5 , 10);

function register_conferences_post_type() {

	$labels = array(
		'name'                  => _x( 'Conferences', 'Post Type General Name', CEYLON_CONF_TEXT_DOMAIN ),
		'singular_name'         => _x( 'Conference', 'Post Type Singular Name', CEYLON_CONF_TEXT_DOMAIN ),
		'menu_name'             => __( 'Conferences', CEYLON_CONF_TEXT_DOMAIN ),
		'name_admin_bar'        => __( 'Conferences', CEYLON_CONF_TEXT_DOMAIN ),
		'archives'              => __( 'Conferences Archives', CEYLON_CONF_TEXT_DOMAIN ),
		'attributes'            => __( 'Conferences Attributes', CEYLON_CONF_TEXT_DOMAIN ),
		'parent_item_colon'     => __( 'Parent Item:', CEYLON_CONF_TEXT_DOMAIN ),
		'all_items'             => __( 'All Conferences', CEYLON_CONF_TEXT_DOMAIN ),
		'add_new_item'          => __( 'Add New Conference', CEYLON_CONF_TEXT_DOMAIN ),
		'add_new'               => __( 'Add New', CEYLON_CONF_TEXT_DOMAIN ),
		'new_item'              => __( 'New Conference', CEYLON_CONF_TEXT_DOMAIN ),
		'edit_item'             => __( 'Edit Conference', CEYLON_CONF_TEXT_DOMAIN ),
		'update_item'           => __( 'Update Conference', CEYLON_CONF_TEXT_DOMAIN ),
		'view_item'             => __( 'View Conference', CEYLON_CONF_TEXT_DOMAIN ),
		'view_items'            => __( 'View Conferences', CEYLON_CONF_TEXT_DOMAIN ),
		'search_items'          => __( 'Search Conferences', CEYLON_CONF_TEXT_DOMAIN ),
		'not_found'             => __( 'Not found', CEYLON_CONF_TEXT_DOMAIN ),
		'not_found_in_trash'    => __( 'Not found in Trash', CEYLON_CONF_TEXT_DOMAIN ),
		'featured_image'        => __( 'Conference Image', CEYLON_CONF_TEXT_DOMAIN ),
		'set_featured_image'    => __( 'Set Conference image', CEYLON_CONF_TEXT_DOMAIN ),
		'remove_featured_image' => __( 'Remove featured image', CEYLON_CONF_TEXT_DOMAIN ),
		'use_featured_image'    => __( 'Use as Conference image', CEYLON_CONF_TEXT_DOMAIN ),
		'uploaded_to_this_item' => __( 'Uploaded to this Conference', CEYLON_CONF_TEXT_DOMAIN ),
		'items_list'            => __( 'Conferences list', CEYLON_CONF_TEXT_DOMAIN ),
		'items_list_navigation' => __( 'Conferences list navigation', CEYLON_CONF_TEXT_DOMAIN ),
		'filter_items_list'     => __( 'Filter conferences list', CEYLON_CONF_TEXT_DOMAIN ),
	);

	$rewrite = array(
		'slug'       => 'conferences',
		'with_front' => true,
		'pages'      => true,
		'feeds'      => true,
	);

	$args = array(
		'label'               => __( 'Conference', CEYLON_CONF_TEXT_DOMAIN ),
		'description'         => __( 'Conference Manager', CEYLON_CONF_TEXT_DOMAIN ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'thumbnail' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-nametag',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => 'conferences',
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite,
		'capability_type'     => 'post',
		'show_in_rest'        => true,
	);

	register_post_type( CONFERENCE_POST_TYPE, $args );

}

function add_Conference_thumbnail_to_column( $columns ) {
	return array_merge( $columns,
		array( 'thumbnail' => __( 'Conference Logo', CEYLON_CONF_TEXT_DOMAIN ) ) );
}

function sepaker_thumbnail_column( $column, $post_id ) {
	if ( $column === 'thumbnail' ) {
		echo the_post_thumbnail( array( 72, 72 ) );
	}
}

function conferences_post_meta_fields() {

	Container::make( 'post_meta', __( 'Conference Information', CEYLON_CONF_TEXT_DOMAIN ) )
	         ->where( 'post_type', '=', CONFERENCE_POST_TYPE )
	         ->add_fields( array(
		         Field::make( 'checkbox', 'ccm_page_header', __( 'Hide Header' ) )
		              ->set_option_value( 'hide' )
		              ->set_width( 50 ),
		         Field::make( 'checkbox', 'ccm_page_title', __( 'Hide Title' ) )
		              ->set_option_value( 'hide' )
		              ->set_width( 50 ),
		         Field::make( 'text', 'ccm_conference_teaser', __( 'Teaser Section', CEYLON_CONF_TEXT_DOMAIN ) ),
		         Field::make( 'rich_text', 'ccm_conference_header', __( 'Conference Header', CEYLON_CONF_TEXT_DOMAIN ) ),
		         Field::make( 'rich_text', 'ccm_conference_paragraph', __( 'Conference Description', CEYLON_CONF_TEXT_DOMAIN ) ),
		         Field::make( 'oembed', 'ccm_conference_video', __( 'Conference Video', CEYLON_CONF_TEXT_DOMAIN ) ),
		         Field::make( 'separator', 'crb_separator', __( 'Call to action button' ) ),
		         Field::make( 'text', 'ccm_conference_button_text', __( 'Button Text', CEYLON_CONF_TEXT_DOMAIN ) )
		              ->set_width( 50 ),
		         Field::make( 'text', 'ccm_conference_button_url', __( 'Button URL', CEYLON_CONF_TEXT_DOMAIN ) )
		              ->set_width( 50 ),
		         Field::make( 'rich_text', 'ccm_conference_event_ticket', __( 'Event Ticket', CEYLON_CONF_TEXT_DOMAIN ) ),
		         Field::make( 'text', 'ccm_session_intro', __( 'Session Intro' , CEYLON_CONF_TEXT_DOMAIN ) ),
		         Field::make( 'complex', 'ccm_sessions_details', __( 'Session Details', CEYLON_CONF_TEXT_DOMAIN ) )
		              ->add_fields( 'ccm_session_data', __( 'Session Data' ), array(
			              Field::make( 'select', 'ccm_session_display', __( 'Items Per Row', CEYLON_CONF_TEXT_DOMAIN ) )
			                   ->set_width( 10 )
			                   ->set_options( array(
				                   '1' => 1,
				                   '2' => 2,
				                   '3' => 3,
			                   ) ),
			              Field::make( 'association', 'ccm_session_post_type', __( 'Sessions', CEYLON_CONF_TEXT_DOMAIN ) )
				              ->set_duplicates_allowed( false )
			                   ->set_width( 90 )
			                   ->set_types( array(
				                   array(
					                   'type'      => 'post',
					                   'post_type' => 'ccm_sessions',
				                   )
			                   ) ),
		              ) ),
		         Field::make( 'text', 'ccm_why_title', __( 'Why Conference Title' , CEYLON_CONF_TEXT_DOMAIN ) )
		              ->set_default_value('Why Guild Conferences?'),
		         Field::make( 'complex', 'ccm_conference_why_conference', __( 'Why Guild Conferences', CEYLON_CONF_TEXT_DOMAIN ) )
		              ->add_fields( 'ccm_conference_why_data', __( 'Why Items' ), array(
			              Field::make( 'text', 'ccm_why_conference_title', __( 'Item Title', CEYLON_CONF_TEXT_DOMAIN ) )
			                   ->set_width( 25 ),
			              Field::make( 'rich_text', 'ccm_why_conference_paragraph', __( 'Conference Description', CEYLON_CONF_TEXT_DOMAIN ) )
			                   ->set_width( 75 ),
		              ) ),
		         Field::make( 'rich_text', 'ccm_conference_gurantee', __( 'Conference Guarantee', CEYLON_CONF_TEXT_DOMAIN ) ),
		         Field::make( 'association', 'ccm_testimonials', __( 'Testimonials' ) )
		              ->set_types( array(
			              array(
				              'type'      => 'post',
				              'post_type' => 'ccm_testimonials',
			              )
		              ) )


	         ) );

}

function conference_single_page_template( $single ) {

	global $post;

	if ( $post->post_type == CONFERENCE_POST_TYPE ) {
		if ( file_exists( CEYLON_CONF_PLUGIN_PATH . '/includes/conferences/single-conference.php' ) ) {
			return CEYLON_CONF_PLUGIN_PATH . '/includes/conferences/single-conference.php';
		}
	}

	return $single;
}

function hide_header_body_class( $classes ) {

	global $post;

	if ( carbon_get_post_meta( $post->ID, 'ccm_page_header' ) ) {
		$classes[] = 'hide-header';
	}

	return $classes;
}

function fix_the_title( $title, $name, $id, $type, $subtype ){
  if( $subtype === 'ccm_sessions'){
  	$suffix = carbon_get_post_meta($id , 'ccm_session_date_label' );
  	$time = carbon_get_post_meta( $id , 'ccm_start_time');
  	$new_title = $title.'-'.$suffix.' ('.$time.')';
  	return $new_title;
  } else {
  	return $title;
  }
}

