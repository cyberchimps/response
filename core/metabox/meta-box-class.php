<?php

class Chimps_Metabox {
	function __construct($id, $title, $options) {
		$this->id = $id;
		$this->title = $title;
		$this->options = $options;

		$this->fields = array();
	}

	function tab($title) {
		$this->add(array('title' => $title, 'type' => 'tab'));
		return $this;
	}

	function add($options) {
		$this->fields[] = $options;
	}

	function end() {
		$tabs = array();
		foreach($this->fields as $field) {
			if($field['type'] === 'tab') {
				$tabs[] = array('title' => $field['title'], 'fields' => array());
			} else {
				$tabs[count($tabs) - 1]['fields'][] = $field;
			}
		}

		$final = array (
			'id' => $this->id,
			'title' => $this->title,
			'pages' => $this->options['pages'],
			'tabs' => $tabs
		);

		new RW_Meta_Box_Taxonomy($final);
	}

	/**
	 * Helper Functions
	 */
	function image($id, $name, $desc, $options = array()) {
		$this->add($options + array('type' => 'image', 'id' => $id, 'name' => $name, 'desc' => $desc));
		return $this;
	}

	function text($id, $name, $desc, $options = array()) {
		$this->add($options + array('type' => 'text', 'id' => $id, 'name' => $name, 'desc' => $desc));
		return $this;
	}

	function checkbox($id, $name, $desc, $options = array()) {
		$this->add($options + array('type' => 'checkbox', 'id' => $id, 'name' => $name, 'desc' => $desc));
		return $this;
	}

	function sliderhelp($id, $name, $desc, $options = array()) {
		$this->add($options + array('type' => 'sliderhelp', 'id' => $id, 'name' => $name, 'desc' => $desc));
		return $this;
	}

	function reorder($id, $name, $desc, $options = array()) {
		$this->add($options + array('type' => 'reorder', 'id' => $id, 'name' => $name, 'desc' => $desc));
		return $this;
	}

	function select($id, $name, $desc, $options = array()) {
		$this->add($options + array('type' => 'select', 'id' => $id, 'name' => $name, 'desc' => $desc));
		return $this;
	}

	function section_order($id, $name, $desc, $options = array()) {
		$this->add($options + array('type' => 'section_order', 'id' => $id, 'name' => $name, 'desc' => $desc));
		return $this;
	}

	function pagehelp($id, $name, $desc, $options = array()) {
		$this->add($options + array('type' => 'pagehelp', 'id' => $id, 'name' => $name, 'desc' => $desc));
		return $this;
	}

	function textarea($id, $name, $desc, $options = array()) {
		$this->add($options + array('type' => 'textarea', 'id' => $id, 'name' => $name, 'desc' => $desc));
		return $this;
	}

	function color($id, $name, $desc, $options = array()) {
		$this->add($options + array('type' => 'color', 'id' => $id, 'name' => $name, 'desc' => $desc));
		return $this;
	}

	function image_select($id, $name, $desc, $options = array()) {
		$this->add($options + array('type' => 'image_select', 'id' => $id, 'name' => $name, 'desc' => $desc));
		return $this;
	}

	function single_image($id, $name, $desc, $options = array()) {
		$this->add($options + array('type' => 'single_image', 'id' => $id, 'name' => $name, 'desc' => $desc));
		return $this;
	}
}

/**
 * Meta Box class
 */
class RW_Meta_Box {

	var $_meta_box;
	var $tabs;

	// Create meta box based on given data
	function __construct($meta_box) {
		if (!is_admin()) return;

		// assign meta box values to local variables and add it's missed values
		$this->_meta_box = $meta_box;
		$this->tabs = & $this->_meta_box['tabs'];
		$this->add_missed_values();

		add_action('admin_menu', array(&$this, 'add'));	// add meta box
		add_action('save_post', array(&$this, 'save'));	// save meta box's data

		// check for some special fields and add needed actions for them
		$this->check_field_upload();
		$this->check_field_color();

	}
	

	/******************** BEGIN UPLOAD **********************/

