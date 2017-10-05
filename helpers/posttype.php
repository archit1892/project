<?php

/**
* PratsRoomtypes - Project Management.
*
* @category   Projects
*
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
* PratsRoomtypes - Project Management.
*
* @category   Projects
*
* @author     Sumeet Shroff <sumeet@prateeksha.com>
* @copyright  2016 Sumeet Shroff Prateeksha Web Design (http://www.prateeksha.com)
* @license    see license.txt
*
* @link       http://www.prateeksha.com/
*/

class PratsRoomtypes_Helpers_PostType extends stdClass
{
    /**
    * Block comment
    *
    */
    public static $filters = array();

    /**
    * Block comment
    *
    */
    public static $variables = array();

    /**
    * Method to register the custom posttype
    *
    * @param array $params
    *
    * @return void
    */
    public static function registerA($args)
    {
        // Regsiter the post type
        $defaults = array(
            'labels' => array(
                'name' => __($args['name'], 'pratsroomtypes'),
                'singular_name' => __($args['name'], 'pratsroomtypes'),
                'add_new_item' => sprintf(__('Add New %s', 'pratsroomtypes'), $args['singular_name']),
                'edit_item' => sprintf(__('Edit %s', 'pratsroomtypes'), $args['singular_name']),
                'new_item' => sprintf(__('New %s', 'pratsroomtypes'), $args['singular_name']),
                'view_item' => sprintf(__('View %s', 'pratsroomtypes'), $args['name']),
                'search_items' => sprintf( __('Search %s', 'pratsroomtypes'), $args['name']),
                'not_found' => sprintf( __('No %s found', 'pratsroomtypes'), $args['singular_name']),
                'not_found_in_trash' => sprintf(__('No %s found in trash', 'pratsroomtypes'), $args['name']),
            ),
            'public' => false,
            'show_in_menu' => true,
            'show_ui' => true,
            'rewrite' => false,
            'query_var' => true,
            'supports' => array(
                'title',
                'editor',
                'author',
            ),
            'capability_type' => 'post',
            'menu_icon' => 'dashicons-location-alt',
        );
        $args = wp_parse_args($args, $defaults);
        register_post_type($args['posttype'], $args);

        if ( is_admin() )
        {
            add_action('restrict_manage_posts',
            array(
                $args['classname'],
                'addFilters',
            ));

            add_action('parse_query',
            array(
                $args['classname'],
                'addParseFilters',
            ));
        }
    }

    /**
    * Register Meta Boxes.
    *
    * @param unknown $classname
    */
    public static function registerMetaBoxes($classname)
    {
        $post_type = sanitize_text_field($_GET ['post_type']);
        $post_id = intval(@$_GET ['post']);
        if (isset($_POST ['save']))
        {
            $post_id = intval($_POST ['post_ID']);
        } elseif (isset($_POST ['action']) && $_POST ['action'] == 'editpost')
        {
            $post_id = intval($_POST ['post_ID']);
        }
        $post_type2 = get_post_field('post_type', $post_id);

        if (is_admin() &&
        ($post_type == static::POST_TYPE || $post_type2 == static::POST_TYPE)) {
            add_action('admin_init',
            array(
                $classname,
                'addMetaboxes',
            ));
        }
    }

    /**
    * Method to register the column heads.
    *
    * @param string $classname
    */
    public static function registerColumnHeads($classname)
    {
        $input = PratsRoomtypes::getInput();

        $result = $input->get('post_type');
        if ($result != static::POST_TYPE) {
            return;
        }

        // Columns
        add_filter('manage_posts_columns',
        array(
            $classname,
            'columnsHead',
        ));

        add_action('manage_posts_custom_column',
        array(
            $classname,
            'columnsContent',
        ), 10, 2);
        // / ----------------
    }

    /**
    * Add New column.
    *
    * @param array $defaults
    *
    * @return unknown
    */
    public function columnsHead($defaults)
    {
        // Only if the post type is questionnaire
        return $defaults;
    }

    public static function registerFilters($classname)
    {
        add_action('restrict_manage_posts',
        array(
            $classname,
            'addFilters',
        ));

        add_action('parse_query',
        array(
            $classname,
            'addParseFilters',
        ));
    }

