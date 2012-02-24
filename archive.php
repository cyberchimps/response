<?php 
/**
* Archive template used by the CyberChimps Response Core Framework
*
* Authors: Tyler Cunningham, Trent Lapinski
* Copyright: © 2012
* {@link http://cyberchimps.com/ CyberChimps LLC}
*
* Released under the terms of the GNU General Public License.
* You should have received a copy of the GNU General Public License,
* along with this software. In the main directory, see: /licensing/
* If not, see: {@link http://www.gnu.org/licenses/}.
*
* @package Response
* @since 1.0
*/

	global $options, $themeslug, $post, $sidebar, $content_grid; // call globals
	response_sidebar_init(); // sidebar init
	get_header(); // call header

?>

<div class="container">
	<div class="row">
		<?php if ($options->get($themeslug.'_archive_breadcrumbs') == "1") { response_breadcrumbs();}?>
	</div>
	<div class="row">
	<!--Begin @response before content sidebar hook-->
		<?php response_before_content_sidebar(); ?>
	<!--End @response before content sidebar hook-->
	<?php if (have_posts()) : ?>
	
		<div id="content" class="<?php echo $content_grid; ?>">
		
					
		<!--Begin @response before_archive hook-->
			<?php response_before_archive(); ?>
		<!--End @response before_archive hook-->
		
		<?php while (have_posts()) : the_post(); ?>
		
		<div class="post_container">
			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
		
			<!--Begin @response archive hook-->
				<?php response_loop(); ?>
			<!--End @response archive hook-->
			
			</div><!--end post_class-->
			
			<!--Begin @response post bar hook-->
				<?php response_post_bar(); ?>
			<!--End @response post bar hook-->
			
		</div><!--end post container--> 
	

		 <?php endwhile; ?>
	 
	 <?php else : ?>

		<h2>Nothing found</h2>

	<?php endif; ?>

		<!--Begin @response pagination hook-->
			<?php response_pagination(); ?>
		<!--End @response pagination hook-->
		
		<!--Begin @response after_archive hook-->
			<?php response_after_archive(); ?>
		<!--End @response after_archive hook-->
	
		</div><!--end content_padding-->

	<!--Begin @response after content sidebar hook-->
		<?php response_after_content_sidebar(); ?>
	<!--End @response after content sidebar hook-->
	
		</div><!--end content-->
	</div><!--end row-->
</div><!--end container-->

<?php get_footer(); ?>