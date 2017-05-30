<?php
/**
 * Functions and definitions
 *
 * @package WordPress
 */

// *********************************************
// * Include ACF in theme settings
// *********************************************
add_filter('acf/settings/path', 'my_acf_settings_path');
function my_acf_settings_path( $path ) {
    // Local dev path
    //$path = 'C:/wamp/www/cos/wp-content/themes/cos-responsive-main/acf/';
    $path = get_stylesheet_directory() . '/acf/';
    return $path;    
}
add_filter('acf/settings/dir', 'my_acf_settings_dir');
function my_acf_settings_dir( $dir ) {
    // Local dev path
    //$dir = 'C:/wamp/www/cos/wp-content/themes/cos-responsive-main/acf/';
    $dir = get_stylesheet_directory_uri() . '/acf/';
    return $dir;
}
// Local dev path
//include_once( 'C:/wamp/www/cos/wp-content/themes/cos-responsive-main/acf/acf.php' );
include_once( get_stylesheet_directory() . '/acf/acf.php' );

/** Tell WordPress to run starkers_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'starkers_setup' );

// Do shortcodes in widgets, woohoo!
add_filter('widget_text', 'do_shortcode');

add_action( 'init', 'register_my_menus' );
function register_my_menus() {
	register_nav_menus( 
    array(
      'primary-menu' => __( 'Primary Menu'),      
    )
  );
}


// *********************************************
// * COS JAVASCRIPT FILES
// *********************************************
function load_custom_script() {

    wp_register_script('modernizr', get_template_directory_uri().'/js/modernizr-1.6.min.js');
    wp_enqueue_script('modernizr');

    //This sets jQuery into no conflict mode    
    wp_enqueue_script('jquery.ui');
    wp_enqueue_script('jquery');	

    wp_register_script('cos_js', get_template_directory_uri().'/js/cos.js');
    wp_enqueue_script('cos_js');

    if(is_home()){
    	wp_register_script('flexslider', get_template_directory_uri().'/js/jquery.flexslider-min.js');
    	wp_enqueue_script('flexslider');
    }

    wp_register_style('webfonts', get_template_directory_uri().'/webfonts/fonts.css' );
    wp_enqueue_style('webfonts' );

}

function load_custom_style() {	}

add_action('wp_print_scripts', 'load_custom_script');
add_action('wp_print_styles', 'load_custom_style');


/*****************************
 * Custom Editor stylesheet
 ****************************/
add_editor_style('cos-custom-editor-style.css');


/***************************
 * FontAwesome Integration 
 **************************/
function FontAwesome_icons(){
	echo '<link href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">';
}
add_action('admin_head', 'FontAwesome_icons');
add_action('wp_head', 'FontAwesome_icons');


// ***************************
// * User Role Customization
// ***************************

// Allow editors to edit theme options
$role_object = get_role('editor');
$role_object->add_cap( 'edit_theme_options' );


// Editor Access Gravity Forms 
function add_grav_forms(){
	$role = get_role('editor');
	$role->add_cap('gform_full_access');
}
add_action('admin_init','add_grav_forms');


/***************************
 * Google Analytics 
 **************************/
add_action('wp_head', 'add_googleanalytics');
function add_googleanalytics(){
	$analyticsCode = get_option('COS_Google_Analytics'); 

	if(!empty($analyticsCode)):
	?>
	<!--- Google Analytics -->
  <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '<?php echo $analyticsCode; ?>', 'auto');
  ga('send', 'pageview');
  </script>
<!--- End Google Analytics -->
<?php endif; }


/************************
 * ACF Options Page
 ************************/
if( function_exists('acf_add_options_page') ) {
 
  $option_page = acf_add_options_page(array(
    'page_title'  => 'Theme General Settings',
    'menu_title'  => 'Theme Settings',
    'menu_slug'   => 'theme-general-settings',
    'capability'  => 'edit_theme_options',
    'redirect'    =>  false,
    'position'    =>  '63.3',
  ));
 
}


if ( ! function_exists( 'starkers_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since Starkers HTML5 3.0
 */


/***************************
 * Custom Excerpt 
 **************************/
// Simple function for getting the excerpt of a body of text
// Takes in two parameters: $text = body of text, $cutoff = number of characters in excerpt
function excerpt( $text, $cutoff, $link='', $link_text="Read More" ){
	$excerptText = '';
	if( is_int($cutoff) && $cutoff > 0 ){
		$excerptText = substr( $text, 0, $cutoff ) == $text ? $text : substr( $text, 0, $cutoff ) . '...';
		if( $link != ''){
			$excerptText .= ' <a href="' . $link . '" class="readMore">' . $link_text . '</a>';
		}
	} else { return $text; }

	return $excerptText;
}

function custom_menu_nav( $pageID = 0, $menu_name = 'primary-menu' ){

	$args = array(
		'theme_location'  => 'primary-menu', 
		'menu_class'      => '', 
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'depth'           => 0,
		'walker'          => '',
	);

	echo '<nav class="pageNav" id="custom_menu_nav">';
	wp_nav_menu( $args );
	echo '</nav>';
}
add_action( 'custom_menu_nav', 'custom_menu_nav', 10, 1 );


// ***********************************************
// * Function used to display sidebar navigation 
// * based on the structure of the main menu 
// ***********************************************
function page_nav( $pageID = 0 ){
	$pageID = ($pageID == 0 ? get_the_ID() : $pageID);
	$parent = get_permalink( $pageID );

	$currentPage = get_post( $pageID );
	// Check if post/page is a child or a parent

  //echo "<pre>";  print_r($currentPage); echo "</pre>";
	if( $currentPage->post_parent ){
		$children = wp_list_pages('title_li=&child_of='.$currentPage->post_parent.'&echo=0');
		$title = get_the_title( $currentPage->post_parent );
		$parent = get_permalink( $currentPage->post_parent );

	} else {
		$children = wp_list_pages('title_li=&child_of='.$pageID.'&echo=0');
		$title = get_the_title();
		
	}

	echo '<nav class="pageNav" id="page_nav"><h2><a href="'.$parent.'">'. $title . '</a></h2><ul>';
	echo $children;
	echo '</ul>';

	// Show news categories
	if( $title == 'News' ){
		echo '<ul>';
		echo wp_list_categories('title_li=');
		echo '</ul>';
	}

	echo '</nav>';
}
add_action( 'page_nav', 'page_nav', 10, 1 );
// add_shortcode('page_nav', 'page_nav');


// ***********************************************
// * Function used to display sidebar navigation 
// * based on the People Classifications 
// ***********************************************
function people_nav( $pageID = '' ){
	$pageID = $pageID || get_the_ID();
	$term_id = get_query_var('term_id');
	$currentPage = get_post( $pageID );

	echo '<nav class="pageNav sidebar"><h2><a href="'.home_url().'/people/">People</a></h2><ul>';
	echo show_people_cats( false );
	echo '</ul></nav>';
}
add_shortcode('people_nav', 'people_nav');


//*******************************************************
// * Add PDF, DOC, and EXCEL Filtering to Media Library
//******************************************************* 
function modify_post_mime_types( $post_mime_types ) {
	// select the mime type, here: 'application/pdf'
	// then we define an array with the label values
	$post_mime_types['application/pdf'] = array( __( 'PDFs' ), __( 'Manage PDFs' ), _n_noop( 'PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>' ) );
	$post_mime_types['application/msword'] = array( __( 'DOCs' ), __( 'Manage DOCs' ), _n_noop( 'DOC <span class="count">(%s)</span>', 'DOC <span class="count">(%s)</span>' ) );
    $post_mime_types['application/vnd.ms-excel'] = array( __( 'XLSs' ), __( 'Manage XLSs' ), _n_noop( 'XLS <span class="count">(%s)</span>', 'XLSs <span class="count">(%s)</span>' ) );	
	// then we return the $post_mime_types variable
	return $post_mime_types;
}
// Add Filter Hook
add_filter( 'post_mime_types', 'modify_post_mime_types' );


// ********************************
// * Remove extra admin menu items
// ********************************
add_action( 'admin_menu', 'my_remove_menu_pages' );
function my_remove_menu_pages() {
	remove_menu_page('link-manager.php');	
	remove_menu_page('edit-comments.php');	
 // remove_menu_page('edit.php?post_type=mainlink');  
}


// **************************************************************************
// * Creates a whitelist for the RSS Parsing functions that cannot 
// * verify the events.ucf.edu or news.cos.ucf.edu external & internal DNS
// **************************************************************************
function myWhitelist( $is_external, $host ) {	
	switch ($host) {
		case 'news.cos.ucf.edu':
			$is_external = true;
			break;
		case 'events.ucf.edu':
			$is_external = true;
	}	
	return $is_external;
}
add_filter( 'http_request_host_is_external', 'myWhitelist', 10, 2 ); 


// *********************
// * Breadcrumbs 
// *********************
function breadcrumbs() {
 
  $showOnHome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
  $delimiter = '<span class="crumbSep"> &raquo; </span>'; // delimiter between crumbs
  $home = 'Home'; // text for the 'Home' link
  $showCurrent = 0; // 1 - show current post/page title in breadcrumbs, 0 - don't show
  $before = '<a href="#">'; // tag before the current crumb
  $after = '</a>' . $delimiter; // tag after the current crumb
 
  global $post;
  $homeLink = home_url();

 
  if (is_home() || is_front_page()) {
  	if ($showOnHome == 1) echo '<div id="breadcrumbs"><a href="' . $homeLink . '">' . $home . '</a></div>';
 
  } else {
 
    echo '<div id="breadcrumbs" class="wrap"><a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
 
    if ( is_category() ) {
      global $wp_query;
      $cat_obj    = $wp_query->get_queried_object();
      $thisCat    = $cat_obj->term_id;
      $thisCat    = get_category($thisCat);
      $parentCat  = get_category($thisCat->parent);
      if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
      echo $before . 'Category "' . single_cat_title('', false) . '"' . $after;
 
    } elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('d') . $after;
 
    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('F') . $after;
 
    } elseif ( is_year() ) {
      echo $before . get_the_time('Y') . $after;
 
    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() != 'post' ) {

        $post_type = get_post_type_object(get_post_type());

        $slug = $post_type->rewrite;
        echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->name . '</a> ' . $delimiter . ' ';
        if ($showCurrent == 1) echo $before . get_the_title() . $after;
      } else {

        $cat = get_the_category(); $cat = $cat[0];
        echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
        if ($showCurrent == 1) echo $before . get_the_title() . $after;
      }
 
    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $post_type = get_post_type_object(get_post_type());
      echo $before . $post_type->labels->name . $after;
 
    } elseif ( is_attachment() ) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
      if ($showCurrent == 1) echo $before . get_the_title() . $after;
 
    } elseif ( is_page() && !$post->post_parent ) {
      if ($showCurrent == 1) echo $before . get_the_title() . $after;
 
    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
      if ($showCurrent == 1) echo $before . get_the_title() . $after;
 
    } elseif ( is_search() ) {
      echo $before . 'Search results for "' . get_search_query() . '"' . $after;
 
    } elseif ( is_tag() ) {
      echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
 
    } elseif ( is_author() ) {
       global $author;
      $userdata = get_userdata($author);
      echo $before . 'Articles posted by ' . $userdata->display_name . $after;
 
    } elseif ( is_404() ) {
      echo $before . 'Error 404' . $after;
    }
 
    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __('Page') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }
    echo '<a class="mobile_menu" href="#page_nav">=</a></div>';
  }
} 


// **********************************************
// ********************************************
// * Custom Post Types and related functions
// ********************************************
// **********************************************


