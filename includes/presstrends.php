<?php

// Add PressTrends Option
add_action('admin_menu', 're_presstrends_theme_menu');

function re_presstrends_theme_menu() {
	add_theme_page('PressTrends', 'PressTrends', 'manage_options', 're_presstrends_theme_options', 're_presstrends_theme_options');
}

function re_presstrends_theme_options() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
?>
	<form action="options.php" method="post">
	    <?php settings_fields('presstrends_theme_opt'); ?>
	    <?php do_settings_sections('presstrends_top'); ?>
	    <p class="submit">
	        <input name="Submit" type="submit" value="<?php esc_attr_e('Update'); ?>" />
	    </p>
	</form>
<?php
}

// PressTrends Option Settings
add_action('admin_init', 're_presstrends_theme_init');

function re_presstrends_theme_init(){
	register_setting( 'presstrends_theme_opt', 'presstrends_theme_opt');
	add_settings_section('presstrends_top', '', 're_presstrends_top_text', 'presstrends_top');
	add_settings_field('presstrends_opt_in', 'Activate PressTrends?', 're_presstrends_opt_string', 'presstrends_top', 'presstrends_top');
}

// PressTrends Section Text
function re_presstrends_top_text() {
    echo '<p style="width:190px;float:left;"><img src="http://presstrends.io/images/logo.png"/></p><p style="width:500px;float:left;color:#777;padding-top:2px;"><b>PressTrends</b> helps theme authors build better themes and provide awesome support by retrieving aggregated stats. PressTrends also provides a <a href="http://wordpress.org/extend/plugins/presstrends/" title="PressTrends Plugin for WordPress" target="_blank">plugin</a> that delivers stats on how your site is performing against the web and similar sites like yours. <a href="http://presstrends.io" title="PressTrends" target="_blank">Learn more…</a></p>';
}

// PressTrends Opt-In Option
function re_presstrends_opt_string() {
    $current_key = get_option('presstrends_theme_opt');
    $opt = $current_key['activated'];
	if($opt == 'on') {
	      echo "<input id='presstrends_opt_in' name='presstrends_theme_opt[activated]' checked type='checkbox' />";
	} else {
	      echo "<input id='presstrends_opt_in' name='presstrends_theme_opt[activated]' type='checkbox' />";
	}
}

// Add PressTrends Pointer
function re_be_password_pointer_enqueue( $hook_suffix ) {
	$enqueue = false;

	$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );

	if ( ! in_array( 'activatepresstrends', $dismissed ) ) {
		$enqueue = true;
		add_action( 'admin_print_footer_scripts', 're_be_password_pointer_print_admin_bar' );
	}

	if ( $enqueue ) {
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
	}
}
add_action( 'admin_enqueue_scripts', 're_be_password_pointer_enqueue' );

function re_be_password_pointer_print_admin_bar() {
	$pointer_content  = '<h3>' . 'Activate PressTrends' . '</h3>';
	$pointer_content .= '<p>' . 'Help theme authors build better themes and provide awesome support by retrieving aggregated stats.' . '</p>';

?>
<script type="text/javascript">
//<![CDATA[
jQuery(document).ready( function($) {
	$('#menu-appearance').pointer({
		content: '<?php echo $pointer_content; ?>',
		position: 'bottom',
		pointerWidth: 300,
		close: function() {
			$.post( ajaxurl, {
					pointer: 'activatepresstrends',
					action: 'dismiss-wp-pointer'
			});
		}
	}).pointer('open');
});
//]]>
</script>
<?php
}

// Add Presstrends API
function re_presstrends() {

    // Add your PressTrends and Theme API Keys
    $api_key = 'zwhgyc1lnt56hki8cpwobb47bblas4er226b';
    $auth = 'cfbu300xh4uq7o584ne59vwluhht0rign';

    // NO NEED TO EDIT BELOW
    $data = get_transient( 'presstrends_data' );
    if (!$data || $data == ''){
        $api_base = 'http://api.presstrends.io/index.php/api/sites/add/auth/';
        $url = $api_base . $auth . '/api/' . $api_key . '/';
        $data = array();
        $count_posts = wp_count_posts();
        $count_pages = wp_count_posts('page');
        $comments_count = wp_count_comments();
        if ( function_exists('get_custom_header')) {
	        $theme_data = wp_get_theme();
	       } 
	    else {
		       $theme_data = get_theme_data(get_stylesheet_directory() . '/style.css');	
		}       
        $plugin_count = count(get_option('active_plugins'));
        $all_plugins = get_plugins();
        foreach($all_plugins as $plugin_file => $plugin_data) {
            $plugin_name .= $plugin_data['Name'];
            $plugin_name .= '&';
        }
        $data['url'] = stripslashes(str_replace(array('http://', '/', ':' ), '', site_url()));
        $data['posts'] = $count_posts->publish;
        $data['pages'] = $count_pages->publish;
        $data['comments'] = $comments_count->total_comments;
        $data['approved'] = $comments_count->approved;
        $data['spam'] = $comments_count->spam;
        $data['theme_version'] = $theme_data['Version'];
        $data['theme_name'] = $theme_data['Name'];
        $data['site_name'] = str_replace( ' ', '', get_bloginfo( 'name' ));
        $data['plugins'] = $plugin_count;
        $data['plugin'] = urlencode($plugin_name);
        $data['wpversion'] = get_bloginfo('version');
        foreach ( $data as $k => $v ) {
            $url .= $k . '/' . $v . '/';
        }
        $response = wp_remote_get( $url );
        set_transient('presstrends_data', $data, 60*60*24);
    }
}

$current_key = get_option('presstrends_theme_opt');
$opt = $current_key['activated'];
if($opt == 'on') {
    add_action('admin_init', 're_presstrends');
}

?>