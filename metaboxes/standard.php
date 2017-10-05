<?php

/**
* Prateeksha_PratsPM - Project Management
*
* @category   Projects
* @package    Prateeksha_PratsPM
* @author     Sumeet Shroff <sumeet@prateeksha.com>
* @copyright  2016 Sumeet Shroff Prateeksha Web Design (http://www.prateeksha.com)
* @license    see license.txt
* @link       http://www.prateeksha.com/
*/

if (!defined('ABSPATH')) {
    exit();
}

/**
* Prateeksha_PratsPM - Project Management
*
* @category   Projects
* @package    Prateeksha_PratsPM
* @author     Sumeet Shroff <sumeet@prateeksha.com>
* @copyright  2016 Sumeet Shroff Prateeksha Web Design (http://www.prateeksha.com)
* @license    see license.txt
* @link       http://www.prateeksha.com/
*/

/**
* Class PratsPm_MetaBoxesTemplate
*/
class PratsRoomtypes_Metaboxes_Standard extends PratsRoomtypes_Helpers_MetaBox
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
            'id' => 'standard',
            'label' => __('Select Suite Type'),
            'position' => 'normal',
            'save_function' => 'save',
            'show_priority' => 'high',
            'save_priority' => 5,
            'callback_args' => array()
        );

        parent::registerA($args);
    }

    /**
    * Method to show the standard box
    * Shows all the form elements in normal form
    *
    * @return NULL
    */
    public static function show($post)
    {
        if  ( !$post )
        {
            return NULL;
        }

        $a = PratsRoomtypes::getInstance();
        $results = $a->getOption('standard_roomtypes');

        $selected = PratsRoomtypes_Helpers_Wordpress::getPostMeta($post->ID, 'standard_roomtypes');

        ?>
        <table>
            <tr>
                <td>Type : </td>
                <td><?php
                echo PratsRoomtypes_Helpers_Html::dropdown(array(
                    'varname' => 'standard_roomtypes',
                    'show_firstline' => false,
                    'results' => $results,
                    'results' => $results,
                    'selected' => $selected)
                );
                ?></td>
            </tr>
        </table>

        <input type="hidden" name="standard_meta_noncename"
        id="standard_meta_noncename"
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
        if (!wp_verify_nonce($_POST ['standard_meta_noncename'],
        plugin_basename(__FILE__)))
        {
            return $post_id;
        }

        // Is the user allowed to edit the post or page?
        if (!current_user_can('edit_post', $post_id)) return $post_id;

        $selected = (array)

        // Request object
        $request = PratsRoomtypes::getInput();

        $postmeta = array(
            'standard_roomtypes' => $request->post('standard_roomtypes')
        );

        // Save items
        PratsRoomtypes_Helpers_Wordpress::savePostMeta($post_id, $postmeta);

        return $post_id;
    }

}
