<?php

/**
* PratsRoomtypes - Project Management
*
* @category   Notes
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
* Class PratsPM_Tasks
*
* @category   Notes
* @package    PratsRoomtypes
* @author     Sumeet Shroff <sumeet@prateeksha.com>
* @copyright  2016 Sumeet Shroff Prateeksha Web Design (http://www.prateeksha.com)
* @license    see license.txt
* @link       http://www.prateeksha.com/
*/

class PratsRoomtypes_Metaboxes_Notes extends PratsRoomtypes_Helpers_Metabox
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
            'id' => 'notes',
            'label' => __('Notes'),
            'position' => 'normal',
            'save_function' => 'save',
            'show_priority' => 'default',
            'save_priority' => 5,
            'callback_args' => array()
        );

        parent::registerA($args);
    }
    /**
    * Method to show the notes box
    * Shows all the form elements in normal form
    *
    * @return NULL
    */
    public static function show($post)
    {
        if (!$post->ID) {
            return;
        }

        $request = PratsRoomtypes::getInput();

        // Get the previous data
        $notes = (array)get_post_meta($post->ID, 'notes', true);
        if ( isset($_POST['notes']) ) {
            $notes = $request->post('notes', $notes, 'raw');
        }

        ?>
        <script>

        /**
        * Project - Tasks
        */
        addNotesItem = function() {
            str = '<div class="notes_item">'
            + ' <textarea rows="10" cols="30" name="notes[]" style="width: 90%"></textarea>'
            + '<input type="button" value="Delete" '
            + 'class="button delete" onClick="deleteNoteItem(this);" />'
            + '</div>';
            jQuery('#notes-list').append(str);
            num--;
        }

        deleteNoteItem = function(obj) {
            jQuery(obj).parents('.notes_item').remove();
        }

        </script>
        <div id="notes-list">
            <?php
            if ($notes)
            {
                foreach ($notes as $note)
                {
                    $note = strip_tags($note);
                    ?>
                    <div class="notes_item">
                        <textarea rows="10" cols="30" name="notes[]" style="width: 90%"><?php echo $note; ?></textarea>
                        <input type="button" value="Delete" class="button delete" onClick="deleteNoteItem(this);" />
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <input type="button" id="add" value="Add" class="button btn btn-danger"
        onClick="addNotesItem();" />
        <input type="hidden" name="notes_meta_noncename"
        id="notes_meta_noncename"
        value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>" />
        <?php
        return;
    }

    /**
    * Method to save the meta box
    *
    * @return number
    */
    public static function save($post_id)
    {
        if ( !$post_id )
        {
            return ;
        }

        // Verify NOnce
        if (!wp_verify_nonce($_POST ['notes_meta_noncename'],
        plugin_basename(__FILE__))) {
            return $post->ID;
        }

        // Is the user allowed to edit the post or page?
        if (!current_user_can('edit_post', $post_id)) return $post_id;

        // Request object
        $request = PratsRoomtypes::getInput();

        // Now Save
        $postmeta = array ();

        $results = $request->post('notes', array(), 'array');
        if ( $results )
        {
            foreach($results as $key => $value)
            {
                $results[$key] = strip_tags($value);
            }
        }
        $postmeta ['notes'] = $results;

        // Save items
        PratsRoomtypes_Helpers_Wordpress::savePostMeta($post_id, $postmeta);

        return $post_id;
    }
}
