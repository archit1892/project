<?php

/**
* PratsRoomtypes - Project Management
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
* Class PratsPM_Tasks
*
* @category   Tasks
* @package    PratsRoomtypes
* @author     Sumeet Shroff <sumeet@prateeksha.com>
* @copyright  2016 Sumeet Shroff (http://www.prateeksha.com)
* @license    see license.txt
* @link       http://www.prateeksha.com/
*/

/**
* Class PratsRoomtypes_MetaBoxesTemplate
*/
class PratsRoomtypes_MetaBoxes_Template
{
    /**
    * Method to show the templates box
    *
    * Shows all the template from template directory, child template and the own plugin directory
    *
    * @return NULL
    */
    function show($post)
    {
        // Post
        if (!$post) {
            return;
        }

        $directories = array();
        $directories[] = get_template_directory() . 'pratsroomtypes' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $post->post_type . DIRECTORY_SEPARATOR;
        $directories[] = get_stylesheet_directory() . 'pratsroomtypes' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $post->post_type . DIRECTORY_SEPARATOR;
        $directories[] = PRATSROOMTYPES_PLUGIN_DIR . 'templates' . DIRECTORY_SEPARATOR .
        $post->post_type . DIRECTORY_SEPARATOR;

        $directories = PratsRoomtypes_Helpers_Array::toUnique($directories);

        $templates = array();
        foreach($directories as $directory)
        {
            if ( file_exists($directory) )
            {
                $templates = array_merge(glob($directory . "*.tpl.php"), $templates);
            }
        }

        $results = array();
        foreach($templates as $template)
        {
            $results[$template] = basename($template);
        }

        // Selected
        $selected = get_post_meta($post->ID, 'template', true);
        if ( !empty($selected) && !file_exists($selected) )
        {
            $selected = PRATSROOMTYPES_PLUGIN_DIR . 'templates' . DIRECTORY_SEPARATOR .
            $post->post_type . DIRECTORY_SEPARATOR . 'default.tpl.php';
        }

        // Prepare the select list
        $args = array(
            'varname' => 'template',
            'results' => $results,
            'id' => 'template',
            'selected' => $selected,
            'show_firstline' => true,
            'firstline_text' => 'Select a template',
            'firstline_value' => '',
        );
        echo PratsRoomtypes_Helpers_Html::dropdown($args);

        if ( !empty($selected) )
        {  ?>
            <p>
                <?php
                $url = PratsRoomtypes_Helpers_Common::urlPrint(array( 'type' => 'popup_print', 'post_type' => $post->post_type, 'post_id' => $post->ID));
                $url_print = PratsRoomtypes_Helpers_Common::urlPrint(array( 'type' => 'print', 'post_type' => $post->post_type, 'post_id' => $post->ID));
                add_thickbox();
                ?>
                <a href="<?php echo $url; ?>" class="button thickbox"><?php echo __('View'); ?></a>
                <a href="<?php echo $url_print; ?>" class="button" target="_blank"><?php echo __('Print'); ?></a>
            </p>
            <?php
        } ?>
        <input type="hidden" name="template_meta_noncename"
        id="template_meta_noncename"
        value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>" /><?php
    }

    /**
    * Method to save the meta box
    *
    * @return number
    */
    function save($post_id)
    {
        // Verify NOnce
        if (!wp_verify_nonce($_POST ['template_meta_noncename'],
        plugin_basename(__FILE__))) {
            return $post_id;
        }

        // Is the user allowed to edit the post or page?
        if (!current_user_can('edit_post', $post_id)) return $post_id;

        // Request object
        $request = PratsRoomtypes::getInput();
        $postmeta = array ();
        $postmeta ['template'] = $request->post('template', '', 'string');

        PratsRoomtypes_Helpers_Wordpress::savePostMeta($post_id, $postmeta);

        return $post_id;
    }

}
