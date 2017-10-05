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


class PratsRoomtypes_Helpers_Shortcode
{
    static $return = true;
    static $order = 'post_modified';
    static $order_dir = 'desc';

    /**
    * Put all the headers here
    *
    * @var unknown
    */
    static $column_heads = array ();

    /**
    * Put all the width here
    *
    * @var unknown
    */
    static $column_width = array ();

    static $max_num_pages = 0;
    static $post_per_page = 10;

    /**
    *
    * @param unknown $post
    */
    function showButtons($post)
    {
        ?>
        <span><a class="btn btn-success btn-sm"
            href="<?php
            echo add_query_arg(
            array (
            'action' => 'edit',
            'id' => $post->ID
        ), $page_link);
        ?>">Edit </a></span>
        <?php
    }

}


/**
* Block comment
*
* @param type
* @return void
*/
class PratsRoomtypesShortcodesAppController
{
    function display($atts)
    {
        $view = $atts['view'];
        $filename = PRATSPM_PLUGIN_DIR . 'shortcodes' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view . DIRECTORY_SEPARATOR . 'view.php';
        if ( !file_exists($filename) )
        {
            throw new Exception('Controller could not loaded');
        }

        include_once $filename;
        $class = 'PratsRoomtypesShortcodesView_'.$view;
        $view = new $class;
        $view->display($atts);
    }
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

class PratsRoomtypes_shortcodeHelper_View
{
    public $vars = array();

    /**
    *
    *
    **/
    function display($atts, $format = 'default')
    {
        if ( empty($format) ) $format = 'default';

        if ( is_string($atts))
        {
            $view = $atts;
        }
        else
        {
            $view = $atts['view'];
        }

        include_once PRATSPM_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'shortcodes' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $view . DIRECTORY_SEPARATOR . 'tmpl'. DIRECTORY_SEPARATOR  . $format.'.php';
    }

    /**
    * Method to assign some values to the class
    *
    **/
    function assign($key, $value)
    {
        $this->vars[$key] = $value;
    }

    /**
    * Method to assign some values to the class
    *
    **/
    function assignGet($key)
    {
        if ( isset($this->vars[$key]) ) return $this->vars[$key];
        return NULL;
    }

    /**
    * Method to assign some values to the class
    *
    **/
    function getAssigned($key)
    {
        if ( isset($this->vars[$key]) ) return $this->vars[$key];
        return NULL;
    }

    function pagination()
    {
        $add_args = array (
        'order' => $this->getAssigned('order'),
        'order_dir' => $this->getAssigned('order_dir'),
        'search' => $this->getAssigned('search'));
        $add_args = array_filter($add_args);

        // static::getUrl(array ('search' => $search))
        $big = 999999999;
        $base = str_replace($big, '%#%', html_entity_decode(esc_url(get_pagenum_link($big))));

        return paginate_links( array (
        'base' => $base,
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $this->getAssigned('max_num_pages'),
        'add_args' => $add_args) );
    }

    /**
    * Method to get the start counter
    *
    * @return void
    */
    function getStartCounter()
    {
        return ($this->getAssigned('post_per_page')  * ($this->getAssigned('paged')-1)) + 1;
    }

    /**
    * Block comment
    *
    * @param type
    * @return void
    */
    function getOrderTitle($title, $field, $direction)
    {
        $order = $this->getAssigned('order');
        if ( $order == $field )
        {
            // Change the direction
            if ( strtolower($direction) == 'asc')
            {
                $direction = 'desc';
            }
            else
            {
                $direction = 'asc';
            }

            $direction_html = '<img src="'.PRATSQN_PLUGIN_URL.'/assets/images/arrow-down.png" width="16px" height="16px">';
            if ( $direction == 'desc')
            {
                $direction_html = '<img src="'.PRATSQN_PLUGIN_URL.'/assets/images/arrow-up.png" width="16px" height="16px">';
            }

            $html = sprintf('<a href="javascript:void()" onClick="submitListForm(\'%s\',\'%s\');">%s %s</a>',
            $field, $direction, $title, $direction_html);
        }
        else {
            $html = sprintf('<a href="javascript:void()" onClick="submitListForm(\'%s\',\'%s\');">%s</a>',
            $field, $direction, $title);
        }

        return $html;
    }

    /**
    * Method to return a button. View, Edit
    * Checks for the user can and only then shows the button
    *
    * @param string $page URL
    * @param string $view View
    * @param string $posttype Post Type
    * @param string $action Action - e.g. view, edit
    * @param integer $id Post Id
    * @param string $title Title
    *
    * @return string|HTML Link to the page with the action
    **/
    function button($args)
    {
        $defaults = array(
            'page' => PratsRoomtypes_Helpers_Common::getFrontEndUrl('pratsroomtypesapp'),
            'view' => "project",
            'posttype' => "projects",
            'action' => 'view',
            'id' => 0,
            'title' => 'View',
        );

        $args = wp_parse_args( $args, $defaults );

        if ( current_user_can($args['action'] . '_' . $args['posttype']) )
        {
            $url = add_query_arg(array('action' => $args['action'], 'view' => $args['view'], 'id' => $args['id']), $args['page']);
            return sprintf('<a href="%s" class="btn btn-primary">%s</a>', $url, __($args['title']) );
        }

    }
}
