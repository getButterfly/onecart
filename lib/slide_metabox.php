<?php

// add meta box
add_action( 'add_meta_boxes', 'sd_meta_box_add' );
function sd_meta_box_add() {
	global $post;
    add_meta_box( 'slide-meta-box', __('Setup New Slide','ocart'), 'sd_meta_box_cb', 'slide', 'normal', 'high' );
}

// render meta box
function sd_meta_box_cb($post) {
	global $ocart;
	$slide_content = get_post_meta($post->ID, 'slide_content', true);
	$video_txt1 = get_post_meta($post->ID, 'video_txt1', true);
	$video_txt2 = get_post_meta($post->ID, 'video_txt2', true);
	$video_pos = get_post_meta($post->ID, 'video_pos', true);
	$slide_video = get_post_meta($post->ID, 'slide_video', true);
	$slide_url = get_post_meta($post->ID, 'slide_url', true);
	$slide_button_url = get_post_meta($post->ID, 'slide_button_url', true);
	$slide_button_text = get_post_meta($post->ID, 'slide_button_text', true);
	wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
    ?>
	
    <p>
        <label for="slide_content"><?php _e('Slide Content / Description','ocart'); ?></label> 
        <textarea name="slide_content" id="slide_content"><?php echo $slide_content; ?></textarea>
    </p>
	
    <p>
        <label for="slide_url"><?php _e('Slide URL','ocart'); ?></label> 
        <input type="text" name="slide_url" id="slide_url" value="<?php echo $slide_url; ?>" />
		<span class="description"><?php _e('If you want to link the main slideshow to somewhere. Enter URL here.','ocart'); ?></span>
    </p>
	
    <p>
        <label for="slide_video"><?php _e('Embed Video HTML','ocart'); ?></label> 
        <textarea name="slide_video" id="slide_video" class="code"><?php echo $slide_video; ?></textarea>
    </p>
	
	<p>
		<label for="video_pos"><?php _e('Video Position','ocart'); ?></label>
		<select name="video_pos" id="video_pos">
			<?php if (!isset($video_pos)) $video_pos = ''; ?>
			<option value="0"<?php selected(0, $video_pos); ?>><?php _e('Aligned Right','ocart'); ?></option>
			<option value="1"<?php selected(1, $video_pos); ?>><?php _e('Aligned Left','ocart'); ?></option>
		</select>
		<span class="description"><?php _e('Video position. You can place video on left and have text show on right automatically.','ocart'); ?></span>
	</p>
	
    <p>
        <label for="video_txt1"><?php _e('Video Text Large','ocart'); ?></label> 
        <textarea name="video_txt1" id="video_txt1"><?php echo $video_txt1; ?></textarea>
    </p>
	
    <p>
        <label for="video_txt2"><?php _e('Video Text Smaller','ocart'); ?></label> 
        <textarea name="video_txt2" id="video_txt2"><?php echo $video_txt2; ?></textarea>
    </p>
	
    <p>
        <label for="slide_button_url"><?php _e('Button URL','ocart'); ?></label> 
        <input type="text" name="slide_button_url" id="slide_button_url" value="<?php echo $slide_button_url; ?>" />
		<span class="description"><?php _e('Do you want to place a button in this slideshow?','ocart'); ?></span>
    </p>
	
    <p>
        <label for="slide_button_text"><?php _e('Button Text','ocart'); ?></label> 
        <input type="text" name="slide_button_text" id="slide_button_text" value="<?php echo $slide_button_text; ?>" />
		<span class="description"><?php _e('A text which describes your button / link.','ocart'); ?></span>
    </p>

<?php
}

// save meta box
add_action( 'save_post', 'sd_meta_box_save' );  
function sd_meta_box_save( $post_id ) {

	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
	if( !current_user_can( 'edit_post', $post_id ) ) return;
	
	if (isset($_POST['slide_content']))
		update_post_meta( $post_id, 'slide_content', esc_attr( $_POST['slide_content'] ) );
		
	if (isset($_POST['slide_video']))
		update_post_meta( $post_id, 'slide_video', stripslashes($_POST['slide_video']));
		
	if (isset($_POST['video_pos']))
		update_post_meta( $post_id, 'video_pos', esc_attr( $_POST['video_pos']));
		
	if (isset($_POST['video_txt1']))
		update_post_meta( $post_id, 'video_txt1', esc_attr( $_POST['video_txt1']));
		
	if (isset($_POST['video_txt2']))
		update_post_meta( $post_id, 'video_txt2', esc_attr( $_POST['video_txt2']));
		
	if (isset($_POST['slide_url']))
		update_post_meta( $post_id, 'slide_url', esc_attr( $_POST['slide_url'] ) );
	
	if (isset($_POST['slide_button_url']))
		update_post_meta( $post_id, 'slide_button_url', esc_attr( $_POST['slide_button_url'] ) );
		
	if (isset($_POST['slide_button_text']))
		update_post_meta( $post_id, 'slide_button_text', esc_attr( $_POST['slide_button_text'] ) );

}

?>