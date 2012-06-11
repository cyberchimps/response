<?php
/**
* Theme functions used by the CyberChimps Response Core Framework
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
* @since 1.0.5
*/

/**
* Define global theme functions.
*/ 
	$themename = 'response';
	$themenamefull = 'Response';
	$themeslug = 're';
	$pagedocs = 'http://cyberchimps.com/question/using-the-response-page-options/';
	$sliderdocs = 'http://cyberchimps.com/question/how-to-use-the-response-slider/';
	$root = get_template_directory_uri(); 
	
/**
* Basic theme setup.
*/ 
function response_theme_setup() {
	global $content_width;
	if ( ! isset( $content_width ) ) $content_width = 640; //Set content width
	
	add_theme_support(
		'post-formats',
		array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat')
	);

	add_theme_support( 'post-thumbnails' );
	add_theme_support('automatic-feed-links');
	add_editor_style();
	
	if ( function_exists('get_custom_header')) {
        add_theme_support('custom-background');
	} 
	else {
       	add_custom_background(); //For WP 3.3 and below.	
	}
}
add_action( 'after_setup_theme', 'response_theme_setup' );

/** Replaces RSS link from HTML 'head' with custom feed also used with the 'social icon' */
function response_feed_links() {
	global $themename, $themeslug, $options;
	$my_feed=$options->get($themeslug.'_rsslink');
	if ($my_feed) {
		echo '<link rel="alternate" type="application/rss+xml" title="RSS feed" href="'.$my_feed.'"/>';
	}
	else {
		feed_links();
	}
}
remove_action( 'wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
add_action('wp_head', 'response_feed_links');

/**
* Redirect user to theme options page after activation.
*/ 
if ( is_admin() && isset($_GET['activated'] ) && $pagenow =="themes.php" ) {
	wp_redirect( 'themes.php?page=response' );
}

/**
* Add link to theme options in Admin bar.
*/ 
function response_admin_link() {
	global $wp_admin_bar;

	$wp_admin_bar->add_menu( array( 'id' => 'Response', 'title' => 'Response Options', 'href' => admin_url('themes.php?page=response')  ) ); 
}
add_action( 'admin_bar_menu', 'response_admin_link', 113 );

/**
* Custom markup for gallery posts in main blog index.
*/ 
function response_custom_gallery_post_format( $content ) {
	global $options, $themeslug, $post;
	$root = get_template_directory_uri(); 
	
	ob_start();?>
	
		<?php if ($options->get($themeslug.'_post_formats') == '1') : ?>
			<div class="postformats"><!--begin format icon-->
				<img src="<?php echo get_template_directory_uri(); ?>/images/formats/gallery.png" />
			</div><!--end format-icon-->
		<?php endif;?>
				<h2 class="posts_title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
					<!--Call @Core Meta hook-->
			<?php response_post_byline(); ?>
				<?php
				if ( has_post_thumbnail() && $options->get($themeslug.'_show_featured_images') == '1' && !is_single() ) {
 		 			echo '<div class="featured-image">';
 		 			echo '<a href="' . get_permalink($post->ID) . '" >';
 		 				the_post_thumbnail();
  					echo '</a>';
  					echo '</div>';
				}
			?>	
				<div class="entry" <?php if ( has_post_thumbnail() && $options->get($themeslug.'_show_featured_images') == '1' ) { echo 'style="min-height: 115px;" '; }?>>
				
				<?php if (!is_single()): ?>
				<?php $images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC', 'numberposts' => 999 ) );
					if ( $images ) :
						$total_images = count( $images );
						$image = array_shift( $images );
						$image_img_tag = wp_get_attachment_image( $image->ID, 'thumbnail' );
				?>

				<figure class="gallery-thumb">
					<a href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
					<br /><br />
					This gallery contains <?php echo $total_images ; ?> images
					<?php endif;?>
				</figure><!-- .gallery-thumb -->
				<?php endif;?>
				
				<?php if (is_single()): ?>
					<?php the_content(); ?>
				<?php endif;?>
				</div><!--end entry-->

				<div style=clear:both;></div>
	<?php	
	$content = ob_get_clean();
	
	return $content;
}
add_filter('response_post_formats_gallery_content', 'response_custom_gallery_post_format' ); 
	
/**
* Set custom post excerpt link text based on theme option.
*/ 
function response_excerpt_link($more) {

	global $themename, $themeslug, $options, $post;
    
    	if ($options->get($themeslug.'_excerpt_link_text') == '') {
    		$linktext = '(Read More...)';
   		}
    	else {
    		$linktext = $options->get($themeslug.'_excerpt_link_text');
   		}

	return '<a href="'. get_permalink($post->ID) . '"> <br /><br /> '.$linktext.'</a>';
}
add_filter('excerpt_more', 'response_excerpt_link');

/**
* Set custom post excerpt length based on theme option.
*/ 
function response_excerpt_length($length) {

	global $themename, $themeslug, $options;
	
		if ($options->get($themeslug.'_excerpt_length') == '') {
    		$length = '55';
    	}
    	else {
    		$length = $options->get($themeslug.'_excerpt_length');
    	}
    	
	return $length;
}
add_filter('excerpt_length', 'response_excerpt_length');

/**
* Custom featured image size based on theme options.
*/ 
function response_featured_image() {	
	if ( function_exists( 'add_theme_support' ) ) {
	
	global $themename, $themeslug, $options;
	
	if ($options->get($themeslug.'_featured_image_height') == '') {
		$featureheight = '100';
	}		
	else {
		$featureheight = $options->get($themeslug.'_featured_image_height'); 
	}
	if ($options->get($themeslug.'_featured_image_width') == "") {
			$featurewidth = '100';
	}		
	else {
		$featurewidth = $options->get($themeslug.'_featured_image_width'); 
	} 
	if ($options->get($themeslug.'_featured_image_crop') == "1") {
			$crop = 'true';
	}		
	else {
		$crop = 'false';
	} 
	set_post_thumbnail_size( $featurewidth, $featureheight, $crop );
	}	
}
add_action( 'init', 'response_featured_image', 11);	

/**
* Add Google Analytics support based on theme option.
*/ 
function response_google_analytics() {
	global $themename, $themeslug, $options;
	
	echo stripslashes ($options->get($themeslug.'_ga_code'));

}
add_action('wp_head', 'response_google_analytics');

/**
* Add custom header scripts support based on theme option.
*/ 
function response_custom_scripts() {
	global $themename, $themeslug, $options;
	
	echo stripslashes ($options->get($themeslug.'_custom_header_scripts'));

}
add_action('wp_head', 'response_custom_scripts');

	
/**
* Register custom menus for header, footer.
*/ 
function response_register_menus() {
	register_nav_menus(
	array( 'header-menu' => __( 'Header Menu' ))
  );
}
add_action( 'init', 'response_register_menus' );
	
/**
* Menu fallback if custom menu not used.
*/ 
function response_menu_fallback() {
	global $post; ?>
	
	<ul id="nav_menu">
		<?php wp_list_pages( 'title_li=&sort_column=menu_order&depth=3'); ?>
	</ul><?php
}
/**
* Register widgets.
*/ 
function response_widgets_init() {
    register_sidebar(array(
    	'name' => 'Full Sidebar',
    	'id'   => 'sidebar-widgets',
    	'description'   => 'These are widgets for the full sidebar.',
    	'before_widget' => '<div id="%1$s" class="widget-container">',
    	'after_widget'  => '</div>',
    	'before_title'  => '<h2 class="widget-title">',
    	'after_title'   => '</h2>'
    ));
    register_sidebar(array(
    	'name' => 'Left Half Sidebar',
    	'id'   => 'sidebar-left',
    	'description'   => 'These are widgets for the left half sidebar.',
    	'before_widget' => '<div id="%1$s" class="widget-container">',
    	'after_widget'  => '</div>',
    	'before_title'  => '<h2 class="widget-title">',
    	'after_title'   => '</h2>'
    ));    	
    register_sidebar(array(
    	'name' => 'Right Half Sidebar',
    	'id'   => 'sidebar-right',
    	'description'   => 'These are widgets for the right half sidebar.',
    	'before_widget' => '<div id="%1$s" class="widget-container">',
    	'after_widget'  => '</div>',
    	'before_title'  => '<h2 class="widget-title">',
    	'after_title'   => '</h2>'
   	));
   	register_sidebar(array(
		'name' => 'Footer',
		'id' => 'footer-widgets',
		'description' => 'These are the footer widgets',
		'before_widget' => '<div class="three columns footer-widgets">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="footer-widget-title">',
		'after_title' => '</h3>',
	));
}
add_action ('widgets_init', 'response_widgets_init');

/**
* Initialize Response Core Framework.
*/ 
require_once ( get_template_directory() . '/core/core-init.php' );

/**
* Call additional files required by theme.
*/ 
require_once ( get_template_directory() . '/includes/classy-options-init.php' ); // Theme options markup.
require_once ( get_template_directory() . '/includes/options-functions.php' ); // Custom functions based on theme options.
require_once ( get_template_directory() . '/includes/meta-box.php' ); // Meta options markup.
require_once ( get_template_directory() . '/includes/presstrends.php' ); // Meta options markup.

/**
* End
*/

?>