<?php
/**
* Header actions used by the CyberChimps Response Core Framework
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
* Response header actions
*/
add_action( 'response_after_head_tag', 'response_font' );
add_action( 'response_head_tag', 'response_html_attributes' );
add_action( 'response_head_tag', 'response_meta_tags' );
add_action( 'response_head_tag', 'response_title_tag' );
add_action( 'response_head_tag', 'response_link_rel' );

add_action( 'response_header_sitename', 'response_header_sitename_content');
add_action( 'response_header_site_description', 'response_header_site_description_content' );
add_action( 'response_header_social_icons', 'response_header_social_icons_content' );

add_action( 'response_navigation', 'response_nav' );
add_action( 'response_404_content', 'response_404_content_handler' );

add_action( 'response_logo_icons', 'response_logo_icons_content');
add_action( 'response_custom_header_element', 'response_custom_header_element_content');
add_action( 'response_logo_register', 'response_logo_register_content');
add_action( 'response_banner', 'response_banner_content');

/**
* Establishes the theme font family.
*
* @since 1.0
*/
function response_font() {
	global $themeslug, $options; //Call global variables
	$family = apply_filters( 'response_default_font_family', 'Helvetica, serif' );
	
	if ($options->get($themeslug.'_font') == "" ) {
		$font = apply_filters( 'response_default_font', 'Arial' );
	}		
	else {
		$font = $options->get($themeslug.'_font'); 
	} ?>
	
	<body style="font-family:'<?php echo str_replace("+", " ", $font ); ?>', <?php echo $family; ?>" <?php body_class(); ?> > <?php
}

/**
* Establishes the theme HTML attributes
*
* @since 1.0
*/
function response_html_attributes() { ?>
<!doctype html>
<!--[if lt IE 7 ]> <html class="ie6"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html class=""> <!--<![endif]-->
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes('xhtml'); ?>>
<head profile="http://gmpg.org/xfn/11"> <?php 
}

/**
* Establishes the theme META tags (including SEO options)
*
* @since 1.0
*/
function response_meta_tags() { ?>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /><?php
	global $themeslug, $options, $post; //Call global variables
	if(!$post) return; // in case of 404 page or something?>

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="distribution" content="global" />
<meta name="language" content="en" /> 
<meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; width=device-width"/><?php
}

/**
* Establishes the theme title tags.
*
* @since 1.0
*/
function response_title_tag() {
	global $options, $themeslug, $query, $post; 
	$blogtitle = ($options->get($themeslug.'_home_title'));
	if (!is_404()) {
		$title = get_post_meta($post->ID, 'seo_title' , true);
	}
	else {
		$title = '';
	}

	echo "<title>";
	
	if (function_exists('is_tag') && is_tag()) { /*Title for tags */
		bloginfo('name'); echo ' - '; single_tag_title("Tag Archive for &quot;"); echo '&quot;  ';
	}
	elseif (is_archive()) { /*Title for archives */ 
		bloginfo('name'); echo ' - '; wp_title(''); echo ' Archive '; 
	}    
	elseif (is_search()) { /*Title for search */ 
		bloginfo('name'); echo ' - '; echo 'Search for &quot;'.get_search_query().'&quot;  '; 
	}    
	elseif (is_404()) { /*Title for 404 */
		bloginfo('name'); echo ' - '; echo 'Not Found '; 
	}
	elseif (is_front_page() AND !is_page() AND $blogtitle == '') { /*Title if front page is latest posts and no custom title */
		bloginfo('name'); echo ' - '; bloginfo('description'); 
	}
	elseif (is_front_page() AND !is_page() AND $blogtitle != '') { /*Title if front page is latest posts with custom title */
		bloginfo('name'); echo ' - '; echo $blogtitle ; 
	}
	elseif (is_front_page() AND is_page() AND $title == '') { /*Title if front page is static page and no custom title */
		bloginfo('name'); echo ' - '; bloginfo('description'); 
	}
	elseif (is_front_page() AND is_page() AND $title != '') { /*Title if front page is static page with custom title */
		bloginfo('name'); echo ' - '; echo $title ; 
	}
	elseif (is_page() AND $title == '') { /*Title if static page is static page with no custom title */
		bloginfo('name'); echo ' - '; wp_title(''); 
	}
	elseif (is_page() AND $title != '') { /*Title if static page is static page with custom title */
		bloginfo('name'); echo ' - '; echo $title ; 
	}
	elseif (is_page() AND is_front_page() AND $blogtitle == '') { /*Title if blog page with no custom title */
		bloginfo('name'); echo ' - '; wp_title(''); 
	}
	elseif ($blogtitle != '') { /*Title if blog page with custom title */ 
		bloginfo('name'); echo ' - '; echo $blogtitle ; 
	}
	else { /*Title if blog page without custom title */
		bloginfo('name'); echo ' - '; wp_title(''); 
	}
	echo "</title>";    
}