/*************************
 * Custom Icons for CPTs *
*************************/
add_action('admin_head', 'font_awesome_cpt_icons');
function font_awesome_cpt_icons() {
?>
<style type="text/css">
	#adminmenu #menu-posts-people .wp-menu-image:before {
		content: "\f0c0";
	 	font-family: 'FontAwesome' !important;
	 	font-size: 18px !important;
	}
	#adminmenu #menu-posts-mainlink .wp-menu-image:before {
		content: "\f0c1";
	 	font-family: 'FontAwesome' !important;
	 	font-size: 18px !important;
	}
	#adminmenu #menu-posts-social_media .wp-menu-image:before {
		content: "\f099";
	 	font-family: 'FontAwesome' !important;
	 	font-size: 18px !important;
	}
	#adminmenu #menu-posts-slider .wp-menu-image:before {
		content: "\f152";
	 	font-family: 'FontAwesome' !important;
	 	font-size: 18px !important;
	}	
  #adminmenu #menu-posts-classes .wp-menu-image:before {
    content: "\f19d";
    font-family: 'FontAwesome' !important;
    font-size: 18px !important;
  } 
  #adminmenu #menu-posts-cos_mammals .wp-menu-image:before {
    content: "\f1d6";
    font-family: 'FontAwesome' !important;
    font-size: 18px !important;
  } 

</style>
<?php
}


// *****************************************************
// * Custom Titles for CPTs that don't use Title field
// *****************************************************
function custom_titles($title) {
	$postID   = get_the_ID();
	$postType = get_post_type( $postID );
	
	/* Note that the second field in the $_POST['acf'][***] item will vary from installation to installation */
	if( $postType == 'people' ){		
		$title = $_POST['acf']['field_53da4bc98efb3'].' '.$_POST['acf']['field_53da4be28efb4'];
	} elseif( $postType == 'slider' ){
		$title = $_POST['acf']['field_53da5e0473adc'];
	} elseif( $postType == 'mainlink') {
		$title = $_POST['acf']['field_53da48bc88a0a'];
	} elseif( $postType == 'social_media') {
		$title = $_POST['acf']['field_53da6145129fa'];
	}

	return $title;
}
add_filter('title_save_pre','custom_titles');


// ******************************************************
// * Custom Post Type for Slider (for use in FlexSlider)
// ******************************************************
function slider() {
	$labels = array(
		'name' => _x('Slides', 'post type general name'),
		'singular_name' => _x('Slider Item', 'post type singular name'),
		'add_new' 		=> _x('Add New', 'slider'),
		'add_new_item' 	=> __('Add New Slider Item'),
		'edit_item' 	=> __('Edit Slider Item'),
		'new_item' 		=> __('New Slider Item'),
		'all_items' 	=> __('All Slides'),
		'view_item' 	=> __('View Slider Item'),
		'search_items' 	=> __('Search Slides'),
		'not_found'  	=> __('No slider items found.'),
		'not_found_in_trash'=> __('No slider items found in Trash.'),
		'parent_item_colon' => '',
		'menu_name'  	=> __('Slides'),
	);

	$args = array(
		'labels' 		=> $labels,
		'singular_label'=> __('Slider Item'),
		'public' 		=> true,
		'show_ui' 		=> true,
		'capability_type' => 'post',		
		'hierarchical' 	=> true,
		'rewrite' 		=> true,
		'exclude_from_search' => true,
		'supports' 		=> array('custom-fields'),
	);

	register_post_type( 'slider', $args );
}
add_action('init', 'slider');

// ******************************
// * Function for showing Slides
// ******************************
function cos_show_slider_items() {
	// Get theme option for ordering slides by date or random
  $slider_order_type = get_field('cos_slider_ordering', 'option');
  $slider_orderby = ($slider_order_type === "date" ? "date" : "rand");  

  $sliderArgs = array(
    'post_type'     => 'slider',    
    'orderby'       => $slider_orderby,
    'order'         => 'DESC',
    'posts_per_page'=>  12,
  );
	
	$myQuery = new WP_Query($sliderArgs);		
	
	echo '<ul class="slides">';

	if($myQuery->have_posts()) : while ($myQuery->have_posts()) : $myQuery->the_post();	

		$thisID = get_the_ID();
		$hideTitle = get_field('hide_title');

		$expires = trim(get_field('expires'));
		$isExpired = $expires != '' ? 
			( $expires < date('Ymd') ? true : false )
			: false;

    $sliderLinkTarget = "_self";

		$slide = array(
			'title' 	=> get_field('title'),
			'content' 	=> '',
			'image' 	=> get_field('image'),
      'full_size_banner'=> get_field('full_size_banner'),
			'page' 		=> get_field('page'),
			'file_link' => get_field('file_link'),
			'external_link' => get_field('external_link'),
			//'position' => ucwords( get_field('position') ),
			'is_disabled' => get_field('disabled') ,
			'is_expired'  => $isExpired, // TRUE if expired
			'edit' 		=> get_edit_post_link( $thisID ),			
		);

    // Assign the slide image to another variable for easier use.  
    // Requires the field image to be an Image Object and not a URL in ACF
    $slide_image = $slide['image']; 

		// Skip slider item if it's expired or disabled
		if( $slide['is_expired'] || $slide['is_disabled'] ) continue;

		// Link to an Internal File
		if(!empty($slide['file_link'])){ 
      $slide['page'] = $slide['file_link'];
      $sliderLinkTarget = "_blank";
    }

		// Link to an External Site
		if(!empty($slide['external_link']) && !preg_match("^(http|https)\:\/\/^", $slide['external_link'])){
			$slide['page'] = "http://".$slide['external_link'];
      $sliderLinkTarget = "_blank";
		}
		elseif(!empty($slide['external_link'])){ 
      $slide['page'] = $slide['external_link'];
      $sliderLinkTarget = "_blank";
    }	
		
		// Define 'content' here to it's link at the end matches the title link
		$slide['content'] = excerpt( get_field('content'), 150, $slide['page'], '');

		// Print the Slider Information
		echo '<li class="slideTop '.($slide['full_size_banner'] === 'yes' ? 'full-size' : '').'"><a href="'.$slide['page'].'" target="'.$sliderLinkTarget.'" ><img src="'.$slide_image['sizes']['large'].'" alt="'.$slide['title'].'"/></a>';
		if($hideTitle !== 'yes'){
			echo 	'<div class="slideTitleContent"><h2><a href="'.$slide['page'].'">'.$slide['title'].'</a></h2>'; 
			if(get_field('content'))
				echo 	'<p>'.$slide['content'].'</p></div>';
		}
		echo '</li>';

	endwhile; endif; wp_reset_query();

	echo '</ul>';
}


// ***********************************
// * Custom Post Type for Main Links
// ***********************************
function mainLink() {
	$labels = array(
		'name'          => _x('Main Links', 'post type general name'),
		'singular_name' => _x('Main Link', 'post type singular name'),
		'add_new'       => _x('Add New', 'slider'),
		'add_new_item'  => __('Add New Main Link'),
		'edit_item'     => __('Edit Main Link Details'),
		'new_item'      => __('New Main Link'),
		'all_items'     => __('All Main Links'),
		'view_item'     => __('View Main Link'),
		'search_items'  => __('Search Main Links'),
		'not_found'     => __('No links found.'),
		'not_found_in_trash'  => __('No links found in Trash.'),
		'parent_item_colon'   => '',
		'menu_name'     => __('Main Links'),
	);

	$args = array(
		'labels'            => $labels,
		'singular_label'    => __('Main Link'),
		'public'            => true,
		'show_ui'           => true,
		'capability_type'   => 'post',
		'hierarchical'      => true,
		'rewrite'           => true,
		'exclude_from_search' => true,
		'supports'            => array('custom-fields'),
    	'taxonomies'          => array('cos_mainlinks_cat'),
	);
	register_post_type( 'mainlink', $args );
}
add_action('init', 'mainLink');

// ******************************************
// * Custom Taxonomy for Main Link Locations
// ******************************************
function cos_mainlinks_cat() {
  //Taxonomy for Main Link Locations
  $labels = array(
    'name'          => 'Link Locations',
    'singular_name' => 'Link Location',
    'update_item'   => 'Update Link Location',
    'edit_item'     => 'Update Link Location',
    'all_items'     => 'All Link Location',
    'add_new_item'  => 'Add New Link Location',
    'search_items'  => 'Search Link Locations',
    'popular_items' => 'Popular Link Locations',    
  );
  // Create a new taxonomy
  register_taxonomy(
    'cos_mainlinks_cat',
    'mainlink',
    array(
      'labels'      => $labels,          
      'hierarchical'=> true,
      'query_var'   => true,
      'show_ui'     => true,
      'sort'        => true,      
      'args'        => array( 'orderby' => 'term_order' ),
      'public'      => false,
      'rewrite'     => false,
    ) 
  );
}
add_action( 'init', 'cos_mainlinks_cat' ); 


// **********************************
// * Function for showing Main Links
// **********************************
function cos_show_main_links( $location = 'main-links' ) {

  // Checks to see if passed in $location is a valid taxonomy 
  // term (Link Location). If so, display it's Main Links, if not 
  // display all Main Links
  $term = term_exists( $location, 'cos_mainlinks_cat' );

  if( $term !== 0 && $term !== null ){
    $mainLinkArgs = array( 
      'post_type'         => 'mainlink',        
      'posts_per_page'    => -1,    
      'tax_query'         => array(
        array(
          'taxonomy'      => 'cos_mainlinks_cat',
          'field'         => 'slug',
          'terms'         => $location,
        )
      ),
      'orderby'           => 'menu_order',
      'order'             => 'ASC', 
    );
  } else {
    $mainLinkArgs = array( 
      'post_type'         => 'mainlink',        
      'posts_per_page'    => -1,    
      'orderby'           => 'menu_order',
      'order'             => 'ASC', 
    );  
  }

	$my_query = new WP_Query($mainLinkArgs);	

	if($my_query->have_posts()) : 
	
    echo '<section id="main_links"><div class="wrap '.$location.' ">';

    if( $location === "home-tracks" ){
      $description = strip_tags(term_description( $term['term_id'] ));
      echo '<h2>'.$description.'</h2>';
    }

		$numLinks = $my_query->found_posts;
		if($numLinks > 6) $numLinks = 6;

		while ($my_query->have_posts()) : $my_query->the_post();			
		// Grab the Post ID for the Custom Fields Function			
		$thisID = get_the_ID();

		$mainLink = array(
			'title' 	=> get_field('title'),
			'link' 		=> get_field('link'),
			'file_link' => get_field('file_link'),
			'external_link' => get_field('external_link'),
			'image' 	=> get_field('image'),
			'slice' 	=> ucwords(get_field('slice')),
			'open' 		=> get_field('open'),
		);

		//Link to an Internal File
		if(!empty($mainLink['file_link'])){ $mainLink['link'] = $mainLink['file_link'];}
		//Link to an External Site
		if(!empty($mainLink['external_link']) && !preg_match("^(http|https)\:\/\/^", $mainLink['external_link'])){
			$mainLink['link'] = "http://".$mainLink['external_link'];
		}		
		elseif(!empty($mainLink['external_link'])){ $mainLink['link'] = $mainLink['external_link'];}

		echo '<div class="mainlink-item-'.$numLinks.'">
				<div style="background-image: url('.$mainLink['image'].')" class="slice'.$mainLink['slice'].'"><a href="'.$mainLink['link'].'" target="_'.$mainLink['open'].'" >
					<h2>'.$mainLink['title'].'</h2></a>
				</div>
			</div>';
	endwhile; 

  echo '  </div>
  <div style="clear:both;"></div>
</section>';

  endif; wp_reset_query();
}


// ************************************
// * Custom Post Type for Social Media
// ************************************
function social_media() {
	$labels = array(
		'name' 			=> _x('Social Media', 'post type general name'),
		'singular_name' => _x('Social Media Item', 'post type singular name'),
		'add_new' 		=> _x('Add New', 'slider'),
		'add_new_item' 	=> __('Add New Social Media Item'),
		'edit_item' 	=> __('Edit Social Media Info'),
		'new_item' 		=> __('New Social Media Item'),
		'all_items' 	=> __('All Social Media'),
		'view_item' 	=> __('View Social Media Item'),
		'search_items' 	=> __('Search All Social Media'),
		'not_found'  	=> __('No social media found.'),
		'not_found_in_trash'=> __('No social media found in Trash.'),
		'parent_item_colon' => '',
		'menu_name'  	=> __('Social Media'),
	);
	$args = array(
		'labels' 		=> $labels,
		'singular_label'=> __('Social Media Item'),
		'public' 		=> true,
		'show_ui' 		=> true,
		'capability_type' => 'post',
		'hierarchical' 	=> true,
		'rewrite' 		=> true,
		'exclude_from_search' => true,
		'supports' 		=> array('custom-fields'),
	);

	register_post_type( 'social_media', $args );
}
add_action('init', 'social_media');

