<?php
// include WordPress
define( 'WP_USE_THEMES', false );
require('../../../../wp-load.php');

// search passed $_GET['type']
function mySearchFilter($query) {
	$post_type = $_GET['type'];
	if (!$post_type) {
		$post_type = 'any';
	}
    if ($query->is_search) {
        $query->set('post_type', $post_type);
    };
    return $query;
};
add_filter('pre_get_posts','mySearchFilter');

// search restricted to title only
function search_by_title_only( $search, &$wp_query )
{
    global $wpdb;
    if ( empty( $search ) )
        return $search; // skip processing - no search term in query
    $q = $wp_query->query_vars;
    $n = ! empty( $q['exact'] ) ? '' : '%';
    $search =
    $searchand = '';
    foreach ( (array) $q['search_terms'] as $term ) {
        $term = esc_sql( like_escape( $term ) );
        $search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
        $searchand = ' AND ';
    }
    if ( ! empty( $search ) ) {
        $search = " AND ({$search}) ";
        if ( ! is_user_logged_in() )
            $search .= " AND ($wpdb->posts.post_password = '') ";
    }
    return $search;
}
add_filter( 'posts_search', 'search_by_title_only', 500, 2 );

// filter 's' keyword
$posts = get_posts( array( 'post_type' => 'product', 'numberposts' => -1, 's' => $_GET['s'] ) );
if (count($posts) > 0 && $_GET['s'] != '') {
foreach ($posts as $post): setup_postdata($post);

?>
	
	<li>
		<a href="<?php the_permalink() ?>" id="item-<?php the_ID(); ?>">
			<?php $term = stripslashes($_GET['s']); ?>
			<?php ocart_product('small_thumb') ?>
			<span class="search_title"><?php echo str_replace($term, '<span class="search_term">'.$term.'</span>', $post->post_title); ?></span>
		</a>
	</li>
			
<?php
endforeach;
} else {
?>

	<li class="no-results">
		<?php _e('No products found for this search term.','ocart'); ?>
	</li>

<?php
}
?>