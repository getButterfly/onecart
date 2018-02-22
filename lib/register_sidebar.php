<?php
/************
home-afterslider
home-aftercatalog
main-sidebar
footer-widgets
*********************/

/************************************************************
register sidebar widgets
************************************************************/
register_sidebar(array(
	'name' => __('Homepage - After Slider','ocart'),
	'id' => 'home-afterslider',
	'class' => '',
	'description' => __('Widgets in this area will be shown on the main page after slider.','ocart'),
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
	'before_title'  => '<h3>',
	'after_title'   => '</h3>'
));

register_sidebar(array(
	'name' => __('Homepage - After Catalog','ocart'),
	'id' => 'home-aftercatalog',
	'class' => '',
	'description' => __('Widgets in this area will be shown on the main page after catalog.','ocart'),
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
	'before_title'  => '<h3>',
	'after_title'   => '</h3>'
));

register_sidebar(array(
	'name' => __('Main Sidebar','ocart'),
	'id' => 'main-sidebar',
	'class' => '',
	'description' => __('Widgets in this area will be shown on the main sidebar.','ocart'),
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
	'before_title'  => '<h3>',
	'after_title'   => '</h3>'
));

register_sidebar(array(
	'name' => __('Footer Widgets','ocart'),
	'id' => 'footer-widgets',
	'class' => '',
	'description' => __('Widgets in this area will be shown on the front page footer.','ocart'),
	'before_widget' => '<div id="%1$s" class="section %2$s">',
	'after_widget'  => '</div>',
	'before_title'  => '<h3>',
	'after_title'   => '</h3>'
));

/************************************************************
Registering custom widget modules (+options)
************************************************************/
add_action( 'widgets_init', 'oc_text_init' );
add_action( 'widgets_init', 'oc_latestblog_init' );
add_action( 'widgets_init', 'oc_latestblogs_init' );
add_action( 'widgets_init', 'oc_social_init' );
add_action( 'widgets_init', 'oc_search_init' );
add_action( 'widgets_init', 'oc_ads_init' );
add_action( 'widgets_init', 'oc_tabs_init' );
add_action( 'widgets_init', 'oc_collection_init');

function oc_text_init() { register_widget( 'oc_text' ); }
function oc_latestblog_init() { register_widget( 'oc_latestblog' ); }
function oc_latestblogs_init() { register_widget( 'oc_latestblogs' ); }
function oc_social_init() { register_widget( 'oc_social' ); }
function oc_ads_init() { register_widget( 'oc_ads' ); }
function oc_search_init() { register_widget( 'oc_search' ); }
function oc_tabs_init() { register_widget( 'oc_tabs' ); }
function oc_collection_init() { register_widget( 'oc_collection' ); }