// ******************************************
// * Function for showing Social Media Icons
// ******************************************
function cos_show_social() {

	$socialMediaArgs = array( 
			'post_type' => 'social_media',
	);
	$myQuery = new WP_Query($socialMediaArgs);	  

	if($myQuery->have_posts()) : 		

    echo '<ul id="socialMedia">';

		while ($myQuery->have_posts()) : $myQuery->the_post();		

			$is_disabled = get_field('disable');
			if($is_disabled === true) continue;

			// Grab the Post ID for the Custom Fields Function			
			$thisID = get_the_ID();

			$social = array(
				'label' 	=> get_field('label'),
				'type' 		=> get_field('type'),
				'link'		=> get_field('link'),
				'disabled'	=> get_field('disabled'),
			);

			echo <<<SOCIAL
				<li>
					<a href="{$social['link']}" title="{$social['label']}" class="{$social['type']}" target="_blank"></a>
				</li>
SOCIAL;
		endwhile; 		
 
	echo '</ul>';  

  endif;   

	wp_reset_query();	
}


// ********************************
// * Custom Post Type for Classes
// ********************************
function cos_classes_cpt() {
  $labels = array(
    'name' 			=> _x('Classes', 'post type general name'),
    'singular_name' => _x('Class', 'post type singular name'),
    'add_new' 		=> _x('Add New', 'classes'),
    'add_new_item' 	=> __('Add New Class'),
    'edit_item' 	=> __('Edit Class'),
    'new_item' 		=> __('New Class'),
    'all_items' 	=> __('All Classes'),
    'view_item' 	=> __('View Class'),
    'search_items' 	=> __('Search Classes'),
    'not_found'  	=> __('No Classes found.'),
    'not_found_in_trash'=> __('No Classes found in Trash.'),
    'parent_item_colon' => '',
    'menu_name'  	=> __('Classes'),
  );

  $args = array(
    'labels'              => $labels,
    'singular_label'      => __('Class'),
    'public'              => true,
    'show_ui'             => true,
    'capability_type'     => 'post',    
    'hierarchical'        => true,
    'rewrite'             => true,
    'exclude_from_search' => false,
    'supports'            => array('title','editor','custom-fields'),
    'taxonomies'          => array('cos_classes_cat'),
  );

  register_post_type( 'classes', $args );
}
add_action('init', 'cos_classes_cpt');

// **********************************************
// * Custom taxonomy for a Class's Course Level
// **********************************************
function cos_classes_cat() {
  // create a new taxonomy
  register_taxonomy(
    'cos_classes_cat',
    'classes',
    array(
      'label'		=> __( 'Course Level' ),
      'sort' 		=> true,
      'hierarchical'=> true,
      'args' 		=> array( 'orderby' => 'term_order' ),
      'query_var' 	=> true,
      'rewrite' 	=> false, /*array( 'slug' => 'group' )*/
    )
  );
}
add_action( 'init', 'cos_classes_cat' ); 


function cos_show_classes($course_level){

  $course_args = array(
      'posts_per_page'  =>  -1,
      'post_type'       =>  'classes',
      'tax_query'       => array(
          array(
            'taxonomy'  => 'cos_classes_cat',
            'field'     => 'slug',
            'terms'     => $course_level,            
            'include_children' => FALSE, 
          )
      ),
      'orderby'   =>  'meta_value',
      'meta_key'  =>  'classes_class_number',
      'order'     =>  'ASC',
    );  

  $classes_query = new WP_Query($course_args);

  if($classes_query->have_posts()) : while ($classes_query->have_posts()) : $classes_query->the_post(); 

    echo "<article><h3>".get_the_title()." - ".get_field('classes_class_number')."</h3>
          <p>".get_the_content()."</p>
          <p>Credits: ".get_field('classes_credits');

    if(get_field('classes_pre-requisites')) echo "<br/>Prerequisites: ".get_field('classes_pre-requisites');
    if(get_field('classes_fee')) echo "<br/>Fee: $".get_field('classes_fee');
      
    echo "</p>";

  endwhile; 

  else: echo "<blockquote>There are no classes at this time</blockquote>";

  endif; wp_reset_query();
}
// ******** End Classes CPT Info ********




// ********************************
// * Custom Post Type for People
// ********************************
function people() {
  
  $labels = array(
    'name' 			=> _x('People', 'post type general name'),
    'singular_name' => _x('Person', 'post type singular name'),
    'add_new' 		=> _x('Add New', 'slider'),
    'add_new_item' 	=> __('Add New Person'),
    'edit_item' 	=> __('Edit Person Info'),
    'new_item' 		=> __('New Person'),
    'all_items' 	=> __('All People'),
    'view_item' 	=> __('View Person'),
    'search_items' 	=> __('Search All People'),
    'not_found'  	=> __('No people found.'),
    'not_found_in_trash'	=> __('No people found in Trash.'),
    'parent_item_colon' 	=> '',
    'menu_name'  	=> __('People'),
  );

  $args = array(
    'labels' 	=> $labels,
    'singular_label' 	=> __('Person'),
    'public' 	=> true,
    'show_ui' 	=> true,
    'capability_type' 	=> 'post',
    'hierarchical' 		=> true,
    'rewrite' 	=> array( 'slug' => 'faculty' ),
    'supports' 	=> array('custom-fields', 'revisions'),
    'taxonomies'=> array('people_cat'),
  );

  register_post_type( 'people', $args );
}
add_action('init', 'people');


// **********************************************
// * Custom taxonomy for People classifications
// **********************************************
function people_cat() {
  // create a new taxonomy
  register_taxonomy(
    'people_cat',
    'people',
    array(
      'label' 		=> __( 'Classifications' ),
      'sort' 		=> true,
      'hierarchical'=> true,
      'args' 		=> array( 'orderby' => 'term_order' ),
      'query_var' 	=> true,
      'rewrite' 	=> false, /*array( 'slug' => 'group' )*/
    )
  );
}
add_action( 'init', 'people_cat' ); 



// **************************************************
// * Displaying People Classifications
// **************************************************
function show_people_cats( $displayCats = true ) {

	$cats = get_terms( 'people_cat' );
	$subCats = array();
	$has_subCats = false;
	$peopleCatList = "";

	if( !empty( $cats ) ){
		foreach ($cats as $cat){
			if( $cat->parent != 0 ){
				array_push($subCats, $cat);
			}
		}
		
		foreach ($cats as $cat){
			if( $cat->parent != 0 ){
				continue;
			} else {
				$peopleCatList .= '<li>';
			}

			$peopleCatList .= '<a href="' . esc_attr(get_term_link($cat, 'people_cat' )) . '" title="' . sprintf(__('View All %s Members', 'biology-department'), $cat->name) . '">' . $cat->name . '</a>';
			

			// Add subcategories
			foreach ($subCats as $subCat){
				if( $subCat->parent == $cat->term_id ){
					if( $has_subCats ){
						$peopleCatList .= '<li><a href="' . esc_attr(get_term_link($subCat, 'people_cat' )) . '" title="' . sprintf(__('View All %s Members', 'biology-department'), $subCat->name) . '">' . $subCat->name . '</a></li>'; 
					} else {
						$peopleCatList .= '<ul class="children"><li><a href="' . esc_attr(get_term_link($subCat, 'people_cat' )) . '" title="' . sprintf(__('View All %s Members', 'biology-department'), $subCat->name) . '">' . $subCat->name . '</a></li>'; 
						$has_subCats = true;
					}
				}
			}

			if( $has_subCats ){
				$peopleCatList .= '</ul>';
				$has_subCats = false;
			}

			$peopleCatList .= '</li>';

		}
		if( $displayCats ){
			echo '<ul id="people_cats" class="children" style="display: none;">';
			echo $peopleCatList;
			echo '</ul>';
		}
		return $peopleCatList;
	}

	return false;
}


// **********************************
// * Showing individual Person info
// **********************************
function show_person( $id ) {
  
  //PersonBasics array used to only show info if entered
  $personBasics = array(
    'person_position'   => get_field('position'),
    'person_email'      => antispambot(get_field('email')),
    'person_phone'      => get_field('phone'),
    'person_location'   => get_field('location'),    
  );
  $personPhoto  = "";
  $office_hours = "";

  // Custom Post Type image must be set to "Image ID"
  $personPhoto = wp_get_attachment_image_src(get_field('headshot'), "people-custom-image");

  // If there isn't a "people-custom-image" version graph the 'Small' size
  if(empty($personPhoto))
    $personPhoto = wp_get_attachment_image_src(get_field('headshot'), 'medium' );
  if(empty($personPhoto))
    $personPhoto[0] = get_template_directory_uri().'/images/NoImageAvailable.jpg';;

  // Person's Full Name
  $fullName = trim(get_field('first_name'))."-".trim(get_field('last_name'));

  // All fields beginning with 'p_' are default fields that don't appear as tabular data
  $person = array(
    'p_first_name'  => get_field('first_name'),
    'p_last_name'   => get_field('last_name'),
    'p_photo'       => $personPhoto[0],
    'p_link'        => get_permalink(),
    'news'          => cos_person_tag($fullName), 

    'p_office_hours_private' => get_field('office_hours_private'),

  );

  // If person doesn't have private office hours
  if(($person['p_office_hours_private'] === 'public')&&($person['p_office_hours_private'] !== false)){
  
      // Office Hours
      $person['p_office_hours_mon'] = parse_hrs(get_field('office_hours_mon'));
      $person['p_office_hours_tue'] = parse_hrs(get_field('office_hours_tue'));
      $person['p_office_hours_wed'] = parse_hrs(get_field('office_hours_wed'));
      $person['p_office_hours_thu'] = parse_hrs(get_field('office_hours_thu'));
      $person['p_office_hours_fri'] = parse_hrs(get_field('office_hours_fri'));

      $office_hours = get_hrs( $person );

  }elseif( $person['p_office_hours_private'] === 'private' ){
      $office_hours = '<p class="aligncenter">Office hours are available by appointment only.  Please contact to schedule an appointment.</p>';
  }

  $contentTabs = '<ul class="tabNavigation">';
  $content = '';
  
  $personBasicsContent = "<h1 id='person_name'>".get_field('first_name')." ".get_field('last_name')."</h1><ul class=\"personBasics\">";

  // IF using ACF 5 or the repeater field to handle the Tab Content
  if( have_rows('tabs')):    
    while(have_rows('tabs')) : the_row();
      
      $tab_title = preg_replace('/[^A-Za-z0-9\-]/', '', get_sub_field('tab_title'));
      $contentTabs .= '<li><a href="#' . $tab_title . '">' . get_sub_field('tab_title') . '</a></li>';
      $content     .= '<div id="'. $tab_title . '" class="tabcontentstyle"><h3 class="tab_title">'.get_sub_field('tab_title').'</h3>'. get_sub_field('tab_content') . '</div>';

    endwhile;
  endif; 

  // Office Hours
  if( $office_hours ){
    $contentTabs .= '<li><a href="#office_hrs">Office Hours</a></li>';
    $content .= '<div id="office_hrs" class="tabcontentstyle">'.$office_hours.'</div>';      
  }

  // News articles from COS News Blog
  if(!empty($person['news'])){
      $contentTabs .= '<li><a href="#news">News</a></li>';
      $content     .= '<div id="news" class="tabcontentstyle">' . $person['news'] . '</div>';
  }

  foreach ($personBasics as $field => $value ){
    if($field == "person_email" && !empty($value)){ 
      if($value != "N/A")
        $personBasicsContent .= '<li class="'.$field.'"><a href="mailto:'.$value.'">'.$value.'</a></li>';
    }    
    elseif(!empty($value))
      $personBasicsContent .= '<li class="'.$field.'">'.$value.'</li>';     
  } 

  // CV, Website and specialty
  $person_cv = get_field('curriculum_vitae');
  $person_website = get_field('personal_website');
  $person_specialty  = get_field('research_area');      

  $contentTabs .= '</ul>';

  // Display person photo and "Person Basics"
  echo "<article class=\"person clearfix\"><figure id='person_photo_links'>".(!empty($person['p_photo']) ? "<img src='".$person['p_photo']."' alt='".$person['p_last_name']."' class='person-image' />" : '')."".(!empty($content) ? "$contentTabs" : "");

  // Display CV & Website info
  echo ($person_cv ? "<h4 class='person_cv'><a href='$person_cv'>Ciriculum Vitae</a></h4>" : "");
  echo ($person_website ? "<h4 class='person_website'><a href='$person_website'>Personal Website</a></h4>" : "");
  

  echo "</figure>" ."$personBasicsContent
        </ul>";
  if(!empty($person_specialty)) echo "<p><strong>Research Area(s): </strong> ".strip_tags($person_specialty)."</a>";

  if(!empty($content)){
    echo "
    <div class='tabs'>               
      {$content}    
    </div>  
    ";
  }        
  echo "</article>";

}

