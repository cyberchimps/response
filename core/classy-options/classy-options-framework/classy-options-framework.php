<?php
/*
Plugin Name: Classy Options Framework
Plugin URI: http://wproot.com
Description: A framework for building theme options.
Version: 0.0.1
Author: Utkarsh Kukreti
Author URI: http://utkar.sh
License: GPLv2
*/

/*
Based on a fork of "Options Framework" by Devin Price
(https://github.com/devinsays/options-framework-plugin)
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/* Basic plugin definitions */

define('CLASSY_OPTIONS_FRAMEWORK_URL', get_template_directory_uri() . '/core/classy-options/classy-options-framework/');
define('TEMPLATE_URL', get_template_directory_uri()) ;

/* Make sure we don't expose any info if called directly */

if ( !function_exists( 'add_action' ) ) {
	exit;
}

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'options-medialibrary-uploader.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classy-options.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classy-options-sanitize.php';
ClassyOptionsSanitize::initialize();

