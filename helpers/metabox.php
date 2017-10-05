<?php

/**
* Class for main App for Questionnaire
*
* @category     Core
* @package      PratsQuestionnaire
* @author       Sumeet Shroff <sumeet@prateeksha.com>
* @license      see license.txt
* @link         http://www.prateeksha.com/pratsquestionnaire
* @author       Sumeet Shroff
*/

// Exit if accessed directly.
if (!defined('ABSPATH'))
{
    exit();
}

/**
* Class for main App for Questionnaire
*
* @category     Core
* @package      PratsQuestionnaire
* @author       Sumeet Shroff <sumeet@prateeksha.com>
* @license      see license.txt
* @link         http://www.prateeksha.com/pratsquestionnaire
* @author       Sumeet Shroff
*/

class PratsRoomtypes_Helpers_Metabox extends stdClass
{

    /**
    * Method to register a metabox meta box
    *
    * @param array $args
    *
    * @return NULL
    */
    public static function registerA($args)
    {
        $defaults = array(
            'posttype' => 'post',
            'classname' => __CLASS__,
            'function' => '',
            'id' => substr(str_shuffle(MD5(microtime())), 0, 10),
            'label' => '',
            'position' => 'normal',
            'save_function' => '',
            'show_priority' => 'default',
            'save_priority' => 5,
            'callback_args' => array()
        );

        $args = wp_parse_args($args, $defaults);

        add_meta_box($args ['id'], $args ['label'],
        array(
            $args ['classname'],
            $args ['function'],
        ), $args ['posttype'], $args ['position'],
        $args ['show_priority'],
        $args ['callback_args']);

        if (!empty($args ['save_function']))
        {
            add_action('save_post_'.$args['posttype'],
            array(
                $args ['classname'],
                $args ['save_function'],
            ), $args ['save_priority']);
        }

    }


    /**
    * Block comment
    *
    * @param type
    * @return void
    */
    public static function parseField($post, $field)
    {
        extract($field);
        $field['value'] = get_post_meta($post->ID, $field['id'], true);
        $field['label'] = sprintf('<p>%s</p>', __($name));
        $field['desc'] = sprintf('<p class="form-desc">%s</p>', __($desc));

        $function_name = $type.'Callback';
        $field['input'] = static::$function_name($field);

        return apply_filters('pratsquestionnaires_parsefield', $field);
    }


    /**
    * Text Callback
    *
    * Renders text fields.
    *
    * @since 1.0
    * @param array $args Arguments passed by the setting
    * @global $pratsquestionnaires_options Array of all the EDD Options
    * @return void
    */
    public static function textCallback( $args )
    {
        $size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
        $html = '<input type="text" class="' . $size . '-text" id="' . $args['id'] . '" name="' . $args['id'] . '" value="' . esc_attr( stripslashes( $args['value'] ) ) . '"/>';
        if ( !isset($args['no_label']) || !$args['no_label'] )
        {
            $html .= '<label for="' . $args['id'] . '"> '  . $args['desc'] . '</label>';
        }

        return $html;
    }


    /**
    * Text Callback
    *
    * Renders text fields.
    *
    * @since 1.0
    * @param array $args Arguments passed by the setting
    * @global $pratsquestionnaires_options Array of all the EDD Options
    * @return void
    */
    public static function textareaCallback( $args )
    {
        $value = $args['value'];
        $html = '<textarea class="large-text" cols="50" rows="5" id="' . $args['id'] . '" name="' . $args['id'] . '">' . esc_textarea( stripslashes( $value ) ) . '</textarea>';
        $html .= '<label for="' . $args['id'] . '"> '  . $args['desc'] . '</label>';
        return $html;
    }

    /**
    * Method to show the currency dropdown
    *
    * @param type
    * @return void
    */
    public static function dateCallback( $args )
    {
        // Date
        $script = sprintf("jQuery('#%s').datepicker({
            dateFormat : 'dd-mm-yy'
        });", $args['id']);
        PratsQuestionnaires_Helpers_Common::loadjQuery($script);
        if ( empty($args['value']) )
        {
            $args['value'] = date('d-m-Y');
        }

        return self::textCallback($args);
    }

    /**
    * Method to show the currency dropdown
    *
    * @param type
    * @return void
    */
    public static function currencyCallback( $args )
    {
        // Date
        $options = PratsQuestionnaires_Core_Settings::getCurrencies();
        $html = PratsQuestionnaires_Helpers_Html::dropdown(array(
            'results' => $options,
            'varname' => $args['id'],
            'selected' => esc_attr( stripslashes( $args['value'] ) ) )
        );

        $html .= '<label for="' . $args['id'] . '"> '  . $args['desc'] . '</label>';
        return $html;
    }


    /**
    * Method to show the currency dropdown
    *
    * @param type
    * @return void
    */
    public static function countryCallback( $args )
    {
        // Date
        $options = PratsQuestionnaires_Core_Settings::getCountryList();
        $html = PratsQuestionnaires_Helpers_Html::dropdown(array(
            'results' => $options,
            'varname' => $args['id'],
            'selected' => esc_attr( stripslashes( $args['value'] ) ) )
        );
        $html .= '<label for="' . $args['id'] . '"> '  . $args['desc'] . '</label>';
        return $html;
    }

}
