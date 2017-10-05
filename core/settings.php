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
 * Settings Class
 *
 * @category   Core
 * @package    PratsRoomtypes
 * @author     Sumeet Shroff <sumeet@prateeksha.com>
 * @copyright  2016-17 Sumeet Shroff Prateeksha Web Design (http://www.prateeksha.com)
 * @license    see license.txt
 * @link       http://www.prateeksha.com/
 */
class PratsRoomtypes_Core_Settings
{

    /**
     * Settings container
     *
     * @var array
     * @access public
     */
    public
            $_config = array(
        // Option Name. Used for Config Admin
        'options_name'       => 'pratsroomtypes_settings',
        // These are list of files to be loaded in AutoLoad
        'classes'            => array(
            // Helpers
            'PratsRoomtypes_Helpers_Common',
            'PratsRoomtypes_Helpers_Filters_Input',
            'PratsRoomtypes_Metaboxes_Standard',
            'PratsRoomtypes_Helpers_Taxonomy',
            'PratsRoomtypes_Helpers_MetaBox',
            'PratsRoomtypes_Metaboxes_Notes',
            // Admin Pages
            'PratsRoomtypes_AdminPages_Invoices',
            'PratsRoomtypes_Helpers_Request',
            'PratsRoomtypes_Helpers_WooCommerce',
            'PratsRoomtypes_Taxonomy_Categories',
            'PratsRoomtypes_PostTypes_Roomtypes',
            'PratsRoomtypes_Helpers_PostType',
            'PratsRoomtypes_Models_Users',
            'PratsRoomtypes_Helpers_Models',
            'PratsRoomtypes_Helpers_Html',
            'PratsRoomtypes_MetaBoxes_Status',
            'PratsRoomtypes_MetaBoxes_Template',
            'PratsRoomtypes_Helpers_Array',
            'PratsRoomtypes_Helpers_Common',
            'PratsRoomtypes_Helpers_Wordpress',
            'PratsRoomtypes_Helpers_Matterport',
            'PratsRoomtypes_AdminPages_Options',
            'PratsRoomtypes_MetaBoxes_User',
            'PratsRoomtypes_AdminPages_Reports',
            'PratsRoomtypes_Metaboxes_Wpproperty',
            'PratsRoomtypes_Core_Ajax',
            'PratsRoomtypes_Metaboxes_Pdf',
            'PratsRoomtypes_Helpers_Shortcode',
            // Models
            'PratsRoomtypes_Models_Roomtypes',
            'PratsRoomtypes_Models_Property',
            // Posttypes
            'PratsRoomtypes_Posttypes_Roomtypes',
            // Short Codes
            'PratsRoomtypes_Shortcodes_Tabs',
            'PratsRoomtypes_Shortcodes_Slider',
            'PratsRoomtypes_Shortcodes_Scheduleform',
            'PratsRoomtypes_Shortcodes_Propertycontact',
            // toxonomy
            'PratsRoomtypes_Taxonomy_Buildingfeatures',
            'PratsRoomtypes_Taxonomy_Suitefeatures',
            'PratsRoomtypes_Taxonomy_Rentalterms',
            'PratsRoomtypes_Metaboxes_Datesavailable',
            'PratsRoomtypes_Shortcodes_Emailform',
            'PratsRoomtypes_Shortcodes_Customerform',
            'PratsRoomtypes_Shortcodes_Waitlistform',
            'PratsRoomtypes_Shortcodes_Bottomtagline'
        ),
        // Custom Posttypes used
        'posttypes'          => array(
            'roomtypes',
        ),
        // Short Codes used
        'shortcodes'         => array(
            'tabs',
            'slider',
            'scheduleform',
            'emailform',
            'customerform',
            'waitlistform',
            'propertycontact',
            'bottomtagline'
        ),
        'metaboxes'          => array(
            'wpproperty',
            'standard',
            'datesavailable',
            'pdf',
            'notes'
        ),
        // Short Codes used
        'taxonomy'           => array(
        //'buildingfeatures',
        //'suitefeatures',
        //'rentalterms',
        ),
        // Standard types used for search
        'standard_roomtypes' => array(
            'studio'            => 'Studio',
            'onebedroom'        => '1 Bed',
            'twobedrooms'       => '2 Beds',
            'threebedrooms'     => '3 Beds',
            'twobedroomplusden' => '2 Bedroom + Den',
        ),
        // Standard bath type
        'standard_bathrooms' => array(
            'onebath'    => '1+ Bath',
            'twobaths'   => '2+ Baths',
            'threebaths' => '3 Baths',
            'fourbaths'  => '4+ Baths',
        ),
        // Status used
        'status'             => array(
            'roomtypes' => array(
                'unpaid' => 'Active',
                'paid'   => 'Not Available'
            ),
        ),
        // Default Currency
        'currency'           => 'USD',
        'image_sizes'        => array(
            'thumbnail_360x230_crop' => array(
                'width'  => 360,
                'height' => 230,
                'crop'   => false
            ),
            'thumbnail_370x280_crop' => array(
                'width'  => 370,
                'height' => 280,
                'crop'   => true
            ),
            'thumbnail_430x280_crop' => array(
                'width'  => 430,
                'height' => 280,
                'crop'   => true
            ),
            'thumbnail_650x430_crop' => array(
                'width'  => 650,
                'height' => 430,
                'crop'   => true
            ),
            'thumbnail_320x210_crop' => array(
                'width'  => 320,
                'height' => 210,
                'crop'   => true
            ),
            'property_image'         => array(
                'width'  => 660,
                'height' => 480,
                'crop'   => true
            ),
        ),
        'availability'       => array(
            'days7'  => 7,
            'days30' => 30,
            'days60' => 60
        )
    );

    /**
     * Constructor
     *
     * @return void
     */
    function __construct()
    {
        // Get the options from the database
        $options = get_option($this->_config['options_name']);
        if ($options) {
            foreach ($options as $key => $value) {
                $key                 = sanitize_key($key);
                $this->_config[$key] = $value;
            }
        }

        // Put Image sizes
        //add_image_size( 'thumbnail_360x230_crop', 360, 230 ); // Image sizes to set
        //add_image_size( 'thumbnail_370x280_crop', 370, 280, true ); // For featured
        //add_image_size( 'thumbnail_430x280_crop', 430, 280, true ); // For browse page
        //add_image_size( 'thumbnail_650x430_crop', 650, 430, true ); // For Slider
        //add_image_size( 'thumbnail_320x210_crop', 320, 210, true ); // For Slider
    }

    /**
     * Method to load all the admin menu items
     *
     * @return void
     */
    public static
            function adminMenu()
    {
        // Dashboard
        add_submenu_page('edit.php?post_type=roomtypes', __('Dashboard', 'pratsroomtypes-text'), __('Dashboard', 'pratsroomtypes-text'), 'manage_options', 'reports', array('PratsRoomtypes_AdminPages_Dashboard', 'render'));

        // Options page
        add_submenu_page('edit.php?post_type=roomtypes', __('Options', 'pratsroomtypes-text'), __('Options', 'pratsroomtypes-text'), 'manage_options', 'options', array('PratsRoomtypes_AdminPages_Options', 'render'));

        // Reports
        add_submenu_page('edit.php?post_type=roomtypes', __('Reports', 'pratsroomtypes-text'), __('Reports', 'pratsroomtypes-text'), 'manage_options', 'reports', array('PratsRoomtypes_AdminPages_Reports', 'render'));
    }

    /**
     * Method to load the admin script and stylesheets
     *
     * @return void
     */
    static
            function adminScripts()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_script("jquery-ui-core");
        wp_enqueue_style('wp-color-picker');

        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
        add_thickbox();

        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('jquery-ui-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

        // Simply register
        wp_register_style('custom_wp_admin_css', PRATSROOMTYPES_PLUGIN_URL . '/assets/admin/css/admin-style.css', false, '1.0.0');
        wp_register_script('custom_wp_admin_js', PRATSROOMTYPES_PLUGIN_URL . '/assets/admin/js/admin-script.js', false, '1.0.0', true);

        wp_enqueue_style('custom_wp_admin_css');
        wp_enqueue_script('custom_wp_admin_js');

        $script = sprintf("var ajaxurl='%s'", admin_url('admin-ajax.php'));
        PratsRoomtypes_Helpers_Common::loadScriptDeclaration($script);
    }

    /**
     * Enqueue scripts & styles
     * For the frontend
     *
     * @since 1.0
     *
     * @return void
     */
    public static
            function enqueueScripts()
    {
        // load the feather light script
        wp_register_script('featherlight_script', PRATSROOMTYPES_PLUGIN_URL . '/assets/plugins/featherlight/featherlight.js', false, '1.0.0');

        wp_register_style('featherlight', PRATSROOMTYPES_PLUGIN_URL . '/assets/plugins/featherlight/featherlight.css', false, '1.0.0');

        
        wp_register_script('pratsroom_script', PRATSROOMTYPES_PLUGIN_URL . '/assets/js/script.js', false, '1.0.0');
        // Scroll Script
        //wp_register_script('scroll_script', PRATSROOMTYPES_PLUGIN_URL . '/assets/plugins/jscroll-master/jquery.jscroll.min.js', false, '1.0.0');
        // Scroll
        wp_register_script('viewport_script', PRATSROOMTYPES_PLUGIN_URL . '/assets/plugins/scroll-effects/viewportchecker.js', false, '1.0.0');
        wp_register_script('scroll_effects_script', PRATSROOMTYPES_PLUGIN_URL . '/assets/plugins/scroll-effects/scroll-effects.js', false, '1.0.0');
        wp_register_style('scroll_effects_css', PRATSROOMTYPES_PLUGIN_URL . '/assets/plugins/scroll-effects/scroll-effects.css', false, '1.0.0');
        wp_register_style('animate_css', PRATSROOMTYPES_PLUGIN_URL . '/assets/plugins/animate.css', false, '1.0.0');

        wp_register_script('swipe', '//cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.4/jquery.touchSwipe.min.js', false, '1.0.0');

        wp_enqueue_script('featherlight_script');
        wp_enqueue_style('featherlight');
        wp_enqueue_script('scroll_script');
        wp_enqueue_script('swipe');
        
        wp_enqueue_style('animate_css');
        wp_enqueue_style('scroll_effects_css');
        wp_enqueue_script('viewport_script');
        //wp_enqueue_script('scroll_effects_script');
        if(!is_page(7)){
             wp_enqueue_script('pratsroom_script'); 
        }
      
    }

    /**
     * Get Currencies
     *
     * @since 1.0
     *
     * @return array $currencies A list of the available currencies
     */
    public static
            function getCurrencies()
    {
        $currencies = array(
            'USD'  => __('US Dollars (&#36;)', 'pratsroomtypes-text'),
            'EUR'  => __('Euros (&euro;)', 'pratsroomtypes-text'),
            'GBP'  => __('Pounds Sterling (&pound;)', 'pratsroomtypes-text'),
            'AUD'  => __('Australian Dollars (&#36;)', 'pratsroomtypes-text'),
            'BRL'  => __('Brazilian Real (R&#36;)', 'pratsroomtypes-text'),
            'CAD'  => __('Canadian Dollars (&#36;)', 'pratsroomtypes-text'),
            'CZK'  => __('Czech Koruna', 'pratsroomtypes-text'),
            'DKK'  => __('Danish Krone', 'pratsroomtypes-text'),
            'HKD'  => __('Hong Kong Dollar (&#36;)', 'pratsroomtypes-text'),
            'HUF'  => __('Hungarian Forint', 'pratsroomtypes-text'),
            'ILS'  => __('Israeli Shekel (&#8362;)', 'pratsroomtypes-text'),
            'JPY'  => __('Japanese Yen (&yen;)', 'pratsroomtypes-text'),
            'MYR'  => __('Malaysian Ringgits', 'pratsroomtypes-text'),
            'MXN'  => __('Mexican Peso (&#36;)', 'pratsroomtypes-text'),
            'NZD'  => __('New Zealand Dollar (&#36;)', 'pratsroomtypes-text'),
            'NOK'  => __('Norwegian Krone', 'pratsroomtypes-text'),
            'PHP'  => __('Philippine Pesos', 'pratsroomtypes-text'),
            'PLN'  => __('Polish Zloty', 'pratsroomtypes-text'),
            'SGD'  => __('Singapore Dollar (&#36;)', 'pratsroomtypes-text'),
            'SEK'  => __('Swedish Krona', 'pratsroomtypes-text'),
            'CHF'  => __('Swiss Franc', 'pratsroomtypes-text'),
            'TWD'  => __('Taiwan New Dollars', 'pratsroomtypes-text'),
            'THB'  => __('Thai Baht (&#3647;)', 'pratsroomtypes-text'),
            'INR'  => __('Indian Rupee (&#8377;)', 'pratsroomtypes-text'),
            'TRY'  => __('Turkish Lira (&#8378;)', 'pratsroomtypes-text'),
            'RIAL' => __('Iranian Rial (&#65020;)', 'pratsroomtypes-text'),
            'RUB'  => __('Russian Rubles', 'pratsroomtypes-text')
        );

        return apply_filters('get_currencies', $currencies, 10, 3);
    }

    function getVideoLink($link)
    {
        //v($link);
        /*
          $pattern = '@(http|https)://(www\.)?youtu[^\s]*@i';
          //This was just for test
          //$link = "abc def http://www.youtube.com/watch?v=t-ZRX8984sc ghi jkm";
          $matches = array();
          preg_match_all($pattern, $link, $matches);
          //v($matches);
          foreach ($matches[0] as $match) {
          $link = str_replace($match, '<iframe width="560" height="315" src="' . $match . '" frameborder="0" allowfullscreen></iframe>', $link);
          }
         */

        return array(
            "type"                   => 'video',
            "alt"                    => "video",
            "href"                   => $link,
            "src"                    => PRATSROOMTYPES_PLUGIN_URL . "assets/images/video-play.jpg",
            "thumbnail_320x210_crop" => array(
                PRATSROOMTYPES_PLUGIN_URL . "assets/images/video-play.jpg",
                225, 150, true
            ),
            "thumbnail_650x430_crop" => array(
                PRATSROOMTYPES_PLUGIN_URL . "assets/images/video-play.jpg",
                225, 150, true
            )
        );
    }

}
