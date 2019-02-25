<?php

global $post;

get_header();
?>
<pre>
	<?php

	var_dump( carbon_get_post_meta( $post->ID ,'ccm_sessions_details') );

	?>
</pre>

<?php
get_footer();

?>


