<?php

use Conferences\Helpers;

get_header();

while ( have_posts() ) : the_post();

?>
<div class="conf_single-speaker">
	<?php the_title('<h2>','</h2>');?>
	<div class="speaker-image">
		<?php the_post_thumbnail( array( 100, 100 ) ); ?>
	</div>
	<div class="social-links-wrap">
        <?php Helpers\get_social_links_from_post_id( get_the_ID() ); ?>
	</div>
	<div class="speaker_content">
        <?php the_content();?>
	</div>
</div>
<?php endwhile ;?>
<?php get_footer(); ?>


