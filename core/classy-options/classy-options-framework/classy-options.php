<?php
global $options, $themeslug, $themenamefull;

class ClassyOptions {
	function __construct($id, $name = false) {
		$this->id = $id;
		$this->name = $name ? $name : $id;
		$this->options = array();
		$this->data = get_option($this->id);

		add_action( 'admin_init', array($this, 'admin_init') );
		add_action( 'admin_menu', array($this, 'admin_menu') );
	}

	function admin_menu() {
		global $themenamefull;
		
		$page = add_theme_page( $themenamefull.' Options', $themenamefull, 'edit_theme_options', $this->id, array( $this, 'render' ) );

		add_action( "admin_print_styles-$page", array($this, 'load_styles') );
		add_action( "admin_print_scripts-$page",  array($this, 'load_scripts') );

		add_action( "wp_before_admin_bar_render", array($this, 'add_admin_bar') );
	}

	function admin_init() {
		register_setting( $this->id, $this->id, array($this, 'validate_data') );
	}

	function load_styles() {
		wp_enqueue_style('admin-style', CLASSY_OPTIONS_FRAMEWORK_URL.'css/admin-style.css');
		wp_enqueue_style('color-picker', CLASSY_OPTIONS_FRAMEWORK_URL.'css/colorpicker.css');
		wp_enqueue_style('thickbox');
	}

	function load_scripts() {
		// Inline scripts from options-interface.php
		add_action('admin_head', array($this, 'admin_head'));
		
		// Enqueued scripts
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('thickbox');
		wp_enqueue_script('color-picker', CLASSY_OPTIONS_FRAMEWORK_URL.'js/colorpicker.js', array('jquery'));
		wp_enqueue_script('options-custom', CLASSY_OPTIONS_FRAMEWORK_URL.'js/options-custom.js', array('jquery'));
		wp_enqueue_script('theme-options-custom', get_template_directory_uri().'/library/js/theme-options-custom.js', array('jquery'));
		wp_enqueue_script('media-uploader', CLASSY_OPTIONS_FRAMEWORK_URL.'js/of-medialibrary-uploader.js', array('jquery'));
	}

	function add_admin_bar() {
		global $wp_admin_bar;

		$wp_admin_bar->add_menu( array(
			'parent' => 'appearance',
			'id' => $this->id,
			'title' => __( $this->name ),
			'href' => admin_url( 'themes.php?page=' . $this->id )
		));
	}

	function admin_head() {
		do_action( 'optionsframework_custom_scripts' );
	}

	function get($id) {
		$option = $this->find_option_by_id($id);
		return isset( $this->data[$id] ) ? $this->data[$id] :
			(( $option && isset($option['default'] )) ? $option['default'] : null);
	}

	function find_option_by_id($id) {
		foreach($this->options as $option) {
			if(isset($option['id']) && $option['id'] == $id) {
				return $option;
			}
		}
		return false;
	}
	function add( $option ) {
		$this->options[] = $option;
	}

