<?php 
/**
* 404 template used by the CyberChimps Response Core Framework
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
* @since 1.0.5
*/
	global $options, $themeslug, $post, $sidebar, $content_grid; // call globals
	response_sidebar_init(); // sidebar init
	get_header(); // call header
?>

<div class="container">
	<div class="row">
	<!--Begin response_before_content_sidebar hook-->
		<?php response_before_content_sidebar(); ?>
	<!--End response_before_content_sidebar hook-->
		<div id="content" class="<?php echo $content_grid; ?>">
			<div class="content_padding">
		
			<!--Begin response_before_404 hook-->
      			<?php response_before_404(); ?>
      		<!--End response_before_404 hook-->
		
      		<!--Begin response_404 hook-->
      			<?php response_404(); ?>
      		<!--Begin response_404 hook-->
      		
      		<!--Begin response_after_404 hook-->
      			<?php response_after_404(); ?>
      		<!--End response_after_404 hook-->
      		
			</div><!--end content_padding-->
		</div><!--end content_wrap-->
	
	<!--Begin response_after_content_sidebar hook-->
		<?php response_after_content_sidebar(); ?>
	<!--End response_after_content_sidebar hook-->
	
	</div><!--end row-->
</div><!--end container-->

<?php get_footer(); ?>