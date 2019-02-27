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

			$rows = $session['ccm_session_display'];

			$first_session = $session['ccm_session_post_type'][0]['id'];

			$date = 'Day 0' . $day . ' - ' . get_session_date_by_id( $first_session );

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

	?>

    <div class="single-session">
        <h4><?php echo $post->post_title; ?></h4>
        <p class="session-time">
			<span class="start-time">
				<?php echo carbon_get_post_meta( $session_id, 'ccm_start_time' ) ?>
			</span>
            <span class="end-time">
				<?php echo carbon_get_post_meta( $session_id, 'ccm_end_time' ) ?>
			</span>
        </p>
		<?php echo $post->post_content; ?>
    </div>

	<?php


}

function get_session_date_by_id( $session_id ) {

	return carbon_get_post_meta( $session_id, 'ccm_session_date' );
}

function get_conference_header_section( $conference_id ){

    $page_title = carbon_get_post_meta( $conference_id , 'ccm_page_title' );
    $page_teaser = carbon_get_post_meta( $conference_id ,'ccm_conference_teaser');
	$conference_header = carbon_get_post_meta($conference_id , 'ccm_conference_header');
	$conference_intro = carbon_get_post_meta( $conference_id , 'ccm_conference_paragraph' );
	$confernce_video = carbon_get_post_meta( $conference_id ,'ccm_conference_video');

    if( !$page_title ){
        echo sprintf('<h2>%s</h2>', get_the_title( $conference_id ) );
    }

    if( !empty( $page_teaser ) ){
	    echo sprintf('<h2>%s</h2>', $page_teaser );
    }

	if( !empty( $conference_header ) ){
		echo sprintf('<h3>%s</h3>', $conference_header );
	}

	if( !empty( $conference_intro ) ) {
	    echo sprintf( '<div class="conference-intro">%s</div>' , wpautop($conference_intro) );
    }

	if( !empty( $confernce_video) ){
	    echo sprintf( '<div class="conference-video">%s</div>', wp_oembed_get( $confernce_video )
            );
    }



}