/************************************************************
show a products collection widget
************************************************************/
class oc_collection extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'oc_collection', 'description' => __('Display a products collection in a slider', 'ocart') );

		$control_ops = array( 'width' => 300, 'height' => 200, 'id_base' => 'oc_collection_widget' );

		parent::__construct( 'oc_collection_widget', __('ocCommerce - Collection', 'ocart'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		//Our variables from the widget settings.
		$collection_name = $instance['collection_name'];
		$collection_title = $instance['collection_title'];
		$collection_count = $instance['collection_count'];
		$collection_orderby = $instance['collection_orderby'];
		$collection_order = $instance['collection_order'];
		$collection_exclude = $instance['collection_exclude'];
		$collection_auto = $instance['collection_auto'];
		$collection_timer = $instance['collection_timer'];
		$collection_formula = $instance['collection_formula'];

		// Display widget
		$unique_id = $this->id;
		echo ocart_new_collection($collection_formula, $collection_name, $collection_title, $collection_count, $collection_orderby, $collection_order, $collection_exclude, $collection_auto, $collection_timer, $unique_id);

	}

	//Update the widget
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML
		$instance['collection_name'] = $new_instance['collection_name'];
		$instance['collection_title'] = $new_instance['collection_title'];
		$instance['collection_count'] = $new_instance['collection_count'];
		$instance['collection_orderby'] = $new_instance['collection_orderby'];
		$instance['collection_order'] = $new_instance['collection_order'];
		$instance['collection_exclude'] = $new_instance['collection_exclude'];
		$instance['collection_auto'] = $new_instance['collection_auto'];
		$instance['collection_timer'] = $new_instance['collection_timer'];
		$instance['collection_formula'] = $new_instance['collection_formula'];

		return $instance;
	}

	function form( $instance ) {

		//Set up some default widget settings.
		$defaults = array( 'collection_formula' => 0, 'collection_title' => '' , 'collection_name' => '', 'collection_count' => '', 'collection_orderby' => 'date', 'collection_order' => 'DESC', 'collection_exclude' => '', 'collection_auto' => 'true', 'collection_timer' => 500 );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
		<?php
		$terms = get_terms('collection', 'orderby=name&hide_empty=0');
		$count = count($terms);
		if ( $count > 0 ){
		?>
			<label for="<?php echo $this->get_field_id( 'collection_name' ); ?>"><?php _e('Choose a collection:', 'ocart'); ?></label>
			<select id="<?php echo $this->get_field_id( 'collection_name' ); ?>" name="<?php echo $this->get_field_name( 'collection_name' ); ?>" class="widefat">
				<?php foreach($terms as $term) { ?>
				<option value="<?php echo $term->slug; ?>" <?php selected($term->slug, $instance['collection_name']); ?>><?php echo $term->name; ?></option>
				<?php } ?>
			</select>
		<?php } else {
		echo __('You did not create any custom collections yet.','ocart');
		} ?>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'collection_formula' ); ?>"><?php _e('Use an Automatic Collection:', 'ocart'); ?></label>
			<select id="<?php echo $this->get_field_id( 'collection_formula' ); ?>" name="<?php echo $this->get_field_name( 'collection_formula' ); ?>" class="widefat">
				<option value="0" <?php selected(0, $instance['collection_formula']); ?>><?php _e('Select...','ocart'); ?></option>
				<option value="bestsellers" <?php selected('bestsellers', $instance['collection_formula']); ?>><?php _e('Best Sellers','ocart'); ?></option>
			</select><br />
			<small><?php _e('Choosing a predefined collection will override your existing collection. A predefined collection is made to show automatic results based on your chosen collection.','ocart'); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'collection_title' ); ?>"><?php _e('Override Collection Title:', 'ocart'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'collection_title' ); ?>" name="<?php echo $this->get_field_name( 'collection_title' ); ?>" value="<?php echo $instance['collection_title']; ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'collection_count' ); ?>"><?php _e('Number of Products:', 'ocart'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'collection_count' ); ?>" name="<?php echo $this->get_field_name( 'collection_count' ); ?>" value="<?php echo $instance['collection_count']; ?>" class="widefat" />
			<small><?php _e('Leave blank if you want to show all products in that collection.','ocart'); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'collection_orderby' ); ?>"><?php _e('Order by:', 'ocart'); ?></label>
			<select id="<?php echo $this->get_field_id( 'collection_orderby' ); ?>" name="<?php echo $this->get_field_name( 'collection_orderby' ); ?>" class="widefat">
				<option value="date"<?php selected('date', $instance['collection_orderby']); ?>><?php _e('Date','ocart'); ?></option>
				<option value="menu_order"<?php selected('menu_order', $instance['collection_orderby']); ?>><?php _e('Menu Order','ocart'); ?></option>
				<option value="rand"<?php selected('rand', $instance['collection_orderby']); ?>><?php _e('Random','ocart'); ?></option>
				<option value="title"<?php selected('title', $instance['collection_orderby']); ?>><?php _e('Title','ocart'); ?></option>
				<option value="name"<?php selected('name', $instance['collection_orderby']); ?>><?php _e('Slug','ocart'); ?></option>
				<option value="modified"<?php selected('modified', $instance['collection_orderby']); ?>><?php _e('Last Modified','ocart'); ?></option>
				<option value="ID"<?php selected('ID', $instance['collection_orderby']); ?>><?php _e('ID','ocart'); ?></option>
			</select><br />
			<small><?php printf(__('You can use \'menu_order\' and re-order your products using ajax drag-and-drop and sort products <a href="%s">here</a>.','ocart'),
			admin_url().'edit.php?post_type=product&page=product-order'
			); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'collection_order' ); ?>"><?php _e('Order:', 'ocart'); ?></label>
			<select id="<?php echo $this->get_field_id( 'collection_order' ); ?>" name="<?php echo $this->get_field_name( 'collection_order' ); ?>" class="widefat">
				<option value="DESC"<?php selected('DESC', $instance['collection_order']); ?>><?php _e('Descending Order','ocart'); ?></option>
				<option value="ASC"<?php selected('ASC', $instance['collection_order']); ?>><?php _e('Ascending Order','ocart'); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'collection_exclude' ); ?>"><?php _e('Exclude IDs:', 'ocart'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'collection_exclude' ); ?>" name="<?php echo $this->get_field_name( 'collection_exclude' ); ?>" value="<?php echo $instance['collection_exclude']; ?>" class="widefat" />
			<small><?php _e('Enter a comma seperated list of products IDs you want to exclude.','ocart'); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'collection_auto' ); ?>"><?php _e('Autoplay Slider:', 'ocart'); ?></label>
			<select id="<?php echo $this->get_field_id( 'collection_auto' ); ?>" name="<?php echo $this->get_field_name( 'collection_auto' ); ?>" class="widefat">
				<option value="true"<?php selected('true', $instance['collection_auto']); ?>><?php _e('On','ocart'); ?></option>
				<option value="false"<?php selected('false', $instance['collection_auto']); ?>><?php _e('Off','ocart'); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'collection_timer' ); ?>"><?php _e('Delay (Timer):', 'ocart'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'collection_timer' ); ?>" name="<?php echo $this->get_field_name( 'collection_timer' ); ?>" value="<?php echo $instance['collection_timer']; ?>" class="widefat" />
			<small><?php _e('This setting has effect only If you enable Auto Play feature for this slider.','ocart'); ?></small>
		</p>

	<?php
	}
}

