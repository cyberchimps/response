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

add_action ('response_blog_slider_lite', 'response_slider_lite_content' );
add_action ('response_page_slider_lite', 'response_slider_lite_content' );

/**
* Lite slider functions
*/
function response_blog_slider_lite_content() {

/* Call globals. */	

	global $themename, $themeslug, $options, $wp_query, $post, $slider_default;

/* End globals. */

/* Define variables. */	

        $tmp_query = $wp_query; 
		$category = $options->get($themeslug.'_slider_category');
		$root = get_template_directory_uri();
		
/* Define blog category */

	if ($category == 'all' OR $category == 'All' OR $category == '') {
		$blogcategory = '';
	}
	
	else {
		$blogcategory = $category;
	}
	
/* End blog category */
		
    query_posts('category_name='.$blogcategory.'&showposts=50');
    	
	    if (have_posts()) :
	    	$out = "<div id='orbitDemo'>"; 
	    	$i = 0;
	    	
	    	if ($options->get($themeslug.'_slider_posts_number') == '')
	    	$no = '5';
	    	else $no = $options->get($themeslug.'_slider_posts_number');

	    	while (have_posts() && $i<$no) : 
	    	
	    		the_post(); 
	    		
	    		$postimage 	= get_post_meta($post->ID, 'slider_image' , true);
	    		$text 		= get_post_meta($post->ID, 'slider_text' , true);
	    		$permalink 	= get_permalink();
	    		$thetitle	= get_the_title(); 
	    		
	    	/* Controls slide image and thumbnails */

	    	if ($postimage != '' ){
	    		$image = $postimage;
	    	}
	    	
	    	else {
	    		$image = $slider_default;
	    	}

	    		
	    	/* Markup for slides */

	    	$out .= "<a href='$link' $caption>
	    				<img src='$image' width='$imgwidth' alt='Slider' />
	    						<span class='orbit-caption' id='htmlCaption$i'>$title <br /> $text</span>
	    				</a>
	    			";

	    	/* End slide markup */	
	    	 
	      	$i++;
	      	endwhile;
	      	$out .= "</div>";
	    endif; 
	    
	    $wp_query = $tmp_query;

	    	   
	    if ($options->get($themeslug.'_slider_delay') == '')
	    	$delay = '3500';
	    else $delay = $options->get($themeslug.'_slider_delay');
	  
	    if ($options->get($themeslug.'hide_slider_navigation') != '0' OR $options->get($themeslug.'hide_slider_navigation') == '' ) {
	    	$navigation = 'true';
	    }
	    else {
	    
	     $navigation = 'false'; 
	    	echo '<style type="text/css">';
			echo ".nivo-controlNav {display: none;}";
			echo ".slider_nav {display: none;}";
			echo '#slider-wrapper {margin-bottom: 20px;}';
			echo '</style>';
	    
	    }
	    
	    wp_reset_query();
/* Begin NivoSlider javascript */ 
    
    $out .= <<<OUT
<script type="text/javascript">
   $(window).load(function() {
    $('#orbitDemo').orbit({
         animation: '$animation',
         advanceSpeed: $sliderdelay,
         captionAnimation: 'slideOpen',		// fade, slideOpen, none
         captionAnimationSpeed: 800,  
         bullets: $dots,
     });
     });
</script>
OUT;

/* End NivoSlider javascript */ 

echo $out;
/* END */ 



?>
<div class="slider_nav"></div>
<?php

}

/**
* End
*/

?>