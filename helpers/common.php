<?php

// Exit if accessed directly.
if (!defined('ABSPATH'))
{
    exit();
}


class PratsRoomtypes_Helpers_Common
{

    /**
    * Function to get the Wordpress Url. Same function for backend frontent
    *
    * This function is wrapper function for Wordpress get_site_url and get_admin_url.
    * For Frontend,
    * For backeend, you have several options like 'edit', 'add'
    * It will form the url and return it
    *
    * @param type
    *
    * @return void
    */

    function urlPrint($args = array())
    {
        $defaults = array(
            'type' => 'popup_print',
            'post_type' => "",
            'post_id' => 0,
        );

        $args = wp_parse_args( $args, $defaults );

        extract($args);

        $type = strtolower(sanitize_text_field($type));

        if ( $type == 'print')
        {
            $array = array('post_type' => $args['post_type'], 'action' => 'pratsroomtypes_print', 'id' => $args['post_id']);
        }
        else
        {
            $array = array('post_type' => $args['post_type'], 'action' => 'pratsroomtypes_print', 'id' => $args['post_id'], 'TB_iframe' => 'true&width=600&height=550');
        }

        return add_query_arg( $array, get_admin_url().'edit.php');
    }

    /**
    * Function to get the Wordpress Url. Same function for backend frontent
    *
    * This function is wrapper function for Wordpress get_site_url and get_admin_url.
    * For Frontend,
    * For backeend, you have several options like 'edit', 'add'
    * It will form the url and return it
    *
    * @param type
    *
    * @return void
    */

    function getUrl($args = array())
    {
        $defaults = array(
            'type' => 'edit',
            'post_type' => "",
            'post_id' => 0,
            'place' => 'admin'
        );

        $args = wp_parse_args( $args, $defaults );

        if ( $args['place'] == 'site')
        {
            //return
        }

        switch(strtolower($args['type']))
        {
            case 'add':
            return add_query_arg( array('post_type' => $args['post_type']), get_admin_url().'post-new.php');
            break;

            default:
            return sprintf('%s/edit.php?post_type=%s', get_admin_url(), $args['post_type']);
            break;
        }

    }


    /**
    *
    * @param unknown $minutes
    * @return string
    */
    public static function showCurrency($amount, $currency = 'USD')
    {
        //$formatter = new NumberFormatter('en_GB',  NumberFormatter::CURRENCY);
        //echo 'UK: ', $formatter->formatCurrency($amount, 'EUR'), PHP_EOL;

        $amount = PratsRoomtypes_Helpers_Filters_Input::clean($amount, 'currency');
        $symbol = PratsRoomtypes_Core_Settings::currencySymbol($currency);
        return sprintf('%s %s', $symbol, $amount);
    }

    /**
    *
    * @param unknown $minutes
    * @return string
    */
    public static function showCurrencyUsingCode($number, $currency_code = '')
    {
        // Round it off
        $number = number_format(ceil($number), 2);

        $currency_symbol = '';
        $a = PratsRoomtypes::getInstance();
        $currency_symbols = $a->getOption('currency_symbols');

        // Check if it is set
        if (isset($currency_symbols [$currency_code]))
        {
            $currency_symbol = $currency_symbols [$currency_code];
        }

        // Right now we are putting all symobols in the front
        return sprintf('%s %s', $currency_symbol, $number);
    }


    public static function loadjQuery($script)
    {
        ?>
        <script>
        jQuery( function() {
            <?php echo $script; ?>
        });
        </script>
        <?php
    }

    public static function loadScriptDeclaration($script)
    {
        ?>
        <script>
            <?php echo $script; ?>
        </script>
        <?php
    }

    public static function getFrontEndUrl()
    {

    }


    /**
    * Get Plural Label
    *
    * @since 1.0.8.3
    * @return string $defaults['plural'] Plural label
    */
    public static function getLabelPlural( $lowercase = false )
    {
        $defaults = self::getDefaultLabels();
        return ( $lowercase ) ? strtolower( $defaults['plural'] ) : $defaults['plural'];
    }

    /**
    * Get Default Labels
    *
    * @since 1.0.8.3
    * @return array $defaults Default labels
    */
    public static function getDefaultLabels()
    {
        $defaults = array(
            'singular' => __( 'Download', 'iwp-txt' ),
            'plural'   => __( 'Downloads', 'iwp-txt')
        );
        return apply_filters( 'iwp_default_downloads_name', $defaults );
    }
}
