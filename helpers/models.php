<?php

/**
* PratsRoomtypes - Project Management
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
* Class Helper Model
*
* @category   Helpers
* @package    PratsRoomtypes
* @author     Sumeet Shroff <sumeet@prateeksha.com>
* @copyright  2016 Sumeet Shroff Prateeksha Web Design (http://www.prateeksha.com)
* @license    see license.txt
* @link       http://www.prateeksha.com/
*/
class PratsRoomtypes_Helpers_Models
{
    /**
    * Taxonomies
    * @var array
    */
    var $taxonomies = array ();

    /**
    * Main Post Type
    * This is reset by child functions
    *
    * @var string
    */
    var $post_type = 'post';

    var $max_num_pages = 0;
    var $query = NULL;

    /**
    * This is the main arguments array
    *
    * @var unknown
    */
    protected $args = array ('posts_per_page' => -1);

    var $order_fields = array (
    'ID',
    'post_author',
    'post_date',
    'post_content',
    'post_title',
    'post_excerpt',
    'post_status',
    'comment_status',
    'post_name',
    'post_modified',
    'post_parent',
    'post_type',
    'comment_count');

    var $debug = false;

    function __construct()
    {
    }

    function reset()
    {
        $this->args = array();
        return $this;
    }

    /**
    * Method to set the post per page
    * Please set to -1 if u want ALL records
    *
    * @param unknown $value
    *
    * @return PratsRoomtypesModel
    */
    function setPostsPerPage($value)
    {
        $this->args ['posts_per_page'] = intval($value);
        return $this;
    }

    /**
    *
    * @param unknown $value
    * @return PratsQnModel
    */
    function getPostsPerPage()
    {
        return $this->args ['posts_per_page'];
    }

    /**
    * Method to set the order of the query
    *
    * @param string $order
    * @param string $direction
    *
    * @return PratsRoomtypesModel
    */
    function setOrder($orderby, $direction)
    {
        // Validate and add postmeta as meta query
        if (!in_array($orderby, $this->order_fields))
        {
            $this->args ['meta_query'] [$orderby] = array (
            'key' => $orderby,
            'compare' => 'EXISTS');
        }

        if (!in_array(strtolower($direction),
        array (
        'asc',
        'desc'
        ))) {
            $direction = 'asc';
        }
        // /

        // Set
        // $this->args ['orderby'] = sanitize_sql_orderby($orderby);
        $this->args ['orderby'] = ($orderby);
        $this->args ['order'] = $direction;
        // /

        return $this;
    }

    /**
    * Sets the page
    *
    * @param unknown $value
    * @return PratsRoomtypesModel
    */
    function setPaged($value)
    {
        $this->args ['paged'] = intval($value);
        return $this;
    }

    /**
    * Sets the parent
    *
    * @param unknown $value
    * @return PratsRoomtypesModel
    */
    function setParent($value)
    {
        $this->args ['post_parent'] = intval($value);
        return $this;
    }

    /**
    * Sets the meta value
    *
    * @param unknown $key
    * @param unknown $value
    * @param string $compare
    */
    function setMetaValue($key, $value, $compare = '=', $array_key = NULL, $type = 'CHAR' )
    {
        if (!empty($array_key))
        {
            $this->args ['meta_query'] [$array_key] = array (
            'key' => $key,
            'value' => $value,
            'compare' => $compare);
        }
        else
        {
            $this->args ['meta_query'] [] = array (
            'key' => $key,
            'value' => $value,
            'compare' => $compare,
            'type' => $type);
        }

        return $this;
    }

    /**
    * Method to get only selected Posts
    *
    * @param array $values Array of Post Ids
    *
    * @return PratsRoomtypesModel
    */
    function inPosts($values)
    {
        $this->args ['post__in'] = $values;
        return $this;
    }

    /**
    * Sets the author.
    * If the author is NULL|0 then throws an Exception
    *
    * @param integer $value User Id
    *
    * @return PratsTbModel_questionnaires | Exception on error
    */
    function setAuthor($value)
    {
        // Catch the error
        $value = intval($value);
        if ( $value <= 0 )
        {
            throw new Exception('Value is NULL for author');
        }

        $this->args ['author'] = $value;
        return $this;
    }

