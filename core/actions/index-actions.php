<?php
/**
* Index actions used by the CyberChimps Response Core Framework
*
* Author: Tyler Cunningham
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

/**
* Response index actions
*/

add_action( 'response_index', 'response_index_content');

/**
* Index content
*
* @since 1.0
*/
function response_index_content() { 

	global $options, $themeslug, $post, $sidebar, $content_grid; // call globals ?>
	
	<!--Begin response_sidebar_init-->
		<?php response_sidebar_init(); ?>
	<!--End response_sidebar_init-->
	<div class="row">
<!--Begin response_before_content_sidebar hook-->
		<?php response_before_content_sidebar(); ?>
	<!--End response_before_content_sidebar hook-->

		<div id="content" class="<?php echo $content_grid; ?>">
	
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
			<div class="post_container">
				<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
		
				<!--Begin response_loop hook-->
					<?php response_loop(); ?>
				<!--End response_loop hook-->	
			
				<!--Begin response_link_pages hook-->
					<?php response_link_pages(); ?>
				<!--End response_link_pages hook-->
			
				<!--Begin response_post_edit_link hook-->
					<?php response_edit_link(); ?>
				<!--End response_post_edit_link hook-->		
							
				<!--Begin response_post_tags hook-->
					<?php response_post_tags(); ?>
				<!--End response_post_tags hook-->
							
				<!--Begin response_post_bar hook-->
					<?php response_post_bar(); ?>
				<!--End response_post_bar hook-->
				
				</div><!--end post_class-->
			</div><!--end post container-->
			
			<?php if (is_attachment()) : ?>
			
			<div id="image_pagination">
				<div class="image_wrap">
					<div class="previous_image"> <?php previous_image_link( array( 100, 1000 ) ); ?></div>
					<div class="next_image"><?php next_image_link( array( 100, 100 )); ?></div>
				</div>
			</div>
			<?php endif; ?>
			
			<?php if (is_single() && $options->get($themeslug.'_post_pagination') == "1") : ?>
			<div id="post_pagination"><div class="pagination_text">
				<!--Begin @response post pagination hook-->
					<?php response_post_pagination(); ?>
				<!--End @response post pagination hook-->	
			
			</div></div>		
				<?php endif;?>

			<?php if (is_single()):?>
			<?php comments_template(); ?>
			<?php endif ?>
			
			<?php endwhile; ?>
		
			<?php else : ?>

				<h2>Not Found</h2>

			<?php endif; ?>
			
			<!--Begin response_pagination hook-->
			<?php response_pagination(); ?>
			<!--End response_pagination hook-->
			
		</div><!--end content-->

	<!--Begin response_after_content_sidebar hook-->
		<?php response_after_content_sidebar(); ?>
	<!--End response_after_content_sidebar hook-->

</div>
<?php }

/**
* End
*/

?>