    /**
    * Block comment
    *
    * @param type
    * @return void
    */
    public function addFilters()
    {
        global $post_type;
        if ($post_type != static::POST_TYPE)
        {
            return;
        }

        // Helpers
        $input = PratsRoomtypes_Core_Init::getInput();

        if (in_array('users', static::$filters))
        {
            $filter_customer_id = $input->get('filter_customer_id', 0, 'integer');

            echo PratsRoomtypes_Helpers_Html::dropdownUsers( array(
                'selected' => $filter_customer_id,
                'varname' => 'filter_customer_id',
                'show_firstline' => true,
                'firstline_text' => 'All customers'));
            }


            if (in_array('status', static::$filters))
            {
                $filter_status = $input->get('filter_status', 0, 'integer');

                $args = array(
                    'key' => static::POST_TYPE,
                    'selected' => $filter_status,
                    'varname' => 'filter_status'
                );

                echo PratsRoomtypes_Helpers_Html::dropdownStatus( $args );
            }

        }

        /**
        * Method to parse the query for filter.
        *
        * @param object $query
        *            Wp_Query object
        */
        public function addParseFilters($query)
        {
            global $pagenow, $post_type;
            if ($post_type != static::POST_TYPE) {
                return;
            }

            if (!$query->is_main_query())
            {
                return;
            }

            if (!is_admin())
            {
                return;
            }

            if ($pagenow != 'edit.php') {
                return;
            }

            $input = PratsRoomtypes::getInput();

            if (in_array('users', static::$filters))
            {
                $filter_customer_id = $input->get('filter_customer_id', 0, 'integer');
                if ($filter_customer_id)
                {
                    $query->query_vars ['author'] = intval($filter_customer_id);
                }
            }

            if (in_array('status', static::$filters))
            {
                $filter_status = $input->get('filter_status', 0, 'string');
                if ($filter_status) {
                    $query->set('meta_key', 'status');
                    $query->set('meta_value', $filter_status);
                }
            }

            // Call the child function
            static::addParseFiltersSub($query);
        }

        /**
        * Child Method to parse the query for filter
        * This method is dummy here. It is called by parent.
        *
        * @param object $query Wp_Query object
        */
        public function addParseFiltersSub($query)
        {
            return true;
        }

        /**
        * Show the content in the browser window.
        *
        * @param string  $column_name
        * @param int $post_ID
        */
        public function columnsContent($column_name, $post_ID)
        {
            switch($column_name)
            {
                case 'status':
                $status = PratsRoomtypes_Helpers_Wordpress::getPostMeta($post_ID, 'status');
                echo PratsRoomtypes::getInstance()->getStatus(static::POST_TYPE, $status);
                break;

                case 'startdate':
                echo get_post_meta($post_ID, 'startdate', true);
                break;

                case 'amount':
                echo get_post_meta($post_ID, 'currency', true).' ';
                echo get_post_meta($post_ID, 'amount', true);
                break;
            }
        }

        /**
        * Method to add meta box
        *
        * @param unknown $args
        *
        * @return NULL
        */
        public static function addMetabox($args)
        {
            $args = array_merge(
            array(
                'classname' => __CLASS__,
                'function' => '',
                'id' => substr(str_shuffle(MD5(microtime())), 0, 10),
                'label' => '',
                'position' => 'normal',
                'save_function' => '',
                'show_priority' => 'default',
                'save_priority' => 5,
                'callback_args' => array()
            ), $args);

            add_meta_box($args ['id'], $args ['label'],
            array(
                $args ['classname'],
                $args ['function'],
            ), static::POST_TYPE, $args ['position'],
            $args ['show_priority'],
            $args ['callback_args']);

            if (!empty($args ['save_function']))
            {
                add_action('save_post',
                array(
                    $args ['classname'],
                    $args ['save_function'],
                ), $args ['save_priority']);
            }
        }


        function showStatusMetaBox()
        {
            add_meta_box('show', 'Status',
            array(
                'PratsRoomtypes_MetaBoxes_Status',
                'show',
            ), static::POST_TYPE, 'side');

            add_action('save_post',
            array(
                'PratsRoomtypes_MetaBoxes_Status',
                'save',
            ), 3, 2);
        }

        /**
        * Block comment
        *
        * @param type
        * @return void
        */
        function showCustomerMetaBox()
        {
            add_meta_box('authordiv', 'Customers',
            array(
                'PratsRoomtypes_MetaBoxes_User',
                'wptShowMetabox',
            ), static::POST_TYPE, 'normal', 'high',
            array(
                'label' => 'Customer',
                'roles' => array(
                    'subscriber',
                ),
            ));

            add_action('save_post',
            array(
                'PratsRoomtypes_MetaBoxes_User',
                'save',
            ), 3, 2);
        }


