<div id="comments">
	
	<?php if ( post_password_required() ) : ?>
		<!-- if comment is password protected show nothing -->
	</div><!-- #comments -->
	<?php
			return;
		endif;
	?>

	<?php // You can start editing here -- including this comment! ?>
	<?php if ( have_comments() ) : global $wp_query; ?>
		
		<?php if (!empty($comments_by_type['comment'])) { ?>
		
		<h3><?php $count = count($wp_query->comments_by_type['comment']); if ($count == 1) printf(__('%s Comment','ocart'), $count); else printf(__('%s Comments','ocart'), $count); ?></h3>
		
		<ul class="commentlist">
			<?php wp_list_comments('type=comment&avatar_size=48&max_depth=3&callback=ocart_comment&reverse_top_level=true'); ?>
		</ul><div class="clear"></div>
		
		<?php } // comment type = comment ?>

	<?php elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) : // if comments are closed.
	endif; ?>

	<?php
	// comment reply
	global $aria_req;
	$fields = array(
		'author' => '<p class="comment-form-author"><input id="author" name="author" type="text" value="' .__('Your Name','ocart'). '" size="30"' . $aria_req . ' /></p>',
		'email'  => '<p class="comment-form-email"><input id="email" name="email" type="text" value="' .__('Email','ocart'). '" size="30"' . $aria_req . ' /></p>',
		'url'    => '<p class="comment-form-url"><input id="url" name="url" type="text" value="' .__('Website','ocart'). '" size="30" /></p><div class="clear"></div>',
	);
	$comments_args = array(
		'title_reply' => __('Leave your comment','ocart'),
		'title_reply_to' => __('Leave your comment','ocart'),
		'comment_field' => '<p class="comment-form-comment"><textarea id="comment" name="comment" aria-required="true"></textarea></p>',
		'comment_notes_after' => '',
		'comment_notes_before' => '',
		'label_submit' => __('Submit','ocart'),
		'fields' => $fields
	);
	comment_form($comments_args);
	?>

</div><!-- #comments -->
