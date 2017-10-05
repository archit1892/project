<?php


/**
* PratsRoomtypes
*
* @category   Shortcodes
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
* Class PratsPM_Tasks
*
* @category   Tasks
* @package    PratsRoomtypes
* @author     Sumeet Shroff <sumeet@prateeksha.com>
* @copyright  2016 Sumeet Shroff (http://www.prateeksha.com)
* @license    see license.txt
* @link       http://www.prateeksha.com/
*/


class PratsRoomtypes_Shortcodes_Slider extends PratsRoomtypes_Helpers_Shortcode
{

    /**
    * Block comment
    *
    * @param type
    * @return void
    */
    function register()
    {
        add_shortcode('roomtypes_slider', array (__CLASS__, 'render'));
    }

    /**
    * Function to render the form
    *
    * @param array $atts
    *
    * @return NULL|string
    */
    public static function render($atts)
    {
        global $post;
        if ( !$post->ID )
        {
            return NULL;
        }

        // Get Input
        $input = PratsRoomtypes::getInput();

        $default = array(
            'template' => 'slidercollage.tpl.php'
        );
        $args = wp_parse_args($atts, $default);

        // Prepare the
        $filename = PRATSROOMTYPES_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'shortcodes' . DIRECTORY_SEPARATOR . $args['template'];
        if ( !file_exists($filename) )
        {
            $filename = PRATSROOMTYPES_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'shortcodes' . DIRECTORY_SEPARATOR . $default['template'];
        }

        include $filename;

        return NULL;
    }

}
