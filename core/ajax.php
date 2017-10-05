<?php



class PratsRoomtypes_Core_Ajax
{

    public static function addAction()
    {
        add_action('wp_ajax_user_address', array (__CLASS__,  'user_address_action_callback'));
    }

    /**
    * Ajax functions
    */
    function user_address_action_callback()
    {
        // if (!wp_verify_nonce($_REQUEST ['nonce'], "user_address")) {
        // exit("No naughty business please");
        // }

        if (!is_admin())
        {
            exit("No naughty business please");
        }

        $user_id = intval($_POST ['user_id']);
        if (!$user_id) {
            exit("No naughty business please");
        }

        $reponse = array (
        'type' => 'success',
        'user_id' => $user_id,
        'billing_name' => get_user_meta($user_id, 'billing_name'),
        'billing_address' => get_user_meta($user_id, 'billing_address'),
        'billing_street' => get_user_meta($user_id, 'billing_street'),
        'billing_postcode' => get_user_meta($user_id, 'billing_postcode'),
        'billing_city' => get_user_meta($user_id, 'billing_city'),
        'billing_state' => get_user_meta($user_id, 'billing_state'),
        'billing_country' => get_user_meta($user_id, 'billing_country'),
        'billing_email' => get_user_meta($user_id, 'billing_email'),
        'billing_phone' => get_user_meta($user_id, 'billing_phone'));

        $result = json_encode($reponse);
        echo $result;
        
        wp_die();
    }
}