	function render() {
		global $themenamefull;
		settings_errors(); ?>
<div class="wrap">

	<div id="of_container">
		<form action="options.php" method="post" enctype="multipart/form-data">
			<?php settings_fields($this->id); ?>

			<div id="header">
				<div class="logo">
				<h2><?php echo $themenamefull; ?> Options</h2>
				</div>
				<div class="upgradepro">
				<a href="http://cyberchimps.com/responsepro/" target="_blank">Upgrade to Response Pro for just $25</a>
				</div>
				<div class="clear"></div>
					<p class="submit">
						<input type="submit" class="button-primary" name="update" value="<?php esc_attr_e( 'Save Options' ); ?>" />
					</p>
				<div class="menu">
					<ul class="buttons">
						<li><a href="http://cyberchimps.com/" target="_blank">CyberChimps</a></li>
						<li><a href="http://cyberchimps.com/store/" target="_blank">Store</a></li>
						<li><a href="http://cyberchimps.com/support" target="_blank">Support</a></li>
						<li><a href="http://cyberchimps.com/response/docs/" target="_blank">Documentation</a></li>
						<li><a href="http://cyberchimps.com/forum/" target="_blank">Forum</a></li>
						<li><a href="http://twitter.com/#!/cyberchimps" target="_blank">Twitter</a></li>
						<li><a href="http://facebook.com/cyberchimps/" target="_blank">Facebook</a></li>
					</ul>
				</div>

			</div>
			<div id="main">
				<?php $return = $this->fields(); ?>
				
				<div id="content">
					<?php echo $return[0]; /* Settings */ ?>
				</div>
				<div class="clear"></div>
			</div>
			<div class="of_admin_bar">
			    <div id="top"><a href='#TOP'><img src="<?php echo get_template_directory_uri() ;?>/images/options/top.png" /></a></div>
				<input type="submit" class="reset-button button-secondary" name="reset" value="<?php esc_attr_e( 'Restore Defaults' ); ?>" onclick="return confirm( '<?php print esc_js( __( 'Click OK to reset. Any theme settings will be lost!' ) ); ?>' );" />
				<div class="chimps"><a href="http://cyberchimps.com/" target="_blank"><img src="<?php echo get_template_directory_uri() ;?>/images/options/cyberchimpsmini.png" /></a></div>
			</div>
			<div class="clear"></div>
		</form>
	</div> <!-- / #of_container -->  
</div> <!-- / .wrap -->

<?php
	}
	function default_data() {
		$ret = array();
		foreach($this->options as $option) {
			if(isset($option['id']) && isset($option['default'])) {
				$ret[$option['id']] = $option['default'];
			}
		}
		return $ret;
	}
	function validate_data( $input ) {

		/*
		 * Restore Defaults.
		 *
		 * In the event that the user clicked the "Restore Defaults"
		 * button, the options defined in the theme's options.php
		 * file will be added to the option for the active theme.
		 */

		if ( isset( $_POST['reset'] ) || ! isset( $_POST['update'] ) ) {
			add_settings_error( $this->id, 'restore_defaults', __( 'Default options restored.', 'optionsframework' ), 'updated fade' );
			return $this->default_data();
		}

		if( isset( $_POST['import' ] ) ) {
			if( trim( $_POST['import' ] ) ) {
				$string = stripslashes( trim( $_POST['import'] ) );
				$try = unserialize( $string );
				if($try) {
					add_settings_error( $this->id, 'import', __( 'Options Imported', 'optionsframework' ), 'updated fade' );
					return $try;
				} else {
					add_settings_error( $this->id, 'import', __( 'Invalid Data for Import', 'optionsframework' ), 'updated fade' );
				}
			}
		}
		/*
		 * Update Settings.
		 */

		if ( isset( $_POST['update'] ) ) {
			$clean = array();
			foreach ( $this->options as $option ) {

				if ( ! isset( $option['id'] ) ) {
					continue;
				}

				if ( ! isset( $option['type'] ) ) {
					continue;
				}

				$id = preg_replace( '/\W/', '', strtolower( $option['id'] ) );

				// Set checkbox to false if it wasn't sent in the $_POST
				if ( 'checkbox' == $option['type'] && ! isset( $input[$id] ) ) {
					$input[$id] = '0';
				}

				// Set each item in the multicheck to false if it wasn't sent in the $_POST
				if ( 'multicheck' == $option['type'] && ! isset( $input[$id] ) ) {
					foreach ( $option['options'] as $key => $value ) {
						$input[$id][$key] = '0';
					}
				}

				if ( 'upload' == $option['type'] ) {
					if ($_FILES[$id]['name'] != '') {
						$overrides = array('test_form' => false); 
						$file = wp_handle_upload($_FILES[$id], $overrides);
						$clean[$id] = $file;
					} 

					elseif(isset($_POST["{$id}_text"]) && $_POST["{$id}_text"] != '') {
						$input['file'] = array('url' => $_POST["{$id}_text"]);
						$clean[$id] = array('url' => $_POST["{$id}_text"]);				    } 

					else {
						$clean[$id] = null;
					}
				}

				// For a value to be submitted to database it must pass through a sanitization filter
				if ( has_filter( 'cof_sanitize_' . $option['type'] ) && isset( $input[$id] ) ) {
					$clean[$id] = apply_filters( 'cof_sanitize_' . $option['type'], $input[$id], $option );
				}

			}

			add_settings_error( $this->id, 'save_options', __( 'Options saved.', 'optionsframework' ), 'updated fade' );
			return $clean;
		}
	}

