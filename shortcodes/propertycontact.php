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
*
* @category   Shortcodes
* @package    PratsRoomtypes
* @author     Sumeet Shroff <sumeet@prateeksha.com>
* @copyright  2016 Sumeet Shroff (http://www.prateeksha.com)
* @license    see license.txt
* @link       http://www.prateeksha.com/
*/

class PratsRoomtypes_Shortcodes_Propertycontact extends PratsRoomtypes_Helpers_Shortcode
{
    /**
    * Method to add the shortcode
    *
    * @return void
    */
    public static function register()
    {
        // Add shortcode
        add_shortcode('propertycontact', array (__CLASS__, 'render'));
    }

    /**
    * Function to render the Tabs
    *
    * @param array $atts
    *
    * @return NULL|string
    */
    public static function render($atts)
    {
        global $post;
        if ( !$post->ID || $post->post_type != 'property')
        {
            return NULL;
        }

        // Get Input
        $input = PratsRoomtypes::getInput();

        $default = array(
            'template' => 'tabs.tpl.php'
        );
        $args = wp_parse_args($atts, $default);
        ?>
        <script>
        jQuery( function() {
            jQuery('.slidenext').click( function() {
                jQuery(this).parent().find('div').animate(
                    {
                        height: "toggle"
                    }, 1000
                );
            }); 
        })
        </script>

        <ul class="propertycontact">
			
			    <li>
                <a class="btn-green slidenext"  href="javascript:void()">Schedule a Viewing</a>
                <div class="slidedropdown" style="display: none;">
                    <?php echo do_shortcode('[roomtypes_customerform]'); ?>
                </div>
            </li>
			
            <li>
                <a class="btn btn-green slidenext" href="javascript:void()">
                    <?php echo __('Call'); ?>
                </a>
                <div class="slidedropdown" style="display: none;">
					<?php
					$number = PratsRoomtypes_Helpers_Wordpress::getPostMeta($post->ID, 'phone_number');
					?>
                    <a href="tel:<?php echo preg_replace("/[^0-9]/","", $number); ?>">
						<?php echo $number; ?>
					</a>
                </div>
            </li>
            <li>
                <a class="btn-green slidenext" href="javascript:void();">Email</a>
                <div class="slidedropdown emailform">
					<?php echo do_shortcode('[roomtypes_emailform]'); ?>                    
                </div>
            </li>
    
        </ul>
        <div class="clearfix"></div>
        <?php
        return NULL;
    }

}
