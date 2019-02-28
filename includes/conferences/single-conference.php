<?php

use Conferences\Helpers;

global $post;

get_header();

?>

<div class="single-conferences" id="conferences-<?php echo $post->ID; ?>">


	<?php

	Helpers\session_list_display( $post->ID, 'ccm_sessions_details' );

	?>

</div>

<?php

get_footer();

?>


