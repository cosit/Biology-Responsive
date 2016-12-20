<?php
/**
 * Template Name: Mammal Results
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
			
			<header class="entry-header"> 
				<h1 class="entry-title"> <i class="fa fa-map-marker"></i>
					<?php the_title(); ?></h1>
			</header>
			<div class="entry-content">
			<?php 

			// Grab and sanitize results from _POST
			$mammalInfraclass = esc_attr($_POST["mammalinfraclass"]);
			$mammalOrder 	  = esc_attr($_POST["mammalorder"]);
			$mammalFamily 	  = esc_attr($_POST["mammalfamily"]);
			$mammalGenus 	  = esc_attr($_POST["mammalgenus"]);

			// Set the $paged varible for pagination
			$paged = get_query_var('paged');

			// Set and build out custom taxonomy query
			$tax_query = array('relation' => 'AND');

			if (isset($mammalInfraclass)&&!empty($mammalInfraclass)){
				$tax_query[] = array(
					'taxonomy'	=>	'cos_mammal_infraclass',
					'field'		=>	'slug',
					'terms'		=>	$mammalInfraclass,
				);		
			}
			if (isset($mammalOrder)&&!empty($mammalOrder)){
				$tax_query[] = array(
					'taxonomy'	=>	'cos_mammal_order',
					'field'		=>	'slug',
					'terms'		=>	$mammalOrder,
				);		
			}
			if (isset($mammalFamily)&&!empty($mammalFamily)){
				$tax_query[] = array(
					'taxonomy'	=>	'cos_mammal_family',
					'field'		=>	'slug',
					'terms'		=>	$mammalFamily,
				);	
			}
			if (isset($mammalGenus)&&!empty($mammalGenus)){
				$tax_query[] = array(
					'taxonomy'	=>	'cos_mammal_genus',
					'field'		=>	'slug',
					'terms'		=>	$mammalGenus,
				);			
			}

			$max_posts_per_page = 40;

			$args = array(
			  	'post_type'		 	=>	'cos_mammals',		
			  	'posts_per_page'	=>	$max_posts_per_page,		  
			  	'paged'				=>	$paged,
				'tax_query'			=>	$tax_query,
				'orderby'			=>	'name',
				'order'				=>	'ASC'			  
			);

			$my_query = new WP_Query( $args );

			if($my_query->have_posts()): 
				$names_to_list = "";					

			$number_results = $my_query->found_posts; 
			$result_counter = "0";

			while ( $my_query->have_posts() ) : $my_query->the_post();

				if(($result_counter === "0" )&&( $number_results >= "6")) 
					$names_to_list .= "<ul class='group_results one_half'>";
				elseif(($result_counter == ($max_posts_per_page/2))&&($number_results > "10"))
					$names_to_list .= "</ul><ul class='group_results one_half'>";

				$names_to_list .= "<li><strong><a href=" . get_permalink() . ">" . the_title('','',false) . "</a></strong></li>";
				$result_counter++;
				
			endwhile; 		
				$names_to_list .= "</ul>";
			?>
			<br/>
			<h4>Number of Common Name results for mammals that match your criteria: <?php echo $number_results; ?> </h4>
			<blockquote>
			<?php 
				if(!empty($mammalInfraclass)) 
					echo "<strong>Infraclass: </strong>".ucfirst($mammalInfraclass)." >> ";
				if(!empty($mammalOrder)) 
					echo "<strong>Order: </strong>".ucfirst($mammalOrder)." >> "; 
				if(!empty($mammalFamily)) 
					echo "<strong>Family: </strong>".ucfirst($mammalFamily)." >> ";		
				if(!empty($mammalGenus)) 
					echo "<strong>Genus: </strong>".ucfirst($mammalGenus)."";	
			?></blockquote>

			<?php echo $names_to_list; ?>			

			<?php 
				/* Call our pagination function and pass in our custom query max_num_pages */
				kriesi_pagination($my_query->max_num_pages); 

			// If there are no results display a message and display the search again
			else:
				echo "<h3>There are no Mammals matching your criteria.</h3><br/>";					
			endif;
			
			echo "<hr><h3>Perform a new search</h3> ".do_shortcode('[show_cos_mammals]'); 

			?>
			</div>
		</div><!-- .innerContent -->
	</div><!-- .wrap clearfix -->
</section>

<?php get_footer(); ?>