	// Check field upload and add needed actions
	function check_field_upload() {
		if ($this->has_field('image') || $this->has_field('file')) {
			add_action('post_edit_form_tag', array(&$this, 'add_enctype'));				// add data encoding type for file uploading
			add_action('admin_head-post.php', array(&$this, 'add_script_upload'));		// add scripts for handling add/delete images
			add_action('admin_head-post-new.php', array(&$this, 'add_script_upload'));
			add_action('delete_post', array(&$this, 'delete_attachments'));				// delete all attachments when delete post
		}
	}

	// Add data encoding type for file uploading
	function add_enctype() {
		echo ' enctype="multipart/form-data"';
	}

	// Add scripts for handling add/delete images
	function add_script_upload() {
		echo '
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			// add more file
			$(".rw-add-file").click(function(){
				var $first = $(this).parent().find(".file-input:first");
				$first.clone().insertAfter($first).show();
				return false;
			});

			// delete file
			$(".rw-delete-file").click(function(){
				var $parent = $(this).parent(),
					data = $(this).attr("rel");
				$.post(ajaxurl, {action: \'rw_delete_file\', data: data}, function(response){
					$parent.fadeOut("slow");
					
				});
				return false;
			});
		});
		</script>';
	}

	// Delete all attachments when delete post
	function delete_attachments($post_id) {
		$attachments = get_posts(array(
			'numberposts' => -1,
			'post_type' => 'attachment',
			'post_parent' => $post_id
		));
		if (!empty($attachments)) {
			foreach ($attachments as $att) {
				wp_delete_attachment($att->ID);
			}
		}
	}

	/******************** END UPLOAD **********************/

	/******************** BEGIN COLOR PICKER **********************/

	// Check field color
	function check_field_color() {
		if ($this->has_field('color') && $this->is_edit_page()) {
			wp_enqueue_style('farbtastic');									// enqueue built-in script and style for color picker
			wp_enqueue_script('farbtastic');
		}
	}

	// Custom script for color picker
	function add_script_color() {
		$ids = array();
		foreach($this->tabs as $tab) {
			foreach ($tab['fields'] as $field) {
				if ('color' == $field['type']) {
					$ids[] = $field['id'];
				}
			}
		}
		echo '
		<script type="text/javascript">
		jQuery(document).ready(function($){
		';
		foreach ($ids as $id) {
			echo "
			$('#picker-$id').farbtastic('#$id');
			$('#select-$id').click(function(){
				$('#picker-$id').toggle();
				return false;
			});
			";
		}
		echo '
		});
		</script>
		';
	}

	/******************** END COLOR PICKER **********************/


	/******************** BEGIN META BOX PAGE **********************/

	// Add meta box for multiple post types
	function add() {
		foreach ($this->_meta_box['pages'] as $page) {
			add_meta_box($this->_meta_box['id'], $this->_meta_box['title'], array(&$this, 'show'), $page, $this->_meta_box['context'], $this->_meta_box['priority']);
		}
	}

	// Callback function to show fields in meta box
	function show() {
		global $post;

		wp_nonce_field(basename(__FILE__), 'rw_meta_box_nonce');

		echo '<div class="metabox-tabs-div">';

		foreach($this->_meta_box['tabs'] as $counter => $tab) {
			$counter++;
			$id = preg_replace("/[^a-zA-Z0-9]+/", "-", $tab['title']);
			echo "<div class='subsection' id='subsection-{$id}'>";
			echo "<h4>{$tab['title']}<span></span></h4>";
			echo "<div class='subsection-items'>";
			$this->render_fields($tab['fields'], "tab{$counter}");
			echo "</div>";
			echo "</div>";
		}
		echo '</div>';

		$this->add_script_color();
	}

	function render_fields($fields, $tab = '') {
		global $post;
		echo '<div class="', $tab,'">';
		echo '<table class="form-table">';
		foreach($fields as $field) {
			$meta = get_post_meta($post->ID, $field['id'], !(isset($field['multiple']) && $field['multiple']));
			$meta = !empty($meta) ? $meta : (isset($field['std']) ? $field['std'] : '');
			
			echo '<tr class="'.$field['id'].'">';
			// call separated methods for displaying each type of field
			call_user_func(array(&$this, 'show_field_' . $field['type']), $field, $meta);
			echo '</tr>';
		}
		echo '</table>';
		echo '</div>';
	}
	/******************** END META BOX PAGE **********************/

	/******************** BEGIN META BOX FIELDS **********************/

	function show_field_begin($field, $meta) {
		echo "<th style='width:20%'><label for='{$field['id']}'>{$field['name']}</label></th><td>";
	}

	function show_field_end($field, $meta) {
		echo "<br />{$field['desc']}</td>";
	}

	function show_field_text($field, $meta) {
		$this->show_field_begin($field, $meta);
		echo "<input type='text' name='{$field['id']}' id='{$field['id']}' value='$meta' size='30' style='width:60%' />";
		$this->show_field_end($field, $meta);
	}

	function show_field_textarea($field, $meta) {
		$this->show_field_begin($field, $meta);
		echo "<textarea name='{$field['id']}' cols='60' rows='15' style='width:97%'>$meta</textarea>";
		$this->show_field_end($field, $meta);
	}

	function show_field_select($field, $meta) {
		if (!is_array($meta)) $meta = (array) $meta;
		$this->show_field_begin($field, $meta);
		echo "<select name='{$field['id']}" . ((isset($field['multiple']) && $field['multiple']) ? "[]' multiple='multiple' style='height:auto'" : "'") . ">";
		foreach ($field['options'] as $key => $value) {
			echo "<option value='$key'" . selected(in_array($key, $meta), true, false) . ">$value</option>";
		}
		echo "</select>";
		$this->show_field_end($field, $meta);
	}

	function show_field_radio($field, $meta) {
		$this->show_field_begin($field, $meta);
		foreach ($field['options'] as $key => $value) {
			echo "<input type='radio' name='{$field['id']}' value='$key'" . checked($meta, $key, false) . " /> $value ";
		}
		$this->show_field_end($field, $meta);
	}

	function show_field_checkbox($field, $meta) {
		$this->show_field_begin($field, $meta);
		echo "<input type='checkbox' class='checkbox' name='{$field['id']}' id='checkbox-{$field['id']}' " . ($meta === 'on' ? 'checked="checked"' : '' ) . " value='1'/> {$field['desc']}</td>";
	}

	function show_field_wysiwyg($field, $meta) {
		$this->show_field_begin($field, $meta);
		echo "<textarea name='{$field['id']}' class='theEditor' cols='60' rows='15' style='width:97%'>$meta</textarea>";
		$this->show_field_end($field, $meta);
	}
	
	
	function show_field_pagehelp($field, $meta) {
		global $themenamefull, $pagedocs; 
		
		$this->show_field_begin($field, $meta);
		echo "Visit our $themenamefull Page Options help page here: <a href='$pagedocs' target='_blank'>Page Options Documentation</a></td>";
	}
		
	function show_field_sliderhelp($field, $meta) {
		global $themenamefull, $sliderdocs;
		
		$this->show_field_begin($field, $meta);
		echo "Visit our $themenamefull Slider help page here: <a href='$sliderdocs' target='_blank'>Slider Documentation</a></td>";
	}
	
	function show_field_reorder($field, $meta) {
		$this->show_field_begin($field, $meta);
		echo "Install the <a href='http://wordpress.org/extend/plugins/post-types-order/' target='_blank'>Post Types Order Plugin</a> to control the order of your custom slides.</td>";
	}

	function show_field_file($field, $meta) {
		global $post;

		if (!is_array($meta)) $meta = (array) $meta;

		$this->show_field_begin($field, $meta);
		echo "{$field['desc']}<br />";

		if (!empty($meta)) {
			// show attached files
			$attachs = get_posts(array(
				'numberposts' => -1,
				'post_type' => 'attachment',
				'post_parent' => $post->ID
			));

			$nonce = wp_create_nonce('rw_ajax_delete_file');

			echo '<div style="margin-bottom: 10px"><strong>' . _('Uploaded files') . '</strong></div>';
			echo '<ol>';
			foreach ($attachs as $att) {
				if (wp_attachment_is_image($att->ID)) continue; // what's image uploader for?

				$src = wp_get_attachment_url($att->ID);
				if (in_array($src, $meta)) {
					echo "<li>" . wp_get_attachment_link($att->ID) . " (<a class='rw-delete-file' href='javascript:void(0)' rel='{$post->ID}!{$field['id']}!{$att->ID}!{$src}!{$nonce}'>Delete</a>)</li>";
				}
			}
			echo '</ol>';
		}

		// show form upload
		echo "<div style='clear: both'><strong>" . _('Upload new files') . "</strong></div>
			<div class='new-files'>
				<div class='file-input'><input type='file' name='{$field['id']}[]' /></div>
				<a class='rw-add-file' href='javascript:void(0)'>" . _('Add more file') . "</a>
			</div>
		</td>";
	}

	function show_field_image($field, $meta) {
		global $post;

		if (!is_array($meta)) $meta = (array) $meta;

		$this->show_field_begin($field, $meta);
		echo "{$field['desc']}<br />";

		if (!empty($meta)) {
			// show attached images
			$attachs = get_posts(array(
				'numberposts' => -1,
				'post_type' => 'attachment',
				'post_parent' => $post->ID,
				'post_mime_type' => 'image', // get attached images only
				'output' => ARRAY_A
			));

			$nonce = wp_create_nonce('rw_ajax_delete_file');

			echo '<div style="margin-bottom: 10px"><strong>' . _('Uploaded images') . '</strong></div>';
			foreach ($attachs as $att) {
				$src = wp_get_attachment_image_src($att->ID, 'full');
				$src = $src[0];

				if (in_array($src, $meta)) {
					echo "<div style='margin: 0 10px 10px 0; float: left'><img width='150' src='$src' /><br />
							<a class='rw-delete-file' href='javascript:void(0)' rel='{$post->ID}!{$field['id']}!{$att->ID}!{$src}!{$nonce}'>Delete</a>
						</div>";
				}
			}
		}

		// show form upload
		echo "<div style='clear: both'><strong>" . _('Upload new images (Make sure to publish the post to save)') . "</strong></div>
			<div class='new-files'>
				<div class='file-input'><input type='file' name='{$field['id']}[]' /></div>
				
			</div>
		</td>";
	}

	function show_field_color($field, $meta) {
		if (empty($meta)) $meta = '#';
		$this->show_field_begin($field, $meta);
		echo "<input type='text' name='{$field['id']}' id='{$field['id']}' value='$meta' size='8' />
			  <a href='#' id='select-{$field['id']}'>" . _('Select a color') . "</a>
			  <div style='display:none' id='picker-{$field['id']}'></div>";
		$this->show_field_end($field, $meta);
	}

	function show_field_checkbox_list($field, $meta) {
		if (!is_array($meta)) $meta = (array) $meta;
		$this->show_field_begin($field, $meta);
		$html = array();
		foreach ($field['options'] as $key => $value) {
			$html[] = "<input type='checkbox' name='{$field['id']}[]' value='$key'" . checked(in_array($key, $meta), true, false) . " /> $value";
		}
		echo implode('<br />', $html);
		$this->show_field_end($field, $meta);
	}

	function show_field_date($field, $meta) {
		$this->show_field_text($field, $meta);
	}

	function show_field_time($field, $meta) {
		$this->show_field_text($field, $meta);
	}

	function show_field_section_order($field, $meta) {
		$root = get_template_directory_uri();  
		$this->show_field_begin($field, $meta);
		$meta = explode(",", $meta);
		echo "<div class='section_order'>";
		echo "<div class='left_list'>";
		echo "<div id='inactive'>Inactive Elements</div>";
		echo "<div class='list_items'>";
			foreach($field['options'] as $key => $value) {
				if(in_array($key, $meta)) continue;
				echo "<div class='list_item'>";
					echo "<img src='$root/images/minus.png' class='action' title='Remove'/>";
					echo "<span data-key='{$key}'>{$value}</span>";
				echo "</div>";
			}
		echo "</div>";
		echo "</div>";
		echo "<div id='arrow'><img src='$root/images/arrowdrag.png' /></div>";
		echo "<div class='right_list'>";
		echo "<div id='active'>Active Elements</div>";
		echo "<div id='drag'>Drag & Drop Elements</div>";
		echo "<div class='list_items'>";
			foreach($meta as $key) {
				if(!$key) continue;
				$value = $field['options'][$key];
				echo "<div class='list_item'>";
					echo "<img src='$root/images/minus.png' class='action' title='Remove'/>";
					echo "<span data-key='{$key}'>{$value}</span>";
				echo "</div>";
			}
		echo "</div>";
		echo "</div>";
		echo "<input type='hidden' id={$field['id']} name={$field['id']} />";
		echo "</div>";
?>

<script type="text/javascript">
		jQuery(function($) {
			function update(base) {
				var hidden = base.find("input[type='hidden']");
				var val = [];
				base.find('.right_list .list_items span').each(function() {
					val.push($(this).data('key'));
				})
				hidden.val(val.join(",")).change();
				$('.right_list .action').show();
				$('.left_list .action').hide();
			}
			$(".left_list .list_items").delegate(".action", "click", function() {
				var item = $(this).closest('.list_item');
				$(this).closest('.section_order').children('.right_list').children('.list_items').append(item);
				update($(this).closest(".section_order"));
			});
			$(".right_list .list_items").delegate(".action", "click", function() {
				var item = $(this).closest('.list_item');
				$(this).val('Add');
				$(this).closest('.section_order').children('.left_list').children('.list_items').append(item);
				$(this).hide();
				update($(this).closest(".section_order"));
			});
			$(".right_list .list_items").sortable({
				update: function() {
					update($(this).closest(".section_order"));
				},
				connectWith: '.left_list .list_items'
			});

			$(".left_list .list_items").sortable({
				connectWith: '.right_list .list_items'
			});

			$('.section_order').each(function() {
				update($(this));
			});
		});
</script>
<style type="text/css">
.left_list, .right_list {
	float: left;
	margin: 20px;
	width: 130px;
}
.list_items {
	padding-bottom: 5px;
}
</style>

	<?php
		$this->show_field_end($field, $meta);
	}

	function show_field_image_select($field, $meta) {
		$this->show_field_begin($field, $meta);
		// var_dump($field, $meta);
		echo "<div class='image_select'>";
		foreach($field['options'] as $key=>$option) {
			echo "<img data-key='{$key}' class='" . ($key == $meta ? ' selected' : '' ) . "' src='{$option}' />";
		}
		echo "<input type='hidden' name='{$field['id']}' />";
		echo "</div>";
		// $this->show_field_end($field, $meta);
	}

	function show_field_single_image($field, $meta) {
		$this->show_field_begin($field, $meta);

		if($meta) {
			echo "<img class='image_preview' src='{$meta}' /><br/>";
		}

		echo "<input type='file' name='{$field['id']}' />";
		echo "<br/>or enter URL<br/>";
		echo "<input type='text' size='50' name='{$field['id']}_url' value='{$meta}'/>";
		$this->show_field_end($field, $meta);
	}

	/******************** END META BOX FIELDS **********************/

	/******************** BEGIN META BOX SAVE **********************/

	// Save data from meta box
	function save($post_id) {

		if (isset($_POST['post_type'])) {
			$post_type = $_POST['post_type'];
		}
		else {
		$post_type = 'null';
		}

		$post_type_object = get_post_type_object($post_type);

		if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)						// check autosave
		|| (!isset($_POST['post_ID']) || $post_id != $_POST['post_ID'])			// check revision
		|| (!in_array($_POST['post_type'], $this->_meta_box['pages']))			// check if current post type is supported
		|| (!check_admin_referer(basename(__FILE__), 'rw_meta_box_nonce'))		// verify nonce
		|| (!current_user_can($post_type_object->cap->edit_post, $post_id))) {	// check permission
			return $post_id;
		}

		foreach($this->tabs as $tab) {
			foreach ($tab['fields'] as $field) {
				$name = $field['id'];
				$type = $field['type'];
				$old = get_post_meta($post_id, $name, !(isset($field['multiple']) && $field['multiple']));
				$new = isset($_POST[$name]) ? $_POST[$name] : ((isset($field['multiple']) && $field['multiple']) ? array() : '');

				// validate meta value
				if (class_exists('RW_Meta_Box_Validate') && method_exists('RW_Meta_Box_Validate', $field['validate_func'])) {
					$new = call_user_func(array('RW_Meta_Box_Validate', $field['validate_func']), $new);
				}

				// call defined method to save meta value, if there's no methods, call common one
				$save_func = 'save_field_' . $type;
				if (method_exists($this, $save_func)) {
					call_user_func(array(&$this, 'save_field_' . $type), $post_id, $field, $old, $new);
				} else {
					$this->save_field($post_id, $field, $old, $new);
				}
			}
		}
	}

	// Common functions for saving field
	function save_field($post_id, $field, $old, $new) {
		$name = $field['id'];

		// single value
		if (!(isset($field['multiple']) && $field['multiple'])) {
			if ('' != $new && $new != $old) {
				update_post_meta($post_id, $name, $new);
			} elseif ('' == $new) {
				delete_post_meta($post_id, $name, $old);
			}
			return;
		}

		// multiple values
		// get new values that need to add and get old values that need to delete
		$add = array_diff($new, $old);
		$delete = array_diff($old, $new);
		foreach ($add as $add_new) {
			add_post_meta($post_id, $name, $add_new, false);
		}
		foreach ($delete as $delete_old) {
			delete_post_meta($post_id, $name, $delete_old);
		}
	}

	function save_field_wysiwyg($post_id, $field, $old, $new) {
		$new = wpautop($new);
		$this->save_field($post_id, $field, $old, $new);
	}

	function save_field_file($post_id, $field, $old, $new) {
		$name = $field['id'];
		if (empty($_FILES[$name])) return;

		$this->fix_file_array($_FILES[$name]);

		foreach ($_FILES[$name] as $position => $fileitem) {
			$file = wp_handle_upload($fileitem, array('test_form' => false));

			if (empty($file['file'])) continue;
			$filename = $file['file'];

			$attachment = array(
				'post_mime_type' => $file['type'],
				'guid' => $file['url'],
				'post_parent' => $post_id,
				'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
				'post_content' => ''
			);
			$id = wp_insert_attachment($attachment, $filename, $post_id);
			if (!is_wp_error($id)) {
				wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $filename));
				add_post_meta($post_id, $name, $file['url'], false);	// save file's url in meta fields
			}
		}
	}

	// Save images, call save_field_file, cause they use the same mechanism
	function save_field_image($post_id, $field, $old, $new) {
		$this->save_field_file($post_id, $field, $old, $new);
	}

	function save_field_single_image($post_id, $field, $old, $new) {
		if(isset($_FILES[$field['id']]) && $_FILES[$field['id']]['tmp_name']) {
			$overrides = array('test_form' => false);
			$file = wp_handle_upload($_FILES[$field['id']], $overrides);
			if(!is_wp_error($file)) {
				update_post_meta($post_id, $field['id'], $file['url']);
			}
		} elseif(isset($_POST[$field['id'] . '_url'])) {
			$url = $_POST[$field['id'] . '_url'];
			update_post_meta($post_id, $field['id'], $url);
		}
	}

	function save_field_checkbox($post_id, $field, $old, $new) {
		$new = $new ? "on" : "off";
		update_post_meta($post_id, $field['id'], $new);
	}

	/******************** END META BOX SAVE **********************/

	/******************** BEGIN HELPER FUNCTIONS **********************/

	// Add missed values for meta box
	function add_missed_values() {
		// default values for meta box
		$this->_meta_box = array_merge(array(
			'context' => 'normal',
			'priority' => 'high',
			'pages' => array('if_custom_slides')
		), $this->_meta_box);

		// default values for fields
		foreach($this->tabs as $tabkey => $tab) {
			foreach ($tab['fields'] as $key => $field) {
				$multiple = in_array($field['type'], array('checkbox_list', 'file', 'image')) ? true : false;
				$std = $multiple ? array() : '';
				$format = 'date' == $field['type'] ? 'yy-mm-dd' : ('time' == $field['type'] ? 'hh:mm' : '');
				$this->tabs[$tabkey][$key] = array_merge(array(
					'multiple' => $multiple,
					'std' => $std,
					'desc' => '',
					'format' => $format,
					'validate_func' => ''
				), $field);
			}
		}
	}

	// Check if field with $type exists
	function has_field($type) {
		foreach($this->_meta_box['tabs'] as $tab) {
			foreach($tab['fields'] as $field) {
				if ($type == $field['type']) return true;
			}
		}
		return false;
	}

	// Check if current page is edit page
	function is_edit_page() {
		global $pagenow;
		if (in_array($pagenow, array('post.php', 'post-new.php'))) return true;
		return false;
	}

	/**
	 * Fixes the odd indexing of multiple file uploads from the format:
	 *     $_FILES['field']['key']['index']
	 * To the more standard and appropriate:
	 *     $_FILES['field']['index']['key']
	 */
	function fix_file_array(&$files) {
		$output = array();
		foreach ($files as $key => $list) {
			foreach ($list as $index => $value) {
				$output[$index][$key] = $value;
			}
		}
		$files = $output;
	}

	/******************** END HELPER FUNCTIONS **********************/
}

