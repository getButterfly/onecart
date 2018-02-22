<?php

/**
 * Add extra fields to custom taxonomy edit and add form callback functions
 */
// Edit taxonomy page
function extra_edit_tax_fields($tag) {
    // Check for existing taxonomy meta for term ID.
	if (isset($tag) && is_object($tag)) {
		$t_id = $tag->term_id;
		$term_meta = get_option( "taxonomy_$t_id" );
	}
	?>
    <tr class="form-field">
    <th scope="row" valign="top"><label for="term_meta[seotitle]"><?php _e( 'OneCart SEO Title', 'ocart'); ?></label></th>
        <td>
            <input type="text" name="term_meta[seotitle]" id="term_meta[seotitle]" value="<?php echo isset($term_meta) && esc_attr($term_meta['seotitle']) ? esc_attr($term_meta['seotitle']) : ''; ?>">
            <p class="description"><?php _e( 'Enter title for this taxonomy. Leave blank for automatic SEO title.','ocart' ); ?></p>
        </td>
    </tr>
    <tr class="form-field">
    <th scope="row" valign="top"><label for="term_meta[seodescription]"><?php _e( 'OneCart SEO Description', 'ocart'); ?></label></th>
        <td>
            <input type="text" name="term_meta[seodescription]" id="term_meta[seodescription]" value="<?php echo isset($term_meta) && esc_attr($term_meta['seodescription']) ? esc_attr($term_meta['seodescription']) : ''; ?>">
            <p class="description"><?php _e( 'Enter description for this taxonomy. Leave blank for automatic SEO description.','ocart'); ?></p>
        </td>
    </tr>
    <tr class="form-field">
    <th scope="row" valign="top"><label for="term_meta[seosep]"><?php _e( 'OneCart SEO Title Seperator', 'ocart'); ?></label></th>
        <td>
			<select name="term_meta[seosep]" id="term_meta[seosep]">
				<option value="default" <?php if (isset($term_meta['seosep']) && $term_meta['seosep'] == 'default') echo 'selected="selected"'; ?> ><?php echo ' | '. get_bloginfo('name'); ?></option>
				<option value="custom" <?php if (isset($term_meta['seosep']) && $term_meta['seosep'] == 'custom') echo 'selected="selected"'; ?>><?php _e('Custom','ocart'); ?></option>
				<option value="none" <?php if (isset($term_meta['seosep']) && $term_meta['seosep'] == 'none') echo 'selected="selected"'; ?>><?php _e('Do not use','ocart'); ?></option>
			</select>
            <p class="description"><?php _e( 'By default, the store name is used as a seperator.','ocart' ); ?></p>
        </td>
    </tr>
    <tr class="form-field">
    <th scope="row" valign="top"><label for="term_meta[seocustomsep]"><?php _e( 'OneCart SEO Custom Title Seperator', 'ocart'); ?></label></th>
        <td>
            <input type="text" name="term_meta[seocustomsep]" id="term_meta[seocustomsep]" value="<?php echo isset($term_meta) && esc_attr($term_meta['seocustomsep']) ? esc_attr($term_meta['seocustomsep']) : ''; ?>">
            <p class="description"><?php _e( 'If you choose to use a custom seperator besides title, enter it here.','ocart' ); ?></p>
        </td>
    </tr>
<?php
}

// Add taxonomy page
function extra_add_tax_fields( $tag ) {
    // Check for existing taxonomy meta for term ID.
	if (isset($tag) && is_object($tag)) {
		$t_id = $tag->term_id;
		$term_meta = get_option( "taxonomy_$t_id" );
	}
	?>
    <div class="form-field">
        <label for="term_meta[seotitle]"><?php _e( 'OneCart SEO Title', 'ocart'); ?></label>
        <input type="text" name="term_meta[seotitle]" id="term_meta[seotitle]" value="<?php echo isset($term_meta) && esc_attr($term_meta['seotitle']) ? esc_attr($term_meta['seotitle']) : ''; ?>">
        <p class="description"><?php _e( 'Enter title for this taxonomy. Leave blank for automatic SEO title.','ocart' ); ?></p>
    </div>
    <div class="form-field">
        <label for="term_meta[seodescription]"><?php _e( 'OneCart SEO Description', 'ocart'); ?></label>
        <input type="text" name="term_meta[seodescription]" id="term_meta[seodescription]" value="<?php echo isset($term_meta) && esc_attr($term_meta['seodescription']) ? esc_attr($term_meta['seodescription']) : ''; ?>">
        <p class="description"><?php _e( 'Enter description for this taxonomy. Leave blank for automatic SEO description.','ocart' ); ?></p>
    </div>
    <div class="form-field">
        <label for="term_meta[seosep]"><?php _e( 'OneCart SEO Title Seperator', 'ocart'); ?></label>
		<select name="term_meta[seosep]" id="term_meta[seosep]">
			<option value="default" <?php if (isset($term_meta['seosep']) && $term_meta['seosep'] == 'default') echo 'selected="selected"'; ?> ><?php echo ' | '. get_bloginfo('name'); ?></option>
			<option value="custom" <?php if (isset($term_meta['seosep']) && $term_meta['seosep'] == 'custom') echo 'selected="selected"'; ?>><?php _e('Custom','ocart'); ?></option>
			<option value="none" <?php if (isset($term_meta['seosep']) && $term_meta['seosep'] == 'none') echo 'selected="selected"'; ?>><?php _e('Do not use','ocart'); ?></option>
		</select>
        <p class="description"><?php _e( 'By default, the store name is used as a seperator.','ocart' ); ?></p>
    </div>
    <div class="form-field">
        <label for="term_meta[seocustomsep]"><?php _e( 'OneCart SEO Custom Title Seperator', 'ocart'); ?></label>
        <input type="text" name="term_meta[seocustomsep]" id="term_meta[seocustomsep]" value="<?php echo isset($term_meta) && esc_attr($term_meta['seocustomsep']) ? esc_attr($term_meta['seocustomsep']) : ''; ?>">
        <p class="description"><?php _e( 'If you choose to use a custom seperator besides title, enter it here.','ocart' ); ?></p>
    </div>
<?php
}

// Save extra taxonomy fields callback function.
function save_extra_taxonomy_fields( $term_id ) {
    if ( isset( $_POST['term_meta'] ) ) {
        $t_id = $term_id;
        $term_meta = get_option( "taxonomy_$t_id" );
        $cat_keys = array_keys( $_POST['term_meta'] );
        foreach ( $cat_keys as $key ) {
            if ( isset ( $_POST['term_meta'][$key] ) ) {
                $term_meta[$key] = $_POST['term_meta'][$key];
            }
        }
        // Save the option array.
        update_option( "taxonomy_$t_id", $term_meta );
    }
}