// *********************************************************
// * Retrieve articles from the COS News blog based on tag
// *********************************************************
function cos_person_tag($full_tag_name){

  include_once( ABSPATH . WPINC . '/feed.php' );
  
  $stuffToReturn = "";

  $feed = "http://sciences.ucf.edu/news/tag/".$full_tag_name."/feed";

  $rss = fetch_feed($feed);   

  if(!is_wp_error($rss)){

    $maxitems = 4;
    $items = $rss->get_items(0, $maxitems);

    if(empty($items)): return false; 

    else: 

      $stuffToReturn .= "<h3 class='tab_title'>News</h3>";

    foreach ($items as $item) : 
    
      $stuffToReturn .= "<article class='news-tag'>";      
      $stuffToReturn .=       
        "<h2><a href='".$item->get_permalink()."'  target='_blank'>".$item->get_title()."</a></h2>"; 
        
      //Strip out any image tag because it was already stripped out and used above
      $postDescription = substr(preg_replace("/<img[^>]+\>/i", "", $item->get_description()), 0, strpos($item->get_description(), ' ', 210 ));
      $postDescription .= '... <a href="'.$item->get_permalink().'">Read more</a>';
      
      $stuffToReturn .= $postDescription;
      
      $stuffToReturn .= "</article>";

    endforeach;     

    if($maxitems > 0 ){
      $stuffToReturn .= "<p><strong><a href='http://news.cos.ucf.edu/tag/$full_tag_name' target='_blank'>Click here to read additional news stories</a></strong></p>";
    }

    return $stuffToReturn;

    endif; 

  }
}

// ************************************************************
// * Displays list of People based on category (default: all)
// * Used in the 'People List' page template
// ************************************************************
function show_people( $catID = 0 ) { 

	if( $catID ){
		$facultyArgs = array( 
			'posts_per_page'	=> -1,
			'tax_query' 		=> array(
				array(
					'taxonomy' 	=> 'people_cat',
					'field' 	=> 'slug',
					'terms' 	=> $catID,
					//Only shows current Cat, doesn't show people in its sub cats
					'include_children' => FALSE, 
				)
			),
			'orderby' 	=> 'meta_value',
      		'meta_key' 	=> 'last_name',
			'order' 	=> 'ASC',
		);
	} else {
		$facultyArgs = array( 
			'post_type' 	=> 'people',
			'posts_per_page'=> -1,
			'orderby' 		=> 'meta_value',
      		'meta_key' 		=> 'last_name',
			'order' 		=> 'ASC',
		);
	}

	$my_query = new WP_Query($facultyArgs);

	echo '<div id="people_list">';

	if($my_query->have_posts()) : while ($my_query->have_posts()) : $my_query->the_post();	
		// Grab the Post ID for the Custom Fields Function	
		$thisID = get_the_ID();

		// Custom Post Type image must be set to "Image ID"
		$personPhoto = wp_get_attachment_image_src(get_field('headshot'), "people-custom-image-thumb");
		// If there isn't a "people-custom-image" version graph the 'Small' size
		if(empty($personPhoto))
			$personPhoto = wp_get_attachment_image_src(get_field('headshot'), 'small' );		

		$personLink =  get_permalink();
		$personFirstName = get_field('first_name');
		$personLastName  = get_field('last_name');

		$person = array(					
			'position'	=> get_field('position'),
			'location'  => get_field('location'),
			'phone'		=> get_field('phone'),
			'email'		=> antispambot(get_field('email')),				
		);

		if($person['email'] === "N/A")
			$person['email'] = "";
				
		if( !$personPhoto[0]){
			$personPhoto[0] = get_template_directory_uri().'/images/NoImageAvailable.jpg';
		}

		// display person if person is in category, or category is 'all'
		echo <<<PEOPLE
		<div class="personContainer">
			<div class="figureContainer"><div class="figure"><a href="{$personLink}"><img src="{$personPhoto[0]}" alt="{$personLastName}"/></a></div></div>
			<ul class="personBasics">
				<h2><a href="{$personLink}" class="personLink">{$personFirstName} {$personLastName}</a></h2>
PEOPLE;
			echo (!empty($person['position']) ? "<li class='person_position'><i class='icon-user'></i>".$person['position']."</li>" : "");
			echo (!empty($person['location']) ? "<li class='person_location'><i class='icon-map-marker'></i>".$person['location']."</li>" : "");
			echo (!empty($person['phone']) ? "<li class='person_phone'><i class='icon-mobile-phone'></i>".$person['phone']."</li>" : "");
			if(!empty($person['email']) && $person['email'] != "N/A")
				echo "<li class='person_email'><i class='icon-envelope-alt'></i><a href='mailto:".$person['email']."'>".$person['email']."</a></li>";
			
			echo '</ul>
			<div style="clear:both; height:1px; margin-bottom:-1px;">&nbsp;</div>';
		
		echo '</div>';
	endwhile; endif; wp_reset_query();
	echo '</div>';
}
// **************************
// * Shortcode for Show People
// **************************
function show_people_short($atts){

  extract( shortcode_atts( array(
    'classification' => '',    
  ), $atts ) );

  return show_people($classification);
}
add_shortcode('show_people', 'show_people');

// ******************************
// * Show Office Hours Functions
// ******************************
function show_office_hours( $is_sidebar=true ) {
  if( is_home() ){
    $is_sidebar = false;
  }
  $officeHoursArgs = array( 
    'post_type' 	=> 'people',
    'people_cat' 	=> 0,
    'posts_per_page'=> -1,
  );
  $my_query = new WP_Query( $officeHoursArgs );

  // Custom Options
  $title = "Office Hours";
  $subtitle = "Faculty with office hours today (<strong>".date("l")."</strong>):";
  $today = date( "w" ); // Don't change
  $no_office_hrs = "Sorry, we couldn't find any faculty with office hours today.";

  if( $is_sidebar ){
    // echo '<h3>'.$title.'</h3>'; // Shown in widget; not necessary
    echo '<p class="center">'.$subtitle.'</p>';
    echo '<ul class="xoxo">';
  } else {
    echo '<div class="officeHours">';
    // echo '<h1>'.$title.'</h1>'; // Shown in widget; not necessary
    echo '<h3>'.$subtitle.'</h3>';
  }

  if($my_query->have_posts()) : while ($my_query->have_posts()) : $my_query->the_post();     
    // Grab the Post ID for the Custom Fields Function      
    $thisID = get_the_ID();

    // Custom Post Type image must be set to "Image ID"
    $personPhoto = wp_get_attachment_image_src(get_field('headshot'), "people-custom-image");
    // If there isn't a "people-custom-image" version graph the 'Small' size
    if(empty($personPhoto))
      $personPhoto = wp_get_attachment_image_src(get_field('headshot'), 'small' );
    // If there isn't an image
    if( !$personPhoto[0]){
      $personPhoto[0] = get_template_directory_uri().'/images/NoImageAvailable.jpg';
    }

    $person = array(
      'id' => $thisID,
      'first_name'  => get_field('first_name'),
      'last_name'   => get_field('last_name'),
      'photo'       => $personPhoto[0],
      'phone'       => get_field('phone'),
      'email'       => antispambot(get_field('email')),
      'location'    => get_field('location'),
      'position'    => get_field('position'),
      'biography'   => get_field('biography'),
      'research_ex' => excerpt(get_field('research_areas'), 140),
      'cv'          => get_field('curriculum_vitae'),
      'link'        => get_permalink(),

      // Office Hours
      'office_hours_mon' => parse_hrs(get_field('office_hours_mon')),
      'office_hours_tue' => parse_hrs(get_field('office_hours_tue')),
      'office_hours_wed' => parse_hrs(get_field('office_hours_wed')),
      'office_hours_thu' => parse_hrs(get_field('office_hours_thu')),
      'office_hours_fri' => parse_hrs(get_field('office_hours_fri')),

      'office_hours_private' => get_field('office_hours_private'),
    );

    if( $person['office_hours_private'] === true || $person['office_hours_private'] === 'yes') continue;

    switch ($today){
      case 1: // Monday
        if($person['office_hours_mon']) echo_hrs( $person, "mon", $is_sidebar );
        break;
      case 2: // Tuesday  
        if($person['office_hours_tue']) echo_hrs( $person, "tue", $is_sidebar );
        break;
      case 3: // Wednesday
        if($person['office_hours_wed']) echo_hrs( $person, "wed", $is_sidebar );
        break;
      case 4: // Thursday
        if($person['office_hours_thu']) echo_hrs( $person, "thu", $is_sidebar );
        break;
      case 5: // Friday
        if($person['office_hours_fri']) echo_hrs( $person, "fri", $is_sidebar );
        break;
    }

  endwhile; endif; wp_reset_query();

  if( $is_sidebar ){
    echo '</ul>';
  } else {
    echo '</div>';
  }
}
add_action('office_hours', 'show_office_hours');

function parse_hrs( $hours ) {
  $hrs_array = preg_split("/[-,]/", $hours);
  //echo "<pre>"; print_r($hrs_array); echo "</pre>";
  foreach( $hrs_array as &$value ){
    $value = strtotime( $value );
    $value = date( "g:i A", $value );
  }
  unset( $value );


  // There must be an even number of elements in array
  if( count($hrs_array) % 2 == 0 ){
    return $hrs_array;
  } elseif (preg_match('/private/', strtolower($hours)) ){
    return "private";
  }else {
    return false;
  }
}

function echo_hrs( $person, $day, $is_sidebar ) {
  $office_hours_today = 'office_hours_'.$day;
  $parity = true;
  $connector = "&nbsp;to&nbsp;";
  $separator = "</li><li>";
  
  if( !$is_sidebar ){
    echo("<div>");
    echo("<figure><a href='".$person['link']."'><img src=".$person['photo']." /></a></figure>");
    echo('<ul class="person_office_hrs xoxo">');
    echo("<h4><a href=".$person['link'].">".$person['last_name'].", ".$person['first_name']."</a></h4>");

    echo('<li>');
    foreach( $person[$office_hours_today] as $hour ){
      $separator = ($hour == end($person[$office_hours_today]) ? "" : $separator);

      echo('<strong>'.$hour.'</strong>');
      echo( $parity ? $connector : $separator );
      $parity = !$parity;
    }
    echo('</li>');

    person_toolbar( $person );
    echo "</ul>";
    echo "</div>";
  } else {
    echo '<li>';
    echo("<a href=".$person['link'].">".$person['last_name'].", ".$person['first_name']."</a>");
    echo '</li>';
    echo '<ul class="officeHours"><li>';
    foreach( $person[$office_hours_today] as $hour ){
      $separator = ($hour == end($person[$office_hours_today]) ? "" : $separator);

      echo('<strong>'.$hour.'</strong>');
      echo( $parity ? $connector : $separator );
      $parity = !$parity;
    }
    echo '</li></ul>';
  }
}

