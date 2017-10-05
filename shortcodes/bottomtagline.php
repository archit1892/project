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

class PratsRoomtypes_Shortcodes_Bottomtagline extends PratsRoomtypes_Helpers_Shortcode
{
    /**
    * Method to add the shortcode
    *
    * @return void
    */
    public static function register()
    {
        // Add shortcode
        add_shortcode('bottomtagline', array (__CLASS__, 'render'));
    }

    public static function render($atts)
    {
        $atts = shortcode_atts(
        array(
            'post_id' => 0
        ), $atts, 'bottomtagline' );
        extract($atts);

        if (!$post_id) {
            global $post;
            $post_id = $post->ID;
        }

        if (!$post_id) {
            return NULL;
        }

        $title = get_post_field( 'post_title', $post_id);

        ob_start();
        ?>
        <div class="col-md-9">
            <h2 class="tagline">
                <?php echo sprintf(__('Be the first to know about new listings, join the waitlist for <strong>%s</strong>'), $title); ?>
            </h2>
        </div>
        <div class="col-md-3 text-center">
            <a class="btn btn-greyborder" href="#"
            data-featherlight="#waitlistform"><?php _e('Join Waitlist'); ?></a>
        </div>
        <?php
        return ob_get_clean();

        /**'overlayOpacity': '0.5',
        'overlayColor': 'black',**/
    }

}
