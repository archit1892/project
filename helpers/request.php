<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit();
}

class PratsRoomtypes_Helpers_Request
{
    var $post;

    var $get;

    var $request;

    function __construct()
    {
        // Set all the values @todo
        $this->request = (object) $_REQUEST;
        $this->get = (object) $_GET;
        $this->post = (object) $_POST;
    }

    function base($name, $default = NULL, $type = 'string', $type_request = 'GET')
    {
        $type_request = strtoupper($type_request);
        switch($type_request)
        {
            case 'POST':
            $variables = $this->post;
            break;

            case 'REQUEST':
            $variables = $this->request;
            break;

            default :
            $variables = $this->get;
            break;
        }

        if ( !isset($variables->$name) )
        {
            return $default;
        }

        $return = $variables->$name;
        $return = PratsRoomtypes_Helpers_Filters_Input::clean($return, $type);
        return $return;
    }

    function get($name, $default = NULL, $type = 'string')
    {
        return self::base($name, $default, $type, 'GET');
    }

    function request($name, $default = NULL, $type = 'string')
    {
        return self::base($name, $default, $type, 'REQUEST');
    }

    function post($name, $default = NULL, $type = 'string')
    {
        return self::base($name, $default, $type, 'POST');
    }

}