function get_hrs( $person ){
  $parity = true;
  $absent_msg = 'Not Available';
  $connector = "&nbsp;to&nbsp;";
  $separator = "</li><li>";
  $emptyHours = "";
  $days = array('mon'=>'Monday', 'tue'=>'Tuesday', 'wed'=>'Wednesday', 'thu'=>'Thursday', 'fri'=>'Friday',);
  $hours = '<ul>';

  foreach( $days as $day => $dayTitle ){
    $office_hours_today = 'p_office_hours_'.$day;
    
    $hours .= '<li><h2>' . $dayTitle . '</h2>';
    $hours .= '<ul><li>';

    if( is_array($person[$office_hours_today]) ){
      foreach( $person[$office_hours_today] as $hour ){

        $hours .= '<strong>'.$hour.'</strong>';
        $hours .=  $parity ? $connector : $separator ;
        $parity = !$parity;
      }
      $hours = substr( $hours, 0, -9 ); // Remove the extra </li><li>
      $emptyHours = "Have Office Hours";
    } else {
      $hours .= '<em>' . $absent_msg . '</em>';
    }
    
    $hours .= '</li></ul></li>';
  }

  $hours .= '</ul>';

  if(empty($emptyHours))
    return "";
  else
    return $hours;
}

function person_toolbar( $person ){
  $msg = array(
    'email' => "Send Email",
    'link' => "Visit Page",    
  );
  echo '<ul class="person_toolbar">';
  echo '<li><a href="mailto:'.$person['email'].'">'.$msg['email'].'</a></li>';
  echo '<li><a href="'.$person['link'].'">'.$msg['link'].'</a></li>';
  echo '</ul>';
}


// ****************************
// * Default theme setup info
// ****************************
function starkers_setup() {

	// Post Format support. You can also use the legacy "gallery" or "asides" (note the plural) categories.
	add_theme_support( 'post-formats', array( 'aside', 'gallery' ) );

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	add_theme_support( "title-tag" );

	// Set custom Image Size for People Images
	if ( function_exists( 'add_image_size' ) ) { 
		add_image_size( 'people-custom-image', 250, 9999 );	
    	add_image_size( 'people-custom-image-thumb', 250, 250, array('center','top') ); 
		add_filter( 'image_size_names_choose', 'custom_image_sizes_choose' );
	}
	
	function custom_image_sizes_choose( $sizes ) {
		$custom_sizes = array(
			'people-custom-image' 		=> 'Person Image',
      		'people-custom-image-thumb' => 'Person Image Thumbnail'
		);
		return array_merge( $sizes, $custom_sizes );
	}

	// Set the image link to and align default to none
	update_option('image_default_link_type','none');
	update_option('image_default_align', 'none' );


	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'biology-department', get_template_directory() . '/languages' );

	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

  /*
   * Switch default core markup for search form, comment form, and comments to output valid HTML5.
   */
  add_theme_support( 'html5', array( 'gallery'  ) );
}
endif;

if ( ! function_exists( 'starkers_menu' ) ):
/**
 * Set our wp_nav_menu() fallback, starkers_menu().
 *
 * @since Starkers HTML5 3.0
 */
	function starkers_menu() {
		echo '<nav id="main_menu">';

		echo '<ul id="startNav"><li><a href="'.home_url().'">Home</a></li>';
		wp_list_pages('title_li=');
		echo '</ul></nav>';
	}
endif;

/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * @since Starkers HTML5 3.2
 */
add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * @since Starkers HTML5 3.0
 * @deprecated in Starkers HTML5 3.2 for WordPress 3.1
 *
 * @return string The gallery style filter, with the styles themselves removed.
 */
function starkers_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
// Backwards compatibility with WordPress 3.0.
if ( version_compare( $GLOBALS['wp_version'], '3.1', '<' ) )
	add_filter( 'gallery_style', 'starkers_remove_gallery_css' );

if ( ! function_exists( 'starkers_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * @since Starkers HTML5 3.0
 */
function starkers_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<article <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
			<?php echo get_avatar( $comment, 40 ); ?>
			<?php printf( __( '%s says:', 'biology-department' ), sprintf( '%s', get_comment_author_link() ) ); ?>
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<?php _e( 'Your comment is awaiting moderation.', 'biology-department' ); ?>
			<br />
		<?php endif; ?>

		<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php
				/* translators: 1: date, 2: time */
				printf( __( '%1$s at %2$s', 'biology-department' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'biology-department' ), ' ' );
			?>

		<?php comment_text(); ?>

		<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<article <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
		<p><?php _e( 'Pingback:', 'biology-department' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'biology-department'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;

/**
 * Closes comments and pingbacks with </article> instead of </li>.
 *
 * @since Starkers HTML5 3.0
 */
function starkers_comment_close() {
	echo '</article>';
}

/**
 * Removes the default styles that are packaged with the Recent Comments widget.
 *
 * @updated Starkers HTML5 3.2
 */
function starkers_remove_recent_comments_style() {
  add_filter( 'show_recent_comments_widget_style', '__return_false' );
}
add_action( 'widgets_init', 'starkers_remove_recent_comments_style' );

if ( ! function_exists( 'starkers_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post—date/time and author.
 *
 * @since Starkers HTML5 3.0
 */
function starkers_posted_on() {
  printf( __( 'Posted on %2$s by %3$s', 'biology-department' ),
    'meta-prep meta-prep-author',
    sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><time datetime="%3$s" pubdate>%4$s</time></a>',
      get_permalink(),
      esc_attr( get_the_time() ),
      get_the_date('Y-m-d'),
      get_the_date()
    ),
    sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
      get_author_posts_url( get_the_author_meta( 'ID' ) ),
      sprintf( esc_attr__( 'View all posts by %s', 'biology-department' ), get_the_author() ),
      get_the_author()
    )
  );
}
endif;

if ( ! function_exists( 'starkers_posted_in' ) ) :
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 * @since Starkers HTML5 3.0
 */
function starkers_posted_in() {
  // Retrieves tag list of current post, separated by commas.
  $tag_list = get_the_tag_list( '', ', ' );
  if ( $tag_list ) {
    $posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'biology-department' );
  } elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
    $posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'biology-department' );
  } else {
    $posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'biology-department' );
  }
  // Prints the string, replacing the placeholders.
  printf(
    $posted_in,
    get_the_category_list( ', ' ),
    $tag_list,
    get_permalink(),
    the_title_attribute( 'echo=0' )
  );
} endif;

/**
 * Adjusts the comment_form() input types for HTML5.
 *
 * @since Starkers HTML5 3.0
 */
function starkers_fields($fields) {
$commenter = wp_get_current_commenter();
$req = get_option( 'require_name_email' );
$aria_req = ( $req ? " aria-required='true'" : '' );
$fields =  array(
	'author' => '<p><label for="author">' . __( 'Name' ) . '</label> ' . ( $req ? '*' : '' ) .
	'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
	'email'  => '<p><label for="email">' . __( 'Email' ) . '</label> ' . ( $req ? '*' : '' ) .
	'<input id="email" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',
	'url'    => '<p><label for="url">' . __( 'Website' ) . '</label>' .
	'<input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>',
);
return $fields;
}
add_filter('comment_form_default_fields','starkers_fields');


// ***************************
// * Dashboard Cusomtizations 
// ***************************

// Change Log-In Screen Logo
function my_custom_login_logo() { ?>
    <style type="text/css">
        body.login div#login h1 a { 
        	background-image: url('<?php echo get_template_directory_uri(); ?>/images/logo.png');
        	width: 283px; 
        	height: 56px; 
        	background-size: inherit; }
    </style>
<?php }
add_action('login_enqueue_scripts', 'my_custom_login_logo');

// Change Log-In Screen Logo URL
function put_my_url(){
	// putting my URL in place of the WordPress one
	return ('http://www.cos.ucf.edu/it'); 
}
add_filter('login_headerurl', 'put_my_url');

// Change Log-In Screen Logo Hover State
function put_my_title(){
    // Change the title from "Powered by WordPress"
    return ('College of Sciences Information Technology');     
}
add_filter('login_headertitle', 'put_my_title');

// Add error/info message box to top of the Dashboard
function showMessage($message, $errormsg){
    if ($errormsg) {
        echo '<div id="message" class="error">';
    }
    else {
        echo '<div id="message" class="updated fade">';
    }
    echo "<p><strong>$message</strong></p></div>";
} 

// Write message to show in the error/info box
// Set boolean to True for red error box, False for yellow info box
function showAdminMessages(){
	showMessage("Please do not update any WordPress software.  If prompted for an update, please contact COSIT at <a href='mailto:cosit@ucf.edu?subject=WordPress Site Update For: ".get_bloginfo('name')."&body=This site (".site_url().") is due for a WordPress update, please forward on to COS Web.'>cosit@ucf.edu</a>", false);
}
add_action('admin_notices', 'showAdminMessages');

// Customize WordPress Dashboard Footer
function remove_footer_admin () {
	echo "&copy; ".date('Y')." - UCF College of Sciences Information Technology";
}
add_filter('admin_footer_text', 'remove_footer_admin');

// Adding a custom widget in WordPress Dashboard
function wpc_dashboard_widget_function() {
	// Entering the text between the quotes
	echo "<ul>
	<li><strong>Release Date:</strong> July 2012</li>
	<li><strong>Updated Date:</strong> April 2014</li>
	<li><strong>Author:</strong> College of Sciences Information Technology</li>
	<li><strong>Support E-Mail:</strong> <a href='mailto:cosit@ucf.edu'>cosit@ucf.edu</a></li>
	<li><strong>Support Phone:</strong> 407-823-2793</li>
	</ul>";
}
function wpc_add_dashboard_widgets() {
	wp_add_dashboard_widget('wp_dashboard_widget', 'Support Contact Information', 'wpc_dashboard_widget_function');
}
add_action('wp_dashboard_setup', 'wpc_add_dashboard_widgets' );

//Hiding Default Dashboard Widgets
add_action('wp_dashboard_setup', 'wpc_dashboard_widgets');
function wpc_dashboard_widgets() {
	global $wp_meta_boxes;
	//Main Column Widgets
		// Today widget
		//unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
		// Last comments
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
		// Incoming links
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
		// Plugins
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
		//Side Column Widgets
		//Quick Press
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
		//Recent Drafts
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
		//WordPress Blog
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
		//Other WordPress News
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
}


// ****************************************
// * Custom Pagination from Kriesi.at
// * http://www.kriesi.at/archives/how-to-build-a-wordpress-post-pagination-without-plugin
// ****************************************
function kriesi_pagination($pages = '', $range = 2){  

     $showitems = ($range * 2)+1;  

     global $paged;
     if(empty($paged)) $paged = 1;

     if($pages == ''){
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages){
             $pages = 1;
         }
     }   

     if(1 != $pages){

         echo "<div class='pagination'>";
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a>";

         for ($i=1; $i <= $pages; $i++){
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )){
                 echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
             }
         }

         if ($paged < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a>";  
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
         echo "</div>\n";
     }
}


// ***********************************************
// * Metronet Plugin Reorder
// * Add support for using this plugin with CPTS
// ***********************************************
add_filter( 'metronet_reorder_post_types', 'slug_set_reorder' );
function slug_set_reorder( $post_types ) {
    $post_types = array( 'mainlink');
    return $post_types;
}


// *****************************************************
// **************************************************
// * Widgets and Widget Related Functions
// **************************************************
// *****************************************************


