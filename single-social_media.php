<?php
/**
 * The Template for displaying individual Social Media of the CPT "social_media".
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
		
		<div class="innerContent fullwidth">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>			
			<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> >
			
			</article>
		</div>
	
<?php endwhile; ?>
	</div>
</section>

<?php get_footer(); ?>