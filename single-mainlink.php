<?php
/**
 * The Template for displaying individual Main Link  of the CPT "mainlink".
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
			<header><h1><?php the_title(); ?></h1></header>
			<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> >
			<?php 
				$mainlinkImage = get_field('image');				
				if(!empty($mainlinkImage))
					echo "<img src='$mainlinkImage' alt='".get_the_title()."'/>";				
				?>
				
				<footer>
					<?php edit_post_link( __( 'Edit Main Link', 'starkers' ), '', '' ); ?>
				</footer>
			</article>
		</div>
	
<?php endwhile; ?>
	</div>
</section>

<?php get_footer(); ?>