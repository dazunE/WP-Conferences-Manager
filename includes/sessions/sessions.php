<?php

namespace CeylonConferences\Sessions;

use Carbon_Fields\Container;
use Carbon_Fields\Field;


const SESSION_POST_TYPE = 'cp_sessions';

add_action( 'init', __NAMESPACE__ . '\\register_sessions_post_type' );
add_action( 'carbon_fields_register_fields', __NAMESPACE__ . '\\sessions_post_meta_fields' );
add_action( 'admin_menu' ,__NAMESPACE__. '\\add_session_sub_menu');
//add_action( 'manage_cp_sessions_posts_custom_column' , __NAMESPACE__.'\\sepaker_thumbnail_column' , 2 , 10 );
//add_filter( 'manage_cp_sessions_posts_columns' ,__NAMESPACE__.'\\add_session_thumbnail_to_column' , 1 , 10 );

function register_sessions_post_type() {

	$labels = array(
		'name'                  => _x( 'Sessions', 'Post Type General Name', CEYLON_CONF_TEXT_DOMAIN ),
		'singular_name'         => _x( 'Session', 'Post Type Singular Name', CEYLON_CONF_TEXT_DOMAIN ),
		'menu_name'             => __( 'Sessions', CEYLON_CONF_TEXT_DOMAIN ),
		'name_admin_bar'        => __( 'Sessions', CEYLON_CONF_TEXT_DOMAIN ),
		'archives'              => __( 'Sessions Archives', CEYLON_CONF_TEXT_DOMAIN ),
		'attributes'            => __( 'Sessions Attributes', CEYLON_CONF_TEXT_DOMAIN ),
		'parent_item_colon'     => __( 'Parent Item:', CEYLON_CONF_TEXT_DOMAIN ),
		'all_items'             => __( 'All sessions', CEYLON_CONF_TEXT_DOMAIN ),
		'add_new_item'          => __( 'Add New session', CEYLON_CONF_TEXT_DOMAIN ),
		'add_new'               => __( 'Add New', CEYLON_CONF_TEXT_DOMAIN ),
		'new_item'              => __( 'New session', CEYLON_CONF_TEXT_DOMAIN ),
		'edit_item'             => __( 'Edit session', CEYLON_CONF_TEXT_DOMAIN ),
		'update_item'           => __( 'Update session', CEYLON_CONF_TEXT_DOMAIN ),
		'view_item'             => __( 'View session', CEYLON_CONF_TEXT_DOMAIN ),
		'view_items'            => __( 'View sessions', CEYLON_CONF_TEXT_DOMAIN ),
		'search_items'          => __( 'Search sessions', CEYLON_CONF_TEXT_DOMAIN ),
		'not_found'             => __( 'Not found', CEYLON_CONF_TEXT_DOMAIN ),
		'not_found_in_trash'    => __( 'Not found in Trash', CEYLON_CONF_TEXT_DOMAIN ),
		'featured_image'        => __( 'session Image', CEYLON_CONF_TEXT_DOMAIN ),
		'set_featured_image'    => __( 'Set session image', CEYLON_CONF_TEXT_DOMAIN ),
		'remove_featured_image' => __( 'Remove featured image', CEYLON_CONF_TEXT_DOMAIN ),
		'use_featured_image'    => __( 'Use as session image', CEYLON_CONF_TEXT_DOMAIN ),
		'uploaded_to_this_item' => __( 'Uploaded to this session', CEYLON_CONF_TEXT_DOMAIN ),
		'items_list'            => __( 'Sessions list', CEYLON_CONF_TEXT_DOMAIN ),
		'items_list_navigation' => __( 'Sessions list navigation', CEYLON_CONF_TEXT_DOMAIN ),
		'filter_items_list'     => __( 'Filter sessions list', CEYLON_CONF_TEXT_DOMAIN ),
	);

	$rewrite = array(
		'slug'       => 'sessions',
		'with_front' => true,
		'pages'      => true,
		'feeds'      => true,
	);

	$args = array(
		'label'               => __( 'Session', CEYLON_CONF_TEXT_DOMAIN ),
		'description'         => __( 'Conference Manager Sessions', CEYLON_CONF_TEXT_DOMAIN ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => false,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-list-view',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => 'sessions',
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite,
		'capability_type'     => 'post',
		'show_in_rest'        => true,
	);

	register_post_type( SESSION_POST_TYPE, $args );

}

function add_session_thumbnail_to_column( $columns ) {
	return array_merge( $columns,
		array( 'thumbnail' => __( 'session Avatar', CEYLON_CONF_TEXT_DOMAIN ) ) );
}

function sessions_post_meta_fields() {

	Container::make( 'post_meta', __( 'Speaker Information', CEYLON_CONF_TEXT_DOMAIN ) )
	         ->where( 'post_type', '=', SESSION_POST_TYPE )
	         ->set_context( 'side' )
	         ->add_fields( array(
		         Field::make( 'association', 'ccm_session_speaker', __( 'Session Speaker', CEYLON_CONF_TEXT_DOMAIN ) )
		              ->set_types( array(
			              array(
				              'type'      => 'post',
				              'post_type' => 'cp_speakers',
			              )
		              ) )
		              ->set_duplicates_allowed( false )
		              ->set_max( 1 )

	         ) );

	Container::make( 'post_meta', __( 'Session Information', CEYLON_CONF_TEXT_DOMAIN ) )
	         ->where( 'post_type', '=', SESSION_POST_TYPE )
	         ->set_context( 'side' )
	         ->add_fields( array(
		         Field::make( 'date', 'ccm_session_date', __( 'Event Start Date', CEYLON_CONF_TEXT_DOMAIN ) )
		              ->set_attribute( 'placeholder', __( 'Date of event start', CEYLON_CONF_TEXT_DOMAIN ) )
		              ->set_storage_format( 'Y-m-d' ),
		         Field::make( 'time', 'ccm_', __( 'Starting time', CEYLON_CONF_TEXT_DOMAIN ) )
		              ->set_attribute( 'placeholder', 'Session Start Time', CEYLON_CONF_TEXT_DOMAIN ),
		         Field::make( 'select', 'ccm_session_type', __( 'Session Type', CEYLON_CONF_TEXT_DOMAIN ) )
		              ->set_options( array(
			              'regular' => __( 'Regular Session', CEYLON_CONF_TEXT_DOMAIN ),
			              'break'   => __( 'Session Break', CEYLON_CONF_TEXT_DOMAIN ),
		              ) ),
		         Field::make( 'text', 'ccm_session_slides', __( 'Session Slides', CEYLON_CONF_TEXT_DOMAIN ) )
		              ->set_attribute( 'type', 'url' ),
		         Field::make( 'text', 'ccm_session_video', __( 'Session Video', CEYLON_CONF_TEXT_DOMAIN ) )
		              ->set_attribute( 'type', 'url' )
	         ) );

}

function add_session_sub_menu(){
	add_submenu_page(
		'edit.php?post_type=cp_conferences',
		__('Sessions',CEYLON_CONF_TEXT_DOMAIN),
		__('Sessions',CEYLON_CONF_TEXT_DOMAIN),
		'manage_options',
		'edit.php?post_type='.SESSION_POST_TYPE
	);
}
