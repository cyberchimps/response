<?php

// Add scripts and stylesheet

function enqueue_store_styles() {
 
 	global $themename, $themeslug, $options;
 	wp_register_style($themeslug.'storecss', get_template_directory_uri(). '/core/classy-options/themes.css');

      
    wp_enqueue_style($themeslug.'storecss');  
}

// Add page to the menu
function cyberchimps_store_add_menu() {
	$page = add_theme_page('CyberChimps Store Page', 'CyberChimps Themes', 'administrator', 'themes', 'cyberchimps_store_page_init');
	
	
  add_action('admin_print_styles-' . $page, 'enqueue_store_styles');  

}

add_action('admin_menu', 'cyberchimps_store_add_menu');

// Create the page
function cyberchimps_store_page_init() {
	$root = get_template_directory_uri(); 
?>

<div id="contain">
	<div id="themesheader">
		<a href="http://cyberchimps.com" target="_blank"><img src="<?php echo $root ;?>/images/themes/cyberchimps.png" /></a>
		<br />
		<span class="pro">Professional WordPress Themes</span>
		<br /><br />
		<div class="menu">
		<ul>
			<li><a href="http://cyberchimps.com/store/" target="_blank">CyberChimps Store</a></li>
			<li><a href="http://cyberchimps.com/support" target="_blank">Support</a></li>
			<li><a href="http://cyberchimps.com/ifeaturepro/docs/">Documentation</a></li>
			<li><a href="http://cyberchimps.com/forum/" target="_blank">Forum</a></li>
			<li><a href="http://twitter.com/#!/cyberchimps" target="_blank">Twitter</a></li>
			<li><a href="http://www.facebook.com/CyberChimps" target="_blank">Facebook</a></li>
			<li><a href="http://cyberchimpspro.com/" target="_blank">CyberChimps Pro</a></li>
		</ul>
	</div>
	<div style="clear: both;"></div>
	</div>
	
	<div id="container">
	
	<div class="theme_images">
		<a href="http://cyberchimps.com/ifeaturepro/" target="_blank"><img src="<?php echo $root ;?>/images/themes/ifeaturepro.png" /></a>
	</div>
	<div class="theme_desciptions">
		<div class="theme_titles"><a href="http://cyberchimps.com/ifeaturepro/" target="_blank">iFeature Pro</a></div>
		<br />
		iFeature Pro 4 is one of the most advanced personal content management WordPress Themes in the world and now offers intuitive theme options which make using iFeature Pro even more personal and fun than ever before.
		<br /><br />
		iFeature Pro is an advanced WordPress theme released under the GNU GPL v2. iFeature Pro is optimized for Chrome, Safari, FireFox, and Internet Explorer 9 (we do not support Internet Explorer 6). <br /><br />
		<div class="buy"><a href="http://cyberchimps.com/ifeaturepro/" target="_blank">Buy iFeature Pro</a></div>
	</div>
		
	<div class="theme_images">
		<a href="http://cyberchimps.com/businesspro/" target="_blank"><img src="<?php echo $root ;?>/images/themes/bizpro.png" /></a>
	</div>
	<div class="theme_desciptions">
		<div class="theme_titles"><a href="http://cyberchimps.com/businesspro/" target="_blank">Business Pro</a></div>
		<br />
		Business Pro is a Professional WordPress Theme. Business Pro gives your company the tools to turn WordPress into a modern feature rich Content Management System (CMS).
		<br /><br />
		Business Pro offers intuitive options enabling any business to use WordPress as their content management system. Business Pro offers designers and developers Custom CSS, Import / Export options, and support for CSS3, and HTML5. Even if you are not a designer, Business Pro is built to be business friendly.
		<br /><br />
		<div class="buy"><a href="http://cyberchimps.com/businesspro" target="_blank">Buy Business Pro</a></div>
	</div>
	
	<div class="theme_images">
		<a href="http://cyberchimps.com/responsepro/" target="_blank"><img src="<?php echo $root ;?>/images/themes/responsepro.png" /></a>
	</div>
	<div class="theme_desciptions">
		<div class="theme_titles"><a href="http://cyberchimps.com/responsepro/" target="_blank">Response Pro</a></div>
		<br />
	Response Pro is a Professional WordPress Theme Framework that enables WordPress to be used as an advanced content management system featuring Drag and Drop Header, Page and Blog Elements.
		<br /><br />
		Response Pro is designed for any WordPress user looking for a clean easy to use WordPress theme, web and graphic designers who know CSS looking to design child themes, and developers who want to create their own WordPress themes. It uses the same CyberChimps Response Core Framework that we used to develop iFeature Pro, and unlike other theme frameworks that cost hundreds of dollars, Response Pro is just $25, and can be used on as many websites or projects as you like.<br /><br />
		<div class="buy"><a href="http://cyberchimps.com/responsepro/" target="_blank">Buy Response Pro</a></div>
	</div><br />
	
	<div class="theme_images">
		<a href="http://cyberchimps.com/neuropro/" target="_blank"><img src="<?php echo $root ;?>/images/themes/neuropro.png" /></a>
	</div>
	<div class="theme_desciptions">
		<div class="theme_titles"><a href="http://cyberchimps.com/neuropro/" target="_blank">Neuro Pro</a></div>
		<br />
		Neuro Pro features intuitive design options allowing anyone the ability to easily customize the look and feel of WordPress. We also included several popular color scheme to choose from, as well as beautiful modern background images. Neuro Pro also offers designers and developers Custom CSS, Import / Export options, and support for CSS3 and HTML5.
		<br /><br />
		Neuro Pro is a next generation WordPress theme released under the GNU GPL v2. Neuro Pro is optimized for Chrome, Safari, FireFox, and Internet Explorer 9 (we do not support Internet Explorer 6).
		<br /> <br />
		<div class="buy"><a href="http://cyberchimps.com/neuropro/" target="_blank">Buy Neuro Pro</a></div>
	</div>

	</div>
</div>

<?php
}
