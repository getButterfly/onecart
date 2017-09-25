<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

$id = $_GET['id'];
$post = get_post($id);
ocart_the_content(130,'...','', $post->ID,'char');

?>

