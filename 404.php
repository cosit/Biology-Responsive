<?php
/**
 * The template for displaying 404 pages.
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
	<div class="innerContent">
			<article id="post-<?php the_ID(); ?>" class="not_found">
				<header><h1><?php _e( 'Not Found', 'starkers' ); ?></h1></header>

				<p>Looks like you happened upon the wrong page, sorry about that, give the rest of the site a shot. <a href=" <?php echo home_url( '/' ); ?> " class="bio-btn">Back to home</a></p>

			</article>
		
	</div>
</section>

<?php get_footer(); ?>