<?php

/**

* This is an alternate version of onecart_seo_metabox_add. This function adds a metabox to each registered post type in WordPress.

*/

add_action( 'add_meta_boxes', 'onecart_seo_metabox_add' );

function onecart_seo_metabox_add() {

$post_types = get_post_types();

foreach ( $post_types as $post_type )

	if (!in_array($post_type, array('orders', 'slide'))) {
		add_meta_box( 'seo-meta-box', __('OneCart SEO Options','ocart'), 'onecart_seo_metabox', $post_type, 'normal', 'high' );
	}

}

/*
	render the metabox
*/
function onecart_seo_metabox($post) {
	global $ocart;
	$custom = get_post_custom($post->ID);
	if (isset($custom['seo_title'][0])) { $seo_title = $custom['seo_title'][0]; }
	if (isset($custom['seo_description'][0])) { $seo_description = $custom['seo_description'][0]; }
	if (isset($custom['seo_sep'][0])) { $seo_sep = $custom['seo_sep'][0]; }
	if (isset($custom['seo_custom_sep'][0])) { $seo_custom_sep = $custom['seo_custom_sep'][0]; }
	wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
    ?>

	<p>
		<label for="seo_title"><?php _e('Page SEO Title','ocart'); ?></label>
		<input type="text" name="seo_title" id="seo_title" value="<?php if(!empty($seo_title)) echo $seo_title; ?>" />
		<span class="description"><?php _e('Enter the webpage title here. Leave blank to use automatic SEO title.','ocart'); ?></span>
	</p>

	<p>
		<label for="seo_description"><?php _e('Page SEO Description','ocart'); ?></label>
		<input type="text" name="seo_description" id="seo_description" value="<?php if(!empty($seo_description)) echo $seo_description; ?>" />
		<span class="description"><?php _e('Enter description for SEO here. Leave blank to use automatic SEO description.','ocart'); ?></span>
	</p>
	
	<p>
		<label for="seo_sep"><?php _e('Page SEO Title Seperator','ocart'); ?></label>
		<select name="seo_sep" id="seo_sep">
			<option value="default" <?php if (isset($seo_sep) && $seo_sep == 'default') echo 'selected="selected"'; ?> ><?php echo ' | '. get_bloginfo('name'); ?></option>
			<option value="custom" <?php if (isset($seo_sep) && $seo_sep == 'custom') echo 'selected="selected"'; ?>><?php _e('Custom','ocart'); ?></option>
			<option value="none" <?php if (isset($seo_sep) && $seo_sep == 'none') echo 'selected="selected"'; ?>><?php _e('Do not use','ocart'); ?></option>
		</select>
		<span class="description"><?php _e('You can include a seperator that appears besides the title in your title bar.','ocart'); ?></span>
	</p>
	
	<p>
		<label for="seo_custom_sep"><?php _e('Page SEO Custom Seperator','ocart'); ?></label>
		<input type="text" name="seo_custom_sep" id="seo_custom_sep" value="<?php if(!empty($seo_custom_sep)) echo $seo_custom_sep; ?>" />
		<span class="description"><?php _e('If you choose to use a custom seperator besides title, enter it here.','ocart'); ?></span>
	</p>
	
<?php
}

/*
	save the metabox
*/
add_action( 'save_post', 'onecart_seo_metabox_save' );  
function onecart_seo_metabox_save( $post_id ) {

	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
	if( !current_user_can( 'edit_post', $post_id ) ) return;
	
	// custom fields
	if (isset($_POST['seo_title']))
		update_post_meta( $post_id, 'seo_title', esc_attr( $_POST['seo_title'] ) );
		
	if (isset($_POST['seo_description']))
		update_post_meta( $post_id, 'seo_description', esc_attr( $_POST['seo_description'] ) );
		
	if (isset($_POST['seo_sep']))
		update_post_meta( $post_id, 'seo_sep', esc_attr( $_POST['seo_sep'] ) );
		
	if (isset($_POST['seo_custom_sep']))
		update_post_meta( $post_id, 'seo_custom_sep', esc_attr( $_POST['seo_custom_sep'] ) );

}