// *************************************
// * Register Widget Areas.
// ************************************/
function starkers_widgets_init() {
	// ***** Home Page Widget Areas ***** //
	register_sidebar( array(
		'name' 			=> __( 'Front Page Slider Right', 'biology-department' ),
		'id' 			=> 'front-slider-right-widget-area',
		'description' 	=> __( 'The right side of the slider area on the front page', 'biology-department' ),
		'before_widget' => '',
		'after_widget' 	=> '',
		'before_title' 	=> '<h3>',
		'after_title' 	=> '</h3>',
	) );

	register_sidebar( array(
		'name' 			=> __( 'Front Page Left Column', 'biology-department' ),
		'id' 			=> 'front-left-widget-area',
		'description' 	=> __( 'The left side of the home page', 'biology-department' ),
		'before_widget' => '<div id="left_content">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h2>',
		'after_title' 	=> '</h2>',
	) );

	register_sidebar( array(
		'name' 			=> __( 'Front Page Right Column', 'biology-department' ),
		'id' 			=> 'front-right-widget-area',
		'description' 	=> __( 'The right side of the home page', 'biology-department' ),
		'before_widget' => '<div id="right_content">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h2>',
		'after_title' 	=> '</h2>',
	) );

	register_sidebar( array(
	    'name' 			=> __( 'Sidebar', 'biology-department' ),
	    'id' 			=> 'primary-widget-area',
	    'description' 	=> __( 'The primary sidebar widget area', 'biology-department' ),
	    'before_widget' => '<li class="sidebar_widget">',
	    'after_widget' 	=> '</li>',
	    'before_title' 	=> '<h2>',
	    'after_title' 	=> '</h2>',
  ) );

	register_sidebar( array(
		'name' 			=> __( 'Sidebar - Graduate Template', 'biology-department' ),
		'id' 			=> 'graduate-sidebar-widget-area',
		'description' 	=> __( 'The sidebar widget area for the Graduate template', 'biology-department' ),
		'before_widget' => '<aside><ul><li class="sidebar_widget">',
		'after_widget' 	=> '</li></ul></aside>',
		'before_title' 	=> '<h2>',
		'after_title' 	=> '</h2>',
	) );  

	register_sidebar( array(
		'name' 			=> __( 'Sidebar - Undergraduate Template', 'biology-department' ),
		'id' 			=> 'undergraduate-sidebar-widget-area',
		'description' 	=> __( 'The sidebar widget area for the Undergraduate template', 'biology-department' ),
		'before_widget' => '<aside><ul><li class="sidebar_widget">',
		'after_widget' 	=> '</li></ul></aside>',
		'before_title' 	=> '<h2>',
		'after_title' 	=> '</h2>',
	) );  


	// ***** Sidebar Widget Area ***** //
	register_sidebar( array(
		'name' 			=> __( 'Left Footer Widget Area', 'biology-department' ),
		'id' 			=> 'first-footer-widget-area',
		'description' 	=> __( 'The first footer widget area', 'biology-department' ),
		'before_widget' => '',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="title">',
		'after_title' 	=> '<i class="icon-double-angle-down"></i></h3><div class="footerWidgetDiv">',
	) );

	register_sidebar( array(
		'name' 			=> __( 'Center Footer Widget Area', 'biology-department' ),
		'id' 			=> 'second-footer-widget-area',
		'description' 	=> __( 'The second footer widget area', 'biology-department' ),
		'before_widget' => '',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="title">',
		'after_title' 	=> '<i class="icon-double-angle-down"></i></h3><div class="footerWidgetDiv">',
	) );
	
	register_sidebar( array(
		'name' 			=> __( 'Right Footer Widget Area', 'biology-department' ),
		'id' 			=> 'third-footer-widget-area',
		'description' 	=> __( 'The third footer widget area', 'biology-department' ),
		'before_widget' => '',
		'after_widget' 	=> '</div>',
		'before_title' 	=> '<h3 class="title">',
		'after_title' 	=> '<i class="icon-double-angle-down"></i></h3><div class="footerWidgetDiv">',
	) );
}
/** Register sidebars by running starkers_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'starkers_widgets_init' );


// *******************************************************
// * Function for returning max cache time for fetch_feed
// *******************************************************
function cos_increase_cache(){
  // Change the feed cache recreation period to 2 hours
  return 3600; 
}
// ********************* //


// *********************
// * COS Events Widget
// *********************
class cos_events_widget extends WP_Widget {
  public function __construct() {
    // Widget settings
    $widget_ops = array(
      'classname'    => 'UCF Events Feed', 
      'description'  => 'Shows event feed of specific events.ucf.edu calendar.',
    );

    // Widget control settings
    $control_ops = array(
      'id_base' => 'cos_events_widget',
    );

    parent::__construct(
      'cos_events_widget', 
      'UCF Events Feed',
      $widget_ops,
      $control_ops
    );
  }

  function widget( $args, $instance ){
    extract( $args );
    $title = apply_filters('widget_title', $instance['title']); // widget title
    $id = $instance['id'];
    $backup_id  = $instance['backup_id'];
    $num_events = $instance['num_events'];

    // Before Widget
    echo $before_widget;

    // Title of Widget
    if( $title ){
      echo '<div class="events">' . $before_title . $title . $after_title;
    }

    // Widget Output
    do_action( 'show_events', $id, $backup_id, $num_events, false );

    // After Widget
    echo '</div>' . $after_widget;
  }

  function update($new_instance, $old_instance) {
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['id']    = strip_tags($new_instance['id']);
      $instance['backup_id']  = strip_tags($new_instance['backup_id']);
      $instance['num_events'] = strip_tags($new_instance['num_events']);
      return $instance;
  }

    // Widget Control Panel //
  function form( $instance ) {
    $defaults = array( 'title' => 'Events', 'id' => 719, 'backup_id' => 1, 'num_events' => 5 );
    $instance = wp_parse_args( (array) $instance, $defaults ); ?>

    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>'" type="text" value="<?php echo $instance['title']; ?>" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('id'); ?>">Calendar ID:</label>
      <input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>'" type="text" value="<?php echo $instance['id']; ?>" />
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('backup_id'); ?>">Backup Calendar ID: <em>(When main calendar is empty)</em></label>
        <input class="widefat" id="<?php echo $this->get_field_id('backup_id'); ?>" name="<?php echo $this->get_field_name('backup_id'); ?>'" type="text" value="<?php echo $instance['backup_id']; ?>" />
    <p>
      <label for="<?php echo $this->get_field_id('num_events'); ?>">Number of events to display:</label>
      <input class="widefat" id="<?php echo $this->get_field_id('num_events'); ?>" name="<?php echo $this->get_field_name('num_events'); ?>'" type="text" value="<?php echo $instance['num_events']; ?>" />
    </p>
    <?php 
  }

}
add_action('widgets_init', create_function('', 'return register_widget("cos_events_widget");'));


// ********************************************
// * Show events via Widget on Home Page
// ********************************************
function show_events( $calID = 719, $backupID = 1, $numEvents = 10, $isBackup = false ) {

	// Defaults if fields are left empty or register null
	if( $calID === "" )     $calID = 719;
	if( $isBackup === false ){
		if( $backupID === "" )  $backupID = 719;
	}

	/* Call custom "Simplepie" class that ew've created for the unique "<ucfevent:___>" tags found in the events.ucf.edu feed.  Make sure events.ucf.edu is added to the WP Allowed Hosts whitelist */
	include_once( get_template_directory() . '/simplepie_ucfevent.inc');

	$feed = 'http://events.ucf.edu/?calendar_id='.$calID.'&upcoming=upcoming&format=rss';
	$maxitems = 0;

	// Set Feed cache to two hours
	add_filter( 'wp_feed_cache_transient_lifetime' , 'cos_increase_cache' );
	$rss = fetch_feed($feed);
	remove_filter( 'wp_feed_cache_transient_lifetime' , 'cos_increase_cache' );

	// Check for errors before setting custom class items
	if(!is_wp_error($rss)){

	    // Set our custom item class that we imported above
	    $rss->set_item_class('SimplePie_Item_UCFEvent');
	    $rss->enable_order_by_date(false);   
	    $maxitems = $rss->get_item_quantity($numEvents); 
	    // Trim out possible extra text from Calendar title
	    $currentDescription = str_replace(" - Today's Events", "", $rss->get_description());

	    // If the feed is empty
	    if( $maxitems === 0 ){

	        $calID = $backupID;   
	        // If the main feed is empty, run again using the backup
	        if( $isBackup === false ){
	         	echo "<p class='no_events'>There are no ".$currentDescription." events at this time. "; 
	          	show_events( $calID, "", $numEvents, true ); 
	        } 
	        // If this is the backup feed & it's empty, check to see if the backup is the UCF Calendar.  If not, run again with the UCF calendar, and set the backup to the UCF calendar.
	        elseif ($isBackup === true ){
	          	if( $backupID !== "1" )
	            	show_events( "1", "1", $numEvents, true ); 
	          	// End the recursive looping
	          	else
	            	echo " Please check back later.</p>";
	        }

	    } else {
	      	// If there are items and this is the backup feed
	      	if( $isBackup === true )
	        	echo "Check out the <a href='http://events.ucf.edu/?calendar_id=".$calID."&upcoming=upcoming' target='_blank'>".$currentDescription."</a> calendar below for upcoming events.</p>";
	       
			$items = $rss->get_items(0, $maxitems);
		    // View Full Calendar link
		    echo('<a href="http://events.ucf.edu/?calendar_id='.$calID.'&upcoming=upcoming" target="_blank">View Full Calendar</a>');
		    foreach ($items as $item) : ?>
		    	<article>
	        	<span class="eventDate"><?php echo substr($item->get_startdate(),5,11); ?></span>
	          	<ul class="eventInfo">
	            	<li class="eventTitle"><a href="<?php  echo $item->get_permalink(); ?>" title="<?php echo $item->get_title(); ?>" target="_blank" <?php echo ( substr($item->get_title(),0,40) == $item->get_title() ? '>'.$item->get_title() :' class="expandEventTitle">'.substr($item->get_title(),0,40).'...' ); ?></a></li>
		            <li class="eventTime"><?php 
		              if($item->get_starttime() === $item->get_endtime())
		                echo "ALL DAY";
		              else
		                echo $item->get_starttime()." - ".$item->get_endtime();
		            ?></li>         
		            <li class="eventLocation">
		            <?php 
		            	$location = $item->get_location(); 
		              	if($location['mapurl'] !== '')
		                	echo "<a href='".$location['mapurl']."'>".$location['location']."</a>";
		              	else
		                	echo $location['location'];
		            ?></li>
	          	</ul>
	        	</article> 
	        <?php 
	      	endforeach;   
	    }
	}
  	else {
    	echo "<hr style='width:90%;'><p class='no_events'>Unable to retrieve events from the calendar.  Please try again later.</p>";    
  	}
}
add_action('show_events', 'show_events', 10, 4);


// ****************************
// * COS News Widget
// ****************************
class cos_news_widget extends WP_Widget {
	public function __construct() {
		// Widget settings
		$widget_ops = array(
			'classname' => 'COS News Feed', 
			'description' => 'Shows news feed of specific COS Department',
		);

		// Widget control settings
		$control_ops = array(
			'id_base' => 'cos_news_widget',
		);

		parent::__construct(
			'cos_news_widget', 
			'COS News Feed',
			$widget_ops,
			$control_ops
		);
	}

	function widget( $args, $instance ){
		extract( $args );
		$title = apply_filters('widget_title', $instance['title']); // widget title

		// Before Widget
		echo $before_widget;

		// Title of Widget
		if( $title ){
			echo $before_title . '<a href="https://sciences.ucf.edu/news/category/'.$instance['news_cat'].'">'. $title .'</a>';
			echo "". $after_title."<a class='bio-btn' href='https://news.cos.ucf.edu/category/".$instance['news_cat']."'>All News)</a>";
		}

		// Widget Output
		do_action( 'show_news', $instance['news_cat'], $instance['news_items'] );

		// After Widget
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['news_cat'] = strip_tags($new_instance['news_cat']);
      
      // Store the Widget variable in the Options for global use
      update_option('COS_news_cat', $instance['news_cat']);

			$instance['news_items'] = strip_tags($new_instance['news_items']);
			return $instance;
	}

