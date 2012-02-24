<?php

class ClassyOptionsSanitize {
	static function initialize() {
		add_filter( 'cof_font_face', array( __CLASS__, 'sanitize_font_face' ) );
		add_filter( 'cof_font_style', array( __CLASS__, 'sanitize_font_style' ) );
		add_filter( 'cof_font_face', array( __CLASS__, 'sanitize_font_face' ) );
		add_filter( 'cof_sanitize_typography', array( __CLASS__, 'sanitize_typography' ) );
		add_filter( 'cof_background_attachment', array( __CLASS__, 'sanitize_background_attachment' ) );
		add_filter( 'cof_background_position', array( __CLASS__, 'sanitize_background_position' ) );
		add_filter( 'cof_background_repeat', array( __CLASS__, 'sanitize_background_repeat' ) );
		add_filter( 'cof_sanitize_background', array( __CLASS__, 'sanitize_background' ) );
		// add_filter( 'cof_sanitize_upload', array( __CLASS__, 'sanitize_upload' ) );
		add_filter( 'cof_sanitize_color', array( __CLASS__, 'sanitize_hex' ) );
		add_filter( 'cof_sanitize_multicheck', array( __CLASS__, 'sanitize_multicheck' ), 10, 2 );
		add_filter( 'cof_sanitize_checkbox', array( __CLASS__, 'sanitize_checkbox' ) );
		add_filter( 'cof_sanitize_images', array( __CLASS__, 'sanitize_enum' ), 10, 2);
		add_filter( 'cof_sanitize_radio', array( __CLASS__, 'sanitize_enum' ), 10, 2);
		add_filter( 'cof_sanitize_select', array( __CLASS__, 'sanitize_enum' ), 10, 2);
		add_filter( 'cof_sanitize_section_order', array( __CLASS__, 'sanitize_section_order' ), 10, 2 );
		
		add_filter( 'cof_sanitize_textarea', array( __CLASS__, 'no_filter' ) );
		add_filter( 'cof_sanitize_text', array( __CLASS__, 'no_filter' ) );
	}

	static function no_filter($input) {
		return $input;
	}
	static function sanitize_textarea($input) {
		// global $allowedtags;
		// $output = wp_kses( $input, $allowedtags + array( 'script' ) );
		return $input;
	}

	static function sanitize_checkbox( $input ) {
		if ( $input ) {
			$output = "1";
		} else {
			$output = "0";
		}
		return $output;
	}

	static function sanitize_multicheck( $input, $option ) {
		$output = '';
		if ( is_array( $input ) ) {
			foreach( $option['options'] as $key => $value ) {
				$output[$key] = "0";
			}
			foreach( $input as $key => $value ) {
				if ( array_key_exists( $key, $option['options'] ) && $value ) {
					$output[$key] = "1"; 
				}
			}
		}
		return $output;
	}

	static function sanitize_upload( $input ) {
		$output = '';
		var_dump($_FILES);
		$filetype = wp_check_filetype($input);
		if ( $filetype["ext"] ) {
			$output = $input;
		}
		return $output;
	}

	/* Check that the key value sent is valid */

	static function sanitize_enum( $input, $option ) {
		$output = '';
		if ( array_key_exists( $input, $option['options'] ) ) {
			$output = $input;
		}
		return $output;
	}

	/* Background */

	static function sanitize_background( $input ) {
		$output = wp_parse_args( $input, array(
			'color' => '',
			'image'  => '',
			'repeat'  => 'repeat',
			'position' => 'top center',
			'attachment' => 'scroll'
		) );

		$output['color'] = apply_filters( 'cof_sanitize_hex', $input['color'] );
		$output['image'] = apply_filters( 'cof_sanitize_upload', $input['image'] );
		$output['repeat'] = apply_filters( 'cof_background_repeat', $input['repeat'] );
		$output['position'] = apply_filters( 'cof_background_position', $input['position'] );
		$output['attachment'] = apply_filters( 'cof_background_attachment', $input['attachment'] );

		return $output;
	}

	static function sanitize_background_repeat( $value ) {
		$recognized = self::recognized_background_repeat();
		if ( array_key_exists( $value, $recognized ) ) {
			return $value;
		}
		return apply_filters( 'cof_default_background_repeat', current( $recognized ) );
	}

	static function sanitize_background_position( $value ) {
		$recognized = self::recognized_background_position();
		if ( array_key_exists( $value, $recognized ) ) {
			return $value;
		}
		return apply_filters( 'cof_default_background_position', current( $recognized ) );
	}

	static function sanitize_background_attachment( $value ) {
		$recognized = self::recognized_background_attachment();
		if ( array_key_exists( $value, $recognized ) ) {
			return $value;
		}
		return apply_filters( 'cof_default_background_attachment', current( $recognized ) );
	}


	/* Typography */

	static function sanitize_typography( $input ) {
		$output = wp_parse_args( $input, array(
			'size'  => '',
			'face'  => '',
			'style' => '',
			'color' => ''
		) );

		$output['size']  = apply_filters( 'cof_font_size', $output['size'] );
		$output['face']  = apply_filters( 'cof_font_face', $output['face'] );
		$output['style'] = apply_filters( 'cof_font_style', $output['style'] );
		$output['color'] = apply_filters( 'cof_color', $output['color'] );

		return $output;
	}