/************************************************************
custom text widget
************************************************************/
class oc_text extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'oc_text', 'description' => __('A text or HTML widget', 'ocart') );

		$control_ops = array( 'width' => 300, 'height' => 200, 'id_base' => 'oc_text_widget' );

		parent::__construct( 'oc_text_widget', __('ocCommerce - Text', 'ocart'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		//Our variables from the widget settings.
		$title = apply_filters('widget_title', $instance['title'] );
		$content = $instance['content'];

		// Display widget
		echo $before_widget . $before_title . $title . $after_title;
		echo wpautop( $content );
		echo $after_widget;

	}

	//Update the widget
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['content'] = $new_instance['content'];

		return $instance;
	}

	function form( $instance ) {

		//Set up some default widget settings.
		$defaults = array( 'title' => __('About OneCart','ocart'), 'content' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'ocart'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>

		<p>
			<textarea id="<?php echo $this->get_field_id( 'content' ); ?>" name="<?php echo $this->get_field_name( 'content' ); ?>" class="widefat" rows="16" cols="20"><?php if (isset($instance['content'])) { echo $instance['content']; } ?></textarea>
		</p>

	<?php
	}
}

/************************************************************
display latest blog post widget
************************************************************/
class oc_latestblog extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'oc_latestblog', 'description' => __('Your latest blog post', 'ocart') );

		$control_ops = array( 'width' => 300, 'height' => 200, 'id_base' => 'oc_latestblog_widget' );

		parent::__construct( 'oc_latestblog_widget', __('ocCommerce - Latest Post', 'ocart'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		//Our variables from the widget settings.
		$title = apply_filters('widget_title', $instance['title'] );
		$words = $instance['words'];

		// Display widget
		echo $before_widget . $before_title . $title . $after_title;

		global $WP_Query;
		$posts = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 1 ));
		if ($posts->have_posts() ) :
		while ( $posts->have_posts() ) : $posts->the_post();
		?>

			<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
			<div class="meta"><?php printf(__('by %s','ocart'), get_the_author()); ?><span class="divid">|</span><?php comments_popup_link( __('0 Comments','ocart'), __('1 Comment','ocart'), __('% Comments','ocart') ); ?></div>
			<?php ocart_the_content($words); ?>

		<?php endwhile; ?>

			<div class="link"><a href="<?php echo get_permalink( get_page_by_path( 'blog' ) ); ?>"><?php _e('View Blog','ocart'); ?></a></div>

		<?php else : ?>

			<p><?php _e('There are no blog posts yet.','ocart'); ?></p>

		<?php
		endif;
		echo $after_widget;

	}

	//Update the widget
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['words'] = strip_tags( $new_instance['words'] );

		return $instance;
	}

	function form( $instance ) {

		//Set up some default widget settings.
		$defaults = array( 'title' => __('Recent Blog','ocart'), 'words' => 15 );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'ocart'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'words' ); ?>"><?php _e('Excerpt Length (in words):', 'ocart'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'words' ); ?>" name="<?php echo $this->get_field_name( 'words' ); ?>" value="<?php echo $instance['words']; ?>" class="widefat" />
		</p>

	<?php
	}
}

