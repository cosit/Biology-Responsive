<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers HTML5 3.0
 */

get_header(); ?>
<section id="main_content">
	<div class="wrap clearfix">
	<?php if (function_exists('breadcrumbs')) breadcrumbs(); ?>
	<?php get_search_form(); ?>
		
		<div id="sidebar" style="float: <?php echo ( get_field('cos_sidebar_location', 'option') ? get_field('cos_sidebar_location', 'option') : "right" ) ;?>;">
			<?php 
				custom_menu_nav();
			?>
			<?php get_sidebar(); ?>
		</div>	

		<div class="innerContent">

		<?php get_template_part( 'loop', 'single' ); ?>

		</div>

	</div>
</section>

<?php get_footer(); ?>	