/**
* Sets the header link rel attributes
*
* @since 1.0
*/
function response_link_rel() {
global $themeslug, $options; //Call global variables
	$favicon = $options->get($themeslug.'_favicon'); //Calls the favicon URL from the theme options 
	
	if ($options->get($themeslug.'_font') == "" AND $options->get($themeslug.'_custom_font') == "") {
		$font = apply_filters( 'response_default_font', 'Arial' );
	}		
	elseif ($options->get($themeslug.'_custom_font') != "" && $options->get($themeslug.'_font') == 'custom') {
		$font = $options->get($themeslug.'_custom_font');	
	}	
	else {
		$font = $options->get($themeslug.'_font'); 
	} 
?>
	
<link rel="shortcut icon" href="<?php echo stripslashes($favicon['url']); ?>" type="image/x-icon" />

<link rel="stylesheet" href="<?php bloginfo( 'template_url' ); ?>/core/css/foundation.css" type="text/css" />
<link rel="stylesheet" href="<?php bloginfo( 'template_url' ); ?>/core/css/app.css" type="text/css" />
<link rel="stylesheet" href="<?php bloginfo( 'template_url' ); ?>/core/css/ie.css" type="text/css" />
<link rel="stylesheet" href="<?php bloginfo( 'template_url' ); ?>/css/shortcode.css" type="text/css" />
<link rel="stylesheet" href="<?php bloginfo( 'template_url' ); ?>/css/elements.css" type="text/css" />
<link rel="stylesheet" href="<?php bloginfo( 'template_url' ); ?>/css/style.css" type="text/css" />

<?php if (is_child_theme()) :  //add support for child themes?>
	<link rel="stylesheet" href="<?php echo bloginfo('stylesheet_directory') ; ?>/style.css" type="text/css" />
<?php endif; ?>

<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<link href='//fonts.googleapis.com/css?family=<?php echo $font ; ?>' rel='stylesheet' type='text/css' /> <?php
}


/**
* Header left content (sitename or logo)
*
* @since 1.0
*/
function response_header_sitename_content() {
	global $themeslug, $options; //Call global variables
	$logo = $options->get($themeslug.'_logo'); //Calls the logo URL from the theme options
	
	if ($options->get($themeslug.'_custom_logo') == '1') { ?>
	<div id="logo">
		<a href="<?php echo home_url(); ?>/"><img src="<?php echo stripslashes($logo['url']); ?>" alt="logo"></a>
	</div> <?php
	}
						
	else{ ?>
		<h1 class="sitename"><a href="<?php echo home_url(); ?>/"><?php bloginfo('name'); ?> </a></h1>
		<?php
	}						 
}

function response_header_site_description_content() {
	global $themeslug, $options; ?>
	
	<div id="description">
		<h1 class="description"><?php bloginfo('description'); ?>&nbsp;</h1>
	</div> <?php
}


