<?php
/************************************************************
custom taxonomies
************************************************************/
add_action( 'init', 'create_collection_taxonomies' );
add_action( 'init', 'create_product_category_taxonomies' );
add_action( 'init', 'create_brand_taxonomies');
add_action( 'init', 'create_color_taxonomies' );
add_action( 'init', 'create_size_taxonomies');

/************************************************************
create additional fields
************************************************************/
add_action( 'init', 'do_fields_for_terms' );
function do_fields_for_terms() {
	$args=array('public' => true,'_builtin' => false);
	$output = 'names'; // or objects
	$operator = 'and'; // 'and' or 'or'
	$taxonomies=get_taxonomies($args,$output,$operator);
	if  ($taxonomies) {
		foreach ($taxonomies as $taxonomy ) {
			add_action( $taxonomy.'_edit_form_fields', 'extra_edit_tax_fields', 10, 2 );
			add_action( $taxonomy.'_add_form_fields', 'extra_add_tax_fields', 10, 2 );
			add_action( 'edited_'.$taxonomy, 'save_extra_taxonomy_fields', 10, 2 );   
			add_action( 'create_'.$taxonomy, 'save_extra_taxonomy_fields', 10, 2 );
		}
	}
	$args=array('public' => true,'_builtin' => true);
	$output = 'names'; // or objects
	$operator = 'and'; // 'and' or 'or'
	$taxonomies=get_taxonomies($args,$output,$operator);
	if  ($taxonomies) {
		foreach ($taxonomies as $taxonomy ) {
			add_action( $taxonomy.'_edit_form_fields', 'extra_edit_tax_fields', 10, 2 );
			add_action( $taxonomy.'_add_form_fields', 'extra_add_tax_fields', 10, 2 );
			add_action( 'edited_'.$taxonomy, 'save_extra_taxonomy_fields', 10, 2 );   
			add_action( 'create_'.$taxonomy, 'save_extra_taxonomy_fields', 10, 2 );
		}
	}
}

function create_collection_taxonomies() {
  $labels = array(
    'name' => __( 'Collections', 'ocart' ),
    'singular_name' => __( 'Collection', 'ocart' ),
    'search_items' =>  __( 'Search Collections', 'ocart' ),
    'all_items' => __( 'Collections', 'ocart' ),
    'parent_item' => __( 'Parent Collection', 'ocart' ),
    'parent_item_colon' => __( 'Parent Collection:', 'ocart' ),
    'edit_item' => __( 'Edit Collection', 'ocart' ), 
    'update_item' => __( 'Update Collection', 'ocart' ),
    'add_new_item' => __( 'Add New Collection', 'ocart' ),
    'new_item_name' => __( 'New Collection Name', 'ocart' ),
    'menu_name' => __( 'Collections', 'ocart' ),
  ); 	
  register_taxonomy('collection','product', array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'collection', 'hierarchical' => true ),
  ));
}

function create_product_category_taxonomies() {
  $labels = array(
    'name' => __( 'Categories', 'ocart' ),
    'singular_name' => __( 'Category', 'ocart' ),
    'search_items' =>  __( 'Search Categories', 'ocart' ),
    'all_items' => __( 'All Categories', 'ocart' ),
    'parent_item' => __( 'Parent Category', 'ocart' ),
    'parent_item_colon' => __( 'Parent Category:', 'ocart' ),
    'edit_item' => __( 'Edit Category', 'ocart' ), 
    'update_item' => __( 'Update Category', 'ocart' ),
    'add_new_item' => __( 'Add New Category', 'ocart' ),
    'new_item_name' => __( 'New Category Name', 'ocart' ),
    'menu_name' => __( 'Categories', 'ocart' ),
  ); 	
  register_taxonomy('product_category','product', array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'product_category', 'hierarchical' => true ),
  ));
}

function create_brand_taxonomies() {
  $labels = array(
    'name' => __( 'Brands', 'ocart'),
    'singular_name' => __( 'Brand', 'ocart' ),
    'search_items' =>  __( 'Search Brands', 'ocart' ),
    'popular_items' => __( 'Popular Brands', 'ocart' ),
    'all_items' => __( 'All Brands', 'ocart' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Brand', 'ocart' ), 
    'update_item' => __( 'Update Brand', 'ocart' ),
    'add_new_item' => __( 'Add New Brand', 'ocart' ),
    'new_item_name' => __( 'New Brand Name', 'ocart' ),
    'separate_items_with_commas' => __( 'Separate brands with commas', 'ocart' ),
    'add_or_remove_items' => __( 'Add or remove brands', 'ocart' ),
    'choose_from_most_used' => __( 'Choose from the most used brands', 'ocart' ),
    'menu_name' => __( 'Brands', 'ocart' ),
  ); 
  register_taxonomy('brand','product',array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'brand' ),
  ));
}

function create_color_taxonomies() {
  $labels = array(
    'name' => __( 'Colors', 'ocart' ),
    'singular_name' => __( 'Color', 'ocart' ),
    'search_items' =>  __( 'Search Colors', 'ocart' ),
    'popular_items' => __( 'Popular Colors', 'ocart' ),
    'all_items' => __( 'All Colors', 'ocart' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Color', 'ocart' ), 
    'update_item' => __( 'Update Color', 'ocart' ),
    'add_new_item' => __( 'Add New Color', 'ocart' ),
    'new_item_name' => __( 'New Color Name', 'ocart' ),
    'separate_items_with_commas' => __( 'Separate colors with commas', 'ocart' ),
    'add_or_remove_items' => __( 'Add or remove colors', 'ocart' ),
    'choose_from_most_used' => __( 'Choose from the most used colors', 'ocart' ),
    'menu_name' => __( 'Colors', 'ocart' ),
  ); 
  register_taxonomy('color','product',array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'color' ),
  ));
}

function create_size_taxonomies() {
  $labels = array(
    'name' => __( 'Sizes', 'ocart' ),
    'singular_name' => __( 'Size', 'ocart'),
    'search_items' =>  __( 'Search Sizes', 'ocart' ),
    'popular_items' => __( 'Popular Sizes', 'ocart' ),
    'all_items' => __( 'All Sizes', 'ocart' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Size', 'ocart' ), 
    'update_item' => __( 'Update Size', 'ocart' ),
    'add_new_item' => __( 'Add New Size', 'ocart' ),
    'new_item_name' => __( 'New Size Name', 'ocart' ),
    'separate_items_with_commas' => __( 'Separate sizes with commas', 'ocart' ),
    'add_or_remove_items' => __( 'Add or remove sizes', 'ocart' ),
    'choose_from_most_used' => __( 'Choose from the most used sizes', 'ocart' ),
    'menu_name' => __( 'Sizes', 'ocart' ),
  ); 
  register_taxonomy('size','product',array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'size' ),
  ));
}

?>