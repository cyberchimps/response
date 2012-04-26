<?php
/**
* Global actions used by the CyberChimps Response Core Framework
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
* Response global actions
*/

add_action( 'response_loop', 'response_loop_content' );
add_action( 'response_post_byline', 'response_post_byline_content' );
add_action( 'response_edit_link', 'response_edit_link_content' );
add_action( 'response_post_tags', 'response_post_tags_content' );
add_action( 'response_post_bar', 'response_post_bar_content' );

/**
* Check for post format type, apply filter based on post format name for easy modification.
*
* @since 1.0
*/
function response_loop_content($content) { 

	global $options, $themeslug, $post; //call globals
	
	if (is_single()) {
		 $post_formats = $options->get($themeslug.'_single_post_formats');
		 $featured_images = $options->get($themeslug.'_single_show_featured_images');
		 $excerpts = $options->get($themeslug.'_single_show_excerpts');
	}
	elseif (is_archive()) {
		 $post_formats = $options->get($themeslug.'_archive_post_formats');
		 $featured_images = $options->get($themeslug.'_archive_show_featured_images');
		 $excerpts = $options->get($themeslug.'_archive_show_excerpts');
	}
	else {
		 $post_formats = $options->get($themeslug.'_post_formats');
		 $featured_images = $options->get($themeslug.'_show_featured_images');
		 $excerpts = $options->get($themeslug.'_show_excerpts');
	}
	
	if (get_post_format() == '') {
		$format = "default";
	}
	else {
		$format = get_post_format();
	} ?>
		
		<?php ob_start(); ?>
			
			<?php if ($post_formats != '0') : ?>
			<div class="postformats"><!--begin format icon-->
				<img src="<?php echo get_template_directory_uri(); ?>/images/formats/<?php echo $format ;?>.png" alt="formats" />
			</div><!--end format-icon-->
			<?php endif; ?>
				<h2 class="posts_title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
					<!--begin response_post_byline hook-->
						<?php response_post_byline(); ?>
					<!--end response_post_byline hook-->
				<?php
				if ( has_post_thumbnail() && $featured_images == '1') {
 		 			echo '<div class="featured-image">';
 		 			echo '<a href="' . get_permalink($post->ID) . '" >';
 		 				the_post_thumbnail();
  					echo '</a>';
  					echo '</div>';
				}
			?>	
				<div class="entry" <?php if ( has_post_thumbnail() && $featured_images == '1' && !is_single()  ) { echo 'style="min-height: 115px;" '; }?>>
					<?php 
						if ($excerpts == '1' && !is_single() ) {
						the_excerpt();
						}
						else {
							the_content(__('Read more...', 'response'));
						}
					 ?>
				</div><!--end entry-->
	
		<?php	
		
		$content = ob_get_clean();
		$content = apply_filters( 'response_post_formats_'.$format.'_content', $content );
	
		echo $content; 
}

/**
* Sets up the HTML for the postbar area.
*
* @since 3.1
*/
function response_post_bar_content() { 
	global $options, $themeslug; 
	
	if (is_single()) {
		$hidden = $options->get($themeslug.'_single_hide_byline'); 
	}
	elseif (is_archive()) {
		$hidden = $options->get($themeslug.'_archive_hide_byline'); 
	}
	else {
		$hidden = $options->get($themeslug.'_hide_byline'); 
	}?>
	
	<div id="comments">
	<?php if (($hidden[$themeslug.'_hide_comments']) != '0'):?><?php comments_popup_link( __('No Comments', 'response' ), __('1 Comment', 'response' ), __('% Comments' , 'response' )); //need a filer here ?>.<?php endif;?>
	</div>
	<?php
}

/**
* Sets the post byline information (author, date, category). 
*
* @since 1.0
*/
function response_post_byline_content() {
	global $options, $themeslug; //call globals.  
	if (is_single()) {
		$hidden = $options->get($themeslug.'_single_hide_byline'); 
	}
	elseif (is_archive()) {
		$hidden = $options->get($themeslug.'_archive_hide_byline'); 
	}
	else {
		$hidden = $options->get($themeslug.'_hide_byline'); 
	}?>
	
	<div class="meta">
		<?php if (($hidden[$themeslug.'_hide_date']) != '0'):?> <?php printf( __( 'Published on', 'response' )); ?> <a href="<?php the_permalink() ?>"><?php echo get_the_date(); ?></a>,<?php endif;?>
		<?php if (($hidden[$themeslug.'_hide_author']) != '0'):?><?php printf( __( 'by', 'response' )); ?> <?php the_author_posts_link(); ?> <?php endif;?> 
		<?php if (($hidden[$themeslug.'_hide_categories']) != '0'):?><?php printf( __( 'in', 'response' )); ?> <?php the_category(', ') ?>.<?php endif;?>
		
	</div> <?php
}

/**
* Sets up the WP edit link
*
* @since 1.0
*/
function response_edit_link_content() {
	edit_post_link('Edit', '<p>', '</p>');
}

/**
* Sets up the tag area
*
* @since 1.0
*/
function response_post_tags_content() {
	global $options, $themeslug; 
	if (is_single()) {
		$hidden = $options->get($themeslug.'_single_hide_byline'); 
	}
	elseif (is_archive()) {
		$hidden = $options->get($themeslug.'_archive_hide_byline'); 
	}
	else {
		$hidden = $options->get($themeslug.'_hide_byline'); 
	}?>

	<?php if (has_tag() AND ($hidden[$themeslug.'_hide_tags']) != '0'):?>
	<div class="tags">
			<?php the_tags( __('Tags: ', 'response'), ', ', '<br />'); ?>
		
	</div><!--end tags--> 
	<?php endif;
}

/**
* End
*/

?>