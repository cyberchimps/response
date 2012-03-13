<?php 
/**
* Header template used by the CyberChimps Response Core Framework
*
* Authors: Tyler Cunningham, Trent Lapinski
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
	global $options, $themeslug; // call globals
?>
	<?php response_head_tag(); ?>

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?> <!-- wp_enqueue_script( 'comment-reply' );-->
<?php wp_head(); ?> <!-- wp_head();-->
	
</head><!-- closing head tag-->

<!--Begin response_after_head_tag hook-->
	<?php response_after_head_tag(); ?>
<!--End response_after_head_tag hook-->
	
<!--Begin response_before_header hook-->
	<?php response_before_header(); ?> 
<!--End response_before_header hook-->
			
<header>		
	<?php
		foreach(explode(",", $options->get('header_section_order')) as $fn) {
			if(function_exists($fn)) {
				call_user_func_array($fn, array());
			}
		}
	?>
</header>

<!--Begin response_after_header-->
	<?php response_after_header(); ?> 
<!--End response after_header hook-->