        function showTemplateMetaBox()
        {
            $args = array(
                'classname' => 'PratsRoomtypes_MetaBoxes_Template',
                'function' => 'show',
                'label' => 'Template',
                'position' => 'side',
                'save_function' => 'save',
            );
            self::addMetabox($args);
        }

        /**
        * Block comment
        *
        * @param type
        * @return void
        */
        public static function parseField($post, $field)
        {
            extract($field);
            $field['value'] = get_post_meta($post->ID, $field['id'], true);
            $field['label'] = sprintf('<p>%s</p>', __($name));
            $field['desc'] = sprintf('<p class="form-desc">%s</p>', __($desc));

            $function_name = $type.'Callback';
            $field['input'] = self::$function_name($field);

            return apply_filters('pratsroomtypes_parsefield', $field);
        }


        /**
        * Text Callback
        *
        * Renders text fields.
        *
        * @since 1.0
        * @param array $args Arguments passed by the setting
        * @global $pratsroomtypes_options Array of all the EDD Options
        * @return void
        */
        function textCallback( $args )
        {
            $size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
            $html = '<input type="text" class="' . $size . '-text" id="' . $args['id'] . '" name="' . $args['id'] . '" value="' . esc_attr( stripslashes( $args['value'] ) ) . '"/>';
            if ( !isset($args['no_label']) || !$args['no_label'] )
            {
                $html .= '<label for="' . $args['id'] . '"> '  . $args['desc'] . '</label>';
            }

            return $html;
        }


        /**
        * Text Callback
        *
        * Renders text fields.
        *
        * @since 1.0
        * @param array $args Arguments passed by the setting
        * @global $pratsroomtypes_options Array of all the EDD Options
        * @return void
        */
        function textareaCallback( $args )
        {
            $value = $args['value'];
            $html = '<textarea class="large-text" cols="50" rows="5" id="' . $args['id'] . '" name="' . $args['id'] . '">' . esc_textarea( stripslashes( $value ) ) . '</textarea>';
            $html .= '<label for="' . $args['id'] . '"> '  . $args['desc'] . '</label>';
            return $html;
        }

        /**
        * Method to show the currency dropdown
        *
        * @param type
        * @return void
        */
        function dateCallback( $args )
        {
            // Date
            $script = sprintf("jQuery('#%s').datepicker({
                dateFormat : 'dd-mm-yy'
            });", $args['id']);
            PratsRoomtypes_Helpers_Common::loadjQuery($script);
            if ( empty($args['value']) )
            {
                $args['value'] = date('d-m-Y');
            }

            return self::textCallback($args);
        }

        /**
        * Method to show the currency dropdown
        *
        * @param type
        * @return void
        */
        function currencyCallback( $args )
        {
            // Date
            $options = PratsRoomtypes_Core_Settings::getCurrencies();
            $html = PratsRoomtypes_Helpers_Html::dropdown(array(
                'results' => $options,
                'varname' => $args['id'],
                'selected' => esc_attr( stripslashes( $args['value'] ) ) )
            );

            $html .= '<label for="' . $args['id'] . '"> '  . $args['desc'] . '</label>';
            return $html;
        }


        /**
        * Method to show the currency dropdown
        *
        * @param type
        * @return void
        */
        function countryCallback( $args )
        {
            // Date
            $options = PratsRoomtypes_Core_Settings::getCountryList();
            $html = PratsRoomtypes_Helpers_Html::dropdown(array(
                'results' => $options,
                'varname' => $args['id'],
                'selected' => esc_attr( stripslashes( $args['value'] ) ) )
            );
            $html .= '<label for="' . $args['id'] . '"> '  . $args['desc'] . '</label>';
            return $html;
        }

        /**
        * Yes No Dropdown Callback
        * Renders Dropdown with Yes No Options
        *
        * @param array $args
        *
        * @return void
        */
        function yesnoCallback( $args )
        {
            $defaults = array(
                'varname' => $args['id'],
                'results' => array(
                    'yes' => __('Yes', 'pratsroomtypes-text'),
                    'no' => __('No', 'pratsroomtypes-text')),
                    'id' => 'yesno',
                    'selected' =>  $args['value'],
                    'show_firstline' => false
                );

                $args = wp_parse_args($args, $defaults);

                $html = PratsRoomtypes_Helpers_Html::dropdown($args);
                $html .= '<label for="' . $args['id'] . '"> '  . $args['desc'] . '</label>';

                return $html;
            }

        }