	function fields() {

		global $allowedtags;

		$option_name = $this->id;

		$settings = $this->data;
		$options = $this->options;

		$counter = 0;
		$menu = '';
		$output = '';

		foreach ($options as $value) {

			$counter++;
			$val = '';
			$select_value = '';
			$checked = '';

			// Wrap all options
			if ( ($value['type'] != "heading") && ($value['type'] != "info" && $value['type'] != "subsection" && $value['type'] != "subsection_end") && $value['type'] != "open_outersection" && $value['type'] != "close_outersection" ) {

				// Keep all ids lowercase with no spaces
				$value['id'] = isset( $value['id'] ) ? preg_replace('/\W/', '', strtolower($value['id']) ) : "";

				$id = 'section-' . $value['id'];

				$class = 'section ';
				if ( isset( $value['type'] ) ) {
					$class .= ' section-' . $value['type'];
				}
				if ( isset( $value['class'] ) ) {
					$class .= ' ' . $value['class'];
				}

				$output .= '<div id="' . esc_attr( $id ) .'" class="' . esc_attr( $class ) . '">'."\n";
				$output .= '<h3 class="heading">' . esc_html( $value['name'] ) . '</h3>' . "\n";
				$output .= '<div class="option">' . "\n" . '<div class="controls">' . "\n";
			 }
			
			// Set default value to $val
			if ( isset( $value['default']) ) {
				$val = $value['default'];
			}
			
			// If the option is already saved, ovveride $val
			if ( ($value['type'] != "heading") && ($value['type'] != "info" && $value['type'] != "subsection" && $value['type'] != "subsection_end") && $value['type'] != "open_outersection" && $value['type'] != "close_outersection" ) {
				if ( isset($settings[($value['id'])]) ) {
						$val = $settings[($value['id'])];
						// Striping slashes of non-array options
						if (!is_array($val)) {
							$val = stripslashes($val);
						}
				}
			}
			// If there is a description save it for labels
			$explain_value = '';
			if ( isset( $value['desc'] ) ) {
				$explain_value = $value['desc'];
			}
									  
			switch ( $value['type'] ) {
			
			// Basic text input
			case 'text':
				$output .= '<input id="' . esc_attr( $value['id'] ) . '" class="of-input" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" type="text" value="' . esc_attr( $val ) . '" />';
			break;
			
			// Textarea
			case 'textarea':
				$cols = '8';
				$ta_value = '';
				
				if(isset($value['options'])){
					$ta_options = $value['options'];
					if(isset($ta_options['cols'])){
						$cols = $ta_options['cols'];
					} else { $cols = '8'; }
				}
				
				$val = stripslashes( $val );
				
				$output .= '<textarea id="' . esc_attr( $value['id'] ) . '" class="of-input" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" cols="'. esc_attr( $cols ) . '" rows="8">' . esc_textarea( $val ) . '</textarea>';
			break;
			
			// Select Box
			case 'select':
				$output .= '<select class="of-input" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" id="' . esc_attr( $value['id'] ) . '">';
				
				foreach ($value['options'] as $key => $option ) {
					$selected = '';
					 if( $val != '' ) {
						 if ( $val == $key) { $selected = ' selected="selected"';} 
					}
					 $output .= '<option'. $selected .' value="' . esc_attr( $key ) . '">' . esc_html( $option ) . '</option>';
				 } 
				 $output .= '</select>';
			break;

			
			// Radio Box
			case "radio":
				$name = $option_name .'['. $value['id'] .']';
				foreach ($value['options'] as $key => $option) {
					$id = $option_name . '-' . $value['id'] .'-'. $key;
					$output .= '<input class="of-input of-radio" type="radio" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" value="'. esc_attr( $key ) . '" '. checked( $val, $key, false) .' /><label for="' . esc_attr( $id ) . '">' . esc_html( $option ) . '</label><br />';
				}
			break;
			
			// Image Selectors
			case "images":
				$name = $option_name .'['. $value['id'] .']';
				foreach ( $value['options'] as $key => $option ) {
					$selected = '';
					$checked = '';
					if ( $val != '' ) {
						if ( $val == $key ) {
							$selected = ' of-radio-img-selected';
							$checked = ' checked="checked"';
						}
					}
					$output .= '<input type="radio" id="' . esc_attr( $value['id'] .'_'. $key) . '" class="of-radio-img-radio" value="' . esc_attr( $key ) . '" name="' . esc_attr( $name ) . '" '. $checked .' />';
					$output .= '<div class="of-radio-img-label">' . esc_html( $key ) . '</div>';
					$output .= '<img src="' . esc_url( $option ) . '" alt="' . $option .'" class="of-radio-img-img' . $selected .'" onclick="document.getElementById(\''. esc_attr($value['id'] .'_'. $key) .'\').checked=true;" />';
				}
			break;
			
			// Checkbox
			case "checkbox":
				$output .= '<input id="' . esc_attr( $value['id'] ) . '" class="checkbox of-input" type="checkbox" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" '. checked( $val, 1, false) .' />';
				$output .= '<label class="explain" for="' . esc_attr( $value['id'] ) . '">' . wp_kses( $explain_value, $allowedtags) . '</label>';
			break;
			
			// Multicheck
			case "multicheck":
				foreach ($value['options'] as $key => $option) {
					$checked = '';
					$label = $option;
					$option = preg_replace('/\W/', '', strtolower($key));

					$id = $option_name . '-' . $value['id'] . '-'. $option;
					$name = $option_name . '[' . $value['id'] . '][' . $option .']';

				    if ( isset($val[$option]) ) {
						$checked = checked($val[$option], 1, false);
					}

					$output .= '<input id="' . esc_attr( $id ) . '" class="checkbox of-input" type="checkbox" name="' . esc_attr( $name ) . '" ' . $checked . ' /><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label><br />';
				}
			break;
			
			// Color picker
			case "color":
				$output .= '<div id="' . esc_attr( $value['id'] . '_picker' ) . '" class="colorSelector"><div style="' . esc_attr( 'background-color:' . $val ) . '"></div></div>';
				$output .= '<input class="of-color" name="' . esc_attr( $option_name . '[' . $value['id'] . ']' ) . '" id="' . esc_attr( $value['id'] ) . '" type="text" value="' . esc_attr( $val ) . '" />';
			break; 
			
			// Uploader
			case "upload":
				// $output .= optionsframework_medialibrary_uploader( $value['id'], $val, null ); // New AJAX Uploader using Media Library	
				if(isset($val['url'])) {
					$output .= "Preview: " . "<img src='{$val['url']}' width='350px' height='50px'/><br/>";
				}
				$output .= " &nbsp;&nbsp;&nbsp;&nbsp; URL <input type='text' name='{$value['id']}_text' size='72' value='" . (isset($val['url']) ? $val['url'] : "") . "'/>";
				$output .= " or upload File: <input type='file' id='{$value['id']}' name='{$value['id']}'>";
			break;
			
			// Typography
			case 'typography':	
			
				$typography_stored = $val;
				
				// Font Size
				$output .= '<select class="of-typography of-typography-size" name="' . esc_attr( $option_name . '[' . $value['id'] . '][size]' ) . '" id="' . esc_attr( $value['id'] . '_size' ) . '">';
				for ($i = 9; $i < 71; $i++) { 
					$size = $i . 'px';
					$output .= '<option value="' . esc_attr( $size ) . '" ' . selected( $typography_stored['size'], $size, false ) . '>' . esc_html( $size ) . '</option>';
				}
				$output .= '</select>';
			
				// Font Face
				$output .= '<select class="of-typography of-typography-face" name="' . esc_attr( $option_name . '[' . $value['id'] . '][face]' ) . '" id="' . esc_attr( $value['id'] . '_face' ) . '">';
				
				$faces = ClassyOptionsSanitize::recognized_font_faces();
				foreach ( $faces as $key => $face ) {
					$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $typography_stored['face'], $key, false ) . '>' . esc_html( $face ) . '</option>';
				}			
				
				$output .= '</select>';	

				// Font Weight
				$output .= '<select class="of-typography of-typography-style" name="'.$option_name.'['.$value['id'].'][style]" id="'. $value['id'].'_style">';

				/* Font Style */
				$styles = ClassyOptionsSanitize::recognized_font_styles();
				foreach ( $styles as $key => $style ) {
					$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $typography_stored['style'], $key, false ) . '>'. $style .'</option>';
				}
				$output .= '</select>';

				// Font Color		
				$output .= '<div id="' . esc_attr( $value['id'] ) . '_color_picker" class="colorSelector"><div style="' . esc_attr( 'background-color:' . $typography_stored['color'] ) . '"></div></div>';
				$output .= '<input class="of-color of-typography of-typography-color" name="' . esc_attr( $option_name . '[' . $value['id'] . '][color]' ) . '" id="' . esc_attr( $value['id'] . '_color' ) . '" type="text" value="' . esc_attr( $typography_stored['color'] ) . '" />';

			break;
			
			// Background
			case 'background':
				
				$background = $val;
				
				// Background Color		
				$output .= '<div id="' . esc_attr( $value['id'] ) . '_color_picker" class="colorSelector"><div style="' . esc_attr( 'background-color:' . $background['color'] ) . '"></div></div>';
				$output .= '<input class="of-color of-background of-background-color" name="' . esc_attr( $option_name . '[' . $value['id'] . '][color]' ) . '" id="' . esc_attr( $value['id'] . '_color' ) . '" type="text" value="' . esc_attr( $background['color'] ) . '" />';
				
				// Background Image - New AJAX Uploader using Media Library
				if (!isset($background['image'])) {
					$background['image'] = '';
				}
				
				$output .= optionsframework_medialibrary_uploader( $value['id'], $background['image'], null, '',0,'image');
				$class = 'of-background-properties';
				if ( '' == $background['image'] ) {
					$class .= ' hide';
				}
				$output .= '<div class="' . esc_attr( $class ) . '">';
				
				// Background Repeat
				$output .= '<select class="of-background of-background-repeat" name="' . esc_attr( $option_name . '[' . $value['id'] . '][repeat]'  ) . '" id="' . esc_attr( $value['id'] . '_repeat' ) . '">';
				$repeats = of_recognized_background_repeat();
				
				foreach ($repeats as $key => $repeat) {
					$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['repeat'], $key, false ) . '>'. esc_html( $repeat ) . '</option>';
				}
				$output .= '</select>';
				
