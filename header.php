<?php global $ocart; ?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta http-equiv="x-ua-compatible" content="ie=edge">

<?php ocart_seo(); ?>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>">

<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php do_action('ocart_after_body_start'); ?>

<script>
window.twttr = (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0],
        t = window.twttr || {};
    if (d.getElementById(id)) return t;
    js = d.createElement(s);
    js.id = id;
    js.src = "https://platform.twitter.com/widgets.js";
    fjs.parentNode.insertBefore(js, fjs);

    t._e = [];
    t.ready = function(f) {
        t._e.push(f);
    };

    return t;
}(document, "script", "twitter-wjs"));
</script>

<div id="toTop"><?php _e('Back to Top','ocart'); ?></div>

<div id="wrapper">
