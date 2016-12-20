<?php
/**
 * The Template for displaying custom taxonomies (mostly people).
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers HTML5 3.0
 */

get_header(); 
$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );?>

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
			<h1>People - <?php echo $term->name; //echo ucwords( preg_replace("/-/", " ", $_GET['people_cat'] ) ); ?> </h1>
			<?php show_people( $term->slug ); ?>
		</div>
	
	</div> <!-- End Wrap -->
</section>

<?php get_footer(); ?>