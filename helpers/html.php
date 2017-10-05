<?php

/**
 * Class for Common functions
 *
 * @category   Tasks
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
 * @category   Tasks
 * @package    Prateeksha_PratsInvoice
 * @author     Sumeet Shroff <sumeet@prateeksha.com>
 * @copyright  2016 Sumeet Shroff Prateeksha Web Design (http://www.prateeksha.com)
 * @license    see license.txt
 * @link       http://www.prateeksha.com/
 */
class PratsRoomtypes_Helpers_Html
{

    /**
     * Method to show a dropdown
     *
     * @param string $variable_name    Variable Name
     * @param array $results
     * @param string $key
     * @param string $value
     * @param string $selected
     * @param boolean $show_firstline
     * @param string $firstline
     * @param string $firstline_value
     * @param string $use_associate_array
     *
     * @return NULL|string
     */
    public static
            function dropdown($args)
    {
        $defaults = array(
            'varname'           => '',
            'results'           => array(),
            'id'                => 'ID',
            'size'              => 'regular',
            'selected'          => '',
            'disabled'          => '',
            'show_firstline'    => true,
            'firstline_text'    => 'Please select',
            'firstline_value'   => '',
            'multiple'          => false,
            'use_select2'       => false,
            'select2_css_class' => 'bigdrop'
        );

        $args = wp_parse_args($args, $defaults);

        // Extract to variables
        extract($args);

        // Sanitize the variables
        $show_firstline = (boolean) $show_firstline;
        $firstline_text = sanitize_text_field($firstline_text);
        $id             = sanitize_key($id);
        $varname        = sanitize_text_field($varname);

        $use_select2 = (boolean) $use_select2;
        if ($use_select2) {
            wp_enqueue_style('select2', PRATSROOMTYPES_PLUGIN_URL . '/assets/plugins/select2/select2.min.css');
            wp_enqueue_script('select2-js', PRATSROOMTYPES_PLUGIN_URL . '/assets/plugins/select2/select2.min.js');

            $script = "jQuery('#$id').select2( {
                placeholder: \"$firstline_text\",               
            } );
               
            ";
            PratsRoomtypes_Helpers_Common::loadjQuery($script);
        }

        // Items Container
        $options = array();

        // Show the first line
        if (!$use_select2 && $show_firstline) {
            $options [] = sprintf('<option value="%s">%s</option>', $firstline_value, __($firstline_text, 'pratsroomtypes'));
        }

        // Set
        $multiple = (boolean) $multiple;
        if ($multiple) {
            $selected = (array) $selected;
        }


        // Iterate
        if ($results) {
            $values = array_keys($results);

            foreach ($results as $value => $text) {
                // If it is a user
                if (is_a($text, 'WP_User')) {
                    $options [] = sprintf('<option value="%s" %s>%s</option>', $text->ID, ($text->ID == $args['selected'] ? 'selected' : ''), $text->display_name);
                }
                else {
                    $is_disabled = false;
                    if ($disabled) {
                        if (in_array($values, $disabled)) {
                            $is_disabled = true;
                        }
                    }

                    if ($multiple) {
                        $options [] = sprintf('<option value="%s" %s %s>%s</option>', $value, (in_array($value, $selected) ? 'selected' : ''), ($disabled ? 'disabled' : ''), $text);
                    }
                    else {
                        $options [] = sprintf('<option value="%s" %s %s>%s</option>', $value, ($value == $args['selected'] ? 'selected' : ''), ($disabled ? 'disabled' : ''), $text);
                    }
                }
            }
        }


        // Prepare the final HTML
        $html = sprintf('<select class="%s-select select-%s" id="%s" name="%s" %s>', esc_attr($args['size']), esc_attr($args['varname']), sanitize_key($id), $args['varname'], ($multiple ? 'multiple' : ''));
        $html .= join("\n", $options);
        $html .= '</select>';

        return $html;
    }

    /**
     * Method to show the dropdown for status
     *
     * @param string $args    Arguments
     *
     * @return NULL|string
     */
    function dropdownStatus($args)
    {
        if (!isset($args['key'])) {
            throw new Exception(__('key not defined'));
        }

        // Get the list of status
        $a               = PratsRoomtypes::getInstance();
        $args['results'] = $a->getOptionArray('status', $args['key']);

        $defaults = array(
            'varname'         => 'status',
            'results'         => array(),
            'id'              => 'status',
            'selected'        => 0,
            'show_firstline'  => true,
            'firstline_text'  => 'Select status',
            'firstline_value' => '',
        );

        $args = wp_parse_args($args, $defaults);

        return self::dropdown($args);
    }

    /**
     * Method to dropdown Proprieties
     *
     * @param unknown $key
     * @param string $selected
     * @return NULL|string
     */
    function dropdownProperties($args)
    {
        // Selected
        $selected = esc_attr(stripslashes(@$args['selected']));

        // Get the list of status
        $rows = (array) PratsRoomtypes::getModel('property')->setPostsPerPage(-1)->getList(array('posts_per_page' => -1));

        // Prepare
        $results = array();
        foreach ($rows as $result) {
            $results[$result->ID] = $result->post_title;
        }

        $defaults = array(
            'varname'         => 'property',
            'results'         => $results,
            'id'              => 'property',
            'selected'        => $selected,
            'show_firstline'  => true,
            'firstline_text'  => __('Select a property'),
            'firstline_value' => '');
        $args     = wp_parse_args($args, $defaults);

        return self::dropdown($args);
    }

    /**
     *
     * @param unknown $key
     * @param string $selected
     * @return NULL|string
     */
    function dropdownCurrency($args)
    {
        // Selected
        $selected = esc_attr(stripslashes(@$args['selected']));

        // Get the list of status
        $results = (array) PratsRoomtypes_Core_Settings::getCurrencies();

        $defaults = array(
            'varname'         => 'currency',
            'results'         => $results,
            'id'              => 'currency',
            'selected'        => $selected,
            'show_firstline'  => true,
            'firstline_text'  => __('Select a currency'),
            'firstline_value' => '');

        $args = wp_parse_args($args, $defaults);

        return self::dropdown($args);
    }

    /**
     * Method to show the dropdown for status
     *
     * @param unknown $key
     * @param string $selected
     * @return NULL|string
     */
    public static
            function dropdownUsers($args = array())
    {
        $defaults = array(
            'varname'         => '',
            'results'         => array(),
            'id'              => 'ID',
            'selected'        => '',
            'show_firstline'  => true,
            'firstline_text'  => 'Please select',
            'firstline_value' => '',
        );

        $model           = PratsRoomtypes::getModel('users');
        $users           = $model->setRole('subscriber')->setOrder('display_name', 'asc')->getList();
        $args['results'] = $users;

        $args = wp_parse_args($args, $defaults);

        return self::dropdown($args);
    }

}
