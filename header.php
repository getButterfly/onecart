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

<div id="toTop"><?php _e('Back to Top','ocart'); ?></div>

<div id="wrapper">
