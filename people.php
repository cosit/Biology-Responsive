<?php
/*
Template Name: People List
*/
?>
<?php get_header(); ?>

<section id="main_content" class="peopleContent">
	<div class="wrap clearfix">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

	<?php if (function_exists('breadcrumbs')) breadcrumbs(); ?>
	<?php get_search_form(); ?>
		<div id="sidebar" style="float: <?php echo ( get_field('cos_sidebar_location', 'option') ? get_field('cos_sidebar_location', 'option') : "right" ) ;?>;">
			<?php 
				custom_menu_nav();
			?>
			<?php get_sidebar(); ?>
		</div>	
		<div class="innerContent">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header>
					<h1>People - All</h1>
				</header>				

					<?php show_people(); ?>
							
				<footer>
					<?php edit_post_link( __( 'Edit', 'starkers' ), '', '' ); ?>
				</footer>
			</article>
		</div>		

<?php endwhile; ?>
	</div> <!-- End Wrap -->
</section>

<?php get_footer(); ?>