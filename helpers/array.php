<?php

/**
* Array Helper class
*
* @category   Helpers
* @package    PratsRoomtypes
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
* Class Helper Array
*
* @category   Helpers
* @package    PratsRoomtypes
* @author     Sumeet Shroff <sumeet@prateeksha.com>
* @copyright  2016 Sumeet Shroff Prateeksha Web Design (http://www.prateeksha.com)
* @license    see license.txt
* @link       http://www.prateeksha.com/
*/

class PratsRoomtypes_Helpers_Array
{
    /**
    * Convert all values to Integer
    *
    * Convers all the values to integer. Used for array of IDs
    *
    * @param array $array Reference to an array
    *
    * @return array
    **/
    public static function toInteger(&$array)
    {
        // Iterates the values and converts them to integer
        return array_map('intval', $array);
    }

    /**
    * Convert all values to Integer
    *
    * Convers all the values to integer. Used for array of IDs
    *
    * @param array $array Reference to an array
    *
    * @return array
    **/
    public static function toUnique(&$array)
    {
        if ( !$array ) return array();
        return array_unique($array);
    }

    /**
    * Convert all values to Integer
    *
    * Convers all the values to integer. Used for array of IDs
    *
    * @param array $array Reference to an array
    *
    * @return array
    **/
    public static function in(&$array, $key)
    {
        if ( !$array ) return NULL;
        if ( !isset($array) ) return NULL;
        return $array[$key];
    }


}
