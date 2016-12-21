<?php
/**
 * Template Name: One column, no sidebar
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers HTML5 3.0
 */

get_header(); ?>

<section id="main_content">
	<div class="wrap clearfix">		
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

	<?php if (function_exists('breadcrumbs')) breadcrumbs(); ?>
	<?php get_search_form(); ?>
	
		<div class="innerContent fullwidth">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header>
					<h1><?php the_title(); ?></h1>
				</header>				
					
					<?php the_content(); ?>
							
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