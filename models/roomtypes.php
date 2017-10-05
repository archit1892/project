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

class PratsRoomtypes_Models_Roomtypes extends PratsRoomtypes_Helpers_Models
{
    /**
    * Main Post Type
    *
    * @var string
    */
    var $post_type = 'roomtypes';

    /**
    * Set project id
    *
    * @param integer $value
    *
    * @return object $this
    */
    function setProperty($value)
    {
        $this->setMetaValue('property', (int)$value);
        return $this;
    }

}
