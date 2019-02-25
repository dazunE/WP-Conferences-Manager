<?php

namespace CeylonConferences\Sessions;

use Carbon_Fields\Container;
use Carbon_Fields\Field;


const SESSION_POST_TYPE = 'cp_sessions';

add_action( 'init', __NAMESPACE__ . '\\register_sessions_post_type' );
add_action( 'init', __NAMESPACE__ . '\\session_types_taxonomy' );
add_action( 'carbon_fields_register_fields', __NAMESPACE__ . '\\sessions_post_meta_fields' );
//add_action( 'admin_menu', __NAMESPACE__ . '\\add_session_sub_menu' );
add_action( 'manage_' . SESSION_POST_TYPE . '_posts_custom_column', __NAMESPACE__ . '\\session_additional_columns_data', 2, 10 );
add_filter( 'manage_' . SESSION_POST_TYPE . '_posts_columns', __NAMESPACE__ . '\\session_additional_columns', 1, 10 );
add_filter( 'manage_edit-' . SESSION_POST_TYPE . '_sortable_columns', __NAMESPACE__ . '\\session_manage_sortable_columns' );

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
		'show_in_menu'        => true,
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

function sessions_post_meta_fields() {

	Container::make( 'post_meta', __( 'Speaker Information', CEYLON_CONF_TEXT_DOMAIN ) )
	         ->where( 'post_type', '=', SESSION_POST_TYPE )
	         ->add_fields( array(
		         Field::make( 'association', 'ccm_session_speaker', __( 'Session Speaker', CEYLON_CONF_TEXT_DOMAIN ) )
		              ->set_types( array(
			              array(
				              'type'      => 'post',
				              'post_type' => 'cp_speakers',
			              )
		              ) )
		              ->set_duplicates_allowed( false )
		              ->set_max( 2 )

	         ) );

	Container::make( 'post_meta', __( 'Session Information', CEYLON_CONF_TEXT_DOMAIN ) )
	         ->where( 'post_type', '=', SESSION_POST_TYPE )
	         ->set_context( 'side' )
	         ->add_fields( array(
		         Field::make( 'date', 'ccm_session_date', __( 'Session Date', CEYLON_CONF_TEXT_DOMAIN ) )
		              ->set_attribute( 'placeholder', __( 'Date of event start', CEYLON_CONF_TEXT_DOMAIN ) )
		              ->set_storage_format( get_option( 'date_format' ) ),
		         Field::make( 'text' , 'ccm_session_date_label' , __( 'Date Label', CEYLON_CONF_TEXT_DOMAIN ) ),
		         Field::make( 'time', 'ccm_start_time', __( 'Starting time', CEYLON_CONF_TEXT_DOMAIN ) )
		              ->set_attribute( 'placeholder', 'Session Start Time', CEYLON_CONF_TEXT_DOMAIN )
	         ->set_storage_format( get_option( 'time_format' ) ),
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

function add_session_sub_menu() {
	add_submenu_page(
		'edit.php?post_type=cp_conferences',
		__( 'Sessions', CEYLON_CONF_TEXT_DOMAIN ),
		__( 'Sessions', CEYLON_CONF_TEXT_DOMAIN ),
		'manage_options',
		'edit.php?post_type=' . SESSION_POST_TYPE
	);
}

function session_additional_columns( $columns ) {

	$columns = array_slice( $columns, 0, 2, true ) + array( 'ccm_session_speakers' => __( 'Speakers', CEYLON_CONF_TEXT_DOMAIN ) ) + array_slice( $columns, 2, null, true );
	$columns = array_slice( $columns, 0, 1, true ) + array( 'ccm_session_date' => __( 'Session Date', CEYLON_CONF_TEXT_DOMAIN ) ) + array_slice( $columns, 1, null, true );
	$columns = array_slice( $columns, 0, 1, true ) + array( 'ccm_session_time' => __( 'Time', CEYLON_CONF_TEXT_DOMAIN ) ) + array_slice( $columns, 1, null, true );

	return $columns;
}

function session_additional_columns_data( $column, $post_id ) {

	switch ( $column ) {
		case 'ccm_session_time':
			$session_time = carbon_get_post_meta( $post_id, 'ccm_start_time' ) ;
			$session_time = ( $session_time ) ? $session_time  : '&mdash;';
			echo esc_html( $session_time );
			break;

		case 'ccm_session_date':
			$session_date = carbon_get_post_meta( $post_id, 'ccm_session_date' );
			echo esc_html( $session_date );
			break;

		case 'ccm_session_speakers':
			$speakers     = array();
			$speakers_ids = array();
			$speakers_obj = carbon_get_post_meta( $post_id, 'ccm_session_speaker' );

			if ( ! empty( $speakers_obj ) ) {
				foreach ( $speakers_obj as $single_speaker ) {
					array_push( $speakers_ids, $single_speaker['id'] );
				}
			}
			if ( ! empty( $speakers_obj ) ) {
				$speakers = get_posts( array(
					'post_type'      => 'cp_speakers',
					'posts_per_page' => - 1,
					'post__in'       => $speakers_ids,
				) );
			}

			$output = array();

			foreach ( $speakers as $speaker ) {
				$output[] = sprintf( '<a href="%s">%s</a>', esc_url( get_edit_post_link( $speaker->ID ) ), esc_html( apply_filters( 'the_title', $speaker->post_title ) ) );
			}

			echo implode( ', ', $output );

			break;
	}

}

function session_manage_sortable_columns( $sortable ) {

	$sortable['ccm_session_time'] = '_ccm_session_time';
	$sortable['ccm_session_date'] = 'ccm_session_date';

	return $sortable;
}

function session_types_taxonomy() {

	$labels  = array(
		'name'                       => _x( 'Types', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Type', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Type', 'text_domain' ),
		'all_items'                  => __( 'All Types', 'text_domain' ),
		'parent_item'                => __( 'Parent Type', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Type:', 'text_domain' ),
		'new_item_name'              => __( 'New Type Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Type', 'text_domain' ),
		'edit_item'                  => __( 'Edit Type', 'text_domain' ),
		'update_item'                => __( 'Update Type', 'text_domain' ),
		'view_item'                  => __( 'View Type', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove types', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Types', 'text_domain' ),
		'search_items'               => __( 'Search Types', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No types', 'text_domain' ),
		'items_list'                 => __( 'Types list', 'text_domain' ),
		'items_list_navigation'      => __( 'Types list navigation', 'text_domain' ),
	);
	$rewrite = array(
		'slug'         => 'sessions-type',
		'with_front'   => true,
		'hierarchical' => false,
	);
	$args    = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'rewrite'           => $rewrite,
	);
	register_taxonomy( 'ccm_session_type', array( SESSION_POST_TYPE ), $args );

}





