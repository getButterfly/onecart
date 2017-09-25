<?php get_header(); ?>

<?php get_template_part('template','header'); ?>

<?php get_template_part('template','nav'); ?>


<?php if (!ocart_hide_slider()) get_template_part('template','banner'); ?>

<div id="catalog-noajax">
<?php get_template_part('template','catalog-noajax'); ?>
</div>

<?php get_template_part('template','bottom'); ?>

<?php get_template_part('template','footer'); ?>

<?php get_footer(); ?>