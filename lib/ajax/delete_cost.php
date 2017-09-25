<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../../wp-load.php');

global $ocart;
$data = array();

$post_id = $_GET['post_id'];
$data['success'] = '<span class="success">'.__('Successfully deleted!','ocart').'</span>';
delete_post_meta( $post_id, $_GET['field'] );

echo json_encode($data);

?>