		// Widget Control Panel //
	function form( $instance ) {
 		$defaults = array( 
 			'title' => 'News',
 			'news_cat' => '',
 			'news_items' => 5,
 		);
 		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

 		<p>
 			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
 			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>'" type="text" value="<?php echo $instance['title']; ?>" />
 		</p>

 		<p>
 			<label for="<?php echo $this->get_field_id('news_cat'); ?>">News Category:</label>
            <select name="<?php echo $this->get_field_name('news_cat'); ?>'" id="<?php echo $this->get_field_id('news_cat'); ?>" value="<?php echo $instance['title']; ?>" >
            	<option value="anthropology" <?php selected( $instance['news_cat'], 'anthropology' );?>>Anthropology</option>
            	<option value="biology-departments" <?php selected( $instance['news_cat'], 'biology-departments' );?>>Biology</option>
            	<option value="chemistry-departments" <?php selected( $instance['news_cat'], 'chemistry-departments' );?>>Chemistry</option>
            	<option value="communication" <?php selected( $instance['news_cat'], 'communication' );?>>Communication, Nicholson School of</option>
            	<option value="forensic-science" <?php selected( $instance['news_cat'], 'forensic-science' );?>>Forensic Science, National Center of</option>
            	<option value="global-perspectives" <?php selected( $instance['news_cat'], 'global-perspectives' );?>>Global Perspectives</option>
            	<option value="lou-frey-institute" <?php selected( $instance['news_cat'], 'lou-frey-institute' );?>>Lou Frey Institute</option>
            	<option value="mathematics" <?php selected( $instance['news_cat'], 'mathematics' );?>>Mathematics</option>
            	<option value="physics-departments" <?php selected( $instance['news_cat'], 'physics-departments' );?>>Physics</option>
            	<option value="political-science-departments" <?php selected( $instance['news_cat'], 'political-science-departments' );?>>Political Science</option>
            	<option value="psychology-departments" <?php selected( $instance['news_cat'], 'psychology-departments' );?>>Psychology</option>
            	<option value="sociology" <?php selected( $instance['news_cat'], 'sociology' );?>>Sociology</option>
            	<option value="statistics" <?php selected( $instance['news_cat'], 'statistics' );?>>Statistics</option>
            </select>
 		</p>

 		<p>
 			<label for="<?php echo $this->get_field_id('news_items'); ?>">Articles to show:</label>
 			<select name="<?php echo $this->get_field_name('news_items'); ?>'" id="<?php echo $this->get_field_id('news_items'); ?>" value="<?php echo $instance['news_items']; ?>" >
 				<option value="1" <?php selected( $instance['news_items'], 1 );?>>1</option>
 				<option value="2" <?php selected( $instance['news_items'], 2 );?>>2</option>
 				<option value="3" <?php selected( $instance['news_items'], 3 );?>>3</option>
 				<option value="4" <?php selected( $instance['news_items'], 4 );?>>4</option>
 				<option value="5" <?php selected( $instance['news_items'], 5 );?>>5</option>
 				<option value="6" <?php selected( $instance['news_items'], 6 );?>>6</option>
 				<option value="7" <?php selected( $instance['news_items'], 7 );?>>7</option>
 				<option value="8" <?php selected( $instance['news_items'], 8 );?>>8</option>
 				<option value="9" <?php selected( $instance['news_items'], 9 );?>>9</option>
 				<option value="10" <?php selected( $instance['news_items'], 10 );?>>10</option>
 			</select>
 		</p>
 		<?php 
 	}

}
add_action('widgets_init', create_function('', 'return register_widget("cos_news_widget");'));


// *********************************************
// * Show News Items on Home Page (via Widget)
// *********************************************
function show_news( $cat, $items_to_show = 3 ) { ?>
	<div class="news"> 		
		<?php 				
		$feed = "https://sciences.ucf.edu/news/category/".$cat."/feed";			
		
		// Set Feed cache to two hours
    add_filter( 'wp_feed_cache_transient_lifetime' , 'cos_increase_cache' );
    $rss = fetch_feed($feed);
    remove_filter( 'wp_feed_cache_transient_lifetime' , 'cos_increase_cache' );

		if(!is_wp_error($rss)){

			$maxitems = $rss->get_item_quantity($items_to_show);
			$items = $rss->get_items(0, $maxitems);

			foreach ($items as $item) : 
						
			?>
			<article>				
				<h3><a href="<?php echo $item->get_permalink(); ?>"><?php echo $item->get_title(); ?></a></h3>			
				<?php 
        // Restrict the description to the content before the […] 
        echo substr($item->get_description(), 0, strpos($item->get_description(), "[&#8230;]")+9 );
        ?>

				<aside>Published:<br/><?php echo $item->get_date(); ?></aside>
			</article>
			<?php endforeach; 

		}else{
			echo '<span class="error">Unable to retrieve feed. Please try again later.</span>';
		}
		?>
	</div> <?php
}
add_action( 'show_news', 'show_news', 10, 2 );


// **************************************
// * Shortcode for adding news to pages
// **************************************
function show_news_full() { 
	include_once( ABSPATH . WPINC . '/feed.php' );
	?>
	<div class="news">     	
	<?php 

	$feed = 'https://sciences.ucf.edu/news/?category_name='.get_option('COS_news_cat').'&feed=rss2'; // specify feed url	

	// Set Feed cache to two hours
  add_filter( 'wp_feed_cache_transient_lifetime' , 'cos_increase_cache' );
  $rss = fetch_feed($feed);
  remove_filter( 'wp_feed_cache_transient_lifetime' , 'cos_increase_cache' );

	if(!is_wp_error($rss)){

		$maxitems = $rss->get_item_quantity(15);
		$items = $rss->get_items(0, $maxitems);

		foreach($items as $item) :
		
		?>	
	    <article>
			
			<h3><a href="<?php echo $item->get_permalink(); ?>"><?php echo $item->get_title(); ?></a></h3>
			
			<?php 
      // Restrict the description to the content before the […] 
      echo substr($item->get_description(), 0, strpos($item->get_description(), "[&#8230;]")+9 );
      ?>

			<aside>Published:<br/><?php echo $item->get_date(); ?></aside>
		</article>
	<?php endforeach; 		
	} 	?>

	<h3 class="clear"><a href="https://sciences.ucf.edu/news/?category_name=<?php echo get_option('COS_news_cat'); ?>">Click here for more <?php echo get_bloginfo('name'); ?> news</a></h3>
</div> <?php
}
add_shortcode('show_news_full', 'show_news_full');


// ******************************
// * COS Contact Widget
// ******************************
class cos_contact_widget extends WP_Widget {
	public function __construct() {
		// Widget settings
		$widget_ops = array(
			'classname' => 'COS Contact Info', 
			'description' => 'Displays contact information for current department',
		);

		// Widget control settings
		$control_ops = array(
			'id_base' => 'cos_contact_widget',
		);

		parent::__construct(
			'cos_contact_widget', 
			'COS Contact Info',
			$widget_ops,
			$control_ops
		);
	}

	function widget( $args, $instance ){
		extract( $args );
		$title = apply_filters('widget_title', $instance['title']); // widget title

 		$argsss = array( 
 			'title' => $instance['title'],
 			'dept' => $instance['dept'],
 			'address' => $instance['address'],
 			'email' => $instance['email'],
 			'phone' => $instance['phone'], 
 			'fax' => $instance['fax'],
 		);

		// Before Widget
		echo $before_widget;
		
		// Widget Output  -  SHOW CONTACT BOX
		do_action( 'show_contact_area', $argsss  );

		// After Widget
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['dept'] = strip_tags($new_instance['dept']);
			$instance['address'] = strip_tags($new_instance['address']);
			$instance['email'] = strip_tags($new_instance['email']);
			$instance['phone'] = strip_tags($new_instance['phone']);
			$instance['fax'] = strip_tags($new_instance['fax']);
			return $instance;
	}

		// Widget Control Panel //
	function form( $instance ) {
 		$defaults = array( 
 			'title' => 'Contact Us',
 			'dept' => '',
 			'address' => '',
 			'email' => '',
 			'phone' => '', 
 			'fax' => '',
 		);
 		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

 		<!-- Title -->
 		<p>
 			<label for="<?php echo $this->get_field_id('title'); ?>">Title: (<em>e.g. <strong>"Contact Us"</strong></em>)</label>
 			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>'" type="text" value="<?php echo $instance['title']; ?>" />
 		</p>

 		<!-- Department -->
 		<p>
 			<label for="<?php echo $this->get_field_id('dept'); ?>">Department:</label>
 			<textarea class="widefat" id="<?php echo $this->get_field_id('dept'); ?>" name="<?php echo $this->get_field_name('dept'); ?>'" rows="2"><?php echo $instance['dept']; ?></textarea>
 		</p>

 		<!-- Address -->
 		<p>
 			<label for="<?php echo $this->get_field_id('address'); ?>">Address:</label>
 			<textarea class="widefat" id="<?php echo $this->get_field_id('address'); ?>" name="<?php echo $this->get_field_name('address'); ?>'" rows="5"><?php echo $instance['address']; ?></textarea>
 		</p>

 		<!-- Email -->
 		<p>
 			<label for="<?php echo $this->get_field_id('email'); ?>">Email Address(es):</label>
 			<textarea class="widefat" id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>'" rows="2"><?php echo $instance['email']; ?></textarea>
 		</p>

 		<!-- Phone and Fax -->
 		<p>
 			<label for="<?php echo $this->get_field_id('phone'); ?>">Phone Number:</label>
 			<input class="widefat" id="<?php echo $this->get_field_id('phone'); ?>" name="<?php echo $this->get_field_name('phone'); ?>'" type="text" value="<?php echo $instance['phone']; ?>" />
 		</p>
  		<p>
 			<label for="<?php echo $this->get_field_id('fax'); ?>">Fax Number:</label>
 			<input class="widefat" id="<?php echo $this->get_field_id('fax'); ?>" name="<?php echo $this->get_field_name('fax'); ?>'" type="text" value="<?php echo $instance['fax']; ?>" />
 		</p>
 		<?php 
 	}
}
add_action('widgets_init', create_function('', 'return register_widget("cos_contact_widget");'));


// **********************
// * Office Hours Widget
// **********************
class cos_office_hours_widget extends WP_Widget {
  public function __construct() {
    // Widget settings
    $widget_ops = array(
      'classname' => 'COS Office Hours', 
      'description' => 'Shows faculty office hours based on day of week',
    );

    // Widget control settings
    $control_ops = array(
      'id_base' => 'cos_office_hours_widget',
    );

    parent::__construct(
      'cos_office_hours_widget', 
      'COS Office Hours',
      $widget_ops,
      $control_ops
    );
  }

  function widget( $args, $instance ){
    extract( $args );
    $title = apply_filters('widget_title', $instance['title']); // widget title

    // Before Widget
    echo $before_widget;

    // Title of Widget
    if( $title ){
      echo $before_title . $title . $after_title;
    }

    // Widget Output
    do_action( 'office_hours', true );

    // After Widget
    echo $after_widget;
  }

  function update($new_instance, $old_instance) {
      $instance['title'] = strip_tags($new_instance['title']);
      return $instance;
  }

    // Widget Control Panel //
  function form( $instance ) {
    $defaults = array( 'title' => 'Office Hours' );
    $instance = wp_parse_args( (array) $instance, $defaults ); ?>

    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>'" type="text" value="<?php echo $instance['title']; ?>" />
    </p>
    <?php 
  }
} add_action('widgets_init', create_function('', 'return register_widget("cos_office_hours_widget");'));

