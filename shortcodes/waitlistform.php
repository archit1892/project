<?php
/**
* Shortcode for Tabs
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
* Class Shortcode Tabs
* http://www.123contactform.com/form-2427888/My-Form-2
*
* @category   Shortcodes
* @package    PratsRoomtypes
* @author     Sumeet Shroff <sumeet@prateeksha.com>
* @copyright  2016 Sumeet Shroff (http://www.prateeksha.com)
* @license    see license.txt
* @link       http://www.prateeksha.com/
*/

class PratsRoomtypes_Shortcodes_Waitlistform extends PratsRoomtypes_Helpers_Shortcode
{
    /**
    * Method to add the shortcode
    *
    * @return void
    */
    public static function register()
    {
        // Add shortcode
        add_shortcode('roomtypes_waitlistform', array (__CLASS__, 'render'));
    }

    public static function render($atts)
    {
        $atts = shortcode_atts(
        array(
            'suite' => 0,
            'post_id' => 0
        ), $atts, 'roomtypes_waitlistform' );
        extract($atts);

        if (!$post_id) {
            return NULL;
        }

        $suite = intval($suite);

        // Company Id, Location,
        $company_id = PratsRoomtypes_Helpers_Wordpress::getPostMeta($post_id, 'company_id');
        $location = PratsRoomtypes_Helpers_Wordpress::getPostMeta($post_id, 'location');
        $title = get_post_field( 'post_title', $post_id);

        $suite_title = '';
        if ( $suite )
        $suite_title = get_post_field( 'post_title', $suite);

        ob_start();
        $queried_post = get_page_by_path('join-waitlist');
        $url = add_query_arg( array(
            'property_id' => $post_id,
            'title' => $title,
            'location' => $location,
            'company_id' => $company_id,
            'suite' => $suite,
            'suite_title' => $suite_title,
        ), get_permalink($queried_post) );
        ?>
        <a class="btn btn-sm btn-greyborder-small" href="#" data-featherlight="#waitlistform">
            <?php _e('Join Waitlist'); ?>
        </a>
        <?php
        return ob_get_clean();

        /**'overlayOpacity': '0.5',
			'overlayColor': 'black',**/
    }

}
