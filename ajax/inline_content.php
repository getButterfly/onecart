<?php

// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

// request product ratings
add_action( 'comment_form_logged_in_after', 'additional_fields' );
add_action( 'comment_form_after_fields', 'additional_fields' );
function additional_fields () {
?>
	<div class="rating-title"><?php _e('Rate this product','ocart'); ?></div>
	<div class="rating">
		<div class="ratings_stars" id="star_1"></div>
		<div class="ratings_stars" id="star_2"></div>
		<div class="ratings_stars" id="star_3"></div>
		<div class="ratings_stars" id="star_4"></div>
		<div class="ratings_stars" id="star_5"></div>
		<div class="clear"></div>
    </div>
	<input type="hidden" name="rating" id="rating" value="" />
<?php
}

// tab, product id, fetch post
$tab = $_GET['tab'];
$id = $_GET['id'];
$post = get_post($id);
setup_postdata($post);

// content tab
if ($tab == 'tab_content') {
	if (get_the_content()) {
		the_content();
	} else {
		echo '<p>'.__('No description has been entered yet.','ocart').'</p>';
	}
}

// custom tab
if ($tab == 'tab_custom') {
	$tab_content = get_post_meta($post->ID, 'customtab_content', true);
	echo wpautop($tab_content);
	echo '<div class="clear"></div>';
}

// video
if ($tab == 'tab_video') {
	echo '<p>'.sprintf(__('Product video is playing in a popup. <a href="javascript:lightbox(null, \'%s/ajax/playvideo.php\', \'\', \'%s\')">Replay?</a>','ocart'), get_template_directory_uri(), $id).'</p>';
}

// reviews
if ($tab == 'tab_reviews') {
	$post_comments = get_comments(array( 'type' => 'comment', 'status' => 'approve', 'post_id' => $post->ID ));
	if (!$post_comments) {
	
		// no reviews
		echo '<p>'.__('No reviews has been submitted for this product yet.','ocart').'</p>';
		
		// add review form
		global $aria_req;
		$fields = array(
			'author' => '<p class="comment-form-author"><input id="author" name="author" type="text" value="" placeholder="'.__('Name','ocart').'" size="30"' . $aria_req . ' /><span class="is_req">*</span></p>',
			'email'  => '<p class="comment-form-email"><input id="email" name="email" type="text" value="" placeholder="'.__('Email','ocart').'" size="30"' . $aria_req . ' /><span class="is_req">*</span></p>'
		);
		$comments_args = array(
			'title_reply' => '<h4>'.__('Add a Review','ocart').'</h4>',
			'title_reply_to' => __('Add a Review','ocart'),
			'comment_field' => '<p class="comment-form-comment"><textarea id="comment" name="comment" aria-required="true"></textarea></p>',
			'comment_notes_after' => '',
			'comment_notes_before' => '',
			'must_log_in' => '<p class="must-log-in">' .  sprintf( __( 'You must <a href="javascript:lightbox(null, \'%s/ajax/login.php\');">log in</a> to review this product.','ocart'), get_template_directory_uri() ) . '</p>',
			'logged_in_as' => '',
			'id_submit' => 'review_submit',
			'id_form' => 'reviewform',
			'label_submit' => __('Submit Review','ocart'),
			'fields' => $fields
		);
		comment_form($comments_args, $post->ID);

	} else {
	
		// toggle review
		echo '<p id="toggle_review"><a href="#toggle_review_form">'.__('Add a Review','ocart').'</a></p>';
	
		// add review form (toggled by default)
		echo '<div class="toggled_review_form">';
		global $aria_req;
		$fields = array(
			'author' => '<p class="comment-form-author"><input id="author" name="author" type="text" value="" placeholder="'.__('Name','ocart').'" size="30"' . $aria_req . ' /><span class="is_req">*</span></p>',
			'email'  => '<p class="comment-form-email"><input id="email" name="email" type="text" value="" placeholder="'.__('Email','ocart').'" size="30"' . $aria_req . ' /><span class="is_req">*</span></p>'
		);
		$comments_args = array(
			'title_reply' => '',
			'title_reply_to' => '',
			'comment_field' => '<p class="comment-form-comment"><textarea id="comment" name="comment" aria-required="true"></textarea></p>',
			'comment_notes_after' => '',
			'comment_notes_before' => '',
			'must_log_in' => '<p class="must-log-in">' .  sprintf( __( 'You must <a href="javascript:lightbox(null, \'%s/ajax/login.php\');">log in</a> to review this product.','ocart'), get_template_directory_uri() ) . '</p>',
			'logged_in_as' => '',
			'id_submit' => 'review_submit',
			'id_form' => 'reviewform',
			'label_submit' => __('Submit Review','ocart'),
			'fields' => $fields
		);
		comment_form($comments_args, $post->ID);
		echo '</div>';
	
		// show comments
		echo '<ul class="reviews">';
		foreach($post_comments as $comment) {
			?>
			<li>
				<div class="customer_info">
					<div class="customer_meta">
						<span class="customer_name"><?php echo $comment->comment_author; ?></span>
						<span class="customer_time"><?php echo $comment->comment_date; ?></span>
					</div>
					<div class="customer_rating"><?php ocart_get_user_rating($comment->comment_ID) ?></div>
					<div class="customer_opinion">
						<?php echo wpautop($comment->comment_content); ?>
					</div>
				</div><div class="clear"></div>
			</li>
			<?php
		}
		echo '</ul>';

	}
}

?>

