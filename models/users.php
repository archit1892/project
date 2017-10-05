<?php

/**
* Prateeksha_PratsInvoice - Project Management
*
* @category   Tasks
* @package    Prateeksha_PratsInvoice
* @author     Sumeet Shroff <sumeet@prateeksha.com>
* @copyright  2016 Sumeet Shroff (http://www.prateeksha.com)
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
* @copyright  2016 Sumeet Shroff (http://www.prateeksha.com)
* @license    see license.txt
* @link       http://www.prateeksha.com/
*/

class PratsRoomtypes_Models_Users extends PratsRoomtypes_Helpers_Models
{

   var $role = '';
   var $roles = array ();
   var $roles_not_in = array ();

   var $order_fields = array (
      'ID',
      'user_login',
      'user_nicename',
      'user_email',
      'user_registered',
      'user_status',
      'display_name'
   );

   function __construct()
   {
      $this->order = 'login';
      parent::__construct();
   }

   /**
    * Sets the role
    *
    * @param string $role
    */
   function setRole($role)
   {
      $this->args ['role'] = $role;
      return $this;
   }

   /**
    *
    * @param unknown $roles
    * @return PratsRoomtypesModel_Users
    */
   function setRoles($roles)
   {
      $this->args ['role__in'] = $roles;
      return $this;
   }

   /**
    * Method to get the list
    *
    * @return NULL|WP_Query
    *
    * @since Wordpress WP_Query
    */
   function getList()
   {
      // Prepare the arguments
      $args = $this->_prepare();

      // The Query
      $query = new WP_User_Query($args);

      // Save in the class variables
      $this->max_num_pages = $query->max_num_pages;
      $this->query = $query;

      // Return
      return $query->get_results();
   }

   /**
    * Get the count of posts
    *
    * @return number
    */
   function getCount()
   {
      $args = $this->_prepare();
      $query = new WP_User_Query($args);
      return (int) $query->found_posts;
   }

   /**
    *
    *
    * @param unknown $user_id
    * @param unknown $meta_key
    * @param unknown $meta_value
    * @param string $type
    */
   function updateMembers($user_id, $member_id)
   {
      $meta_key = 'members';

      // Get the value
      $meta_value = get_user_meta($user_id, $meta_key, true);

      // If empty, the reset
      if (empty($meta_value)) {
         $meta_value = array ();
      }

      // Set the values
      if (!in_array($member_id, $meta_value)) {
         $meta_value [] = $member_id;
      }

      // Update
      return update_user_meta($user_id, $meta_key, $meta_value);
   }

   /**
    *
    *
    * @param unknown $user_id
    * @param unknown $meta_key
    * @param unknown $meta_value
    * @param string $type
    */
   function getMembers($user_id)
   {
      $meta_key = 'members';

      // Get the value
      return (array) get_user_meta($user_id, $meta_key, true);
   }

}
