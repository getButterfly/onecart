<?php

/************************************************************
search by ID
************************************************************/
function my_search_pre_get_posts($query)
{
    // Verify that we are on the search page that that this came from the event search form
    if($query->query_vars['s'] != '' && is_search())
    {
	
		// search by order ID
		if (isset($_GET['searchby']) && $_GET['searchby'] == 'orderID') {
		    // If "s" is a positive integer, assume post id search and change the search variables
			if(absint($query->query_vars['s']))
			{
				// Set the post id value
				$query->set('p', $query->query_vars['s']);

				// Reset the search value
				$query->set('s', '');
			}
		}
		
		// search by meta key (customer email)
		if (isset($_GET['searchby']) && $_GET['searchby'] == 'customer_email') {
			
			$query->set('meta_key', 'custID');
			$query->set('meta_value', $query->query_vars['s']);
			
			// Reset the search value
			$query->set('s', '');
		}
		
    }
}

// Filter the search page
add_filter('pre_get_posts', 'my_search_pre_get_posts');

/************************************************************
hide "No categories" from output by wp_list_categories
************************************************************/
function ocart_list_categories($content) {
  if (!empty($content)) {
    $content = str_ireplace('<li>' .__( "No categories" ). '</li>', "", $content);
	$content = preg_replace('` title="(.+)"`', '', $content);
	$content = preg_replace('|<a href="(.+?)">(.+?)</a> \((\d+?)\)|i', '<a href="$1">$2<span>($3)</span></a>', $content);
  }
  return $content;
}
add_filter('wp_list_categories','ocart_list_categories');

/************************************************************
html email thru wp_mail function
************************************************************/
add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));

/************************************************************
add category and slug to nav items ID
************************************************************/
add_filter('nav_menu_item_id' , 'ocart_add_tax_to_nav' , 10 , 2);
function ocart_add_tax_to_nav($content, $item){
	$term = get_term_by('id', $item->object_id, $item->object);
	if ($term){
	return $item->object.'-'.$term->slug;
	}
}

/************************************************************
enable comments by default
************************************************************/
function default_comments_on( $data ) {
    if( $data['post_type'] == 'product' ) {
        $data['comment_status'] = 'open';
    }
    return $data;
}
add_filter( 'wp_insert_post_data', 'default_comments_on' );

?>