    /**
    * Method to set the start date
    *
    * @param unknown $value
    *
    * @return PratsRoomtypesModel
    */
    function setStartdate($value)
    {
        $this->setMetaValue('startdate', $value, '>=');
        return $this;
    }

    /**
    * Method to set the categories
    *
    * @param unknown $category_id
    */
    function setCategory($category_id)
    {
        $this->args ['category__in'] = $category_id;
    }

    /**
    * Set the author parameters
    *
    * @param integer $value
    *
    * @return PratsRoomtypesModel_pratspm
    */
    function setSearch($value)
    {
        if (!empty($$value)) {
            $this->args ['s'] = esc_sql($value);
        }
        return $this;
    }

    /**
    *
    *
    * @param unknown $value
    *
    * @return PratsRoomtypesModel
    */
    function setSearchPhrase($value)
    {
        $this->args ['search_phrase'] [] = array (
        'key' => 'post_title',
        'value' => esc_sql($value),
        'compare' => '=');

        $this->args ['search_phrase'] [] = array ('key' => 'post_content',
        'value' => esc_sql($this->search_phrase), 'compare' => '=');

        return $this;
    }

    /**
    * Prepare the query for execution
    * This is the most important function
    * in this class and is changed very often
    *
    * @return multitype:string number
    */
    function _prepare($args = array())
    {
        $defaults = array (
        'post_type' => $this->post_type,
        'orderby' => 'post_title',
        'order' => 'asc',
        'post_status' => 'publish',
        'meta_query' => $this->args['meta_query'],
        'search_phrase' => $this->args ['search_phrase'],
        'debug' => false);

        $args = wp_parse_args($args, $defaults);

        if ($this->debug || $args['debug']) {
            v($args);
        }

        return $args;
    }

    /**
    * Get the count of posts
    *
    * @return number
    */
    function getCount()
    {
        // Prepare
        $args = $this->_prepare();

        // The Query
        $the_query = new WP_Query($args);
        return (int) $the_query->found_posts;
    }

    /**
    * Method to get the list
    *
    * @return NULL|WP_Query
    *
    * @since Wordpress WP_Query
    */
    function getList($args = array())
    {
        // Prepare the arguments
        $args = $this->_prepare($args);

        // The Query
        $query = new WP_Query($args);

        // Save in the class variables
        $this->max_num_pages = $query->max_num_pages;
        $this->query = $query;

        // Return
        return $query->get_posts();
    }

    /**
    * Method to get a single post using the same arguments
    *
    * @return NULL|WP_Query
    *
    * @since 1.0
    */
    function getSingle()
    {
        // Get all the pratspms
        $args = $this->_prepare();
        $args ['posts_per_page'] = 1;
        $posts_array = get_posts($args);

        if ($posts_array) {
            return $posts_array [0];
        }

        return null;
    }

    /**
    * Set project id
    *
    * @param integer $value
    *
    * @return object $this
    */
    function setProject($value)
    {
        $this->setMetaValue('project', $value, '=');
        return $this;
    }

    /**
    * Set project id
    *
    * @param integer $value
    *
    * @return object $this
    */
    function setStatus($value)
    {
        $this->setMetaValue('status', $value, '=');
        return $this;
    }

    /**
    * Method to load a single record
    *
    * @return post
    *
    * @since 1.0
    */
    function load($id)
    {
        return get_post($id);
    }

    function debug($value = true)
    {
        $this->debug = (boolean) $value;
        return $this;
    }

    /**
    * Function to get Maxaximum Number of Pages
    *
    * @return integer
    **/
    function getMaxNumPages()
    {
        return intval($this->max_num_pages);
    }


    function textCallback($text)
    {
        return $text;
    }

    function authorCallback($id)
    {
        $id = intval($id);
        $user_info = get_userdata($id);
        if ( $user_info )
        {
            return $user_info->display_name;
        }
        return $id;
    }

}
