<?php

/**
 * Settings
 *
 * @category   Core
 * @package    PratsRoomtypes
 * @author     Sumeet Shroff <sumeet@prateeksha.com>
 * @copyright  2016-17 Sumeet Shroff Prateeksha Web Design (http://www.prateeksha.com)
 * @license    see license.txt
 * @link       http://www.prateeksha.com/
 */
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit();
}

/**
 * Settings
 *
 * @category   Core
 * @package    PratsRoomtypes
 * @author     Sumeet Shroff <sumeet@prateeksha.com>
 * @copyright  2016-17 Sumeet Shroff Prateeksha Web Design (http://www.prateeksha.com)
 * @license    see license.txt
 * @link       http://www.prateeksha.com/
 */
class PratsRoomtypes_Core_Init
{

    /**
     * Instance of this object
     *
     * @var string
     * @access protected
     * */
    protected static
            $_instance;

    /**
     * Instance of this object
     *
     * @var array
     * @access protected
     * */
    protected
            $_config = array();

    /**
     * Provide access to this singleton object
     *
     * @return this class
     */
    public static
            function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Instantiate class
     * Sets the paths for template, css, js and icons, and helpers
     * Loads the posttypes, metaboxes
     * Registers the post types, meta boxes
     */
    protected
            function __construct()
    {
        // Load the core file
        include_once 'settings.php';
        $a             = new PratsRoomtypes_Core_Settings;
        $this->_config = $a->_config;

        // Set the Auto Load Function
        spl_autoload_register(array($this, '__autoload'));

        // Loads the postypes first
        $this->loadCoreModules('posttypes');
        $this->loadCoreModules('taxonomy');
        $this->loadCoreModules('shortcodes');

        $image_sizes = $this->getOption('image_sizes');
        if ($image_sizes) {
            foreach ($image_sizes as $key => $value) {
                add_image_size($key, $value['width'], $value['height'], $value['crop']);
            }
        }

        add_filter('wpp::get_properties::matching_ids', array('PratsRoomtypes_Models_Property', "parse_request_availability"), 1, 1);
        add_filter('wpp::get_properties::matching_ids', array('PratsRoomtypes_Models_Property', "parse_request_bedrooms"), 1, 1);
        add_filter('wpp::get_properties::matching_ids', array('PratsRoomtypes_Models_Property', "parse_request_neighbourhood"), 1, 2);
        add_filter('wpp::get_properties::matching_ids', array('PratsRoomtypes_Models_Property', "parse_request_show_only_pet_friendly"), 1, 3);


        // Admin Initialize
        add_action('init', array(&$this, 'init'));
        add_action('admin_init', array(&$this, 'adminInit'));
        add_action('admin_init', array('PratsRoomtypes_Adminpages_Options', 'register_settings'));
        add_action('admin_menu', array('PratsRoomtypes_Core_Settings', 'adminMenu'));
        add_action('admin_enqueue_scripts', array('PratsRoomtypes_Core_Settings', 'adminScripts'));

        // Front Initialize
        add_action('wp_enqueue_scripts', array('PratsRoomtypes_Core_Settings', 'enqueueScripts'));

        // Notices
        add_action('admin_notices', array('PratsRoomtypes_Core_Init', 'notices'), 10, 2);
    }

    /**
     * Block comment
     *
     * @return void
     */
    function init()
    {
        
    }

    function adminInit()
    {
        $this->loadCoreModules('metaboxes');
    }

    /**
     * Load Core modules
     *
     * @return void
     */
    function loadCoreModules($modules)
    {
        foreach ($this->_config[$modules] as $module) {
            $classname = sprintf("PratsRoomtypes_%s_%s", ucfirst($modules), ucfirst($module));
            call_user_func(array($classname, 'register'));
        }
    }

    /**
     * Block comment
     *
     * @param type
     * @return void
     */
    function getOption($key, $default = NULL, $type = 'string')
    {
        if (!isset($this->_config[$key])) {
            return $default;
        }

        $value = $this->_config[$key];
        return $value;
    }

