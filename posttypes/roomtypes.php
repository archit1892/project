<?php
/**
 * Prateeksha_PratsInvoice - Project Management
 *
 * @category   RoomTypes
 * @package    Prateeksha_PratsInvoice
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
 * Class PratsRoomtypes_Tasks
 *
 * @category   RoomTypes
 * @package    Prateeksha_PratsInvoice
 * @author     Sumeet Shroff <sumeet@prateeksha.com>
 * @copyright  2016 Sumeet Shroff Prateeksha Web Design (http://www.prateeksha.com)
 * @license    see license.txt
 * @link       http://www.prateeksha.com/
 */
class PratsRoomtypes_Posttypes_Roomtypes extends PratsRoomtypes_Helpers_PostType
{

    /**
     * Post Type
     * @var string
     */
    const
            POST_TYPE = 'roomtypes';

    /**
     * Filters list
     *
     * @param type
     * @return void
     */
    public static
            $filters = array('property');

    /**
     * Method to register the Invoice posttype
     * Removes the "editor" content support from PostType
     *
     * @param string $plural
     * @param string $singular
     * @param array $params
     *
     * @return void
     */
    public static
            function register()
    {
        //'RoomTypes', 'RoomType',
        // Register the post type
        $args = array(
            'posttype'      => 'roomtypes',
            'name'          => 'Room Types',
            'classname'     => __CLASS__,
            'singular_name' => 'Room Type',
            'supports'      => array(
                'title',
                'editor',
            ),
        );

        // Call the parent
        parent::registerA($args);

        if (is_admin()) {
            self::registerMetaBoxes(__CLASS__);
            //        self::registerFilters(__CLASS__);
            self::registerColumnHeads(__CLASS__);
        }
    }

    /**
     * Method to add meta boxes to the task edit form
     *
     * @return void
     */
    function addMetaboxes()
    {
        self::showStatusMetaBox();
        self::showTemplateMetaBox();

        $args = array(
            'classname'     => __CLASS__,
            'function'      => 'showDetailsMetaBox',
            'label'         => 'Details',
            'save_function' => 'saveDetailsMetaBox');

        self::addMetabox($args);
    }

    /**
     * Block comment
     *
     * @param type
     * @return void
     */
    function boxDetailsVariables()
    {

        $return = array(
            'price_min' => array(
                'id'       => 'price_min',
                'name'     => __('Price Minimum', 'pratsroomtypes-text'),
                'desc'     => __('Set the minimum price e.g. $905', 'pratsroomtypes-text'),
                'type'     => 'text',
                'size'     => 'fullwidth',
                'no_label' => true,
                'std'      => 0
            ),
            'price_max' => array(
                'id'       => 'price_max',
                'name'     => __('Price Maximum', 'pratsroomtypes-text'),
                'desc'     => __('Set the maximum price e.g. $905', 'pratsroomtypes-text'),
                'type'     => 'text',
                'size'     => 'fullwidth',
                'no_label' => true,
                'std'      => 0
            ),
            'view3d'    => array(
                'id'       => 'view3d',
                'name'     => __('View 3D', 'pratsroomtypes-text'),
                'desc'     => __('Set the availability of rooms', 'pratsroomtypes-text'),
                'type'     => 'textarea',
                'size'     => 'fullwidth',
                'no_label' => true,
                'std'      => 0
            ),
        );

        return apply_filters('pratsroomtypes_details_variables', $return);
    }

    /**
     * Method to thoe the details metabox
     *
     * @param type
     * @return void
     */
    function showDetailsMetaBox($post)
    {
        // Populate the fields
        $fields = self::boxDetailsVariables();
        foreach ($fields as $key => &$field) {
            $field = self::parseField($post, $field);
        }
        ?>
        <table width="100%">
            <tr>
                <td width="15%" valign="top">
                    <?php echo $fields['price_min']['name']; ?>
                </td>
                <td valign="top" width="35%">
                    <?php echo $fields['price_min']['input']; ?>
                    <?php echo $fields['price_min']['desc']; ?>
                </td>
                <td width="15%" valign="top">
                    <?php echo $fields['price_max']['name']; ?>
                </td>
                <td valign="top" width="35%">
                    <?php echo $fields['price_max']['input']; ?>
                    <?php echo $fields['price_max']['desc']; ?>
                </td>
            </tr>
            <tr>
                <td width="15%" valign="top">
                    <?php echo $fields['view3d']['name']; ?>
                </td>
                <td valign="top">
                    <?php echo $fields['view3d']['input']; ?>
                    <?php echo $fields['view3d']['desc']; ?>
                </td>
            </tr>
        </table>

        <input type="hidden" name="<?php echo static::POST_TYPE; ?>_details_meta_noncename"
               id="<?php echo static::POST_TYPE; ?>_details_meta_noncename"
               value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>" />

        <?php
    }