				// Background Position
				$output .= '<select class="of-background of-background-position" name="' . esc_attr( $option_name . '[' . $value['id'] . '][position]' ) . '" id="' . esc_attr( $value['id'] . '_position' ) . '">';
				$positions = of_recognized_background_position();
				
				foreach ($positions as $key=>$position) {
					$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['position'], $key, false ) . '>'. esc_html( $position ) . '</option>';
				}
				$output .= '</select>';
				
				// Background Attachment
				$output .= '<select class="of-background of-background-attachment" name="' . esc_attr( $option_name . '[' . $value['id'] . '][attachment]' ) . '" id="' . esc_attr( $value['id'] . '_attachment' ) . '">';
				$attachments = of_recognized_background_attachment();
				
				foreach ($attachments as $key => $attachment) {
					$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $background['attachment'], $key, false ) . '>' . esc_html( $attachment ) . '</option>';
				}
				$output .= '</select>';
				$output .= '</div>';
			
			break;  
			
			// Info
			case "info":
				$class = 'section';
				if ( isset( $value['type'] ) ) {
					$class .= ' section-' . $value['type'];
				}
				if ( isset( $value['class'] ) ) {
					$class .= ' ' . $value['class'];
				}

				$output .= '<div class="' . esc_attr( $class ) . '">' . "\n";
				if ( isset($value['name']) ) {
					$output .= '<h3 class="heading">' . ( $value['name'] ) . '</h3>' . "\n";
				}
				if ( isset( $value['desc'] ) ) {
					$output .= wpautop( wp_kses( $value['desc'], $allowedtags) ) . "\n";
				}
				$output .= '<div class="clear"></div></div>' . "\n";
			break;                       

			case "export":
				$output .= "<textarea rows='10'>" . esc_html(serialize($settings)) . "</textarea>";
				break;
			case "import":
				$output .= "<textarea name='import' rows='10'></textarea>";
				break;
			// Heading for Navigation
			case "heading":
				if($counter >= 2){
				   $output .= '</div></div>'."\n";
				}
				
				$jquery_click_hook = preg_replace('/\W/', '', strtolower($value['name']) );
				$jquery_click_hook = "of-option-" . $jquery_click_hook;
				$menu .= '<li>';
				$icon = isset($value['icon']) ? " style=\"background-image: url({$value['icon']}); background-position: 8px center; background-repeat: no-repeat; \"" : "";
				$menu .= '<a id="'.  esc_attr( $jquery_click_hook ) . '-tab" title="' . esc_attr( $value['name'] ) . '" href="' . esc_attr( '#'.  $jquery_click_hook ) . '"' . $icon . '>' . esc_html( $value['name'] ) . ' <span></span></a></li>';
				$output .= '<div class="group" id="' . esc_attr( $jquery_click_hook ) . '">' . "<h2>{$value['name']}<span class='plus'>"
					// . "<img src='" . CLASSY_OPTIONS_FRAMEWORK_URL . "/images/downarrow.png'>"
					. "</span></h3><div class='group-items'>\n";
				break;

			case "subsection":
				$id = strtolower(preg_replace("/\W/", "", $value['name']));
				$output .= "<div class='subsection' id='subsection-{$id}'><h3>{$value['name']}<span class='plus'>"
					// . "<img src='" . CLASSY_OPTIONS_FRAMEWORK_URL . "/images/downarrow.png'>"
					. "</span></h3><div class='subsection-items'>";
			break;

			case "subsection_end":
				$output .= "</div></div>";
			break;

			case "open_outersection":
				$output .= "<div class='outersection'>";
			break;

			case "close_outersection":
				$output .= "</div>";
			break;

			case "section_order":
				$root = get_template_directory_uri();  
				$values = explode(",", $val);
				$output .=  "<div class='section_order' id=" . esc_attr($value['id']) . ">";
				$output .=  "<div class='left_list'>";
				$output .=  "<div class='inactive'>Inactive Elements</div>";
				$output .=  "<div class='list_items'>";
				foreach($value['options'] as $k => $v) {
					if(in_array($k, $values)) continue;
					$output .=  "<div class='list_item'>";
					$output .=  "<img src='$root/images/minus.png' class='action' title='Remove'/>";
					$output .=  "<span data-key='{$k}'>{$v}</span>";
					$output .=  "</div>";
				}
				$output .=  "</div>";
				$output .=  "</div>";
				$output .=  "<div class='arrow'><img src='$root/images/arrowdrag.png' /></div>";
				$output .=  "<div class='right_list'>";
				$output .=  "<div class='active'>Active Elements</div>";
				$output .=  "<div class='drag'>Drag & Drop Elements</div>";
				$output .=  "<div class='list_items'>";
				foreach($values as $k) {
					if(!$k) continue;
					$val = $value['options'][$k];
					$output .=  "<div class='list_item'>";
					$output .=  "<img src='$root/images/minus.png' class='action' title='Remove'/>";
					$output .=  "<span data-key='{$k}'>{$val}</span>";
					$output .=  "</div>";
				}
				$output .=  "</div>";
				$output .=  "</div>";
				$output .=  "<input type='hidden' id='{$value['id']}' name='{$option_name}[{$value['id']}]' />";
				$output .=  "</div>";

			break;

			}

			if ( ($value['type'] != "heading") && ($value['type'] != "info" && $value['type'] != "subsection" && $value['type'] != "subsection_end") && $value['type'] != "open_outersection" && $value['type'] != "close_outersection" ) {
				if ( $value['type'] != "checkbox" ) {
					$output .= '<br/>';
				}
				$explain_value = '';
				if ( isset( $value['desc'] ) ) {
					$explain_value = $value['desc'];
				}
				$output .= "</div>";
				if ( $value['type'] != "checkbox" ) {
					$output .= '<div class="explain">' . wp_kses( $explain_value, $allowedtags) . '</div>'."\n";
				}
				$output .= '<div class="clear"></div></div></div>'."\n";
			}
		}
	    $output .= '</div>';
	    return array($output,$menu);
	}

	function section($text, $options = array()) {
		if(isset($options['icon'])) {
			$this->add( array( 'type' => 'heading', 'name' => $text, 'icon' => $options['icon']));
		} else {
			$this->add( array( 'type' => 'heading', 'name' => $text) );
		}
		return $this;
	}

	function remove_section( $name ) {
		$started = false;
		foreach($this->options as $k => $option) {
			if( $started && $option['type'] == 'heading' ) {
				$end_index = $k;
				break;
			}
			if($option['type'] == 'heading' && $option['name'] == $name) {
				$started = true;
				$start_index = $k;
			}

		}
		if(!isset($end_index) ) {
			$end_index = count($this->options) - 1;
		}

		array_splice($this->options, $start_index, $end_index - $start_index );
		return $this;
	}
	
	function subsection($text) {
		$this->add( array( 'type' => 'subsection', 'name' => $text) );
		return $this;
	}

	function subsection_end() {
		$this->add( array( 'type' => 'subsection_end' ) );
		return $this;
	}
	
	function open_outersection() {
		$this->add( array( 'type' => 'open_outersection' ) );
		return $this;
	}

	function close_outersection() {
		$this->add( array( 'type' => 'close_outersection' ) );
		return $this;
	}

	function info($message) {
		$this->add( array( 'type' => 'info', 'name' => $message ) );
		return $this;
	}

	function text( $key, $label = "", $options = array() ) {
		$this->add( $options + array( 'id' => $key, 'type' => 'text', 'name' => $label ) );
		return $this;
	}

	function textarea( $key, $label = "", $options = array() ) {
		$this->add( $options + array( 'id' => $key, 'type' => 'textarea', 'name' => $label ) );
		return $this;
	}

	function radio( $key, $label = "", $options = array() ) {
		$this->add( $options + array( 'id' => $key, 'type' => 'radio', 'name' => $label, 'options' => $options['options'] ) );
		return $this;
	}

	function images( $key, $label = "", $options = array() ) {
		$this->add( $options + array( 'id' => $key, 'type' => 'images', 'name' => $label, 'options' => $options['options'] ) );
		return $this;
	}

	function checkbox( $key, $label = "", $options = array() ) {
		$this->add( $options + array( 'id' => $key, 'type' => 'checkbox', 'name' => $label ) );
		return $this;
	}

	function multicheck( $key, $label = "", $options = array() ) {
		$this->add( $options + array( 'id' => $key, 'type' => 'multicheck', 'name' => $label, 'options' => $options['options'] ) );
		return $this;
	}

	function color( $key, $label = "", $options = array() ) {
		$this->add( $options + array( 'id' => $key, 'type' => 'color', 'name' => $label ) );
		return $this;
	}

	function upload( $key, $label = "", $options = array() ) {
		$this->add( $options + array( 'id' => $key, 'type' => 'upload', 'name' => $label ) );
		return $this;
	}

	function typography( $key, $label = "", $options = array() ) {
		$this->add( $options + array( 'id' => $key, 'type' => 'typography', 'name' => $label ) );
		return $this;
	}

	function select( $key, $label = "", $options = array() ) {
		$this->add( $options + array( 'id' => $key, 'type' => 'select', 'name' => $label ) );
		return $this;
	}

	function section_order( $key, $label = "", $options = array() ) {
		$this->add( $options + array( 'id' => $key, 'type' => 'section_order', 'name' => $label ) );
		return $this;
	}

	function export( $label ) {
		$this->add( array( 'type' => 'export', 'name' => $label ) );
		return $this;
	}

	function import( $label ) {
		$this->add( array( 'type' => 'import', 'name' => $label ) );
		return $this;
	}
}
