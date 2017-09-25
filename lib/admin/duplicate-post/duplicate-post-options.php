<?php
/**
 * Add an option page
 */
if ( is_admin() ){ // admin actions
	add_action('admin_menu', 'duplicate_post_menu');
	add_action( 'admin_init', 'duplicate_post_register_settings');
}

function duplicate_post_register_settings() { // whitelist options
	register_setting( 'duplicate_post_group', 'duplicate_post_copydate');
	register_setting( 'duplicate_post_group', 'duplicate_post_copyexcerpt');
	register_setting( 'duplicate_post_group', 'duplicate_post_copyattachments');
	register_setting( 'duplicate_post_group', 'duplicate_post_copychildren');
	register_setting( 'duplicate_post_group', 'duplicate_post_copystatus');
	register_setting( 'duplicate_post_group', 'duplicate_post_blacklist');
	register_setting( 'duplicate_post_group', 'duplicate_post_taxonomies_blacklist');
	register_setting( 'duplicate_post_group', 'duplicate_post_title_prefix');
	register_setting( 'duplicate_post_group', 'duplicate_post_title_suffix');
	register_setting( 'duplicate_post_group', 'duplicate_post_roles');
	register_setting( 'duplicate_post_group', 'duplicate_post_show_row');
	register_setting( 'duplicate_post_group', 'duplicate_post_show_adminbar');
	register_setting( 'duplicate_post_group', 'duplicate_post_show_submitbox');
}


function duplicate_post_menu() {
	add_submenu_page('edit.php?post_type=product', __('Duplicate Product Options','ocart'), __('Duplicate Product Options','ocart'), 'add_users', 'duplicatepost', 'duplicate_post_options');
}

