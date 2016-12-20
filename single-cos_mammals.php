<?php
/**
 * The Template for displaying custom taxonomies (mostly people).
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
		
		<div id="" class="innerContent fullwidth">
		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
			<header><h1><?php the_title(); ?></h1></header>
			<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> >
				
			<?php 
				$class_info = array(
					'cos_mammal_infraclass' => get_field('mammal_infraclass'),
					'cos_mammal_order'	=> get_field('mammal_order'),	
					'cos_mammal_family'	=> get_field('mammal_family'),
					'cos_mammal_genus'	=> get_field('mammal_genus'),
				);

				$specimen_type_number = array(
					'Skull'		=>	get_field('mammal_specimen_skull'),
					'Skeleton'	=>	get_field('mammal_specimen_skeleton'),
					'Mount'	=>	get_field('mammal_specimen_mount'),
					'Pelt'	=>	get_field('mammal_specimen_pelt'),
				);

				$cabinet_loc = get_field("mammal_location_cabinet");
				$drawer_loc	 = get_field("mammal_location_drawer");

				$miscellaneous_info = array(
					'Other' 	=> get_field('mammal_other'),
					'Notes'		=> get_field('mammal_notes'),
					'Damaged' 	=> get_field('mammal_damaged'),
				);

				// Class Information
				echo "<h3 class='mammal_header'>Class Information</h3>";
				echo "<p>";
				foreach ($class_info as $class_item => $value) {
					if(!empty($value)){
						$term = get_term($value, $class_item);		
						echo "<strong>".ucfirst(str_replace("cos_mammal_", "", $class_item)).":</strong> $term->name<br/>";
					}
				}		
				// Store Genus term to grab it's name for use in the
				// upload directory
				$genus = get_term($class_info['cos_mammal_genus'], 'cos_mammal_genus');

				echo "<strong>Species: </strong>".ucfirst(get_field("mammal_species"))."<br/>";	
				echo "<strong>Common Name: </strong>".get_field("mammal_common_name")."</p>";
				
				// Specimen Location
				echo "<h3 class='mammal_header'>Specimen Location</h3>";
				echo "<p><strong>Cabinet:</strong> $cabinet_loc<br/><strong>Drawer:</strong> $drawer_loc</p>";

				// Specimen Type/Number
				echo "<h3 class='mammal_header'>Specimen Type/Number</h3>";
				echo "<p>";
				foreach ($specimen_type_number as $specimen_type => $value) {
					if(!empty($value)){							
						echo "<strong>$specimen_type:</strong> $value<br/>";
					}
				}

				// Specimen Type/Number	
				// If there are any Miscellaneous Field values	
				if(array_filter($miscellaneous_info)){	
					echo "<h3 class='mammal_header'>Miscellaneous Information</h3>";
					echo "<p>";
					foreach ($miscellaneous_info as $info => $value) {
						if(!empty($value)){							
							echo "<strong>$info:</strong> $value<br/>";
						}
					}
				}					

				$mammal_picture = get_field('mammal_picture');

				if($mammal_picture === "yes"){
					// Get the root upload directory from WordPress
					$upload_dir = wp_upload_dir();

					// The next folder will be the specimen's Order
					$order_folder = get_term($class_info['cos_mammal_order'], 'cos_mammal_order');

					// Possible folder based on specimen's Family
					$family_folder = get_term($class_info['cos_mammal_family'], 'cos_mammal_family');

					/* Get the upload directory to be used only for scandir.  This first directory assumes the /mammals/order/mammal_picture_folder_location structure
					 */
					$base_upload_dir = $upload_dir['basedir']."/mammals/".$order_folder->name."/".$genus->name." ".strtolower(get_field("mammal_species"))."/";
					
					// Get the upload directory to be used for the image links
					$normal_upload_dir = $upload_dir['baseurl']."/mammals/".$order_folder->name."/".$genus->name." ".strtolower(get_field("mammal_species"))."/";	

					/* If the mammal is using a different folder structure try /mammals/order/family/mammal_picture_folder_location structure */
					if(!is_dir($base_upload_dir)){							
						$base_upload_dir = $upload_dir['basedir']."/mammals/".$order_folder->name."/".$family_folder->name."/".$genus->name." ".strtolower(get_field("mammal_species"))."/";
						
						$normal_upload_dir = $upload_dir['baseurl']."/mammals/".$order_folder->name."/".$family_folder->name."/".$genus->name." ".strtolower(get_field("mammal_species"))."/";
					}

					echo "<h3 class='mammal_header'>Photos</h3>".cos_mammal_pictures($base_upload_dir, $normal_upload_dir);						
				}
			?>				
				<footer>
					<?php edit_post_link( __( 'Edit Mammal', 'starkers' ), '', '' ); ?>
				</footer>
			</article>
		</div>
	
<?php endwhile; ?>
	</div>
</section>

<?php get_footer(); ?>

