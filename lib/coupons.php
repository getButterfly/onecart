<?php
/*
 * Register coupon post type (private)
 */
function register_coupons() {
    $labels = array(
        'name' => __('Coupons', 'ocart'),
        'singular_name' => __('Coupon', 'ocart'),
        'add_new' => __('Add Coupon', 'ocart'),
        'add_new_item' => __('Add New Coupon', 'ocart'),
        'edit_item' => __('Edit Coupon', 'ocart'),
        'new_item' => __('New Coupon', 'ocart'),
        'all_items' => __('Coupons', 'ocart'),
        'view_item' => __('View Coupon', 'ocart'),
        'search_items' => __('Search Coupons', 'ocart'),
        'not_found' => __('No coupons found.', 'ocart'),
        'not_found_in_trash' => __('No coupons found in Trash.', 'ocart'),
        'parent_item_colon' => '',
        'menu_name' => __('Coupons', 'ocart')
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => 'occommerce',
        'query_var' => true,
        'rewrite' => false,
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'supports' => array('title')
    );

    register_post_type('coupon', $args);
}
add_action( 'init', 'register_coupons' );

/*
 * Change status messages when updating a coupon
 */
function coupons_updated_messages($messages) {
    global $post, $post_ID;

    $messages['coupon'] = array(
        0 => '', // Unused. Messages start at index 1.
        1 => __('Coupon updated.', 'ocart'),
        2 => __('Custom field updated.', 'ocart'),
        3 => __('Custom field deleted.', 'ocart'),
        4 => __('Coupon updated.', 'ocart'),
        /* translators: %s: date and time of the revision */
        5 => isset($_GET['revision']) ? sprintf(__('Coupon restored to revision from %s', 'ocart'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
        6 => __('Coupon published.', 'ocart'),
        7 => __('Coupon saved.', 'ocart'),
        8 => __('Coupon submitted.', 'ocart'),
        9 => sprintf( __('Coupon scheduled for: <strong>%1$s</strong>.', 'ocart'), date_i18n(__('M j, Y @ G:i'), strtotime($post->post_date))),
        10 => __('Coupon draft updated.', 'ocart'),
    );

    return $messages;
}

add_filter('post_updated_messages', 'coupons_updated_messages');

/*
 * Manage columns
 */
function cmanage_cols($columns) {
    $columns['usage_limit'] = __('Remaining Coupons', 'ocart');
    $columns['usage_count'] = __('Usage Count', 'ocart');

    return $columns;
}

add_action('manage_edit-coupon_columns', 'cmanage_cols');

/*
 * Render columns
 */
function crender_cols($column) {
    global $post;

    // Remaining coupon limit
    if ((string) $column === 'usage_limit') {
        $usage_limit = get_post_meta($post->ID, 'usage_limit', true);
        echo $usage_limit;
    }

    // Usage count
    if ((string) $column === 'usage_count') {
        $usage_count = get_post_meta($post->ID, 'usage_count', true);
        echo (int) $usage_count;
    }
}

add_action('manage_coupon_posts_custom_column', 'crender_cols');

/*
 * Change status messages when updating a coupon
 */
function ocart_coupon_title($title) {
    $screen = get_current_screen();

    if ('coupon' == (string) $screen->post_type) {
        $title = __('Coupon code', 'ocart');
    }

    return $title;
}

add_filter('enter_title_here', 'ocart_coupon_title');
