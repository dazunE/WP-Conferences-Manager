<?php

namespace Conferences\Helpers;
/**
 * @param $id
 * @param $meta_field
 */
function session_list_display( $id, $meta_field ) {

	$session_data = carbon_get_post_meta( $id, $meta_field );

	if ( ! empty( $session_data ) ) {

		$day = 0;

		foreach ( $session_data as $session ) {
			$day ++;
			$rows          = $session['ccm_session_display'];
			$first_session = $session['ccm_session_post_type'][0]['id'];
			$date          = 'Day 0' . $day . ' - ' . get_session_date_by_id( $first_session );
			echo sprintf(
				'<div class="sessions-wrap">
			    			<h3>%s</h3>
			    		<div class="sessions-row columns-%s">',
				$date,
				$rows
			);
			foreach ( $session['ccm_session_post_type'] as $item ) {

				get_session_data_by_id( absint( $item['id'] ) );
			}
			echo sprintf( '</div></div>' );
		}
	}
}

function get_session_data_by_id( $session_id ) {

	$post = get_post( $session_id );

	$speakers = carbon_get_post_meta( $session_id, 'ccm_session_speaker' );


	?>
    <div class="single-session">
		<?php

		$speaker_collections = array();

		foreach ( $speakers as $speaker ) {
			echo sprintf( '<div class="speaker-image">%s</div>',
				get_the_post_thumbnail( $speaker['id'], array( 100, 100 ) ) );
			array_push( $speaker_collections, get_the_title( $speaker['id'] ) );
		}

		echo sprintf(
			'<h4>%s<span>-</span></h4><span class="speaker-name">%s</span>',
			$post->post_title,
			implode( ",", $speaker_collections ) );

		echo sprintf(
			'<p class="session-time"><span>%s</span>-<span>%s</span><span>(%s)</span></p>',
			carbon_get_post_meta( $session_id, 'ccm_start_time' ),
			carbon_get_post_meta( $session_id, 'ccm_end_time' ),
			carbon_get_post_meta( $session_id, 'ccm_session_timezone' )
		);

		echo sprintf( '<div class="session-description">%s</div>', $post->post_content );

		?>
    </div>

	<?php
}

function get_session_date_by_id( $session_id ) {

	return carbon_get_post_meta( $session_id, 'ccm_session_date' );

}

function get_conference_header_section( $conference_id ) {

	$page_title              = carbon_get_post_meta( $conference_id, 'ccm_page_title' );
	$page_teaser             = carbon_get_post_meta( $conference_id, 'ccm_conference_teaser' );
	$conference_header       = carbon_get_post_meta( $conference_id, 'ccm_conference_header' );
	$conference_intro        = carbon_get_post_meta( $conference_id, 'ccm_conference_paragraph' );
	$conference_video        = carbon_get_post_meta( $conference_id, 'ccm_conference_video' );
	$conference_button       = carbon_get_post_meta( $conference_id, 'ccm_conference_button_text' );
	$conference_purchase_url = carbon_get_post_meta( $conference_id, 'ccm_conference_button_url' );

	if ( ! $page_title ) {
		echo sprintf( '<h2>%s</h2>', get_the_title( $conference_id ) );
	}
	if ( ! empty( $page_teaser ) ) {
		echo sprintf( '<h2>%s</h2>', $page_teaser );
	}
	if ( ! empty( $conference_header ) ) {
		echo sprintf( '<div>%s</div>', wpautop( $conference_header ) );
	}
	if ( ! empty( $conference_intro ) ) {
		echo sprintf( '<div class="conference-intro">%s</div>', wpautop( $conference_intro ) );
	}
	if ( !empty( $conference_button) ){
		echo  sprintf( '<a href="%s" class="btn conference-purchase-btn">%s</a>', esc_url($conference_purchase_url), $conference_button  );
	}
	if ( ! empty( $conference_video ) ) {
		echo sprintf( '<div class="conference-video">%s</div>', wp_oembed_get( $conference_video )
		);
	}

}
