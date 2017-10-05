<?php

/**
* Prateeksha Web Design
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prateeksha.com so we can send you a copy immediately.
*
* @category
* @package     PratsPM_Prateeksha_Project_Management
* @copyright   Copyright (c) 2015 Prateeksha Web Design (http://www.prateeksha.com)
* @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/

/**
* Class PratsPm_MetaBoxesTemplate
*/
class PratsRoomtypes_MetaBoxes_User
{
    /**
    * Method to show the users box
    * Shows all the form elements in normal form
    *
    * @return NULL
    *
    * @author Sumeet Shroff <sumeet@prateeksha.com>
    * @since 1.0
    */
    function wptShowMetabox($post, $args = array())
    {
        $params = array (
        'label' => 'Customers',
        'roles' => array (
        'subscriber'
        )    );

        if (isset($args ['args'])) {
            $params = array_merge($params, $args ['args']);
        }
        ?>
        <p>
            <?php
            $post->post_author = intval($post->post_author);
            $model = PratsRoomtypes::getModel('users');
            foreach ($params ['roles'] as $role) {
                $model->setRole($role);
            }
            $model->setOrder('display_name', 'asc');
            $users = $model->debug(0)->getList();

            // Prepare the html
            ?>
            <select id="select2-authordiv" name="post_author_override" onchange="getCustomerDetails('<?php echo wp_create_nonce("user_address"); ?>')">
                <option value=""><?php echo __('Select a '.$params['label']); ?></option>
                <?php
                foreach ($users as $user)
                {
                    ?>
                    <option value="<?php echo  $user->ID; ?>"
                        <?php
                        echo ($user->ID == $post->post_author ? ' selected' : '');
                        ?>><?php echo $user->display_name; ?>
                    </option><?php
                }
                ?>
            </select>
        </p>
        <p><input type="checkbox" name="create_new_account" value="1"  onChange="jQuery('#create_new_account_table').toggle();" /><?php echo __('Create a new account'); ?></p>
        <table id="create_new_account_table" style="display: none;">
            <tr>
                <td>First name : </td>
                <td><input type="text" name="first_name" value="" /></td>
                <td>Last name : </td>
                <td><input type="text" name="last_name" value="" /></td>
            </tr>
            <tr>
                <td>Email : </td>
                <td><input type="text" name="email" value="" /></td>
                <td>Password : </td>
                <td><input type="password" name="password" value="" /></td>
            </tr>
        </table>

        <input type="hidden" name="users_meta_noncename"
        id="users_meta_noncename"
        value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>" />
        <?php
    }

    /**
    * Block comment
    *
    * @param type
    * @return void
    */
    function save($post_id)
    {
        // Verify NOnce
        if (!wp_verify_nonce($_POST ['users_meta_noncename'],
        plugin_basename(__FILE__))) {
            return $post_id;
        }

        // Is the user allowed to edit the post or page?
        if (!current_user_can('edit_post', $post_id)) return $post_id;

        // Request object
        $request = PratsRoomtypes_Core_Init::getInput();

        $create_new_account = $request->post('create_new_account', '', 'integer');
        if ( $create_new_account )
        {
            $name = sprintf('%s %s', $request->post('first_name'), $request->post('last_name'));
            $userdata = array(
                'user_login'    => $request->post('first_name', '', 'cmd').$request->post('last_name', '', 'cmd'),
                'first_name'    => $request->post('first_name'),
                'last_name'     => $request->post('last_name'),
                'user_email'    => $request->post('email'),
                'display_name'  => $name,
                'nickname'      => $name,
                'user_pass'     => $request->post('password')
            );

            $user_id = wp_insert_user( $userdata );

            //On success
            if ( ! is_wp_error( $user_id ) )
            {
                $my_post = array(
                    'ID'            => $post_id,
                    'post_author'   => $user_id
                );
                // Update the post into the database
                wp_update_post( $my_post );
            }
            else
            {
                $a = PratsRoomtypes::getInstance();
                $error = $a->parseWp_Error($user_id);
                $a->enqueueMessage($error);
            }
        }

        // Now Save
        return $post_id;
    }

}
