<?php
/**
* Hook wrappers used by the CyberChimps Response Core Framework
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
* Sets up sidebar layouts based on theme options. 
*
* @since 1.0
*/
function response_sidebar_init() {
	do_action ('response_sidebar_init');
}

/**
* Placed before the 404 message content (404.php).
*
* @since 1.0
*/
function response_before_404() {
	do_action('response_before_404');
}

/**
* 404 page template message content (404.php).
*
* @since 1.0
*/
function response_404() {
	do_action('response_404');
}

/**
* Placed after the 404 message content (404.php).
*
* @since 1.0
*/
function response_after_404() {
	do_action('response_after_404');
}

/**
* Placed before the archive template content (archive.php). 
*
* @since 1.0
*/
function response_before_archive() {
	do_action('response_before_archive');
}

/**
* Conditionals for various archive page title types (archive.php).
*
* @since 1.0
*/
function response_archive_title() {
	do_action('response_archive_title');
}

/**
* Archive template loop content (archive.php).
*
* @since 1.0
*/
function response_archive() {
	do_action('response_archive');
}

/**
* Placed after the archive template content (archive.php). 
*
* @since 1.0
*/
function response_after_archive() {
	do_action('response_after_archive');
}

/**
* Placed after the comment section content (comments.php). 
*
* @since 1.0
*/
function response_before_comments() {
	do_action('response_before_comments');
}

/**
* Creates the comment section (comments.php). 
*
* @since 1.0
*/
function response_comments() {
	do_action('response_comments');
}

/**
* Placed after the comment section (comments.php). 
*
* @since 1.0
*/
function response_after_comments() {
	do_action('response_after_comments');
}

/**
* For use before main page content. 
*
* @since 1.0
*/
function response_before_page_content() {
	do_action('response_before_page_content');
}

/**
* For use after main page content. 
*
* @since 1.0
*/
function response_after_page_content() {
	do_action('response_after_page_content');
}

/**
* Placed after post entry (sets up sidebar). 
*
* @since 1.0
*/
function response_after_entry() {
	do_action('response_after_entry');
}

/**
* For use before the loop. 
*
* @since 1.0
*/
function response_before_loop() {
	do_action('response_before_loop');
}

/**
* The loop. 
*
* @since 1.0
*/
function response_loop() {
	do_action('response_loop');
}

/**
* The loop (single.php). 
*
* @since 1.0
*/
function response_single_loop() {
	do_action('response_single_loop');
}

/**
* For use after the loop. 
*
* @since 1.0
*/
function response_after_loop() {
	do_action('response_after_loop');
}

/**
* For use before the footer content. 
*
* @since 1.0
*/
function response_before_footer() {
	do_action('response_before_footer_content');
}

/**
* Footer content. 
*
* @since 1.0
*/
function response_footer() {
	do_action('response_footer');
}

/**
* For use after the footer content. 
*
* @since 1.0
*/
function response_after_footer() {
	do_action('response_after_footer_content');
}

/**
* Contains the secondary footer elements. 
*
* @since 1.0
*/
function response_secondary_footer() { 
	do_action('response_secondary_footer');
}

/**
* Post byline content (single.php). 
*
* @since 1.0
*/
function response_single_post_byline() {
	do_action('response_single_post_byline');
}

/**
* Post byline content (archive.php). 
*
* @since 1.0
*/
function response_archive_post_byline() {
	do_action('response_archive_post_byline');
}


/**
* Calls post tags (single.php). 
*
* @since 1.0
*/
function response_single_post_tags() {
	do_action('response_single_post_tags');
}

/**
* Post byline content. 
*
* @since 1.0
*/
function response_post_byline() {
	do_action('response_post_byline');
}

/**
* Calls post tags. 
*
* @since 1.0
*/
function response_post_tags() {
	do_action('response_post_tags');
}

/**
* Calls post tags (archive.php). 
*
* @since 1.0
*/
function response_archive_post_tags() {
	do_action('response_archive_post_tags');
}

