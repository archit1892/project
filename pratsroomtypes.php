<?php

/*
Plugin Name: WP Property Room Types
Plugin URI: http://www.prateeksha.com/plugins/wp_invoices
Description: This is a plugin to add room types and their details to WP_Property
Author: Sumeet Shroff
Version: 0.1
Author URI: http://www.prateeksha.com
*/

/**
* @todo
* For Image uploader
* https://wordpress.org/ideas/topic/ability-to-filter-by-file-extension-on-media-upload-in-thickbox
* 
*
**/

if (!defined('ABSPATH'))
{
    exit('Please do not load this file directly.');
}

// Just used for easy debugging
if (!function_exists('v'))
{
    function v($args)
    {
		foreach(func_get_args() as $var)
		{
			echo '<pre>';
			var_dump($var);
			echo '</pre>';	
		}        
    }
}

// Define the version
define('PRATSROOMTYPES_VERSION', '1.0.0');
define('PRATSROOMTYPES_FILE', __FILE__);
define('PRATSROOMTYPES_PLUGIN_URL', get_permalink() . plugin_dir_url(PRATSROOMTYPES_FILE));
define('PRATSROOMTYPES_PLUGIN_DIR', dirname(PRATSROOMTYPES_FILE) . DIRECTORY_SEPARATOR);

/*
* Load the main initilize file
*/
require (PRATSROOMTYPES_PLUGIN_DIR . '/core/init.php');
require (PRATSROOMTYPES_PLUGIN_DIR . '/adminpages/options.php');
$a = PratsRoomtypes::getInstance();
