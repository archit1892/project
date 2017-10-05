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
<!-- www.123contactform.com script begins here -->
<script type="text/javascript" defer src="//www.123contactform.com/embed/2374067.js" data-role="form"></script>
<!-- www.123contactform.com script ends here -->
*
* @category   Shortcodes
* @package    PratsRoomtypes
* @author     Sumeet Shroff <sumeet@prateeksha.com>
* @copyright  2016 Sumeet Shroff (http://www.prateeksha.com)
* @license    see license.txt
* @link       http://www.prateeksha.com/
*/

class PratsRoomtypes_Shortcodes_Customerform extends PratsRoomtypes_Helpers_Shortcode
{
    /**
    * Method to add the shortcode
    *
    * @return void
    */
    public static function register()
    {
        // Add shortcode
        add_shortcode('roomtypes_customerform', array (__CLASS__, 'render'));
    }

    public static function render()
    {
        global $post;
        $company_id = PratsRoomtypes_Helpers_Wordpress::getPostMeta($post->ID, 'company_id');
        $property_id = $post->ID;
        $property_title = $post->post_title;
        $location = PratsRoomtypes_Helpers_Wordpress::getPostMeta($post->ID, 'location');
        $property_link = get_permalink( $post->ID );
        ob_start();
		
		echo '<iframe allowTransparency="true" style="min-height:650px; height:inherit; overflow:auto;" width="100%" id="contactform123" name="contactform123" marginwidth="0" marginheight="0" frameborder="0" src="http://www.123contactform.com/sf.php?s=123contactform-2374067&control25250795='.$company_id.'&control25767857='.$property_link.'&control25251689='.$property_title.'">
		<p>Your browser does not support iframes. The contact form cannot be displayed. Please use another contact method (phone, fax etc)</p>
		</iframe>';		
        
        $html = ob_get_clean();
        return $html;

        ob_start();
        ?>
        
        <script async type="text/javascript">
			/*
            var servicedomain="www.123contactform.com";
            var customVars= "<?php // echo sprintf("control25250795=%s&control25250796=%s&control25251689=%s&control25251690=%s", $company_id, $property_id, $property_title, $location); ?>";
            var frmRef='';
            try { frmRef=window.top.location.href; } catch(err) {};
            var cfJsHost = (("https:" == document.location.protocol) ? "https://" : "http://");
            document.write(
              unescape("%3Cscript src='" + cfJsHost + servicedomain +
                       "/includes/easyXDM.min.js' type='text/javascript'%3E%3C/script%3E"));
            frmRef=encodeURIComponent(frmRef).replace('%26','[%ANDCHAR%]');
            document.write(
              unescape("%3Cscript src='"+ cfJsHost + servicedomain +
                       "/jsform-2374067.js?" + customVars + "&ref=" +
                       frmRef + "' type='text/javascript'%3E%3C/script%3E"));*/
        </script>
        <?php
        return ob_get_clean();
    }

}
