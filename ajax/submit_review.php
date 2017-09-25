<?php
// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

$data = array();

// logged in / guest
if (is_user_logged_in()) {
    $userdata = wp_get_current_user();

    $_POST['author'] = $user_identity;
    $_POST['email'] = $userdata->user_email;
    $user_id = $userdata->ID;
} else {
    $user_id = 0;
}

// validation side
if (!isset($_POST['author']) || empty($_POST['author'])) {
	$data['error'] = 'author';
	echo json_encode($data);
	exit;
} elseif (!isset($_POST['email']) || empty($_POST['email'])) {
	$data['error'] = 'email';
	echo json_encode($data);
	exit;
} elseif (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
	$data['error'] = 'email';
	echo json_encode($data);
	exit;
} elseif (!isset($_POST['comment']) || empty($_POST['comment'])) {
	$data['error'] = 'comment';
	echo json_encode($data);
	exit;
} elseif (!(int)$_POST['rating']) {
	$data['error_rate'] = __('Please rate this product.','ocart');
	echo json_encode($data);
	exit;
} else {

	// insert comment
	$time = current_time('mysql');
	$comment_arr = array(
		'comment_post_ID' => $_POST['comment_post_ID'], // product ID
		'comment_author' => $_POST['author'],
		'comment_author_email' => $_POST['email'],
		'comment_author_url' => '',
		'comment_content' => wp_filter_nohtml_kses($_POST['comment']),
		'comment_type' => '',
		'comment_parent' => 0,
		'user_id' => $user_id,
		'comment_author_IP' => ocart_visitor_IP(),
		'comment_agent' => '',
		'comment_date' => $time,
		'comment_approved' => 1,
	);
	$comment_id = wp_insert_comment($comment_arr);
	
	// add rating meta
	add_comment_meta( $comment_id, 'rating', $_POST['rating'] );
	
	$post_comments = get_comments(array( 'type' => 'comment', 'status' => 'approve', 'post_id' => $_POST['comment_post_ID'], 'count' => true ));
	$data['count_of_reviews'] = $post_comments;
	
	echo json_encode($data);

}

?>