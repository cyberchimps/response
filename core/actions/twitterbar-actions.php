<?php
/**
* Twitterbar actions used by the CyberChimps Response Core Framework
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
* Response Twitterbar actions
*/
add_action( 'response_twitterbar_section', 'response_twitterbar_section_content' );

/**
* Retrieves the Twitterbar options and sets up the HTML
*/
function response_twitterbar_section_content() {
	global $options, $themeslug, $post; //call globals
	$root = get_template_directory_uri();
	
	if (is_page()) {
	$handle = get_post_meta($post->ID, 'twitter_handle' , true); 
	}
	else {
	$handle = $options->get($themeslug.'_blog_twitter');
	}?>
	<div class="row">
		<div id="twitterbar" class="twelve columns"><!--id="twitterbar"-->
			<div id="twittertext">
				<a href=" http://twitter.com/<?php echo $handle ; ?>" > <img src="<?php echo "$root/images/twitterbird.png" ?>" /> <?php echo $handle ;?> - </a><?php twitter_messages($handle); ?>
			</div>
		</div><!--end twitterbar--> 
	</div>
		<?php
}	

/**
* End
*/

?>