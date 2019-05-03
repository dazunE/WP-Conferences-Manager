<?php

use Conferences\Helpers;

get_header();

?>
    <div class="conf-container">
		<?php while ( have_posts() ) : the_post(); ?>
            <div class="conf_header">
                <div class="conf_header_logo">
					<?php the_post_thumbnail( array( 100, 100 ) ); ?>
                </div>
				<?php Helpers\get_conference_header_section( get_the_ID() ); ?>
            </div>
            <div class="conf_content">
                <?php Helpers\get_conference_middle_section( get_the_ID() );?>
            </div>
			<?php Helpers\session_list_display( get_the_ID(), 'ccm_sessions_details' );?>
            <div class="conf_content__bottom">
                <?php Helpers\get_conference_bottom_section( get_the_ID() ); ?>
            </div>
            <div class="conf_default_content">
                <?php the_content();?>
            </div>
		<?php endwhile; ?>
    </div>
<?php
get_footer();
?>
