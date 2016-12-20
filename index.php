<?php
/**
 * The main template file.
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers HTML5 3.0
 */
 
get_header(); ?>
 
<!-- Contact box - not a section because it lies on top of slider -->

<div id="contactContainer">	
	
	<form role="search" method="get" id="<?php echo 'inner_'; ?>searchform" class="<?php echo 'home_search ';?>" action="<?php echo home_url( '/' ); ?>" >
        <div><i class="icon-search"></i>
            <input type="text" value="Search <?php bloginfo( 'name' ); ?>..." name="s" id="s" onfocus="this.value=(this.value=='Search <?php bloginfo( 'name' ); ?>...') ? '' : this.value;" onblur="this.value=(this.value=='') ? 'Search <?php bloginfo( 'name' ); ?>...' : this.value;"/>
        </div>
	</form>		
	<?php //show_contact_area(); ?>
	<?php if ( is_active_sidebar( 'front-slider-right-widget-area' ) ) : ?>
		<?php dynamic_sidebar( 'front-slider-right-widget-area' ); ?>
	<?php endif; ?>
	
</div>


<section id="sliders">
	<div class="flexslider">
		<!-- jQuery slider will be implemented here, and pulled from 'slider' category of posts -->
		<?php cos_show_slider_items(); ?>	
	</div>
</section>

<?php cos_show_main_links( 'home-tracks' ); ?>

<?php
	$dept_description = get_field('cos_home_page_text', 'option');
	if($dept_description):
?>
<div id="dept_description">
	<div class="wrap clearfix">
		<div class="about-header">
			<span class="line-1">ABOUT THE UCF</span>
			<span class="line-2">DEPARTMENT</span>
			<span class="line-3">OF <span class="dept_descr_name"><?php bloginfo( 'name' ); ?></span></span>
		</div>
		<div class="about-content">
			<?php echo $dept_description; ?> <a class="bio-btn" href="about">Find Out More</a>
		</div>
	</div>
</div>
<?php endif; ?>

<?php cos_show_main_links( 'main-links' ); ?>

<!-- Central content for dept home page - default shown is News + Events -->
<section id="main_content">
	<div class="wrap clearfix">
		<?php if ( is_active_sidebar( 'front-left-widget-area' ) ) : ?>
			<?php dynamic_sidebar( 'front-left-widget-area' ); ?>
		<?php endif; ?>
		<?php // show_news(); ?>

		<?php if ( is_active_sidebar( 'front-right-widget-area' ) ) : ?>
			<?php dynamic_sidebar( 'front-right-widget-area' ); ?>
		<?php endif; ?>

	</div>
</section>

<?php get_footer(); ?>
