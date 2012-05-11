<?php
/**
* CyberChimps Response Core Framework Functions
*
* Authors: Tyler Cunningham
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
* Establishes 'response' as the textdomain, sets $locale and file path
*
* @since 1.0
*/
function response_text_domain() {
	load_theme_textdomain( 'response', TEMPLATEPATH . '/core/languages' );

	    $locale = get_locale();
	    $locale_file = TEMPLATEPATH . "/core/languages/$locale.php";
	    if ( is_readable( $locale_file ) )
		    require_once( $locale_file );
		
		return;    
}
add_action('after_setup_theme', 'response_text_domain');

/**
* Load jQuery and register additional scripts.
*/ 
function response_scripts() {
	global $options, $themeslug;
	if ( !is_admin() ) {
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-tabs');
	}
	
	$path =  get_template_directory_uri() ."/core/library";
	
	wp_register_script( 'orbit' ,$path.'/js/foundation/jquery.orbit.js');
	wp_register_script( 'apps' ,$path.'/js/foundation/app.js');
	wp_register_script( 'placeholder' ,$path.'/js/foundation/jquery.placeholder.min.js');
	wp_register_script( 'reveal' ,$path.'/js/foundation/jquery.reveal.js');
	wp_register_script( 'tooltips' ,$path.'/js/foundation/jquery.tooltips.js');
	wp_register_script( 'modernizr' ,$path.'/js/foundation/modernizr.foundation.js');
	wp_register_script( 'menu' ,$path.'/js/menu.js');
	wp_register_script( 'mobilemenu' ,$path.'/js/mobilemenu.js');
	
	wp_enqueue_script ('orbit');
	wp_enqueue_script ('apps');
	wp_enqueue_script ('placeholder');
	wp_enqueue_script ('reveal');
	wp_enqueue_script ('tooltips');
	wp_enqueue_script ('modernizr');
	wp_enqueue_script ('menu');
	wp_enqueue_script ('mobilemenu');
	
	if ($options->get($themeslug.'_responsive_video') == '1' ) {
	
		wp_register_script( 'video' ,$path.'/js/video.js');
		wp_enqueue_script ('video');	
	}
}
add_action('wp_enqueue_scripts', 'response_scripts');	

/**
* Adds "untitled" to posts with no title.
*
* @since 1.0
*/
add_filter('the_title', 'response_title');

function response_title($title) {

	if ($title == '') {
		return 'Untitled';
	} else {
		return $title;
	}
}

/**
* Truncate next/previous post link text for post pagination.
*
* @since 1.0
*/
function response_shorten_linktext($linkstring,$link) {
	$characters = 33;
	preg_match('/<a.*?>(.*?)<\/a>/is',$linkstring,$matches);
	$displayedTitle = $matches[1];
	$newTitle = shorten_with_ellipsis($displayedTitle,$characters);
	return str_replace('>'.$displayedTitle.'<','>'.$newTitle.'<',$linkstring);
}

function shorten_with_ellipsis($inputstring,$characters) {
  return (strlen($inputstring) >= $characters) ? substr($inputstring,0,($characters-3)) . '...' : $inputstring;
}

add_filter('previous_post_link','response_shorten_linktext',10,2);
add_filter('next_post_link','response_shorten_linktext',10,2);

/**
* Comment function
*
* @since 1.0
*/
function response_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
     <div id="comment-<?php comment_ID(); ?>">
      <div class="comment-author vcard">
         <?php echo get_avatar( $comment, 48 ); ?>

         <?php printf(__('<cite class="fn">%s</cite> <span class="says"></span>'), get_comment_author_link()) ?>
      </div>
      <?php if ($comment->comment_approved == '0') : ?>
         <em><?php _e('Your comment is awaiting moderation.', 'response' ) ?></em>
         <br />
      <?php endif; ?>

      <div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>"><?php printf(__('%1$s at %2$s', 'response' ), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)', 'response' ),'  ','') ?></div>

      <?php comment_text() ?>

      <div class="reply">
         <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div>
     </div>
<?php
}

/**
* Breadcrumbs function
*
* @since 1.0
*/
function response_breadcrumbs() {
  global $root;
  
  $delimiter = "<img src='$root/images/breadcrumb-arrow.png'>";
  $home = 'Home'; // text for the 'Home' link
  $before = '<span class="current">'; // tag before the current crumb
  $after = '</span>'; // tag after the current crumb
 
  if ( !is_home() && !is_front_page() && !is_attachment() || is_paged() ) {
 
    echo '<div class="row"><div id="crumbs" class="twelve columns"><div class="crumbs_text">';
 
    global $post;
    $homeLink = get_bloginfo('url');
    echo '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';
 
    if ( is_category() ) {
      global $wp_query;
      $cat_obj = $wp_query->get_queried_object();
      $thisCat = $cat_obj->term_id;
      $thisCat = get_category($thisCat);
      $parentCat = get_category($thisCat->parent);
      if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
      echo $before . 'Archive for category "' . single_cat_title('', false) . '"' . $after;
 
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
        echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
        echo $before . get_the_title() . $after;
      } else {
        $cat = get_the_category(); $cat = $cat[0];
        echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
        echo $before . get_the_title() . $after;
      }
 
    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $post_type = get_post_type_object(get_post_type());
      echo $before . $post_type->labels->singular_name . $after;
 
    } elseif ( is_attachment() ) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
      echo $before . get_the_title() . $after;
 
    } elseif ( is_page() && !$post->post_parent ) {
      echo $before . get_the_title() . $after;
 
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
      echo $before . get_the_title() . $after;
 
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
 
    echo '</div></div></div>';
 
  }
} 

/**
* End
*/
		    
?>