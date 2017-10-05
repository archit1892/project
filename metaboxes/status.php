<?php

/**
* Prateeksha_PratsInvoice - Project Management
*
* @category   Tasks
* @package    Prateeksha_PratsInvoice
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
* Class PratsRoomtypes_Tasks
*
* @category   Tasks
* @package    Prateeksha_PratsInvoice
* @author     Sumeet Shroff <sumeet@prateeksha.com>
* @copyright  2016 Sumeet Shroff Prateeksha Web Design (http://www.prateeksha.com)
* @license    see license.txt
* @link       http://www.prateeksha.com/
*/
class PratsRoomtypes_MetaBoxes_Status
{
    /**
    * Method to show the task box
    * Shows all the form elements in normal form
    *
    * @return NULL
    */
    function show($post)
    {
        $request = PratsRoomtypes::getInput();

        $selected = get_post_meta($post->ID, 'status', true);
        ?>
        <p><?php

        $args = array(
            'key' => $post->post_type,
            'selected' => $selected);
        echo PratsRoomtypes_Helpers_Html::dropdownStatus( $args );

            ?>
        </p>
        <input type="hidden" name="status_meta_noncename"
        id="status_meta_noncename"
        value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>" /></p><?php
        return;
    }

    /**
    * Method to save the meta box
    *
    * @return number
    */
    function save($post_id)
    {
        // Verify NOnce
        if (!wp_verify_nonce($_POST ['status_meta_noncename'],
        plugin_basename(__FILE__))) {
            return $post_id;
        }

        // Is the user allowed to edit the post or page?
        if (!current_user_can('edit_post', $post_id)) return $post_id;

        // Request object
        $request = PratsRoomtypes_Core_Init::getInput();

        // Now Save
        $postmeta = array ();
        $postmeta ['status'] = $request->post('status', '', 'string');

        // Save items
        PratsRoomtypes_Helpers_Wordpress::savePostMeta($post_id, $postmeta);

        return $post_id;
    }

}
