<?php
add_shortcode('collection', 'oc_shortcode_collection');

/************************************************************
display shortcode [collection]
************************************************************/
function oc_shortcode_collection($atts) {
    extract(shortcode_atts(array(
        "auto" => '', // disable automatic by default
        "name" => '', // collection name is required
        "title" => '', // override collection name
        "count" => '', // number of products
        "orderby" => 'date', // order by date
        "order" => 'DESC', // DESC order
        "exclude" => '', // do not exclude by default
        "autoplay" => "true", // autoplay by default
        "timer" => 500, // default timer
        "id" => 'post-content'
    ), $atts));

    return ocart_new_collection($auto, $name, $title, $count, $orderby, $order, $exclude, $autoplay, $timer, $id);
}

/************************************************************
register button [collection]
************************************************************/
add_action('init', 'add_button_collection');

function add_button_collection() {
    if (current_user_can('edit_posts') && current_user_can('edit_pages')) {
        add_filter('mce_external_plugins', 'add_plugin_collection');
        add_filter('mce_buttons', 'register_button_collection');
    }
}

function register_button_collection($buttons) {
    array_push($buttons, "collection");

    return $buttons;
}

function add_plugin_collection($plugin_array) {  
    $plugin_array['collection'] = get_template_directory_uri() . '/lib/js/shortcode_collection.js';  

    return $plugin_array;
}