    /**
     * Method to save the meta box.
     *
     * @param integer $post_id Post ID
     *
     * @return number
     */
    function saveDetailsMetaBox($post_id)
    {
        // Get the posts
        if (!wp_verify_nonce($_POST [static::POST_TYPE . '_details_meta_noncename'], plugin_basename(__FILE__))) {
            return $post_id;
        }

        // Is the user allowed to edit the post or page?
        if (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }

        // Request object
        $input = PratsRoomtypes_Core_Init::getInput();

        // Now Save
        $postmeta                  = array();
        $postmeta ['price_min']    = $input->post('price_min');
        $postmeta ['price_max']    = $input->post('price_max');
        $postmeta ['areasqft']     = $input->post('areasqft');
        $postmeta ['googlemap']    = $input->post('googlemap');
        $postmeta ['view3d']       = $input->post('view3d');
        $postmeta ['availability'] = $input->post('availability', '', 'integer');

        PratsRoomtypes_Helpers_Wordpress::savePostMeta($post_id, $postmeta);

        return $post_id;
    }

    /**
     * Method to show the users box
     * When the customer is selected, the billing address is got
     *
     * @param object $post
     * @param array $args
     *
     * @return NULL
     */
    function wptShowMetabox($post, $args = array())
    {
        $params = array(
            'label' => 'Customers',
            'roles' => array(
                'subscriber'
        ));

        if (isset($args ['args'])) {
            $params = array_merge($params, $args ['args']);
        }
        ?>
        <p>
            <?php
            $post->post_author = intval($post->post_author);
            $model             = PratsRoomtypes_Core_Init::getModel('users');
            foreach ($params ['roles'] as $role) {
                $model->setRole($role);
            }
            $users = $model->debug(0)->getList();

            // Prepare the html
            ?>
            <select id="authordiv" name="post_author_override"
                    onChange="getCustomerDetails();">
                <option value=""><?php echo __('Select a ' . $params['label']); ?></option>
                <?php
                foreach ($users as $user) {
                    ?>
                    <option value="<?php echo $user->ID; ?>"
                    <?php
                    echo ($user->ID == $post->post_author ? ' selected' : '');
                    ?>><?php echo $user->display_name; ?>
                    </option><?php
                }
                ?>
            </select></p>
        <input type="hidden" name="users_meta_noncename"
               id="users_meta_noncename"
               value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>" />
               <?php
           }

           /**
            * Add New column
            *
            * @param array $defaults
            * @return unknown
            */
           function columnsHead($defaults)
           {
               $defaults ['property']     = __('Property', 'pratsroomtypes-text');
               $defaults ['price_min']    = __('Price Min', 'pratsroomtypes-text');
               $defaults ['price_max']    = __('Price Max', 'pratsroomtypes-text');
               $defaults ['availability'] = __('Availability', 'pratsroomtypes-text');
               unset($defaults ['comments']);
               unset($defaults ['date']);
               return $defaults;
           }

           /**
            * Show the content in the browser window
            *
            * @param string  $column_name
            * @param integer $post_ID
            */
           function columnsContent($column_name, $post_ID)
           {
               switch ($column_name)
               {
                   case 'property':
                       echo get_post_field('post_title', PratsRoomtypes_Helpers_Wordpress::getPostMeta($post_ID, 'property'));
                       break;

                   case 'price_min':
                       echo PratsRoomtypes_Helpers_Wordpress::getPostMeta($post_ID, 'price_min');
                       break;

                   case 'price_max':
                       echo PratsRoomtypes_Helpers_Wordpress::getPostMeta($post_ID, 'price_max');
                       break;

                   case 'availability':
                       echo PratsRoomtypes_Helpers_Wordpress::getPostMeta($post_ID, 'availability_date');
                       break;
               }

               parent::columnsContent($column_name, $post_ID);
           }

