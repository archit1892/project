<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit();
}

class PratsRoomtypes_Helpers_Filters_Input
{

    public static function clean($value, $type = 'string')
    {
        $type = strtolower($type);
        switch($type)
        {
            case 'array':
            $value = (array)$value;
            break;

            case 'integer':
            case 'int':
            $value = intval($value);
            break;

            case 'float':
            $value = (float) $value;
            break;

            case 'decimal':
            $value = sprintf('%0.2f', round($value, 2));
            break;

            case 'cmd':
            $value = preg_replace("/[^a-zA-Z0-9]+/", "", $value);
            break;

            case 'currency':
            $value = (float)$value;
            $value = number_format($value, 2);
            break;


            default:
            $value = sanitize_text_field($value);
            break;
        }

        return $value;
    }

}