?>
<?php


/********************* BEGIN EXTENDING CLASS ***********************/

/**
 * Extend RW_Meta_Box class
 * Add field type: 'taxonomy'
 */
class RW_Meta_Box_Taxonomy extends RW_Meta_Box {

	function add_missed_values() {
		parent::add_missed_values();

		// add 'multiple' option to taxonomy field with checkbox_list type
		foreach($this->tabs as $keytab => $tab) {
			foreach ($tab['fields'] as $key => $field) {
				if ('taxonomy' == $field['type'] && 'checkbox_list' == $field['options']['type']) {
					$this->tabs[$keytab]['fields'][$key]['multiple'] = true;
				}
			}
		}
	}

	// show taxonomy list
	function show_field_taxonomy($field, $meta) {
		global $post;

		if (!is_array($meta)) $meta = (array) $meta;

		$this->show_field_begin($field, $meta);

		$options = $field['options'];
		$terms = get_terms($options['taxonomy'], $options['args']);

		// checkbox_list
		if ('checkbox_list' == $options['type']) {
			foreach ($terms as $term) {
				echo "<input type='checkbox' name='{$field['id']}[]' value='$term->slug'" . checked(in_array($term->slug, $meta), true, false) . " /> $term->name<br/>";
			}
		}
		// select
		else {
			echo "<select name='{$field['id']}" . ($field['multiple'] ? "[]' multiple='multiple' style='height:auto'" : "'") . ">";

			foreach ($terms as $term) {
				echo "<option value='$term->slug'" . selected(in_array($term->slug, $meta), true, false) . ">$term->name</option>";
			}
			echo "</select>";
		}

		$this->show_field_end($field, $meta);
	}
}

/********************* END EXTENDING CLASS ***********************/

add_action( 'admin_print_styles-post-new.php', 'metabox_enqueue' );
add_action( 'admin_print_styles-post.php', 'metabox_enqueue' );

function metabox_enqueue() {
	$path =  get_template_directory_uri()."/core/library/js/";
	$path2 = get_template_directory_uri()."/css/";
	$color = get_user_meta( get_current_user_id(), 'admin_color', true );

	wp_register_style(  'metabox-tabs-css', $path2. 'metabox-tabs.css');

	wp_register_script ( 'jf-metabox-tabs', $path. 'metabox-tabs.js');

	wp_enqueue_script('jf-metabox-tabs');
	
	wp_enqueue_script('jf-metabox-tabs');
	wp_enqueue_script('jquerycustom', get_template_directory_uri().'/core/library/js/jquery-custom.js', array('jquery') );
	
	wp_enqueue_style('metabox-tabs-css');
}

/********************* END DEFINITION OF META BOXES ***********************/

function cyberchimps_add_edit_form_multipart_encoding() {
	echo ' enctype="multipart/form-data"';
}
add_action('post_edit_form_tag', 'cyberchimps_add_edit_form_multipart_encoding');