// ***********************************************
// * Show the Contact Info Area on the home page
// ***********************************************
function show_contact_area( $args ){

		// Parse multiple email addresses
		$emails_array = explode("\n", trim( $args['email'] ));
		$emails_string = '';

		foreach ($emails_array as &$email) {
			$email = antispambot(trim($email));
			$email = '<a href="mailto:'.$email.'">'.$email.'</a>';
		}

		$emails_array = implode("\n", $emails_array);

		$contact = array(
			'title' 	=> ''.$args['title'].'',
			'dept' 		=> '<i class="icon-map-marker"></i><p>'.$args['dept'].'</p>',
			'address'	=> '<i class="icon-envelope-alt"></i><p>'.$args['address'].'</p>',
			'email'		=> '<i class="icon-email">@</i><p>'.$emails_array.'</p>',
			'phone'		=> '<i class="icon-mobile-phone"></i><p>P: ' . $args['phone'].'<br/>'.'F: ' . $args['fax'].'</p>',		
		);

		// Break each category into list items
		foreach($contact as $key => &$value){
			if( $key != 'title' ){
				$value = '<li>' . str_replace("\n", "<br/>", $value) . '</li>';
			}
		};

		// Display the list items in this format:
		echo <<<CONTACT
			<div id="contact">
			<h2 class="title"><span class="contact_title">{$contact['title']} <i class="icon-double-angle-down contact-drop"></i></span><i class="icon-search"></i></h2>			
			<ul id="contact_list">				
				{$contact['dept']}
				{$contact['address']}
				{$contact['phone']}				
				{$contact['email']}
			</ul>
			</div>
CONTACT;

	return true;
}
add_action( 'show_contact_area', 'show_contact_area', 10, 1 );


// ************************************
// * COS Did You Know Widget
// ************************************
class cos_dyk_widget extends WP_Widget {
  public function __construct() {
    // Widget settings
    $widget_ops = array(
      'classname'   => 'COS "Did You Know?" Widget', 
      'description' => 'Displays "did you know?" posts from "DYK" category',
    );

    // Widget control settings
    $control_ops = array(
      'id_base' => 'cos_dyk_widget',
    );

    parent::__construct(
      'cos_dyk_widget', 
      'COS "Did You Know?" Widget',
      $widget_ops,
      $control_ops
    );
  }

  function widget( $args, $instance ){
    extract( $args );
    $title = apply_filters('widget_title', $instance['title']); // widget title

    $args = array( 
      'title' => $instance['title'],
      'cat'   => $instance['cat'],
    );

    // Before Widget
    echo $before_widget;

    echo $before_title . $title . $after_title;

    //print_r( $args );
    // Widget Output  
    do_action( 'show_dyk_area', $args  );

    // After Widget
    echo $after_widget;
  }

  function update($new_instance, $old_instance) {
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['cat'] = strip_tags($new_instance['cat']);
      return $instance;
  }

    // Widget Control Panel //
  function form( $instance ) {
    $defaults = array( 
      'title' => 'Did You Know?',
      'cat'   => '',
    );
    $instance = wp_parse_args( (array) $instance, $defaults ); ?>

    <!-- Title --> 
    <p>
      <label for="<?php echo $this->get_field_id('title'); ?>">Title: (<em>e.g. <strong>"Did You Know?"</strong></em>)</label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>'" type="text" value="<?php echo $instance['title']; ?>" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('cat'); ?>"><em>(Advanced) Category to retrieve posts from (default: "dyk"):</em></label>
      <input class="widefat" id="<?php echo $this->get_field_id('cat'); ?>" name="<?php echo $this->get_field_name('cat'); ?>'" type="text" value="<?php echo $instance['cat']; ?>" />
    </p>
    <?php 
  }
}
add_action('widgets_init', create_function('', 'return register_widget("cos_dyk_widget");'));


// ************************************
// * Display DYK posts in Widget area
// ************************************
function show_dyk_area( $args ) {

	// Grab posts from specified category
	if( get_cat_ID( $args['cat'] ) ){
		$dykArgs = array( 
			'category_name'  => $args['cat'],
			'posts_per_page' => 1 ,
			'orderby'        => 'rand'
		);
	} else {
		echo "<em>There is nothing to know at this time.</em>";
		return false;
	}
	$myQuery = new WP_Query($dykArgs);	

	if($myQuery->have_posts()) : while ($myQuery->have_posts()) : $myQuery->the_post();			

		$thisID = get_the_ID();
		the_content();

	break; // Prevent loop from displaying more than one post, just in case.

	endwhile; endif; wp_reset_query();

	return true;
}
add_action( 'show_dyk_area', 'show_dyk_area', 10, 1 );



/*-------------------------------------------------------------------------------
  Custom Columns
-------------------------------------------------------------------------------*/

// ********************************************
// * Column Declarations for Custom Post Types
// ********************************************
function cos_slider_columns_decaration($columns){
  $columns = array(
    'cb'                => '<input type="checkbox" />',    
    'title'             => 'Title',
    'slider_thumbnail'  =>  'Slide (preview)',
    'slider_disabled'   => 'Disabled',
    'slider_expiration' => 'Expiration',
    'author'            =>  'Author',
    'date'              =>  'Date',
  );
  return $columns;
}
function cos_people_columns_decaration($columns){
  $columns = array(
    'cb'                    => '<input type="checkbox" />',    
    'title'                 => 'Title',
    'person_thumbnail'      =>  'Headshot (preview)',
    'person_position'       => 'Position',
    'person_classification' => 'Classifications',
    'author'                =>  'Author',
    'date'                  =>  'Date',
  );
  return $columns;
}
function cos_mainlink_columns_decaration($columns){
  $columns = array(
    'cb'                  => '<input type="checkbox" />',    
    'title'               => 'Title',
    'mainlink_thumbnail'  =>  'Image (preview)',
    'mainlink_locations'  => 'Location(s)',
    'date'                =>  'Date',
  );
  return $columns;
}
function cos_socialmedia_columns_decaration($columns){
  $columns = array(
    'cb'               => '<input type="checkbox" />',    
    'title'            => 'Title',
    'socialmedia_type' =>  'Social Media Type',
    'socialmedia_link' => 'Link',
    'date'             =>  'Date',
  );
  return $columns;
}
function cos_classes_columns_decaration($columns){
  $columns = array(
    'cb'            => '<input type="checkbox" />',    
    'title'         => 'Title',
    'classes_level' =>  'Course Level',
    'classes_number'=> 'Class Number',
    'date'          =>  'Date',
  );
  return $columns;
}
// ***********************************************
// * Function to output the custom column content
// ***********************************************
function cos_slider_columns($column){

  global $post;

  if($column == 'slider_thumbnail'){
    $image = get_field('image');
    if(!empty($image)){
      echo "<img style='max-width:160px; max-height:160px;' src='".$image['sizes']['medium']."' />";
    }
  }elseif($column == 'slider_disabled'){    
    if(get_field('disabled') == true){      
      echo '<input type="checkbox" checked disabled style="margin-left: 8px;">';
    }          
  }elseif($column == 'slider_expiration'){
      $date = get_field('expires');
      if(!empty($date))        
        echo cos_column_date($date);
  }elseif($column == 'person_position'){
      $position = trim(get_field('position'));
      if(!empty($position))
        echo $position;
  }elseif($column == 'person_thumbnail'){
      $image = wp_get_attachment_image_src(get_field('headshot'), 'thumbnail' );
      if(!empty($image)){
        echo "<img style='max-width:120px; max-height:120px;' src='".$image[0]."' />";
      }
  }elseif($column == 'person_classification'){
    $terms = wp_get_post_terms( $post->ID, 'people_cat', array("fields" => "names" ));    
    if(!empty($terms)){
      $person_cats = "";
      foreach ($terms as $term => $value) {
        $person_cats .= " $value,";
      }
      echo rtrim($person_cats, ',');
    }
  }elseif($column == 'mainlink_thumbnail'){
    $image = get_field('image');
    if(!empty($image)){
      echo "<img style='max-width:160px; max-height:160px;' src='".$image."' />";
    }
  }elseif( $column == 'mainlink_locations' ){
    $terms = wp_get_post_terms( $post->ID, 'cos_mainlinks_cat', array("fields" => "names" ));    
    if(!empty($terms)){
      $mainlink_locations = "";
      foreach ($terms as $term => $value) {
        $mainlink_locations .= " $value,";
      }
      echo rtrim($mainlink_locations, ',');
    }
  }elseif($column == 'socialmedia_type'){
    $type = get_field('type');
    if(!empty($type)){
      switch($type){
        case 'fb':
          echo 'Facebook';
          break;
        case 'tw':
          echo 'Twitter';
          break;
        case 'yt':
          echo 'YouTube';
          break;
        case 'fl':
          echo 'Flickr';
          break;
        case 'vi':
          echo 'Vimeo';
          break;
        case 'li' :
          echo 'LinkedIn';
          break;
      }
    }
  }elseif($column == 'socialmedia_link'){
    $link = get_field('link');
    if(!empty($link))   echo "<a href='$link' target='_blank'>$link</a>";    
  }
  // Classes Fields
  elseif($column == 'classes_level'){
    $terms = wp_get_post_terms( $post->ID, 'cos_classes_cat', array("fields" => "names" ));    
    if(!empty($terms)){
      $classes_cat = "";
      foreach ($terms as $term => $value) {
        $classes_cat .= " $value,";
      }
      echo rtrim($classes_cat, ',');
    }
  }elseif($column == 'classes_number'){
    $level = get_field('classes_class_number');
    if(!empty($level)) echo $level;      
  }
}

function cos_column_date( $orig_date ){

  $month  = substr($orig_date, 4, 2);
  $day    = substr($orig_date, 6, 2);
  $year   = substr($orig_date, 0, 4);

  $new_date = date("M d, Y", mktime(0,0,0,$month,$day,$year));

  return $new_date;
}

// Actions to output the custom column content
add_action("manage_slider_posts_custom_column", "cos_slider_columns");
add_action("manage_people_posts_custom_column", "cos_slider_columns");
add_action("manage_classes_posts_custom_column", "cos_slider_columns");
add_action("manage_mainlink_posts_custom_column", "cos_slider_columns");
add_action("manage_social_media_posts_custom_column", "cos_slider_columns");

// Filters for custom column defnitions
add_filter("manage_edit-slider_columns", "cos_slider_columns_decaration");
add_filter("manage_edit-people_columns", "cos_people_columns_decaration");
add_filter("manage_edit-mainlink_columns", "cos_mainlink_columns_decaration");
add_filter("manage_edit-classes_columns", "cos_classes_columns_decaration");
add_filter("manage_edit-social_media_columns", "cos_socialmedia_columns_decaration");



/*-------------------------------------------------------------------------------
  Sortable Columns
-------------------------------------------------------------------------------*/
function my_column_register_sortable( $columns ){
  $columns['featured'] = 'featured';
  return $columns;
}
add_filter("manage_edit-slider_sortable_columns", "my_column_register_sortable" );

// *********  END CUSTOM COLUMNS ********* //

// **************************************************
// * Remove Quick Edit Functionality for non-admins *
// **************************************************
function remove_quick_edit( $actions ) {
  unset($actions['inline hide-if-no-js']);
  return $actions;
}
if ( current_user_can('manage_options') ) {
} else {
  add_filter('page_row_actions','remove_quick_edit',99,1);
  add_filter('post_row_actions','remove_quick_edit',99,1);
}


// *************************
// * Giving Page Shortcode *
// *************************
function cos_giving_shortcode( $atts, $content = null ){
  if( $content != null){

    $content = ltrim($content, "</p>");
    $content = rtrim($content, "<p>");
    ob_start();

    //echo "<pre>$content</pre>";

    echo "<div class='cos_giving_area'>".do_shortcode($content)."</div>";

    return ob_get_clean();
  }
}
add_shortcode('cos_giving_area', 'cos_giving_shortcode');

// *************************
// * Biology Styled Button *
// *************************
function cos_bio_button_shortcode( $atts = [] ){
  $atts =  shortcode_atts( array(
    'url'    => '',
    'text'   => '',    
    'float'  => '',
    'target' => '',
  ), $atts );

  switch( $atts['float'] ){
    case 'left':
      $atts['float'] = 'alignleft';
      break;
    case 'right':
      $atts['float'] = 'alignright';
      break;
    case 'center':
      $atts['float'] = 'aligncenter';
      break;
  }
  ob_start();

  echo "<a href='".esc_url($atts['url'])."' class='bio-btn ".$atts['float']." page-btn ' target='_".$atts['target']."'>".$atts['text']."</a>";

  return ob_get_clean();
}
add_shortcode('bio_button', 'cos_bio_button_shortcode');
