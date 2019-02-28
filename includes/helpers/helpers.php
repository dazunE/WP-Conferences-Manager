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

			$date = 'Day 0'.$day.' - '.get_session_date_by_id($first_session);

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

			echo sprintf('</div></div>');

		}
	}

}

function get_session_data_by_id( $session_id ) {

	$post = get_post($session_id );

	?>

	<div class="single-session">
        <?php get_speaker_by_session_id( $session_id ) ;?>
		<h4><?php echo $post->post_title;?> - <?php get_speaker_name_by_id( $session_id) ;?></h4>
		<p class="session-time">
			<span class="start-time">
				<?php echo carbon_get_post_meta( $session_id , 'ccm_start_time')?>
			</span>
			<span class="end-time">
				<?php echo carbon_get_post_meta( $session_id , 'ccm_end_time')?>
			</span>
		</p>
		<?php echo $post->post_content;?>
	</div>

    <?php


}

function get_session_date_by_id( $id ) {

	return carbon_get_post_meta( $id, 'ccm_session_date' );
}

function get_speaker_by_session_id( $id ){

  $speakers = carbon_get_post_meta( $id, 'ccm_session_speaker');

  if( !empty( $speakers ) ) {
      foreach ( $speakers as $speaker ){
          echo sprintf(
                  '<div class="speaker-avatar">%s</div>',
                  get_the_post_thumbnail( $speaker['id'] , array(120,120))
          );
      }
  }
}

function get_speaker_name_by_id( $id ){

	$speakers = carbon_get_post_meta( $id, 'ccm_session_speaker');

	if( !empty( $speakers ) ) {
		foreach ( $speakers as $speaker ){
			echo sprintf(
				'<span class="speaker-title">%s</span>'
                ,get_the_title($speaker['id'])
			);
		}
	}

}
