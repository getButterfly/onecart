<?php global $ocart; ?>

<div id="footer">
	<div class="wrap">
	
		<div class="alignleft">
			
			<p class="footer_menu">
			<?php
			$menuParameters = array(
				'container'       => false,
				'echo'            => false,
				'fallback_cb'     => false,
				'items_wrap'      => '%3$s',
				'depth'           => 0,
				'theme_location'  => 'footer_menu',
			);
			echo strip_tags(wp_nav_menu( $menuParameters ), '<a>' );
			?>
			</p>

			<?php if (isset($ocart['html_footer'])) { ?>
			<p><?php echo $ocart['html_footer']; ?></p>
			<?php } ?>
			
		</div>
		
		<?php ocart_payment_logos(); ?>
		
		<div class="clear"></div>
	
	</div>
</div>