function duplicate_post_options() {

	if ( current_user_can( 'edit_users' ) && (isset($_GET['settings-updated'])  && $_GET['settings-updated'] == true)){
		global $wp_roles;
		$roles = $wp_roles->get_names();

		$dp_roles = get_option('duplicate_post_roles');
		if ( $dp_roles == "" ) $dp_roles = array();

		foreach ($roles as $name => $display_name){
			$role = get_role($name);

			// role should have at least edit_posts capability
			if ( !$role->has_cap('edit_posts') ) continue;

			/* If the role doesn't have the capability and it was selected, add it. */
			if ( !$role->has_cap( 'copy_posts' )  && in_array($name, $dp_roles) )
			$role->add_cap( 'copy_posts' );

			/* If the role has the capability and it wasn't selected, remove it. */
			elseif ( $role->has_cap( 'copy_posts' ) && !in_array($name, $dp_roles) )
			$role->remove_cap( 'copy_posts' );
		}
	}

	?>
<div class="wrap">
	<div id="icon-options-general" class="icon32">
		<br>
	</div>
	<h2>
	<?php _e("Duplicate Product Options", 'ocart'); ?>
	</h2>

	<form method="post" action="options.php">
	<?php settings_fields('duplicate_post_group'); ?>

		<table class="form-table">

			<tr valign="top">
				<th scope="row"><?php _e("Copy product date also", 'ocart'); ?>
				</th>
				<td><input type="checkbox" name="duplicate_post_copydate" value="1" <?php  if(get_option('duplicate_post_copydate') == 1) echo 'checked="checked"'; ?>"/>
					<span class="description"><?php _e("Normally, the new copy has its publication date set to current time: check the box to copy the original product date", 'ocart'); ?>
				</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e("Copy product status", 'ocart'); ?>
				</th>
				<td><input type="checkbox" name="duplicate_post_copystatus"
					value="1" <?php  if(get_option('duplicate_post_copystatus') == 1) echo 'checked="checked"'; ?>"/>
					<span class="description"><?php _e("Copy the original post status (draft, published, pending) when cloning from the post list.", 'ocart'); ?>
				</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e("Copy excerpt", 'ocart'); ?>
				</th>
				<td><input type="checkbox" name="duplicate_post_copyexcerpt"
					value="1" <?php  if(get_option('duplicate_post_copyexcerpt') == 1) echo 'checked="checked"'; ?>"/>
					<span class="description"><?php _e("Copy the excerpt from the original product", 'ocart'); ?>
				</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e("Copy attachments", 'ocart'); ?>
				</th>
				<td><input type="checkbox" name="duplicate_post_copyattachments"
					value="1" <?php  if(get_option('duplicate_post_copyattachments') == 1) echo 'checked="checked"'; ?>"/>
					<span class="description"><?php _e("Copy the attachments from the original product", 'ocart'); ?>
				</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e("Copy children", 'ocart'); ?>
				</th>
				<td><input type="checkbox" name="duplicate_post_copychildren"
					value="1" <?php  if(get_option('duplicate_post_copychildren') == 1) echo 'checked="checked"'; ?>"/>
					<span class="description"><?php _e("Copy the children from the original product", 'ocart'); ?>
				</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e("Do not copy these fields", 'ocart'); ?>
				</th>
				<td><input type="text" name="duplicate_post_blacklist"
					value="<?php echo get_option('duplicate_post_blacklist'); ?>" /> <span
					class="description"><?php _e("Comma-separated list of meta fields that must not be copied", 'ocart'); ?>
				</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e("Do not copy these taxonomies", 'ocart'); ?>
				</th>
				<td><div
						style="height: 100px; width: 300px; padding: 5px; overflow: auto; border: 1px solid #ccc">
						<?php $taxonomies=get_taxonomies(array('public' => true),'objects');
						$taxonomies_blacklist = get_option('duplicate_post_taxonomies_blacklist');
						if ($taxonomies_blacklist == "") $taxonomies_blacklist = array();
						foreach ($taxonomies as $taxonomy ) : ?>
						<label style="display: block;"> <input type="checkbox"
							name="duplicate_post_taxonomies_blacklist[]"
							value="<?php echo $taxonomy->name?>"
							<?php if(in_array($taxonomy->name,$taxonomies_blacklist)) echo 'checked="checked"'?> />
							<?php echo $taxonomy->labels->name?> </label>
							<?php endforeach; ?>
					</div> <span class="description"><?php _e("Select the taxonomies you don't want to be copied", 'ocart'); ?>
				</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e("Title prefix", 'ocart'); ?>
				</th>
				<td><input type="text" name="duplicate_post_title_prefix"
					value="<?php echo get_option('duplicate_post_title_prefix'); ?>" />
					<span class="description"><?php _e("Prefix to be added before the original title, e.g. \"Copy of\" (blank for no prefix)", 'ocart'); ?>
				</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e("Title suffix", 'ocart'); ?>
				</th>
				<td><input type="text" name="duplicate_post_title_suffix"
					value="<?php echo get_option('duplicate_post_title_suffix'); ?>" />
					<span class="description"><?php _e("Suffix to be added after the original title, e.g. \"(dup)\" (blank for no suffix)", 'ocart'); ?>
				</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e("Roles allowed to copy", 'ocart'); ?>
				</th>
				<td><div
						style="height: 100px; width: 300px; padding: 5px; overflow: auto; border: 1px solid #ccc">
						<?php	global $wp_roles;
						$roles = $wp_roles->get_names();
						foreach ($roles as $name => $display_name): $role = get_role($name);
						if ( !$role->has_cap('edit_posts') ) continue; ?>
						<label style="display: block;"> <input type="checkbox"
							name="duplicate_post_roles[]" value="<?php echo $name ?>"
							<?php if($role->has_cap('copy_posts')) echo 'checked="checked"'?> />
							<?php echo translate_user_role($display_name); ?> </label>
							<?php endforeach; ?>
					</div> <span class="description"><?php _e("Warning: users will be able to copy all posts, even those of other users", 'ocart'); ?>
				</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e("Show links in", 'ocart'); ?>
				</th>
				<td><label style="display: block"><input type="checkbox"
						name="duplicate_post_show_row" value="1" <?php  if(get_option('duplicate_post_show_row') == 1) echo 'checked="checked"'; ?>"/>
						<?php _e("Post list", 'ocart'); ?> </label> <label
					style="display: block"><input type="checkbox"
						name="duplicate_post_show_submitbox" value="1" <?php  if(get_option('duplicate_post_show_submitbox') == 1) echo 'checked="checked"'; ?>"/>
						<?php _e("Edit screen", 'ocart'); ?> </label> <label
					style="display: block"><input type="checkbox"
						name="duplicate_post_show_adminbar" value="1" <?php  if(get_option('duplicate_post_show_adminbar') == 1) echo 'checked="checked"'; ?>"/>
						<?php _e("Admin bar", 'ocart'); ?> (WP 3.1+)</label>
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary"
				value="<?php _e('Save Changes', 'ocart') ?>" />
		</p>

	</form>
</div>
<?php
}
?>