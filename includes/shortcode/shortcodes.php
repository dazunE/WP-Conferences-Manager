<?php

use Conferences\Helpers;

function speaker_short_code( $atts ) {

	$atts = shortcode_atts(
		array(
			'ids'        => null,
			'session'    => null,
			'conference' => null,
			'bio'        => true,
			'social'    => true
		),
		$atts,
		'ccm_speakers'
	);

	ob_start();

	echo '<div class="speakers-wrap">';

	if ( isset( $atts['ids'] ) ) {

		$speaker_ids = explode( ',', $atts['ids'] );

		foreach ( array_unique( $speaker_ids ) as $speaker_id ) {

			echo'<div class="speaker-profile">';
			Helpers\get_the_speaker_profile_by_id( $speaker_id , $atts['bio'] , $atts['social']);
			echo '</div>';

		}

	}

	echo '</div>';

	return ob_get_clean();

}

add_shortcode( 'ccm_speakers', 'speaker_short_code' );


