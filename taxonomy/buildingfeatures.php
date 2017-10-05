<?php

/**
* Settings
*
* @category   Core
* @package    PratsRoomtypes
* @author     Sumeet Shroff <sumeet@prateeksha.com>
* @copyright  2016-17 Sumeet Shroff Prateeksha Web Design (http://www.prateeksha.com)
* @license    see license.txt
* @link       http://www.prateeksha.com/
*/

// Exit if accessed directly.
if (!defined('ABSPATH'))
{
    exit();
}

/**
* Settings
*
* @category   Core
* @package    PratsRoomtypes
* @author     Sumeet Shroff <sumeet@prateeksha.com>
* @copyright  2016-17 Sumeet Shroff Prateeksha Web Design (http://www.prateeksha.com)
* @license    see license.txt
* @link       http://www.prateeksha.com/
*/

class PratsRoomtypes_Taxonomy_Buildingfeatures extends PratsRoomtypes_Helpers_Taxonomy
{
    /**
    * Block comment
    */
    static $TAXONOMY_NAME = 'buildingfeatures';

    /**
    * Post Type
    */
    static $POSTTYPE = 'property';

    /**
    * Method to register the toxonomy
    *
    * @return void
    * @since 1.0
    */
    public static function register()
    {
        $args = array(
            'hierarchical' => false,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'buildingfeatures'
            )
        );

        parent::registerTaxonomy(_('Building Features'), _('Building Features'), $args);
    }

}
