<?php

/************************************************************
define custom post type - product
************************************************************/
function register_products() {
  $labels = array(
    'name' => __('Products', 'ocart'),
    'singular_name' => __('Product', 'ocart'),
    'add_new' => __('Add New Product', 'ocart'),
    'add_new_item' => __('Add New Product', 'ocart'),
    'edit_item' => __('Edit Product', 'ocart'),
    'new_item' => __('New Product', 'ocart'),
    'all_items' => __('All Products', 'ocart'),
    'view_item' => __('View Product', 'ocart'),
    'search_items' => __('Search Products', 'ocart'),
    'not_found' =>  __('No products found.', 'ocart'),
    'not_found_in_trash' => __('No products found in Trash.', 'ocart'), 
    'parent_item_colon' => '',
    'menu_name' => __('Products','ocart')
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
    'menu_position' => 5,
      'menu_icon' => 'dashicons-cart',
    'supports' => array( 'title', 'editor', 'thumbnail', 'comments' )
  ); 
  register_post_type('product',$args);
}
add_action( 'init', 'register_products' );

/************************************************************
change status messages when updating a product
************************************************************/
add_filter('post_updated_messages', 'product_updated_messages');
function product_updated_messages( $messages ) {
	global $post, $post_ID;
	$messages['product'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Product updated. <a href="%s">View product</a>', 'ocart'), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.', 'ocart'),
		3 => __('Custom field deleted.', 'ocart'),
		4 => __('Product updated.', 'ocart'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Product restored to revision from %s', 'ocart'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Product published. <a href="%s">View product</a>', 'ocart'), esc_url( get_permalink($post_ID) ) ),
		7 => __('Product saved.', 'ocart'),
		8 => sprintf( __('Product submitted. <a target="_blank" href="%s">Preview product</a>', 'ocart'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('Product scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview product</a>', 'ocart'),
		  // translators: Publish box date format, see http://php.net/date
		  date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('Product draft updated. <a target="_blank" href="%s">Preview product</a>', 'ocart'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	  );
	return $messages;
}

/************************************************************
manage/edit the columns
************************************************************/
function pmanage_cols($columns) {
	$columns['product_image'] = __('Thumbnail','ocart');
	$columns['product_id'] = __('Product ID','ocart');
	$columns['sales'] = __('Sales','ocart');
	$columns['stockstatus'] = __('Stock status', 'ocart');
	$columns['stocklimit'] = __('Available stock','ocart');
	$date_label = $columns['date'];
	unset($columns['date']);
	$columns['date'] = $date_label;
	return $columns;
}
add_action('manage_edit-product_columns', 'pmanage_cols');

/************************************************************
render the columns
************************************************************/
function prender_cols($column){
	global $post;
	
	// show product thumb
	if ($column == 'product_image') {
		if (has_post_thumbnail()) {
			the_post_thumbnail( 'size-50' );
		} else {
			ocart_default_image('sort');
		}
	}
	
	// show product ID
	if ($column == 'product_id') {
		echo '<span class="num">'.$post->ID.'</span>';
	}
	
	// show sales
	if ($column == 'sales') {
		$sales = (int)get_post_meta($post->ID, 'sales', true);
		echo '<span class="num num-sales">'.$sales.'</span>';
	}
	
	// show stock status
	if ($column == 'stockstatus') {
		$status = get_post_meta($post->ID, 'status', true);
		if ($status == 'sold') {
			echo '<span class="num num-sold">'.ocart_sticker_text('sold').'</span>';
		} elseif ($status == 'instock') {
			echo '<span class="num num-instock">'.ocart_sticker_text('instock').'</span>';
		} else {
			echo '<span class="num num-instock">'.ocart_sticker_text('instock').'</span>';
		}
	}
	
	// show available stock
	if ($column == 'stocklimit') {
		$status = get_post_meta($post->ID, 'status', true);
		$stock = get_post_meta($post->ID, 'stock', true);
		if ($status == 'sold') {
			echo '<span class="num num-stock">0</span>';
		} else {
			if ($stock) {
				echo '<span class="num num-stock">'.$stock.'</span>';
			} else {
				echo '<span class="num num-stock">'.__('N/A','ocart').'</span>';
			}
		}
	}
	
}
add_action('manage_product_posts_custom_column','prender_cols');

/************************************************************
sortable columns
************************************************************/
add_filter('manage_edit-product_sortable_columns', 'pregister_sortable');
function pregister_sortable( $columns ){
	$columns['sales'] = 'sales';
	$columns['stocklimit'] = 'stocklimit';
	$columns['product_id'] = 'product_id';
	return $columns;
}

add_action( 'pre_get_posts', 'my_slice_orderby' );
function my_slice_orderby( $query ) {
	if( ! is_admin() )
		return;
	$orderby = $query->get( 'orderby');
	if ('product_id' == $orderby) {
		$query->set('orderby', 'ID');
	}
	if( 'stocklimit' == $orderby ) {
		$query->set('meta_key','stock');
		$query->set('orderby','meta_value_num');
	}
	if( 'sales' == $orderby ) {
		$query->set('meta_key','sales');
		$query->set('orderby','meta_value_num');
	}
}

/************************************************************
add styles
************************************************************/
add_action('admin_print_styles-edit.php',  'ocart_products_styles');
add_action('admin_print_styles-post-new.php',  'ocart_products_styles');
add_action('admin_print_styles-post.php',  'ocart_products_styles');
function ocart_products_styles(){
	$screen = get_current_screen();
	$screens = array('edit-product','product');

	if( !in_array($screen->id, $screens) )
		return;

	echo "<style>
		.column-title {width: 300px;}
		.column-product_id {width: 100px;}
		.column-product_image {width: 100px;}
		.column-sales {width: 100px}
		.column-stockstatus {width: 140px}
		.column-product_image img {
			border-radius: 50px;
			border: 1px solid #ddd;
		}
		span.num {
			float: left;
			margin: 5px 0 0 0;
			padding: 5px 10px;
			font-size: 11px;
			border-radius: 3px;
			box-shadow: inset 0 1px 1px #666;
			-webkit-transition: all 0.4s ease-in-out;
			-moz-transition: all 0.4s ease-in-out;
			-o-transition: all 0.4s ease-in-out;
			background: #999;
			color: #fff;
		}
		span.num-sales {background: none;color:#444;box-shadow:none;font-size:24px;}
		span.num-instock {background: #708858}
		span.num-sold {background: #885858}
		span.num-stock {background: #947a9c;color:#fff;}
		</style>";

}

?>