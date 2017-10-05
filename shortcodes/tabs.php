<?php

/**
* Shortcode for Tabs
*
* @category   Tasks
* @package    PratsRoomtypes
* @author     Sumeet Shroff <sumeet@prateeksha.com>
* @copyright  2016 Sumeet Shroff (http://www.prateeksha.com)
* @license    see license.txt
* @link       http://www.prateeksha.com/
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit();
}

/**
* Class Shortcode Tabs
*
* @category   Shortcodes
* @package    PratsRoomtypes
* @author     Sumeet Shroff <sumeet@prateeksha.com>
* @copyright  2016 Sumeet Shroff (http://www.prateeksha.com)
* @license    see license.txt
* @link       http://www.prateeksha.com/
*/

class PratsRoomtypes_Shortcodes_Tabs extends PratsRoomtypes_Helpers_Shortcode
{
    /**
    * Method to add the shortcode
    *
    * @return void
    */
    public static function register()
    {
        // Add shortcode
        add_shortcode('roomtypes_tabs', array (__CLASS__, 'render'));
    }

    /**
    * Function to render the Tabs
    *
    * @param array $atts
    *
    * @return NULL|string
    */
    public static function render($atts)
    {
        global $post;
        if ( !$post->ID || $post->post_type != 'property')
        {
            return NULL;
        }

        // Get Input
        $input = PratsRoomtypes::getInput();

        $default = array(
            'template' => 'tabs.tpl.php'
        );
        $args = wp_parse_args($atts, $default);

        // Prepare the
        $filename = PRATSROOMTYPES_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'shortcodes' . DIRECTORY_SEPARATOR . $args['template'];
        if ( !file_exists($filename) )
        {
            $filename = PRATSROOMTYPES_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'shortcodes' . DIRECTORY_SEPARATOR . $default['template'];
        }

        // Include the filename
        include $filename;

        return NULL;
    }

}
