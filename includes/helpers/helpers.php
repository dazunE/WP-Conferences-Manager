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
				'<div class="conf_sessions">
			    			<h3 class="conf_sessions__title">%s</h3>
			    		<div class="conf_sessions__row columns__%s">',
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

/**
 * @param $session_id
 */
function get_session_data_by_id( $session_id ) {

	$post = get_post( $session_id );

	$speakers = carbon_get_post_meta( $session_id, 'ccm_session_speaker' );


	?>
    <div class="conf_sessions__single <?php echo 'sepeakers-' . sizeof( $speakers ); ?>">
		<?php

		$speaker_collections = array();


		foreach ( $speakers as $speaker ) {


			echo sprintf( '<div class="speaker__image">%s</div>',
				get_the_post_thumbnail( $speaker['id'], array( 100, 100 ) ) );
			array_push( $speaker_collections, get_the_title( $speaker['id'] ) );
		}

		echo sprintf(
			'<p class="conf_sessions__time"><span>%s</span>-<span>%s</span><span>(%s)</span></p>',
			carbon_get_post_meta( $session_id, 'ccm_start_time' ),
			carbon_get_post_meta( $session_id, 'ccm_end_time' ),
			carbon_get_post_meta( $session_id, 'ccm_session_timezone' )
		);

		echo sprintf(
			'<h4>%s<span>&nbsp;-&nbsp;</span><span class="speaker__name">%s</span></h4>',
			$post->post_title,
			implode( ", ", $speaker_collections ) );


		echo sprintf( '<div class="session_content">%s</div>', $post->post_content );

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
		echo sprintf( '<h3>%s</h3>', get_the_title( $conference_id ) );
	}
	if ( ! empty( $page_teaser ) ) {
		echo sprintf( '<h3>%s</h3>', $page_teaser );
	}
	if ( ! empty( $conference_header ) ) {
		echo sprintf( '<div class="conf_header__paragraph">%s</div>', wpautop( $conference_header ) );
	}
	if ( ! empty( $conference_intro ) ) {
		echo sprintf( '<div class="conf_header__intro">%s</div>', wpautop( $conference_intro ) );
	}
	if ( ! empty( $conference_button ) ) {
		echo sprintf( '<div class="conf_header__cta"><a href="%s" class="btn purchase_btn">%s</a></div> ', esc_url( $conference_purchase_url ), $conference_button );
	}
	if ( ! empty( $conference_video ) ) {
		echo sprintf( '<div class="conf_header__video">%s</div>', wp_oembed_get( $conference_video )
		);
	}

}

function get_conference_middle_section( $conference_id ) {

	$ticket_details = carbon_get_post_meta( $conference_id, 'ccm_conference_event_ticket' );
	$session_intro  = carbon_get_post_meta( $conference_id, 'ccm_session_intro' );

	if ( ! empty( $ticket_details ) ) {
		echo sprintf( '<div class="conf_content__ticket">%s</div>', wpautop( $ticket_details ) );
	}

	if ( ! empty( $session_intro ) ) {
		echo sprintf( '<h3 class="conf_content__session-intro">%s</h3>', $session_intro );
	}
}

function get_conference_bottom_section( $conference_id ) {

	$why_conference_title    = carbon_get_post_meta( $conference_id, 'ccm_why_title' );
	$why_conference_data     = carbon_get_post_meta( $conference_id, 'ccm_conference_why_conference' );
	$conference_guarantee    = carbon_get_post_meta( $conference_id, 'ccm_conference_gurantee' );
	$conference_testimonials = carbon_get_post_meta( $conference_id, 'ccm_testimonials' );

	$item = 0;

	echo sprintf( '<h3>%s</h3>', $why_conference_title );

	if ( ! empty( $why_conference_data ) ) {

		foreach ( $why_conference_data as $single_item ) {

			$item ++;

			echo sprintf(
				'<div class="conf_why__item"><div class="conf_why__item-number">%s</div><h4>%s</h4>%s</div>',
				$item,
				$single_item['ccm_why_conference_title'],
				wpautop( $single_item['ccm_why_conference_paragraph'] )
			);

		}
	}

	if ( ! empty( $conference_guarantee ) ) {
		echo sprintf( '<div class="conf_guarantee">%s</div>', wpautop( $conference_guarantee ) );
	}

	if ( ! empty( $conference_testimonials ) ) {
		get_posts_from_collection( $conference_testimonials );
	}

}

function get_posts_from_collection( $collection ) {

	echo '<div class="conf_testimonials"><h3>What Past Attendees Are Saying</h3>';

	foreach ( $collection as $single_item ) {
		$post = get_post( absint( $single_item['id'] ) );
		echo sprintf(
			'<div class="conf_testimonial__items">%s<p> ~ <i>%s</i></p><div class="conf_testimonial__item-image">%s</div></div>',
			$post->post_content,
			$post->post_title,
			get_the_post_thumbnail( $post, array( 100, 100 ) )

		);
	}

	echo '</div>';

}

function get_the_speaker_profile_by_id ( $id , $bio , $social ) {


    echo sprintf(
            '<a href="%s"><h3>%s</h3></a>%s<div class="speaker-bio">%s</div>',
        get_the_permalink( $id ),
        get_the_title( $id ),
        get_the_post_thumbnail( $id , array(100,100)),
        $bio ? get_post_field('post_content', $id ) : ''

    );

    if( $social ) {

        echo '<div class="social-links-wrap">';
	    get_social_links_from_post_id( $id );
	    echo '</div>';
    }


}

function get_social_links_from_post_id( $post_id ) {

	$social_links = carbon_get_post_meta( $post_id, 'ccm_social_links' );

	if ( isset( $social_links ) ) {
	    echo '<ul class="social-links">';
		foreach ( $social_links as $link ) {

			echo sprintf( '<li><a href="%s"><i class="fi flaticon-%s"></i> </a></li>'
				, $link['ccm_social_network_link'],
				$link['ccm_social_network_type']
			);
		}
		echo '</ul>';
	}

}
