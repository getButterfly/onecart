<?php

/************************************************************
define custom post type - slide
************************************************************/
function register_slides() {
  $labels = array(
    'name' => __('Slides', 'ocart'),
    'singular_name' => __('Slide', 'ocart'),
    'add_new' => __('Add New Slide', 'ocart'),
    'add_new_item' => __('Add New Slide','ocart'),
    'edit_item' => __('Edit Slide','ocart'),
    'new_item' => __('New Slide','ocart'),
    'all_items' => __('All Slides','ocart'),
    'view_item' => __('View Slide','ocart'),
    'search_items' => __('Search Slides','ocart'),
    'not_found' =>  __('No slides found.','ocart'),
    'not_found_in_trash' => __('No slides found in Trash.','ocart'), 
    'parent_item_colon' => '',
    'menu_name' => __('Slides','ocart')
  );
  $args = array(
    'labels' => $labels,
    'public' => true,
	'exclude_from_search' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'menu_position' => 7,
    'supports' => array( 'title', 'thumbnail' )
  ); 
  register_post_type( 'slide', $args );
}
add_action( 'init', 'register_slides' );

/************************************************************
remove permalink from slide post type
************************************************************/
add_action('admin_head', 'slide_admin_css');
function slide_admin_css() {
	$screen = get_current_screen();
	if ($screen->id == 'slide') :		
		echo "<style>#edit-slug-box{display:none;}</style>";
	endif;
}

?>