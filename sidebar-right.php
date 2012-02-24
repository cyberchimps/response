<div id="sidebar_right">
	<div id="sidebar240">

    <?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Sidebar Right')) : else : ?>
    
        <!-- All this stuff in here only shows up if you DON'T have any widgets active in this zone -->
    
		<div class="widget-container">    
		<h2 class="widget-title">Sidebar Right</h2>
    		<ul>
				<li>Thank you for using Response!.</li>
				<li>&nbsp;</li>
				<li>We designed Response to be as user friendly as possible, but if you do run into trouble we provide a <a href="http://cyberchimps.com/forum">support forum</a>, and <a href="http://www.cyberchimps.com/response/docs/">precise documentation</a>.</li>
				<li>&nbsp;</li>
				<li>(To remove this Widget login to your admin account, go to Appearance, then Widgets and drag new widgets into Sidebar Widgets)</li>
			</ul>
    	</div>
		
		<div class="widget-container">   
    	<h2 class="widget-title">Archives</h2>
    	<ul>
    		<?php wp_get_archives('type=monthly'); ?>
    	</ul>
    	</div>
        
       <div class="widget-container">   
        <h2 class="widget-title">Categories</h2>
        <ul>
    	   <?php wp_list_categories('show_count=1&title_li='); ?>
        </ul>
        </div>
        
    	<div class="widget-container">   
    	<h2 class="widget-title">WordPress</h2>
    	<ul>
    		<?php wp_register(); ?>
    		<li><?php wp_loginout(); ?></li>
    		<li><a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress</a></li>
    		<?php wp_meta(); ?>
    	</ul>
    	</div>
	
	<?php endif; ?>
	</div><!--end sidebar150-->
</div><!--end sidebar_right-->