/**
* Social icons
*
* @since 1.0
*/
function response_header_social_icons_content() { 
	global $options, $themeslug; //call globals
	
	$facebook		= $options->get($themeslug.'_facebook');
	$hidefacebook   = $options->get($themeslug.'_hide_facebook_icon');
	$twitter		= $options->get($themeslug.'_twitter');;
	$hidetwitter    = $options->get($themeslug.'_hide_twitter_icon');;
	$gplus		    = $options->get($themeslug.'_gplus');
	$hidegplus      = $options->get($themeslug.'_hide_gplus_icon');
	$flickr		    = $options->get($themeslug.'_flickr');
	$hideflickr     = $options->get($themeslug.'_hide_flickr');
	$pinterest		= $options->get($themeslug.'_pinterest');
	$hidepinterest	= $options->get($themeslug.'_hide_pinterest');
	$linkedin		= $options->get($themeslug.'_linkedin');
	$hidelinkedin   = $options->get($themeslug.'_hide_linkedin');
	$youtube		= $options->get($themeslug.'_youtube');
	$hideyoutube    = $options->get($themeslug.'_hide_youtube');
	$googlemaps		= $options->get($themeslug.'_googlemaps');
	$hidegooglemaps = $options->get($themeslug.'_hide_googlemaps');
	$email			= $options->get($themeslug.'_email');
	$hideemail      = $options->get($themeslug.'_hide_email');
	$rss			= $options->get($themeslug.'_rsslink');
	$hiderss   		= $options->get($themeslug.'_hide_rss_icon');
	
	if ($options->get($themeslug.'_icon_style') == '') {
		$folder = 'default';
	}
	
	else {
		$folder = $options->get($themeslug.'_icon_style');
	} ?>

	<div id="social">

		<div class="icons">
	
		<?php if ($hidefacebook == '1' AND $facebook != '' OR $hidefacebook == '' AND $facebook != '' ):?>
			<a href="<?php echo $facebook ?>" target="_blank" rel="me"><img src="<?php echo get_template_directory_uri(); ?>/images/social/<?php echo $folder; ?>/facebook.png" alt="Facebook" /></a>
		<?php endif;?>
		<?php if ($hidefacebook == '1' AND $facebook == '' OR $hidefacebook == '' AND $facebook == '' ):?>
			<a href="http://facebook.com" target="_blank" rel="me"><img src="<?php echo get_template_directory_uri(); ?>/images/social/<?php echo $folder; ?>/facebook.png" alt="Facebook" /></a>
		<?php endif;?>
		<?php if ($hidetwitter == '1' AND $twitter != '' OR $hidetwitter == '' AND $twitter != '' ):?>
			<a href="<?php echo $twitter ?>" target="_blank" rel="me"><img src="<?php echo get_template_directory_uri(); ?>/images/social/<?php echo $folder; ?>/twitter.png" alt="Twitter" /></a>
		<?php endif;?>
		<?php if ($hidetwitter == '1' AND $twitter == '' OR $hidetwitter == '' AND $twitter == '' ):?>
			<a href="http://twitter.com" target="_blank" rel="me"><img src="<?php echo get_template_directory_uri(); ?>/images/social/<?php echo $folder; ?>/twitter.png" alt="Twitter" /></a>
		<?php endif;?>
		<?php if ($hidegplus == '1' AND $gplus != ''  OR $hidegplus == '' AND $gplus != '' ):?>
			<a href="<?php echo $gplus ?>" target="_blank" rel="me"><img src="<?php echo get_template_directory_uri(); ?>/images/social/<?php echo $folder; ?>/gplus.png" alt="Gplus" /></a>
		<?php endif;?>
		<?php if ($hidegplus == '1' AND $gplus == '' OR $hidegplus == '' AND $gplus == '' ):?>
			<a href="https://plus.google.com" target="_blank" rel="me"><img src="<?php echo get_template_directory_uri(); ?>/images/social/<?php echo $folder; ?>/gplus.png" alt="Gplus" /></a>
		<?php endif;?>
		<?php if ($hideflickr == '1' AND $flickr != '' ):?>
			<a href="<?php echo $flickr ?>" target="_blank" rel="me"><img src="<?php echo get_template_directory_uri(); ?>/images/social/<?php echo $folder; ?>/flickr.png" alt="Flickr" /></a>
		<?php endif;?>
		<?php if ($hideflickr == '1' AND $flickr == '' ):?>
			<a href="https://flickr.com" target="_blank" rel="me"><img src="<?php echo get_template_directory_uri(); ?>/images/social/<?php echo $folder; ?>/flickr.png" alt="Flickr" /></a>
		<?php endif;?>
		<?php if ($hidepinterest == '1' AND $pinterest != '' ):?>
			<a href="<?php echo $pinterest ?>" target="_blank" rel="me"><img src="<?php echo get_template_directory_uri(); ?>/images/social/<?php echo $folder; ?>/pinterest.png" alt="Pinterest" /></a>
		<?php endif;?>
		<?php if ($hidepinterest == '1' AND $pinterest == '' ):?>
			<a href="https://pinterest.com" target="_blank" rel="me"><img src="<?php echo get_template_directory_uri(); ?>/images/social/<?php echo $folder; ?>/pinterest.png" alt="Pinterest" /></a>
		<?php endif;?>
		<?php if ($hidelinkedin == '1' AND $linkedin != '' ):?>
			<a href="<?php echo $linkedin ?>" target="_blank" rel="me"><img src="<?php echo get_template_directory_uri(); ?>/images/social/<?php echo $folder; ?>/linkedin.png" alt="LinkedIn" /></a>
		<?php endif;?>
		<?php if ($hidelinkedin == '1' AND $linkedin == '' ):?>
			<a href="http://linkedin.com" target="_blank" rel="me"><img src="<?php echo get_template_directory_uri(); ?>/images/social/<?php echo $folder; ?>/linkedin.png" alt="LinkedIn" /></a>
		<?php endif;?>
		<?php if ($hideyoutube == '1' AND $youtube != '' ):?>
			<a href="<?php echo $youtube ?>" target="_blank" rel="me"><img src="<?php echo get_template_directory_uri(); ?>/images/social/<?php echo $folder; ?>/youtube.png" alt="YouTube" /></a>
		<?php endif;?>
		<?php if ($hideyoutube == '1' AND $youtube == '' ):?>
			<a href="http://youtube.com" target="_blank" rel="me"><img src="<?php echo get_template_directory_uri(); ?>/images/social/<?php echo $folder; ?>/youtube.png" alt="YouTube" /></a>
		<?php endif;?>
		<?php if ($hidegooglemaps == '1' AND $googlemaps != ''):?>
			<a href="<?php echo $googlemaps ?>" target="_blank" rel="me"><img src="<?php echo get_template_directory_uri(); ?>/images/social/<?php echo $folder; ?>/googlemaps.png" alt="Google Maps" /></a>
		<?php endif;?>
		<?php if ($hidegooglemaps == '1' AND $googlemaps == ''):?>
			<a href="http://google.com/maps" target="_blank" rel="me"><img src="<?php echo get_template_directory_uri(); ?>/images/social/<?php echo $folder; ?>/googlemaps.png" alt="Google Maps" /></a>
		<?php endif;?>
		<?php if ($hideemail == '1' AND $email != ''):?>
			<a href="mailto:<?php echo $email ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/social/<?php echo $folder; ?>/email.png" alt="E-mail" /></a>
		<?php endif;?>
		<?php if ($hideemail == '1' AND $email == ''):?>
			<a href="mailto:no@way.com" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/social/<?php echo $folder; ?>/email.png" alt="E-mail" /></a>
		<?php endif;?>
		<?php if ($hiderss == '1' and $rss != '' OR $hiderss == '' and $rss != '' ):?>
			<a href="<?php echo $rss ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/social/<?php echo $folder; ?>/rss.png" alt="RSS" /></a>
		<?php endif;?>
		<?php if ($hiderss == '1' and $rss == '' OR $hiderss == '' and $rss == '' ):?>
			<a href="<?php bloginfo('rss2_url'); ?>" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/images/social/<?php echo $folder; ?>/rss.png" alt="RSS" /></a>
		<?php endif;?>
	
		</div><!--end icons--> 
		
	</div><!--end social--> <?php
}

