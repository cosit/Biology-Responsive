<?php
/**
 * The template for displaying the footer.
 *
 * @package WordPress
 * @subpackage Starkers
 * @since Starkers HTML5 3.0
 */
?>
	<div id="backToTop">
		<a href="#top"></a>
	</div>
</div> <!-- /container -->

<footer id="main_footer">
	<!-- Bottom widgets -->
	<?php  
	//If there is nothing in any of the footer widgets show nothing
	if (   ! is_active_sidebar( 'first-footer-widget-area'  )
	    && ! is_active_sidebar( 'second-footer-widget-area' )
		&& ! is_active_sidebar( 'third-footer-widget-area'  )		
	        ){}
	else{ 
	//Display the widget content
	?>
	<section id="widgets">
		<div id="widget_container">
			<?php if ( is_active_sidebar( 'first-footer-widget-area' ) ) : ?>
			<div id="first-footer-widget-area" class="widget">				
				<?php dynamic_sidebar( 'first-footer-widget-area' ); ?>				
			</div>
			<?php endif; ?>

			<?php if ( is_active_sidebar( 'second-footer-widget-area' ) ) : ?>
			<div id="second-footer-widget-area" class="widget">
				
				<?php dynamic_sidebar( 'second-footer-widget-area' ); ?>				
			</div>
			<?php endif; ?>

			<?php if ( is_active_sidebar( 'third-footer-widget-area' ) ) : ?>
			<div id="third-footer-widget-area" class="widget">
				<?php dynamic_sidebar( 'third-footer-widget-area' ); ?>				
			</div>
			<?php endif; ?>
		</div>
	</section>
	<?php } ?>

	<section id="the_footer">
		<div class="wrap">
			
		<?php /*show_people_cats();  //Important: do not remove  */		?>
			<div class="dept_list">
				<h3><span><a href="http://ucf.edu">UCF</a></span> <a href="http://www.cos.ucf.edu">College of Sciences</a></h3>
				<ul>
					<li><a href="http://sciences.ucf.edu/anthropology/" target="_blank">Anthropology</a></li>
					<li><a href="http://sciences.ucf.edu/biology/" target="_blank">Biology</a></li>
					<li><a href="http://sciences.ucf.edu/chemistry/" target="_blank">Chemistry</a></li>
					<li><a href="http://sciences.ucf.edu/communication/" target="_blank">Communication</a></li>
					<li><a href="http://sciences.ucf.edu/math/" target="_blank">Mathematics</a></li>
					<li><a href="http://sciences.ucf.edu/physics/" target="_blank">Physics</a></li>
					<li><a href="http://sciences.ucf.edu/politicalscience/" target="_blank">Political Science</a></li>
					<li><a href="http://sciences.ucf.edu/psychology/" target="_blank">Psychology</a></li>
					<li><a href="http://sciences.ucf.edu/sociology/" target="_blank">Sociology</a></li>
					<li><a href="http://sciences.ucf.edu/statistics/" target="_blank">Statistics</a></li>
				</ul>
			</div>
		</div>
		
		<h3 id="copyright">&copy; <?php echo date('Y');?> University of Central Florida, College of Sciences, All Rights Reserved</h3>
	</section>
</footer>

<?php wp_footer(); ?>

</body>
</html>