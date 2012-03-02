<?php
/**
 * Create meta box for editing pages in WordPress
 *
 * Compatible with custom post types since WordPress 3.0
 * Support input types: text, textarea, checkbox, checkbox list, radio box, select, wysiwyg, file, image, date, time, color
 *
 * @author: Rilwis
 * @url: http://www.deluxeblogtips.com/2010/04/how-to-create-meta-box-wordpress-post.html
 * @usage: please read document at project homepage and meta-box-usage.php file
 * @version: 3.0.1
 */
 

/********************* BEGIN DEFINITION OF META BOXES ***********************/

add_action('init', 'initialize_the_meta_boxes');

function initialize_the_meta_boxes() {

	global  $themename, $themeslug, $themenamefull, $options; // call globals.
	
	// Call taxonomies for select options
	
	$terms2 = get_terms('category', 'hide_empty=0');
	$blogoptions = array();
	$blogoptions['all'] = "All";

		foreach($terms2 as $term) {
			$blogoptions[$term->slug] = $term->name;
		}
	
	// End taxonomy call
	
	$meta_boxes = array();

	$mb = new Chimps_Metabox('pages', $themenamefull.' Page Options', array('pages' => array('page')));
	$mb
		->tab("Page Options")
			->image_select('page_sidebar', 'Select Page Layout', '',  array('options' => array(TEMPLATE_URL . '/images/options/right.png' , TEMPLATE_URL . '/images/options/left.png', TEMPLATE_URL . '/images/options/rightleft.png', TEMPLATE_URL . '/images/options/tworight.png', TEMPLATE_URL . '/images/options/none.png')))
			->checkbox('hide_page_title', 'Page Title', '', array('std' => 'on'))
			->section_order('page_section_order', 'Page Elements', '', array('options' => array(
					'page_slider' => 'Feature Slider',
					'callout_section' => 'Callout',
					'page_section' => 'Page',
					'breadcrumbs' => 'Breadcrumbs',
					),
					'std' => 'page_section,breadcrumbs'
				))

			->pagehelp('', 'Need Help?', '')
		->tab($themenamefull." Slider Options")
			->single_image('page_slide_one_image', 'Slide One Image', '', array('std' =>  TEMPLATE_URL . '/images/responseslider.jpg'))
			->text('page_slide_one_url', 'Slide One Link', '', array('std' => 'http://cyberchimps.com'))
			->single_image('page_slide_two_image', 'Slide Two Image', '', array('std' =>  TEMPLATE_URL . '/images/responseslider.jpg'))
			->text('page_slide_two_url', 'Slide Two Link', '', array('std' => 'http://cyberchimps.com'))
			->single_image('page_slide_three_image', 'Slide Three Image', '', array('std' =>  TEMPLATE_URL . '/images/responseslider.jpg'))
			->text('page_slide_three_url', 'Slide Three Link', '', array('std' => 'http://cyberchimps.com'))
			->sliderhelp('', 'Need Help?', '')
		->tab("Callout Options")
			->textarea('callout_text', 'Callout Text', '')
			->checkbox('extra_callout_options', 'Custom Callout Options', '', array('std' => 'off'))
			->color('custom_callout_text_color', 'Custom Text Color', '')
			->pagehelp('', 'Need help?', '')
		->end();

	foreach ($meta_boxes as $meta_box) {
		$my_box = new RW_Meta_Box_Taxonomy($meta_box);
	}

}
