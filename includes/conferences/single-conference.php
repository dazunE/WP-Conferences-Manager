<?php

use Conferences\Helpers;

global $post;

get_header();
?>
<pre>
	<?php

	Helpers\session_list_display( $post->ID, 'ccm_sessions_details');

	?>
</pre>

<?php
get_footer();

?>


