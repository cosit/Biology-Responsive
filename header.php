<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers HTML5 3.0
 */
?>
<!DOCTYPE html>
<!--[if lt IE 9]>  <html id="ie"> <![endif]-->
<!--[if IE 9]>     <html> <![endif]-->
<!--[if !IE]><!--> <html> <!--<![endif]-->
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php
 
    global $page, $paged;

    $thisDept       = get_bloginfo('name');
    $brandingPrefix = get_field('cos_title_prefix', 'option');
    $brandingLink   = '';

    if($brandingPrefix == "COS")
        $brandingLink = "http://www.cos.ucf.edu/";
    elseif($brandingPrefix == "UCF")
        $brandingLink = "http://www.ucf.edu"; 
    else{
        $brandingPrefix = "UCF";
        $brandingLink   = "http://www.ucf.edu"; 
    }

    if( !is_home() && !is_front_page() ){ wp_title( '', true, 'left' ); } 
    else { echo $brandingPrefix . ' ' . $thisDept;          }          
 
?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="icon" href="https://www.ucf.edu/img/pegasus-icon.png" type="image/png" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/font-awesome.css" media="all">
<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/font-awesome-ie7.css" media="all">
<![endif]--> 
<!--[if gt IE 8]><!-->
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/responsive.css" media="all">
<!--<![endif]-->
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="stylesheet/css" type="text/css" media="print" href="<?php echo get_template_directory_uri(); ?>/print.css">

<?php wp_head(); ?>
<!--[if gt IE 8]><!-->
    <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/responsive.js"></script>
<!--<![endif]-->
 
<body id="top" <?php body_class(); ?>>
 
    <header id="main_header">
        <div class="wrap clearfix">
            <?php cos_show_social(); ?>
            <hgroup id="header_title">

            <?php 
            // If there's a tagline / site description
            if(get_bloginfo('description')): ?>
                <h1 ><span class="branding_prefix" <?php if(get_field('cos_title_size', 'option')) echo 'style="font-size:'.get_field('cos_title_size', 'option').'px !important;"'; ?> ><?php echo "<a href=".$brandingLink.">".$brandingPrefix."</a>" ; ?> </span> 
                <a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home" class="dept_name">
                <span class="<?php if(get_bloginfo('description')) echo 'branding_dept';?>" <?php if(get_field('cos_title_size', 'option')) echo 'style="font-size:'.get_field('cos_title_size', 'option').'px !important;"'; ?> ><?php bloginfo( 'name' ); ?></span></a>
                </h1>
                <?php if(get_bloginfo('description')) echo "<span id='tagline'>".get_bloginfo('description')."</a></span>"; ?>

            <?php else: ?>
                <h1 ><span class="branding_prefix" <?php if(get_field('cos_title_size', 'option')) echo 'style="font-size:'.get_field('cos_title_size', 'option').'px !important;"'; ?>><?php echo "<a href=".$brandingLink.">".$brandingPrefix."</a>" ; ?> </span> 
                <a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home" class="dept_name">
                <span class="<?php if(get_bloginfo('description')) echo 'branding_dept';?>" <?php if(get_field('cos_title_size', 'option')) echo 'style="font-size:'.get_field('cos_title_size', 'option').'px !important;"'; ?> ><?php bloginfo( 'name' ); ?></span></a>
                </h1>                
            <?php endif; ?>
            <div class="nav-mobile"><i class="icon-reorder"></i></div>
            </hgroup>
            
            <form role="search" method="get" id="<?php echo 'inner_'; ?>searchform" class="<?php echo 'mobile_search ';?>" action="<?php echo home_url( '/' ); ?>" >
                <div><i class="icon-search"></i>
                    <input type="text" value="Search <?php bloginfo( 'name' ); ?>..." name="s" id="s" onfocus="this.value=(this.value=='Search <?php bloginfo( 'name' ); ?>...') ? '' : this.value;" onblur="this.value=(this.value=='') ? 'Search <?php bloginfo( 'name' ); ?>...' : this.value;"/>
                </div>
            </form>
            
            <?php wp_nav_menu( array( 'theme_location' => 'primary-menu', 'container' => 'nav', 'fallback_cb' => 'starkers_menu', 'container_id' => 'main_menu' ) ); ?>

            <span id="pageID" style="display:none;"><?php echo get_query_var('page_id'); ?></span>

        </div>

    </header>

<div id="container">