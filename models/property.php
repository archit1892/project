<?php

/**
 * Model for accounts
 * This is not actually a part of the project
 * but we need the usermeta data from the PratsCRM
 *
 * @category   Models
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
 * Class for Model Accounts...
 * Most of the functions are in parent
 *
 * @category   Projects
 * @author     Sumeet Shroff <sumeet@prateeksha.com>
 * @copyright  2016 Sumeet Shroff Prateeksha Web Design (http://www.prateeksha.com)
 * @license    see license.txt
 * @link       http://www.prateeksha.com/
 */
class PratsRoomtypes_Models_Property extends PratsRoomtypes_Helpers_Models
{

    /**
     * Main Post Type
     *
     * @var string
     */
    var
            $post_type = 'property';

    /**
     * Method to parse the availability
     * 
     * @param type $matching_ids
     * 
     * @return type
     */
    function parse_request_availability($matching_ids)
    {
        // Get main plugin
        $a = PratsRoomtypes::getInstance();

        // Input
        $input        = $a->getInput();
        $availability = PratsRoomtypes_Helpers_Array::in($input->request('wpp_search', array(), 'array'), 'availability');

        // Availability
        if (!empty($availability)) {
            $list_of_days = $a->getOption('availability');
            if (isset($list_of_days[$availability])) {
                $args  = array(
                    'post_type'  => 'property',
                    'fields'     => 'ids',
                    'orderby'    => 'meta_value_num',
                    'order'      => 'ASC',
                    'meta_query' => array(
                        array(
                            'key'     => 'days7',
                            'value'   => array('2017-11-03', '2017-12-05'),
                            'compare' => 'BETWEEN',
					        'type' => 'DATE'
                        ),
                    ),
                );
                $query = new WP_Query($args);
                return array_intersect($matching_ids, $query->posts);
            }
        }

        return $matching_ids;
    }

    function parse_request_bedrooms($matching_ids)
    {
        // Get main plugin
        $a = PratsRoomtypes::getInstance();

        $input             = $a->getInput();
        $standard_roomtype = PratsRoomtypes_Helpers_Array::in(
                        $input->request('wpp_search', array(), 'array'), 'standard_roomtypes');

        if (!empty($standard_roomtype)) {
            $roomtypes = $a->getOption('standard_roomtypes');
            if (isset($roomtypes[$standard_roomtype])) {
                $args  = array(
                    'post_type'  => 'roomtypes',
                    'fields'     => 'ids',
                    'meta_query' => array(
                        array(
                            'key'     => 'standard_roomtypes',
                            'value'   => $standard_roomtype,
                            'compare' => '='
                        ),
                    ),
                );
                $query = new WP_Query($args);

                $array = array();
                if ($query->posts) {
                    foreach ($query->posts as $id) {
                        $array[] = PratsRoomtypes_Helpers_Wordpress::getPostMeta($id, 'property');
                    }
                }

                $array = PratsRoomtypes_Helpers_Array::toUnique($array);
                return array_intersect($matching_ids, $array);
            }
        }

        return $matching_ids;
    }

    function parse_request_neighbourhood($matching_ids)
    {
        // Get main plugin
        $a = PratsRoomtypes::getInstance();

        $input          = $a->getInput();
        $neighbourhoods = PratsRoomtypes_Helpers_Array::in($input->request('wpp_search', array(), 'array'), 'neighbourhoods');

        if (!empty($neighbourhoods)) {
            $args  = array(
                'post_type' => 'property',
                'fields'    => 'ids',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'neighbourhoods',
                        'field'    => 'id',
                        'terms'    => $neighbourhoods
                    )
                )
            );
            $query = new WP_Query($args);
            return array_intersect($matching_ids, $query->posts);
        }

        return $matching_ids;
    }

    function parse_request_show_only_pet_friendly($matching_ids)
    {
        // Get main plugin
        $a = PratsRoomtypes::getInstance();

        $input                  = $a->getInput();
        $show_only_pet_friendly = PratsRoomtypes_Helpers_Array::in($input->request('wpp_search', array(), 'array'), 'show_only_pet_friendly');

        // pet_friendly

        if (!empty($show_only_pet_friendly)) {
            $args  = array(
                'post_type'      => 'property',
                'fields'         => 'ids',
                'meta_key'       => 'pet_friendly',
                'meta_value_num' => 'yes',
                'meta_compare'   => '=',
            );
            $query = new WP_Query($args);
            return array_intersect($matching_ids, $query->posts);
        }

        return $matching_ids;
    }

}
