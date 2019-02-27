<?php

namespace Conferences;

add_action( 'after_setup_theme', __NAMESPACE__ . '\\boot_carbon_fields' );
add_action( 'wp_enqueue_scripts' , __NAMESPACE__ . '\\enques_front_end_styles' );


function boot_carbon_fields() {
	\Carbon_Fields\Carbon_Fields::boot();
}

function enques_front_end_styles () {
	if( is_singular('ccm_conferences')){
		wp_enqueue_style( 'single-conferences', CEYLON_CONF_PLUGIN_URL.'/assets/css/conferences.css');
	}
}
