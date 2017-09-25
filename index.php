<?php get_header(); ?>

<?php get_template_part('template','header'); ?>

<?php get_template_part('template','nav'); ?>


<?php if (!ocart_hide_slider()) get_template_part('template','banner'); ?>

<?php get_template_part('template','similar'); ?>

<?php // show collections after slider ?>
<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('home-afterslider') ) : else : endif; ?>

<?php ocart_show_catalog(); ?>

<?php // show collections after catalog ?>
<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('home-aftercatalog') ) : else : endif; ?>

<?php get_template_part('template','bottom'); ?>

<?php get_template_part('template','footer'); ?>

<?php get_footer(); ?>