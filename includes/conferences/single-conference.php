<?php

global $post;

get_header();

var_dump( get_post_meta( $post->ID ) );


get_footer();

?>