/************************************************************
display latest blog posts widget
************************************************************/
class oc_latestblogs extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'oc_latestblogs', 'description' => __('Your latest blog posts', 'ocart') );

		$control_ops = array( 'width' => 300, 'height' => 200, 'id_base' => 'oc_latestblogs_widget' );

		parent::__construct( 'oc_latestblogs_widget', __('ocCommerce - Latest Posts', 'ocart'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		//Our variables from the widget settings.
		$title = apply_filters('widget_title', $instance['title'] );
		$count = $instance['count'];

		// Display widget
		echo $before_widget . $before_title . $title . $after_title;

		?>

		<ul class="liststyle1">

			<?php
			global $WP_Query;
			$posts = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => $count ));
			while ( $posts->have_posts() ) : $posts->the_post();
			?>
			<li>
				<div class="thumb"><?php ocart_thumb(54, 54); ?></div>
				<div class="entry">
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="permalink"><?php the_title(); ?></a>
					<span><?php the_time('j M Y'); ?><?php comments_popup_link( __('0 Comments','ocart'), __('1 Comment','ocart'), __('% Comments','ocart') ); ?></span>
				</div><div class="clear"></div>
			</li>
			<?php endwhile; ?>

		</ul>

		<?php
		echo $after_widget;

	}

	//Update the widget
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['count'] = strip_tags( $new_instance['count'] );

		return $instance;
	}

	function form( $instance ) {

		//Set up some default widget settings.
		$defaults = array( 'title' => __('Latest Blogs','ocart'), 'count' => 3 );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'ocart'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e('Number of posts to display:', 'ocart'); ?></label>
			<select id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" class="widefat">
				<?php for ($i = 1; $i <= 10; $i++) { ?>
				<option value="<?php echo $i; ?>"<?php selected($i, $instance['count']); ?>><?php echo $i; ?></option>
				<?php } ?>
			</select>
		</p>

	<?php
	}
}

/************************************************************
custom social widget
************************************************************/
class oc_social extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'oc_social', 'description' => __('Your social bookmarking pages', 'ocart') );

		$control_ops = array( 'width' => 300, 'height' => 200, 'id_base' => 'oc_social_widget' );

		parent::__construct( 'oc_social_widget', __('ocCommerce - Social Bookmarks', 'ocart'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		//Our variables from the widget settings.
		$title = apply_filters('widget_title', $instance['title'] );

		// Display widget
		global $ocart;
		echo $before_widget . $before_title . $title . $after_title;

		?>

			<ul class="social">
				<?php
				$bookmarks = get_option('occommerce_social_bookmarks');
				foreach($bookmarks as $bookmark) {
					$url = esc_url_raw( $ocart["$bookmark"], array('http','https') );
					if ( $url ) {
				?>
						<li><a href="<?php echo $url; ?>" class="<?php echo $bookmark; ?>"></a></li>
				<?php } } ?>
			</ul>

		<?php

		echo $after_widget;

	}

	//Update the widget
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	function form( $instance ) {

		//Set up some default widget settings.
		$defaults = array( 'title' => __('Connect with Us','ocart') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'ocart'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>

	<?php
	}
}

/************************************************************
custom search widget
************************************************************/
class oc_search extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'oc_search', 'description' => __('A search form for you store', 'ocart') );

		$control_ops = array( 'width' => 300, 'height' => 200, 'id_base' => 'oc_search_widget' );

		parent::__construct( 'oc_search_widget', __('ocCommerce - Search', 'ocart'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		//Our variables from the widget settings.
		$title = apply_filters('widget_title', $instance['title'] );

		// Display widget
		global $ocart;
		echo $before_widget;
		?>

		<form method="get" action="<?php echo home_url(); ?>/">
			<input type="text" value="<?php _e('Enter search word and press enter...','ocart'); ?>" name="s" id="s" class="searchfield" />
		</form>

		<?php
		echo $after_widget;

	}

	//Update the widget
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	function form( $instance ) {

		//Set up some default widget settings.
		$defaults = array( 'title' => __('Search','ocart') );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'ocart'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>

	<?php
	}
}

