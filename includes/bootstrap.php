<?php

namespace Conferences;

add_action( 'after_setup_theme', __NAMESPACE__ . '\\boot_carbon_fields' );
add_action( 'wp_enqueue_scripts' , __NAMESPACE__ . '\\enqueues_front_end_styles' );
add_filter( 'single_template', __NAMESPACE__ . '\\load_single_page_templates' );


function boot_carbon_fields() {
	\Carbon_Fields\Carbon_Fields::boot();
}

function enqueues_front_end_styles () {
	if( is_singular(CONFERENCE_POST_TYPE ) || is_singular( SPEAKER_POST_TYPE )){
		wp_enqueue_style( 'single-conferences', CEYLON_CONF_PLUGIN_URL.'assets/css/conferences.css');
	}
	wp_enqueue_style( 'ccm_shortcodes', CEYLON_CONF_PLUGIN_URL.'assets/css/shot-codes.css');
	wp_enqueue_style( 'ccm_icon-fonts' , CEYLON_CONF_PLUGIN_URL. 'assets/css/icons.css');
}

function load_single_page_templates( $single ) {

	global $post;

	if ( $post->post_type == CONFERENCE_POST_TYPE ) {
		if ( file_exists( CEYLON_CONF_PLUGIN_PATH . '/templates/single-conference.php' ) ) {
			return CEYLON_CONF_PLUGIN_PATH . '/templates/single-conference.php';
		}
	}

	if ( $post->post_type == SPEAKER_POST_TYPE ) {
		if ( file_exists( CEYLON_CONF_PLUGIN_PATH . '/templates/single-speaker.php' ) ) {
			return CEYLON_CONF_PLUGIN_PATH . '/templates/single-speaker.php';
		}
	}


	return $single;
}
