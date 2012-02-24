<div id="sidebar_left">
	<div id="sidebar240">

    <?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Sidebar Left')) : else : ?>
    
        <!-- All this stuff in here only shows up if you DON'T have any widgets active in this zone -->
    
		<div class="widget-container">      
		<h2 class="widget-title">Sidebar Left</h2>
    		<ul>
				<li>Thank you for using Response!.</li>
				<li>&nbsp;</li>
				<li>We designed Response to be as user friendly as possible, but if you do run into trouble we provide a <a href="http://cyberchimps.com/forum">support forum</a>, and <a href="http://www.cyberchimps.com/response/docs/">precise documentation</a>.</li>
				<li>&nbsp;</li>
				<li>(To remove this Widget login to your admin account, go to Appearance, then Widgets and drag new widgets into Sidebar Widgets)</li>
			</ul></ul>
    	</div>
		
		<div class="widget-container">     
		<h2 class="widget-title">Pages</h2>
    	<?php wp_list_pages('title_li=' ); ?>
    	</div>
    	
    	<div class="widget-container">
    	<h2 class="widget-title">Subscribe</h2>
    	<ul>
    		<li><a href="<?php bloginfo('rss2_url'); ?>">Entries (RSS)</a></li>
    		<li><a href="<?php bloginfo('comments_rss2_url'); ?>">Comments (RSS)</a></li>
    	</ul>
    	</div>
	
	<?php endif; ?>
	</div><!--end sidebar150-->
</div><!--end sidebar_left-->