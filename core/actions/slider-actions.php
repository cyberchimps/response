<?php
/**
* Slider actions used by the CyberChimps Response Core Framework
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
* Response slider actions
*/

add_action ('response_blog_slider', 'response_slider_lite_content' );
add_action ('response_page_slider', 'response_slider_lite_content' );

/**
* Lite slider function
*/
function response_slider_lite_content() {

	global $themename, $themeslug, $options, $wp_query, $post, $slider_default, $root;
		
	if (is_page()) {
		$slide1 = get_post_meta($post->ID, 'page_slide_one_image' , true);
		$slide2 = get_post_meta($post->ID, 'page_slide_two_image' , true);
		$slide3 = get_post_meta($post->ID, 'page_slide_three_image' , true);
	
		$link1 = get_post_meta($post->ID, 'page_slide_one_url' , true);
		$link2 = get_post_meta($post->ID, 'page_slide_two_url' , true);
		$link3 = get_post_meta($post->ID, 'page_slide_three_url' , true);
	}
	
	else {
		$slide1source = $options->get($themeslug.'_blog_slide_one_image');
		$slide2source = $options->get($themeslug.'_blog_slide_two_image');
		$slide3source = $options->get($themeslug.'_blog_slide_three_image');
		
		$slide1 = $slide1source['url'];
		$slide2 = $slide2source['url'];
		$slide3 = $slide3source['url'];
	
		$link1 = $options->get($themeslug.'_blog_slide_one_url');
		$link2 = $options->get($themeslug.'_blog_slide_two_url');
		$link3 = $options->get($themeslug.'_blog_slide_three_url');

	}
?>
	<div class="row">
		<div id="orbitDemo">
			<a href="<?php echo $link1; ?>">
	   			<img src="<?php echo $slide1 ;?>" alt="Slider" />
	    	</a>
	    	<a href="<?php echo $link2; ?>">
	   			<img src="<?php echo $slide2 ;?>" alt="Slider" />
	    	</a>
	    	<a href="<?php echo $link3; ?>">
	   			<img src="<?php echo $slide3 ;?>" alt="Slider" />
	    	</a>
		</div>
	</div>

<script type="text/javascript">
	jQuery(document).ready(function ($) {
    $(window).load(function() {
    $('#orbitDemo').orbit({
         animation: 'horizontal-push',
         bullets: true,
     });
     });
     });
</script>

<?php

}

/**
* End
*/

?>