<?php
/**
 * The template for displaying Search Results pages.
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

		<div id="search_results" class="innerContent fullwidth">
<?php if (have_posts()) : ?>
			<header><h1><?php printf( __( 'Search Results for: <span>%s</span>', 'starkers' ), '' . get_search_query() . '' ); ?></h1></header>
			
			<?php while (have_posts()) : the_post(); 

					$the_post_type = get_post_type();
					$post_title = the_title('','',false);
			?>			

				<div <?php post_class("ind-result") ?> id="post-<?php the_ID(); ?>">
					<h3><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h3>			
					<div class="entry">
						<?php
							if($the_post_type == "people"){
								$text = trim(get_field('biography'));

								if(!empty($text)){								
									$my_excerpt = "<p>";
									if( strlen($text) >= 250)
										$my_excerpt .= substr( strip_tags($text), 0, strpos($text, ' ', '250'));
									else
										$my_excerpt .= strip_tags($text);
									
									$my_excerpt .= " [...]</p>";
									echo "<h5 class='search_position'>".get_field('position')."</h5>";
									echo $my_excerpt;
								}
							}
						?>
						<?php the_excerpt(); ?>
					</div>
				</div>
			<?php endwhile; ?>
	
			<?php
				//Custom pagination 
				kriesi_pagination('','2');
				get_template_part( 'loop', 'search' );
			?>
<?php else : ?>
		<h1><?php printf( __( 'Nothing Found for: <span>%s</span>', 'starkers' ), '' . get_search_query() . ''); ?></h1>
			<h2><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'starkers' ); ?></h2>
<?php endif; ?>
		</div> <!-- End innerContent -->
	</div> <!-- End Wrap -->
</section>

<?php get_footer(); ?>