    /**
     * Block comment
     *
     * @param type
     * @return void
     */
    function getOptionArray($key, $subkey, $default = NULL)
    {
        if (!isset($this->_config[$key][$subkey])) {
            return $default;
        }

        return $this->_config[$key][$subkey];
    }

    /**
     * Method to return the status
     *
     * @param type
     * @return void
     */
    function getStatus($key, $subkey)
    {
        $statuses = PratsRoomtypes::getInstance()->getOptionArray('status', $key);
        if (isset($statuses[$subkey])) {
            return $statuses[$subkey];
        }
        return NULL;
    }

    /**
     * Block comment
     *
     * @param type
     * @return void
     */
    function setOption($key, $value = NULL)
    {
        $key = sanitize_key($key);

        $option_name = $this->_config['options_name'];
        $options     = get_option($option_name);
        if (!$options) {
            $options       = array();
            $options[$key] = $value;
            $deprecated    = null;
            $autoload      = 'no';

            add_option($option_name, $options, $deprecated, $autoload);
        }
        else {
            $options[$key] = $value;
            update_option($option_name, $options);
        }

        wp_cache_delete($option_name, 'options');
        $this->_config[$key] = $value;
    }

    /**
     * Block comment
     *
     * @param type
     * @return void
     */
    public static
            function getInput()
    {
        static $request;
        if (!isset($request)) {
            $request = new PratsRoomtypes_Helpers_Request;
        }

        return $request;
    }

    /**
     * Block comment
     *
     * @param type
     * @return void
     */
    function __autoload($name)
    {
        if (in_array($name, $this->_config['classes'])) {
            $path       = strtolower($name);
            $path_array = explode('_', $path);
            unset($path_array[0]);
            $file       = PRATSROOMTYPES_PLUGIN_DIR . implode(DIRECTORY_SEPARATOR, $path_array) . '.php';
            include ($file);
        }
    }

    /**
     * Block comment
     *
     * @param type
     * @return void
     */
    public static
            function parseWp_Error($errors)
    {
        $error = array_pop($errors->errors);
        $error = array_pop($error);
        return $error;
    }

    /**
     * Method to show notices
     *
     *
     * */
    public static
            function notices()
    {
        $a       = PratsRoomtypes::getInstance();
        $notices = $a->getOption('notices');
        if (!$notices) {
            return NULL;
        }

        // There are many messages
        foreach ($notices as $key => $notice) {
            if (!isset($notice['message']) || !isset($notice['message'])) {
                continue;
            }

            $message = $notice['message'];
            $type    = $notice['type'];

            // Type can be Error, Notice, Warning, Message, Success
            switch (strtolower($type))
            {
                // Warning
                case 'warning' :
                    $class = 'notice notice-warning';
                    break;

                // Error
                case 'error' :
                case 'notice' :
                    $class = 'notice notice-error';
                    break;

                // Success
                case 'success' :
                    $class = 'notice notice-success';
                    break;

                default :
                    $class = 'notice notice-info';
                    break;
            }

            printf('<div class="%1$s"><p>%2$s</p></div>', $class, __($message, 'pratsroomtypes-txt'));
        }

        $a->setOption('notices', array());

        return true;
    }

    /**
     * Method to show a message
     *
     * @param string $message
     * @param string $type
     *
     * @return
     */
    function enqueueMessage($message, $type = 'info')
    {
        // Message
        $message = sanitize_text_field($message);
        $type    = sanitize_text_field($type);

        $notices = $this->getOption('notices');

        if (!$notices) {
            $notices = array(array('message' => $message, 'type' => $type));
        }
        else {
            $notices[] = array('message' => $message, 'type' => $type);
        }

        $this->setOption('notices', $notices);
    }

    /**
     * Block comment
     *
     * @param type
     * @return void
     */
    function getModel($model)
    {
        static $models;
        if (isset($models[$model])) {
            return $models[$model];
        }

        $classname      = 'PratsRoomtypes_Models_' . ucfirst($model);
        return $models[$model] = new $classname;
    }

}

class PratsRoomtypes extends PratsRoomtypes_Core_Init
{
    
}