           /**
            * Block comment
            *
            * @param type
            * @return void
            */
           function sortRoomtypes($a, $b)
           {
               global $standard_roomtypes;

               $a->standard_roomtypes = PratsRoomtypes_Helpers_Wordpress::getPostMeta($a->ID, 'standard_roomtypes');

               $b->standard_roomtypes = PratsRoomtypes_Helpers_Wordpress::getPostMeta($b->ID, 'standard_roomtypes');

               if ($standard_roomtypes[$a->standard_roomtypes] == $standard_roomtypes[$b->standard_roomtypes]) {
                   return 0;
               }

               return ($standard_roomtypes[$a->standard_roomtypes] < $standard_roomtypes[$b->standard_roomtypes]) ? -1 : 1;
           }

           /**
            * Method to check availability
            * We have two method here.
            * If today date is more than "Availability" date
            * Then return true.
            * Otherwise check if available in the future
            *
            * @param type
            * @return void
            */
           public static
                   function isAvailable($args)
           {

               $defaults = array(
                   'checkdate' => date('d-m-Y', current_time('timestamp')),
                   'post'      => NULL,
                   'postmeta'  => 'days7'
               );

               // @todo validate date

               $args = wp_parse_args($args, $defaults);
               extract($args);

               // Convert to object
               $post = (object) $post;
               if (is_null($post)) {
                   return false;
               }

               if ($post->post_type != 'property') {
                   $property = PratsRoomtypes_Helpers_Wordpress::getPostMeta($post->ID, 'property');
                   if (!$property) {
                       return false;
                   }
               }
               else {
                   $property = $post->ID;
               }

               // Checks availability date is more than today's date               
               $days = PratsRoomtypes_Helpers_Wordpress::getPostMeta($property, 'days7');
               if ($days) {
                   $availability_date = strtotime(array_shift($days));
                   if ( $availability_date >= strtotime($check_date)) {
                       return true;
                   }
               }
               
               // Otherwise check the other saved days
               $days = PratsRoomtypes_Helpers_Wordpress::getPostMeta($property, $postmeta);
               if (!$days) {
                   return false;
               }

               return in_array($checkdate, $days);
           }

           /**
            * Block comment
            *
            * @param type
            * @return void
            */
           public static
                   function datesAvailable($args)
           {
               // Add the script and css to the queue
               wp_enqueue_style('multidatespicker');
               wp_enqueue_script('multidatespicker-script');

               $defaults = array(
                   'checkdate' => date('y-m-d'),
                   'post_id'   => NULL
               );
               $args     = wp_parse_args($args, $defaults);
               extract($args);

               $dates_input = PratsRoomtypes_Helpers_Wordpress::getPostMeta($post_id, 'dates_input');
               if (empty($dates_input)) {
                   return NULL;
               }
               ?>
        <div id="box-<?php echo $post_id; ?>"></div>
        <script>
            jQuery(function () {

                // ui-state-highlight
                jQuery('#box-<?php echo $post_id; ?>').multiDatesPicker({
                    disabled: true,
                    addDates: [<?php echo $dates_input; ?>]
                });

            });
        </script>
        <?php
        return false;
    }

    /**
     * Block comment
     *
     * @param type
     * @return void
     */
    public
            function addFilters()
    {
        global $post_type;
        if ($post_type != static::POST_TYPE) {
            return;
        }

        // Helpers
        $input = PratsRoomtypes_Core_Init::getInput();

        $filter_property_id = $input->get('filter_property_id', 0, 'integer');
        echo PratsRoomtypes_Helpers_Html::dropdownProperties(array(
            'selected'       => $filter_property_id,
            'varname'        => 'filter_property_id',
            'show_firstline' => true,
            'firstline_text' => 'All properties')
        );
    }

    /**
     * Block comment
     *
     * @param type
     * @return void
     */
    public
            function addParseFilters($query)
    {
        global $pagenow, $post_type;
        if ($post_type != static::POST_TYPE) {
            return;
        }

        if (!$query->is_main_query()) {
            return;
        }

        if (!is_admin()) {
            return;
        }

        if ($pagenow != 'edit.php') {
            return;
        }

        $input              = PratsRoomtypes::getInput();
        $filter_property_id = $input->get('filter_property_id', 0, 'integer');
        if ($filter_property_id) {
            $query->set('meta_key', 'property');
            $query->set('meta_value', $filter_property_id);
        }
    }

}
