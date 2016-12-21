<?php
/*
Template Name: Graduate Classes
*/
?>
<?php get_header(); ?>


<section id="main_content">
	<div class="wrap clearfix">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

	<?php if (function_exists('breadcrumbs')) breadcrumbs(); ?>
	<?php get_search_form(); ?>
		<div id="sidebar" style="float: <?php echo get_field('cos_sidebar_location', 'option');?>;">
			<?php 
				custom_menu_nav();			
			?>
			<?php get_sidebar(); ?>
			<?php if ( is_active_sidebar( 'graduate-sidebar-widget-area' ) ) : ?>
					<?php dynamic_sidebar( 'graduate-sidebar-widget-area' ); ?>
			<?php endif; ?>
		</div>
		<div class="innerContent">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header>
					<h1><?php the_title(); ?></h1>					
				</header>									
					<?php the_content(); ?>

					<?php cos_show_classes('graduate'); ?>
							
					<?php wp_link_pages( array( 'before' => '<nav>' . __( 'Pages:', 'biology-department' ), 'after' => '</nav>' ) ); ?>
							
				<footer>
					<?php edit_post_link( __( 'Edit Page', 'biology-department' ), '', '' ); ?>
				</footer>
			</article>
		</div>

<?php endwhile; ?>
	</div> <!-- End Wrap -->
</section>

<?php get_footer(); ?>