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

class PratsRoomtypes_Helpers_Taxonomy
{
    /**
    * Block comment
    *
    */
    static $TAXONOMY_NAME = '';

    /**
    * Block comment
    *
    */
    static $POSTTYPE = '';

    /**
    * Block comment
    *
    * @param type
    * @return void
    */
    public static function registerTaxonomy($plural, $singular, $args)
    {
        $labels = array(
            'name' => _x($singular, 'taxonomy general name'),
            'singular_name' => _x($singular, 'taxonomy singular name'),
            'search_items' => __('Search '.$plural),
            'all_items' => __('All '.$plural),
            'parent_item' => __('Parent '.$singular),
            'parent_item_colon' => __('Parent '.$singular),
            'edit_item' => __('Edit '.$singular),
            'update_item' => __('Update '.$singular),
            'add_new_item' => __('Add New '.$singular),
            'new_item_name' => __('New '.$singular.' Name'),
            'menu_name' => __($plural)
        );

        $defaults = array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
        );
        $args = wp_parse_args($args, $defaults);

        // Now register the taxonomy
        register_taxonomy( static::$TAXONOMY_NAME, array(static::$POSTTYPE), $args);
    }

    /**
    * Block comment
    *
    * @param type
    * @return void
    */
    function getAllTaxonomies()
    {
        $args=array(
            'public'   => true,
            '_builtin' => false
        );
        $output = 'names'; // or objects
        $operator = 'and';
        $taxonomies = get_taxonomies($args, $output, $operator);
        v($taxonomies);
    }

    /**
    * Block comment
    *
    * @param type
    * @return void
    */
    public static function getList($args = array())
    {
        $defaults = array(
            'taxonomy' => 'suffix',
            'hide_empty' => false,
        );

        $args = wp_parse_args($args, $defaults);

        return get_terms( $args );
    }

    /**
    * Block comment
    *
    * @param type
    * @return void
    */
    public static function getDropdown($args = array())
    {
        $defaults = array(
            'varname' => '',
            'results' => array(),
            'id' => 'ID',
            'selected' => $args['selected'],
            'show_firstline' => false,
            'css_class' => 'medium-text'
        );
        $args = wp_parse_args($args, $defaults);

        $results = array();
        foreach($args['results'] as $row)
        {
            $results[$row->term_id] = $row->name;
        }

        $args['results'] = $results;

        return PratsRoomtypes_Helpers_Html::dropdown($args);
    }

    /**
    * Block comment
    *
    * @param type
    * @return void
    */
    public static function getTerm($args = array())
    {
        $defaults = array(
            'taxonomy' => '',
            'term' => 0,
            'output' => OBJECT,
            'filter' => 'raw'
        );

        $args = wp_parse_args($args, $defaults);
        extract($args);

        $term = intval($term);
        if ( $term <= 0 )
        {
            return NULL;
        }

        $return = get_term( $term, $taxonomy, $output, $filter );
        PratsRoomtypes::checkError($return);

        return $return;
    }

    /**
    * Block comment
    *
    * @param type
    * @return void
    */
    public static function getTermName($args = array())
    {
        $return = self::getTerm($args);
        if ( $return )
        {
            return $return->name;
        }

        return NULL;
    }


}
