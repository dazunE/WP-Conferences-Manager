<?php

use Conferences\Helpers;

function speaker_short_code( $atts ) {

	$atts = shortcode_atts(
		array(
			'ids'        => null,
			'session'    => null,
			'conference' => null,
			'bio'        => true,
			'social'     => true
		),
		$atts,
		'ccm_speakers'
	);

	ob_start();

	echo '<div class="speakers-wrap">';

	if ( isset( $atts['ids'] ) ) {

		$speaker_ids = explode( ',', $atts['ids'] );

		foreach ( array_unique( $speaker_ids ) as $speaker_id ) {

			echo '<div class="speaker-profile">';
			Helpers\get_the_speaker_profile_by_id( $speaker_id, $atts['bio'], $atts['social'] );
			echo '</div>';

		}

	}

	echo '</div>';

	return ob_get_clean();

}

add_shortcode( 'ccm_speakers', 'speaker_short_code' );

function speaker_session_combine_search() {

	$args_speaker = get_posts(
		array(
			'post_type' => SPEAKER_POST_TYPE,
			'post_status' => 'publish',
			's' => 'Edirisinghe'
		)
	);

	$args_sessions = get_posts(

	);

	var_dump( $args_speaker );



}

add_shortcode( 'ccm_search', 'speaker_session_combine_search' );
