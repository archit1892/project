<?php

/**
* PratsRoomtypes - WP Preperty Add-on
* http://multidatespickr.sourceforge.net/
* We are not using multiple dates. But using a single date
*
* @category   Metaxbox
* @package    PratsRoomtypes
* @author     Sumeet Shroff <sumeet@prateeksha.com>
* @copyright  2016 Sumeet Shroff Prateeksha Web Design (http://www.prateeksha.com)
* @license    see license.txt
* @link       http://www.prateeksha.com/
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit();
}

/**
* PratsRoomtypes - WP Preperty Add-on
* http://multidatespickr.sourceforge.net/
*
* @category   Metaxbox
* @package    PratsRoomtypes
* @author     Sumeet Shroff <sumeet@prateeksha.com>
* @copyright  2016 Sumeet Shroff Prateeksha Web Design (http://www.prateeksha.com)
* @license    see license.txt
* @link       http://www.prateeksha.com/
*/

class PratsRoomtypes_Metaboxes_Datesavailable extends PratsRoomtypes_Helpers_MetaBox
{
    /**
    * Method to register a metabox meta box
    *
    * @param array $args
    *
    * @return NULL
    */
    public static function register()
    {
        $args = array(
            'posttype' => 'roomtypes',
            'classname' => __CLASS__,
            'function' => 'show',
            'id' => 'datesavailable',
            'label' => __('Dates Available'),
            'position' => 'normal',
            'save_function' => 'save',
            'show_priority' => 'default',
            'save_priority' => 5,
            'callback_args' => array()
        );

        parent::registerA($args);
    }

    /**
    * Block comment
    *
    * @param type
    * @return void
    */
    public static function show($post)
    {
        // Get the save value
        $availability_date = PratsRoomtypes_Helpers_Wordpress::getPostMeta($post->ID, 'availability_date');

        ?>
        <input type="text" class="datepicker" name="availability_date" value="<?php echo $availability_date; ?>" />
        <script>
        jQuery( function() {
            jQuery(".datepicker").datepicker({
                dateFormat : "dd-mm-yy"
            });
        });
        </script>

        <input type="hidden" name="datesavailable_meta_noncename"
        id="datesavailable_meta_noncename"
        value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>" />
        <?php
    }

    /**
    * Method to save the meta box
    *
    * @return number
    */
    public static function save($post_id)
    {
        // Verify NOnce
        if (!wp_verify_nonce(@$_POST ['datesavailable_meta_noncename'],
        plugin_basename(__FILE__))) {
            return $post_id;
        }

        // Is the user allowed to edit the post or page?
        if (!current_user_can('edit_post', $post_id)) return $post_id;

        // Request object
        $input = PratsRoomtypes::getInput();

        // Now Save
        $postmeta = array ();
        $availability_date = $input->post('availability_date', '');
        $postmeta['availability_date'] = $availability_date;

        // Save
        PratsRoomtypes_Helpers_Wordpress::savePostMeta($post_id, $postmeta);

        // We have to get the postmeta value and then again save 
        // the new date
        $property = PratsRoomtypes_Helpers_Wordpress::getPostMeta($post_id, 'property');
    
       // return $post_id;
    }

}
