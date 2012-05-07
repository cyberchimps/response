<?php
/**
* Pagination actions used by the CyberChimps Response Core Framework
*
* Author: Tyler Cunningham
* Copyright: © 2011
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
* Response pagination actions
*/
add_action('response_pagination', 'response_pagination_content');
add_action('response_link_pages', 'response_link_pages_content');
add_action('response_post_pagination', 'response_post_pagination_content');

/**
* Pagination function
*
* @since 1.0
*/
function response_pagination_content($pages = '', $range = 4)
{
     $showitems = ($range * 2)+1;  
 
     global $paged;
     if(empty($paged)) $paged = 1;
 
     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   
 
     if(1 != $pages)
     {
         echo '<div class="pagination"><span>'.__( 'Page', 'response' ).' '.$paged.' '.__( 'of', 'response' ).' '.$pages.'</span>';
         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo '<a href="'.get_pagenum_link(1).'">'.__( '&laquo; First', 'response' ).'</a>';
         if($paged > 1 && $showitems < $pages) echo '<a href="'.get_pagenum_link($paged - 1).'">'.__( '&lsaquo; Previous', 'response' ).'</a>';
 
         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                 echo ($paged == $i)? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";
             }
         }
 
         if ($paged < $pages && $showitems < $pages) echo '<a href="'.get_pagenum_link($paged + 1).'"">'.__( 'Next &rsaquo;', 'response').'</a>';
         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo '<a href="'.get_pagenum_link($pages).'">'.__( 'Last &raquo;', 'response' ).'</a>';
         echo "</div>\n";
     }
}

/**
* Sets up the previous post link and applies a filter to the link text.
*
* @since 1.0
*/
function response_previous_posts() {
	$previous_text = apply_filters('response_previous_posts_text', '&laquo; Older Entries' ); 
	
	echo "<div class='pagnext-posts'>";
	next_posts_link( __( $previous_text, 'response' ));
	echo "</div>";
}

/**
* Sets up the next post link and applies a filter to the link text. 
*
* @since 1.0
*/
function response_newer_posts() {
	$newer_text = apply_filters('response_newer_posts_text', 'Newer Entries &raquo;' );
	
	echo "<div class='pagprev-posts'>";
	previous_posts_link( __( $newer_text, 'response' ));
	echo "</div>";
}

/**
* Sets up the WP link pages
*
* @since 1.0
*/
function response_link_pages_content() {
	 wp_link_pages(array('before' =>  __('Pages:', 'response' ) , 'next_or_number' => 'number'));
}

/**
* Post pagination links 
*
* @since 1.0
*/
function response_post_pagination_content() {
	global $options, $themeslug?>
	
	<?php if ($options->get($themeslug.'_post_pagination') != "0"):?>
	<?php previous_post_link(); ?><span style="float: right"><?php next_post_link(); ?></span>
	<?php endif; 
}

/**
* End
*/

?>