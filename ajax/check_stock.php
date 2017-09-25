<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

$data = array();

$post = get_post( $_GET['id'] );
setup_postdata($post);

echo json_encode($data);

?>