<?php

use Conferences\Helpers;


get_header();

?>
<div class="single-conferences">
	<?php

	while ( have_posts() ) : the_post();

		?>
        <div class="conference-header">
            <div class="conference-logo">
				<?php the_post_thumbnail( array( 100, 100 ) ) ?>
            </div>
            <?php
            Helpers\get_conference_header_section( get_the_ID() );
            ?>
        </div>
		<?php

		Helpers\session_list_display( get_the_ID(), 'ccm_sessions_details' );

	endwhile;

	?>
</div>
<?php

get_footer();

?>