/**
* Post pagination. 
*
* @since 1.0
*/
function response_link_pages() {
	do_action('response_link_pages');
}

/**
* Creates admin edit link for pages and posts. 
*
* @since 1.0
*/
function response_edit_link() {
	do_action('response_edit_link');
}

/**
* Contains HTML, title, rel and meta elements. 
*
* @since 1.0
*/
function response_head_tag() {
	do_action('response_head_tag');
}

/**
* Placed after closing HEAD tag, contains font function. 
*
* @since 1.0
*/
function response_after_head_tag() {
	do_action('response_after_head_tag');
}

/**
* For adding content before the main header content. 
*
* @since 1.0
*/
function response_before_header() {
	do_action('response_before_header');
}

/**
* For adding content after the main header content. 
*
* @since 1.0
*/
function response_after_header() {
	do_action('response_after_header');
}

/**
* Sitename/logo content. 
*
* @since 1.0
*/
function response_header_sitename() {
	do_action('response_header_sitename');
}

/**
* Site description. 
*
* @since 1.0
*/
function response_header_site_description() {
	do_action('response_header_site_description');
}

/**
* Header social icon section. 
*
* @since 1.0
*/
function response_header_social_icons() {
	do_action('response_header_social_icons');
}

/**
* Site menu. 
*
* @since 1.0
*/
function response_navigation() {
	do_action('response_navigation');
}

/**
* Index pagination. 
*
* @since 1.0
*/
function response_pagination() { 
	do_action('response_pagination');
}

/**
* Post page pagination. 
*
* @since 1.0
*/
function response_links_pages() { 
	do_action('response_links_pages');
}

/**
* Next/Prev post links for single.php. 
*
* @since 1.0
*/
function response_post_pagination() { 
	do_action('response_post_pagination');
}

/**
* Sets up the page section for page.php. 
*
* @since 1.0
*/
function response_page_section() {
	do_action('response_page_section');
}

/**
* Placed before the search result content. 
*
* @since 1.0
*/
function response_before_search() {
	do_action('response_before_search');
}

/**
* Sets up the search result content. 
*
* @since 1.0
*/
function response_search() {
	do_action('response_search');
}

/**
* Placed after the search result content. 
*
* @since 1.0
*/
function response_after_search() {
	do_action('response_after_search');
}

/**
* Generates the lite version of the page Feature slider. 
*
* @since 1.0
*/
function response_blog_slider() {
	do_action('response_blog_slider');
}

/**
* Generates the lite version of the blog Feature slider. 
*
* @since 1.0
*/
function response_page_slider() {
	do_action('response_page_slider');
}

/**
* Generates the before content sidebar. 
*
* @since 1.0
*/
function response_before_content_sidebar() {
	do_action ('response_before_content_sidebar');
}

/**
* Generates the after content sidebar. 
*
* @since 1.0
*/
function response_after_content_sidebar() {
	do_action ('response_after_content_sidebar');
}

/**
* Index content. 
*
* @since 1.0
*/
function response_index() {
	do_action ('response_index');
}

/**
* Postbar. 
*
* @since 1.0
*/
function response_post_bar() {
	do_action ('response_post_bar');
}

/**
* Logo/Register header element. 
*
* @since 1.0
*/
function response_logo_register() {
	do_action('response_logo_register');
}

/**
* Logo/Icons header element
*
* @since 1.0
*/
function response_logo_icons() {
	do_action('response_logo_icons');
}

/**
* Banner header element
*
* @since 1.0
*/
function response_banner() {
	do_action('response_banner');
}

/**
* Custom HTML header element
*
* @since 1.0
*/
function response_custom_header_element() {
	do_action('response_custom_header_element');
}

/**
* Callout Section element
*
* @since 1.0
*/
function response_callout_section() {
	do_action('response_callout_section');
}


/**
* End
*/

?>