/**
* Navigation
*
* @since 1.0
*/
function response_nav() {
	global $options, $themeslug; //call globals 
		
	?>
		
	<div class="container">
		<div class="row">

			<div class="twelve columns" id="menu">

			<div id="nav" class="twelve columns">
		    <?php wp_nav_menu( array(
		    'theme_location' => 'header-menu', // Setting up the location for the main-menu, Main Navigation.
		    'fallback_cb' => 'response_menu_fallback', //if wp_nav_menu is unavailable, WordPress displays wp_page_menu function, which displays the pages of your blog.
		    'items_wrap'      => '<ul id="nav_menu">%3$s</ul>',
			    )
			);
	    	?>
   			</div>
		</div>
	</div>
</div>
 <?php
}

/**
* Logo/Icons header element.
*
* @since 1.0.5
*/
function response_logo_icons_content() {
?>
	<div class="container">
		<div class="row">
		
			<div class="seven columns">
				
				<!--Begin response_header_sitename hook -->
					<?php response_header_sitename(); ?> 
				<!--End response_header_sitename hook -->
			
				
			</div>	
			
			<div id ="register" class="five columns">
				
			<!--Begin response_header_social_icons hook -->
				<?php response_header_social_icons(); ?> 
			<!-- End response_header_social_icons hook -->	
				
			</div>	
		</div><!--end row-->
	</div>

<?php
}

/**
* Banner
*
* @since 1.0.4
*/
function response_banner_content() {
global $themeslug, $options, $root; //Call global variables
$banner = $options->get($themeslug.'_banner'); //Calls the logo URL from the theme options
$url = $options->get($themeslug.'_banner_url');
$default = "$root/images/banner.jpg";

?>
	<div class="container">
		<div class="row">
		
			<div class="twelve columns">
			<div id="banner">
			
			<?php if ($banner != ""):?>
				<a href="<?php echo $url; ?>/"><img src="<?php echo stripslashes($banner['url']); ?>" alt="logo"></a>		
			<?php endif; ?>
			
			<?php if ($banner == ""):?>
				<a href="<?php echo $url; ?>/"><img src="<?php echo $default; ?>" alt="logo"></a>		
			<?php endif; ?>
			
			</div>		
			</div>	
		</div><!--end row-->
	</div>	

<?php
}

/**
* Sets up the header contact area
*
* @since 1.0
*/
function response_custom_header_element_content() { 
	global $themeslug, $options; ?>
	
	<div class="container">
		<div class="row">
		
			<div class="twelve columns">
				
				<?php echo stripslashes ($options->get($themeslug.'_custom_header_element')); 	?>
						
			</div>	
		</div><!--end row-->
	</div>	

<?php	
}


/**
* End
*/

?>