	static function sanitize_font_size( $value ) {
		$recognized = self::recognized_font_sizes();
		$value = preg_replace('/px/','', $value);
		if ( in_array( (int) $value, $recognized ) ) {
			return (int) $value;
		}
		return (int) apply_filters( 'cof_default_font_size', $recognized );
	}


	static function sanitize_font_style( $value ) {
		$recognized = self::recognized_font_styles();
		if ( array_key_exists( $value, $recognized ) ) {
			return $value;
		}
		return apply_filters( 'cof_default_font_style', current( $recognized ) );
	}


	static function sanitize_font_face( $value ) {
		$recognized = self::recognized_font_faces();
		if ( array_key_exists( $value, $recognized ) ) {
			return $value;
		}
		return apply_filters( 'cof_default_font_face', current( $recognized ) );
	}

	/**
	 * Get recognized background repeat settings
	 *
	 * @return   array
	 *
	 */
	static function recognized_background_repeat() {
		$default = array(
			'no-repeat' => 'No Repeat',
			'repeat-x'  => 'Repeat Horizontally',
			'repeat-y'  => 'Repeat Vertically',
			'repeat'    => 'Repeat All',
		);
		return apply_filters( 'cof_recognized_background_repeat', $default );
	}

	/**
	 * Get recognized background positions
	 *
	 * @return   array
	 *
	 */
	static function recognized_background_position() {
		$default = array(
			'top left'      => 'Top Left',
			'top center'    => 'Top Center',
			'top right'     => 'Top Right',
			'center left'   => 'Middle Left',
			'center center' => 'Middle Center',
			'center right'  => 'Middle Right',
			'bottom left'   => 'Bottom Left',
			'bottom center' => 'Bottom Center',
			'bottom right'  => 'Bottom Right'
		);
		return apply_filters( 'cof_recognized_background_position', $default );
	}

	/**
	 * Get recognized background attachment
	 *
	 * @return   array
	 *
	 */
	static function recognized_background_attachment() {
		$default = array(
			'scroll' => 'Scroll Normally',
			'fixed'  => 'Fixed in Place'
		);
		return apply_filters( 'cof_recognized_background_attachment', $default );
	}

	/**
	 * Sanitize a color represented in hexidecimal notation.
	 *
	 * @param    string    Color in hexidecimal notation. "#" may or may not be prepended to the string.
	 * @param    string    The value that this function should return if it cannot be recognized as a color.
	 * @return   string
	 *
	 */

	static function sanitize_hex( $hex, $default = '' ) {
		if ( self::validate_hex( $hex ) ) {
			return $hex;
		}
		return $default;
	}

	/**
	 * Get recognized font sizes.
	 *
	 * Returns an indexed array of all recognized font sizes.
	 * Values are integers and represent a range of sizes from
	 * smallest to largest.
	 *
	 * @return   array
	 */

	static function recognized_font_sizes() {
		$sizes = range( 9, 71 );
		$sizes = apply_filters( 'cof_recognized_font_sizes', $sizes );
		$sizes = array_map( 'absint', $sizes );
		return $sizes;
	}

	/**
	 * Get recognized font faces.
	 *
	 * Returns an array of all recognized font faces.
	 * Keys are intended to be stored in the database
	 * while values are ready for display in in html.
	 *
	 * @return   array
	 *
	 */
	static function recognized_font_faces() {
		$default = array(
			'arial'     => 'Arial',
			'verdana'   => 'Verdana, Geneva',
			'trebuchet' => 'Trebuchet',
			'georgia'   => 'Georgia',
			'times'     => 'Times New Roman',
			'tahoma'    => 'Tahoma, Geneva',
			'palatino'  => 'Palatino',
			'helvetica' => 'Helvetica*',
			'custom'    => 'Custom'
		);
		return apply_filters( 'cof_recognized_font_faces', $default );
	}

	/**
	 * Get recognized font styles.
	 *
	 * Returns an array of all recognized font styles.
	 * Keys are intended to be stored in the database
	 * while values are ready for display in in html.
	 *
	 * @return   array
	 *
	 */
	static function recognized_font_styles() {
		$default = array(
			'normal'      => 'Normal',
			'italic'      => 'Italic',
			'bold'        => 'Bold',
			'bold italic' => 'Bold Italic'
		);
		return apply_filters( 'cof_recognized_font_styles', $default );
	}

	/**
	 * Is a given string a color formatted in hexidecimal notation?
	 *
	 * @param    string    Color in hexidecimal notation. "#" may or may not be prepended to the string.
	 * @return   bool
	 *
	 */

	static function validate_hex( $hex ) {
		$hex = trim( $hex );
		/* Strip recognized prefixes. */
		if ( 0 === strpos( $hex, '#' ) ) {
			$hex = substr( $hex, 1 );
		}
		elseif ( 0 === strpos( $hex, '%23' ) ) {
			$hex = substr( $hex, 3 );
		}
		/* Regex match. */
		if ( 0 === preg_match( '/^[0-9a-fA-F]{6}$/', $hex ) ) {
			return false;
		}
		else {
			return true;
		}
	}

	static function sanitize_section_order($input, $option) {
		return $input;
		$exploded = explode($input, ",");
		$output = array();
		foreach($option['options'] as $key => $value) {
			if(in_array($key, $exploded))
				$output[] = $key;
		}
		return implode(',', $output);
	}
}

