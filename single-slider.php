<?php
/**
 * The Template for displaying individual Slides of the CPT "slider".
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
			<?php 
				$sliderImage = get_field('image');
				$slideContent = get_field('content');
				if(!empty($sliderImage))
					echo "<img src='$sliderImage' alt='".get_the_title()."'/>";
				echo "<h2>".get_the_title()."</h2>";
				if(!empty($slideContent))
					echo "<p>$slideContent</p>";
				?>
				
				<footer>
					<?php edit_post_link( __( 'Edit Slider', 'biology-department' ), '', '' ); ?>
				</footer>
			</article>
		</div>
	
<?php endwhile; ?>
	</div>
</section>

<?php get_footer(); ?>