/************************************************************
custom ads widget
************************************************************/
class oc_ads extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'oc_ads', 'description' => __('An advertisement widget', 'ocart') );

		$control_ops = array( 'width' => 300, 'height' => 200, 'id_base' => 'oc_ads_widget' );

		parent::__construct( 'oc_ads_widget', __('ocCommerce - Advertisement', 'ocart'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		//Our variables from the widget settings.
		$content = $instance['content'];

		// Display widget
		global $ocart;
		echo $before_widget;
		echo $content;
		echo $after_widget;

	}

	//Update the widget
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML
		$instance['content'] = $new_instance['content'];

		return $instance;
	}

	function form( $instance ) {

		//Set up some default widget settings.
		$defaults = array( 'content' => '' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'content' ); ?>"><?php _e('Advertising Code or HTML:', 'ocart'); ?></label>
			<textarea id="<?php echo $this->get_field_id( 'content' ); ?>" name="<?php echo $this->get_field_name( 'content' ); ?>" class="widefat" rows="16" cols="20"><?php if (isset($instance['content'])) { echo $instance['content']; } ?></textarea>
		</p>

	<?php
	}
}

/************************************************************
custom tabs widget
************************************************************/
class oc_tabs extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'oc_tabs', 'description' => __('A custom generated tabbed content', 'ocart') );

		$control_ops = array( 'width' => 300, 'height' => 200, 'id_base' => 'oc_tabs_widget' );

		parent::__construct( 'oc_tabs_widget', __('ocCommerce - Tabs', 'ocart'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		//Our variables from the widget settings.
		$num_recent = $instance['num_recent'];
		$num_popular = $instance['num_popular'];
		$posttype = $instance['posttype'];
		$num_comments = $instance['num_comments'];
		$len_comments = $instance['len_comments'];
		$tags_count = $instance['tags_count'];
		$tags_order = $instance['tags_order'];
		$tags_tax = $instance['tags_tax'];

		// Display widget
		echo $before_widget;

		?>

		<ul class="tabs">
			<li><a href="#"><?php _e('Recent','ocart'); ?></a></li>
			<li><a href="#"><?php _e('Popular','ocart'); ?></a></li>
			<li><a href="#"><?php _e('Comments','ocart'); ?></a></li>
			<li><a href="#"><?php _e('Tags','ocart'); ?></a></li>
		</ul><div class="clear"></div>

		<div class="tabcontent">
			<ul>
				<?php
				global $WP_Query;
				$posts = new WP_Query( array( 'post_type' => $posttype, 'posts_per_page' => $num_recent ));
				while ( $posts->have_posts() ) : $posts->the_post();
				?>
				<li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="permalink"><?php the_title(); ?></a>
				<span class="meta"><?php the_time('j M Y'); ?><?php comments_popup_link( __('0 Comments','ocart'), __('1 Comment','ocart'), __('% Comments','ocart'), '', '' ); ?></span></li>
				<?php
				endwhile;
				?>
			</ul>
		</div>

		<div class="tabcontent">
			<ol>
				<?php
				global $WP_Query;
				$posts = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => $num_popular, 'orderby' => 'comment_count' ));
				while ( $posts->have_posts() ) : $posts->the_post();
				?>
				<li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="permalink"><?php the_title(); ?></a>
				<span class="meta"><?php the_time('j M Y'); ?><?php comments_popup_link( __('0 Comments','ocart'), __('1 Comment','ocart'), __('% Comments','ocart') ); ?></span></li>
				<?php
				endwhile;
				?>
			</ol>
		</div>

		<div class="tabcontent">
			<ul><?php ocart_recent_comments($num_comments, $len_comments); ?></ul>
		</div>

		<div class="tabcontent">
			<div class="tagcloud">
				<?php
				if ($tags_order == 'count') {
					wp_tag_cloud( array( 'smallest' => 8, 'largest' => 8, 'number' => $tags_count, 'orderby' => 'count', 'order' => 'DESC', 'taxonomy' => $tags_tax ) );
				} else {
					wp_tag_cloud( array( 'smallest' => 8, 'largest' => 8, 'number' => $tags_count, 'orderby' => 'name', 'order' => 'ASC', 'taxonomy' => $tags_tax ) );
				}
				?>
				<div class="clear"></div>
			</div>
		</div>

		<?php

		echo $after_widget;

	}

	//Update the widget
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML
		$instance['num_recent'] = strip_tags( $new_instance['num_recent'] );
		$instance['num_popular'] = strip_tags( $new_instance['num_popular'] );
		$instance['posttype'] = strip_tags( $new_instance['posttype'] );
		$instance['num_comments'] = strip_tags( $new_instance['num_comments'] );
		$instance['len_comments'] = strip_tags( $new_instance['len_comments'] );
		$instance['tags_count'] = strip_tags( $new_instance['tags_count'] );
		$instance['tags_order'] = strip_tags( $new_instance['tags_order'] );
		$instance['tags_tax'] = strip_tags( $new_instance['tags_tax'] );

		return $instance;
	}

	function form( $instance ) {

		//Set up some default widget settings.
		$defaults = array( 'num_recent' => 5, 'num_popular' => 5, 'num_comments' => 5, 'len_comments' => 100, 'posttype' => 'post', 'tags_count' => 45, 'tags_order' => 'count', 'tags_tax' => 'post_tag' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'num_recent' ); ?>"><?php _e('Number of Recent Posts:', 'ocart'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'num_recent' ); ?>" name="<?php echo $this->get_field_name( 'num_recent' ); ?>" value="<?php echo $instance['num_recent']; ?>" class="widefat" /><br />
			<small><?php _e('How many recent posts you want to display.','ocart'); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'posttype' ); ?>"><?php _e('Recent Posts Type:', 'ocart'); ?></label>
			<select id="<?php echo $this->get_field_id( 'posttype' ); ?>" name="<?php echo $this->get_field_name( 'posttype' ); ?>" class="widefat">
				<option value="post"<?php selected('post', $instance['posttype']); ?>><?php _e('Post','ocart'); ?></option>
				<option value="product"<?php selected('product', $instance['posttype']); ?>><?php _e('Product','ocart'); ?></option>
			</select><br />
			<small><?php _e('Display latest posts or latest products.','ocart'); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'num_popular' ); ?>"><?php _e('Number of Popular Posts:', 'ocart'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'num_popular' ); ?>" name="<?php echo $this->get_field_name( 'num_popular' ); ?>" value="<?php echo $instance['num_popular']; ?>" class="widefat" /><br />
			<small><?php _e('How many popular posts you want to display.','ocart'); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'num_comments' ); ?>"><?php _e('Number of Recent Comments:', 'ocart'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'num_comments' ); ?>" name="<?php echo $this->get_field_name( 'num_comments' ); ?>" value="<?php echo $instance['num_comments']; ?>" class="widefat" /><br />
			<small><?php _e('How many recent comments you want to display.','ocart'); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'len_comments' ); ?>"><?php _e('Comment Length (characters):', 'ocart'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'len_comments' ); ?>" name="<?php echo $this->get_field_name( 'len_comments' ); ?>" value="<?php echo $instance['len_comments']; ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'tags_count' ); ?>"><?php _e('Number of Tags:', 'ocart'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'tags_count' ); ?>" name="<?php echo $this->get_field_name( 'tags_count' ); ?>" value="<?php echo $instance['tags_count']; ?>" class="widefat" /><br />
			<small><?php _e('How many tags you want to display.','ocart'); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'tags_order' ); ?>"><?php _e('Order Tags by:', 'ocart'); ?></label>
			<select id="<?php echo $this->get_field_id( 'tags_order' ); ?>" name="<?php echo $this->get_field_name( 'tags_order' ); ?>" class="widefat">
				<option value="name"<?php selected('name', $instance['tags_order']); ?>><?php _e('Name','ocart'); ?></option>
				<option value="count"<?php selected('count', $instance['tags_order']); ?>><?php _e('Count','ocart'); ?></option>
			</select><br />
			<small><?php _e('You can order tags by name or count.','ocart'); ?></small>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'tags_tax' ); ?>"><?php _e('Taxonomy to generate the tag cloud:', 'ocart'); ?></label>
			<select id="<?php echo $this->get_field_id( 'tags_tax' ); ?>" name="<?php echo $this->get_field_name( 'tags_tax' ); ?>" class="widefat">
				<?php
				$taxonomies=get_taxonomies('','names');
				if  ($taxonomies) {
					foreach($taxonomies as $taxonomy) {
				?>
				<option value="<?php echo $taxonomy; ?>"<?php selected($taxonomy, $instance['tags_tax']); ?>><?php echo $taxonomy; ?></option>
				<?php
					}
				}
				?>
			</select><br />
			<small><?php _e('You can list tags for any given taxonomy above. Default is <b>post_tag</b>.','ocart'); ?></small>
		</p>

	<?php
	}
}
