<?php

namespace Conferences;

add_action( 'after_setup_theme', __NAMESPACE__ . '\\boot_carbon_fields' );


function boot_carbon_fields() {
	\Carbon_Fields\Carbon_Fields::boot();
}

