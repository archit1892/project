<?php

/**
* PratsRoomtypes - Project Management
*
* @category   Projects
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
* PratsRoomtypes - Project Management
*
* @category   Projects
* @package    PratsRoomtypes
* @author     Sumeet Shroff <sumeet@prateeksha.com>
* @copyright  2016 Sumeet Shroff Prateeksha Web Design (http://www.prateeksha.com)
* @license    see license.txt
* @link       http://www.prateeksha.com/
*/

class PratsRoomtypes_MetaBoxes_Wpproperty extends PratsRoomtypes_Helpers_MetaBox
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
            'id' => 'wpproperty',
            'label' => __('Select Property to Assign Room To'),
            'position' => 'normal',
            'save_function' => 'save',
            'show_priority' => 'default',
            'save_priority' => 5,
            'callback_args' => array()
        );

        parent::registerA($args);
    }


    /**
    * Method to show the property box
    * Shows all the form elements in normal form
    *
    * @return NULL
    */
    function show( $post )
    {
        if ( !post_type_exists( 'property' ) ) {
            echo __('Property Post Type does not exists');
            return ;
        }

        $selected = (int) get_post_meta($post->ID, 'property', true);
        $properties = PratsRoomtypes::getModel('property')
						->setPostsPerPage(-1)
						->getList(array('posts_per_page' => -1));
        ?>
        <p>
            <select id="property" name="property">
                <option value=""><?php echo  __('Select a property'); ?></option>
                <?php
                foreach ($properties as $result) {
                    ?>
                    <option value="<?php echo $result->ID; ?>"
                        <?php
                        echo ($result->ID == $selected ? 'selected' : '');
                        ?>>
                        <?php echo $result->post_title?>
                    </option>
                    <?php
                }
                ?>
            </select>
            <p class="description"><?php echo __('Select a property'); ?></p>
        </p>
        <input type="hidden" name="property_meta_noncename"
        id="property_meta_noncename"
        value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>" />
        <?php

    }

    /**
    * Method to save the meta box
    *
    * @return number
    */
    function save($post_id)
    {
        if ( !post_type_exists( 'property' ) )
        {
            return ;
        }

        // Verify NOnce
        if (!wp_verify_nonce($_POST ['property_meta_noncename'],
        plugin_basename(__FILE__))) {
            return $post_id;
        }

        // Is the user allowed to edit the post or page?
        if (!current_user_can('edit_post', $post_id)) return $post_id;

        // Request object
        $request = PratsRoomtypes::getInput();

        // Now Save
        $postmeta = array ();
        $postmeta ['property'] = $request->post('property', '', 'integer');

        // Save items
        PratsRoomtypes_Helpers_Wordpress::savePostMeta($post_id, $postmeta);

        return $post_id;
    }
}
