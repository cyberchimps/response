<?php
/**
* Callout section actions used by the CyberChimps Response Core Framework
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
* @package Pro
* @since 1.0
*/

/**
* Pro callout actions
*/
add_action ( 'response_callout_section', 'response_callout_section_content' );

/**
* Retrieves the Callout Section options and sets up the HTML
*
* @since 1.0
*/
function response_callout_section_content() {

	global $options, $themeslug, $post; //call globals
	$root = get_template_directory_uri();  

/* Define variables. */	

	if (is_page()) {
		$tcolor = get_post_meta($post->ID, 'custom_callout_text_color' , true);
		$text = get_post_meta($post->ID, 'callout_text' , true);
		}
	
	else {
		$tcolor = $options->get($themeslug.'_blog_callout_text_color');
		$text = $options->get($themeslug.'_blog_callout_text');
		}
	
/* End variable definition. */	

/* Echo custom text color. */

	if ($tcolor != "") {
		echo '<style type="text/css" media="screen">';
		echo "#callout_text {color: $tcolor ;}";
		echo '</style>';
	}
			
/* End CSS. */	

/* Define Callout text. */	

	if ($text == '') {
		$callouttext = 'CyberChimps gives you the tools to turn WordPress into a modern feature rich Content Management System (CMS)';
	}
	else {
		$callouttext = $text;
	}
	
/* End define Callout title. */	

?>
	<div class="row">
		<div id="calloutwrap"  class="twelve columns">
			<div id="callout_text">
				<h2 class="callout_title" ><?php echo $callouttext ?></h2>
			</div>
		</div>
	</div>

<?php
	
}

/**
* End
*/

?>