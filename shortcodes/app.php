<?php

/**
* Shortcode for
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


class PratsRoomtypes_Shortcodes_App extends PratsRoomtypes_Helpers_Shortcode
{

    /**
    * Block comment
    *
    * @param type
    * @return void
    */
    function register()
    {
        add_shortcode('pratsroomtypes_display',
        array (__CLASS__, 'render'));
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
        $input = PratsRoomtypes::getInput();

        // Check for post id

        // Define all the atts
        $template = 'default.tpl.php';
        if ( issset($template['template']))
        {
            $template = $atts['template'].'tpl.php'
            // Check for template
        }

        // Load the template
        include PRATSROOMTYPES_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'roomtypes' . DIRECTORY_SEPARATOR . $template);


        return NULL;
    }

    /**
    * Method to execute the cancel button
    *
    * @author Sumeet Shroff
    * @since 1.0
    *
    * @return void
    */
    public static function menu()
    {
        global $post;
        if ( !$post )
        {
            return ;
        }

        $menus = PratsRoomtypesConfig::getInstance()->frontmenu;
        ?>
        <div id="navbar" class="navbar-collapse2 collapse2">
            <ul class="nav navbar-nav">
                <?php

                foreach($menus as $posttype => $menu)
                {
                    if ( current_user_can('views_'.$posttype) )
                    ?>
                    <li><a href="?page_id=<?php echo $post->ID; ?>&amp;view=<?php echo $posttype; ?>"><?php echo __($menu, 'pratspm'); ?></a></li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <?php

        return true;
    }

    /**
    * Method to execute the cancel button
    *
    * @author Sumeet Shroff
    * @since 1.0
    *
    * @return void
    */
    public static function cancel()
    {
        $common = PratsRoomtypes::helper('common');
        echo $common->alert(__('You have not save your questionnaire'), 'warning');

        $page_link = $common->getPageLink('projects_page');
        $script = 'window.location.href = "' . $page_link . '"';
        $common->addOnLoad($script);

        return null;
    }

}
