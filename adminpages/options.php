<?php

if (!defined('ABSPATH'))
{
    exit('Please do not load this file directly.');
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
class PratsRoomtypes_Adminpages_Options
{

    /**
    * Method to render the
    *
    * @param type
    * @return void
    */
    public static function render()
    {
        $a = PratsRoomtypes::getInstance();

        $options = get_option( $a->getOption('options_name') );
        if ( $options )
        {
            foreach($options as $key => $value)
            {
                $key = sanitize_key($key);
                $config[$key] = $value;
            }
        }

        $input = PratsRoomtypes::getInput();
        $tab = $input->get('tab');
        $tabs = self::getTabs();

        if ( array_key_exists( $tab, $tabs ) )
        {
            $active_tab = $tab;
        }
        else
        {
            $active = 'general';
        }

        ob_start();
        ?>
        <div class="wrap">
            <h2 class="nav-tab-wrapper">
                <?php
                foreach( $tabs as $tab_id => $tab_name )
                {
                    $tab_url = add_query_arg( array(
                        'settings-updated' => false,
                        'tab' => $tab_id
                    ) );

                    $active = $active_tab == $tab_id ? ' nav-tab-active' : '';

                    echo '<a href="' . esc_url( $tab_url ) . '" title="' . esc_attr( $tab_name ) . '" class="nav-tab' . $active . '">';
                    echo esc_html( $tab_name );
                    echo '</a>';
                }
                ?>
            </h2>
            <div id="tab_container">
                <form method="post" action="options.php">
                    <table class="form-table">
                        <?php
                        settings_fields( 'pratsroomtypes_settings' );
                        do_settings_fields( 'pratsroomtypes_settings_' . $active_tab, 'pratsroomtypes_settings_' . $active_tab );
                        ?>
                    </table>
                    <?php submit_button(); ?>
                </form>
            </div><!-- #tab_container-->
        </div><!-- .wrap -->
        <?php
        echo ob_get_clean();

        do_action( 'pratsroomtypes_general_settings_after' );
    }


    /**
    * Retrieve settings tabs
    *
    * @since 1.8
    * @return array $tabs
    */
    public static function getTabs()
    {
        $settings = self::getRegisteredSettings();

        $tabs = array();
        $tabs['general']  	= __( 'General', 'pratsroomtypes-text' );
        $tabs['misc']      = __( 'Misc', 'pratsroomtypes-text' );

        return apply_filters( 'pratsroomtypes_settings_tabs', $tabs );
    }


    /**
    * Add all settings sections and fields
    *
    * @since 1.0
    * @return void
    */
    function register_settings()
    {
        if ( false == get_option( 'pratsroomtypes_settings' ) )
        {
            add_option( 'pratsroomtypes_settings' );
        }

        foreach( self::getRegisteredSettings() as $tab => $settings )
        {
            add_settings_section(
            'pratsroomtypes_settings_' . $tab,
            __return_null(),
            '__return_false',
            'pratsroomtypes_settings_' . $tab
        );

        foreach ( $settings as $option )
        {
            $name = isset( $option['name'] ) ? $option['name'] : '';

            $function_name = $option['type'] . 'Callback';

            add_settings_field(
            'pratsroomtypes_settings[' . $option['id'] . ']',
            $name,
            method_exists( 'PratsRoomtypes_AdminPages_Options', $function_name ) ? array (__CLASS__, $function_name) : array(__CLASS__, 'missingCallback'),
            'pratsroomtypes_settings_' . $tab,
            'pratsroomtypes_settings_' . $tab,
            array(
                'section'     => $tab,
                'id'          => isset( $option['id'] )          ? $option['id']      : null,
                'desc'        => ! empty( $option['desc'] )      ? $option['desc']    : '',
                'name'        => isset( $option['name'] )        ? $option['name']    : null,
                'size'        => isset( $option['size'] )        ? $option['size']    : null,
                'options'     => isset( $option['options'] )     ? $option['options'] : '',
                'std'         => isset( $option['std'] )         ? $option['std']     : '',
                'min'         => isset( $option['min'] )         ? $option['min']     : null,
                'max'         => isset( $option['max'] )         ? $option['max']     : null,
                'step'        => isset( $option['step'] )        ? $option['step']    : null,
                'select2'     => isset( $option['select2'] )     ? $option['select2'] : null,
                'placeholder' => isset( $option['placeholder'] ) ? $option['placeholder'] : null
                ) );
            }
        }

        // Creates our settings in the options table
        register_setting( 'pratsroomtypes_settings', 'pratsroomtypes_settings', array (__CLASS__, 'settings_sanitize') );
    }


    /**
    * Retrieve the array of plugin settings
    *
    * @since 1.8
    * @return array
    */
    function getRegisteredSettings()
    {
        /**
        * Settings Array
        */
        $pratsroomtypes_settings = array (
        //* General Settings */
        'general' => apply_filters( 'pratsroomtypes_settings_general',
        array(
            'currency' => array(
                'id' => 'currency',
                'name' => __( 'Currency', 'pratsroomtypes-text' ),
                'desc' => __( 'Choose your currency. Note that some payment gateways have currency restrictions.', 'pratsroomtypes-text' ),
                'type' => 'select',
                'options' => PratsRoomtypes_Core_Settings::getCurrencies(),
                'select2' => true
            ),
            'currency_position' => array(
                'id' => 'currency_position',
                'name' => __( 'Currency Position', 'pratsroomtypes-text' ),
                'desc' => __( 'Choose the location of the currency sign.', 'pratsroomtypes-text' ),
                'type' => 'select',
                'options' => array (
                'before' => __( 'Before - $10', 'pratsroomtypes-text' ),
                'after' => __( 'After - 10$', 'pratsroomtypes-text' ))
            ),
            'thousands_separator' => array(
                'id' => 'thousands_separator',
                'name' => __( 'Thousands Separator', 'pratsroomtypes-text' ),
                'desc' => __( 'The symbol (usually , or .) to separate thousands', 'pratsroomtypes-text' ),
                'type' => 'text',
                'size' => 'small',
                'std' => ','
            ),
            'decimal_separator' => array(
                'id' => 'decimal_separator',
                'name' => __( 'Decimal Separator', 'pratsroomtypes-text' ),
                'desc' => __( 'The symbol (usually , or .) to separate decimal points', 'pratsroomtypes-text' ),
                'type' => 'text',
                'size' => 'small',
                'std' => '.'
            ),
            ) ),
            /** Misc Settings */
            'misc' => apply_filters('pratsroomtypes_settings_misc',
            array(
            )
            )
        );

        return apply_filters( 'pratsroomtypes_registered_settings', $pratsroomtypes_settings );
    }

    /**
    * Block comment
    *
    * @param type
    *
    * @return void
    */
    public static function settings_sanitize($input)
    {
        $pratsroomtypes_options = get_option( 'pratsroomtypes_settings' );

        // Check security
        if ( empty( $_POST['_wp_http_referer'] ) )
        {
            return $input;
        }

        parse_str( $_POST['_wp_http_referer'], $referrer );

        $settings = self::getRegisteredSettings();

        $tab      = isset( $referrer['tab'] ) ? $referrer['tab'] : 'general';

        $input = $input ? $input : array();

        $input = apply_filters( 'pratsroomtypes_settings_' . $tab . '_sanitize', $input );

        // Loop through each setting being saved and pass it through a sanitization filter
        foreach ( $input as $key => $value )
        {
            // Get the setting type (checkbox, select, etc)
            $type = isset( $settings[$tab][$key]['type'] ) ? $settings[$tab][$key]['type'] : false;

            if ( $type )
            {
                // Field type specific filter
                $input[$key] = apply_filters( 'pratsroomtypes_settings_sanitize_' . $type, $value, $key );
            }

            // General filter
            $input[$key] = apply_filters( 'pratsroomtypes_settings_sanitize', $input[$key], $key );
        }

        // Loop through the whitelist and unset any that are empty for the tab being saved
        if ( ! empty( $settings[$tab] ) )
        {
            foreach ( $settings[$tab] as $key => $value )
            {
                // settings used to have numeric keys, now they have keys that match the option ID. This ensures both methods work
                if ( is_numeric( $key ) )
                {
                    $key = $value['id'];
                }
                //if ( empty( $input[$key] ) ) {
                //	unset( $pratsroomtypes_options[$key] );
                //}
            }
        }

        // Merge our new settings with the existing
        //
        if( is_array( $pratsroomtypes_options ) )
        {
            $output = array_merge( $pratsroomtypes_options, $input );
        } else
        {
            $output = $input;
        }

        add_settings_error( 'pratsroomtypes-notices', '', __( 'Settings updated.', 'pratsroomtypes-txt' ), 'updated' );

        return $output;

    }


    /**
    * Get sale notification email text
    *
    * Returns the stored email text if available, the standard email text if not
    *
    * @since 1.7
    * @author Daniel J Griffiths
    * @return string $message
    */
    function getDefaultSaleNotificationEmail()
    {
        $pratsroomtypes_options = get_option( 'pratsroomtypes_settings' );

        $default_email_body = __( 'Hello', 'pratsroomtypes-txt' ) . "\n\n" . sprintf( __( 'A %s purchase has been made', 'pratsroomtypes-txt' ), PratsRoomtypes_Helpers_Common::getLabelPlural() ) . ".\n\n";
        $default_email_body .= sprintf( __( '%s sold:', 'pratsroomtypes-txt' ), PratsRoomtypes_Helpers_Common::getLabelPlural() ) . "\n\n";
        $default_email_body .= '{download_list}' . "\n\n";
        $default_email_body .= __( 'Purchased by: ', 'pratsroomtypes-txt' ) . ' {name}' . "\n";
        $default_email_body .= __( 'Amount: ', 'pratsroomtypes-txt' ) . ' {price}' . "\n";
        $default_email_body .= __( 'Payment Method: ', 'pratsroomtypes-txt' ) . ' {payment_method}' . "\n\n";
        $default_email_body .= __( 'Thank you', 'pratsroomtypes-txt' );

        $message = ( isset( $pratsroomtypes_options['sale_notification'] ) && !empty( $pratsroomtypes_options['sale_notification'] ) ) ? $pratsroomtypes_options['sale_notification'] : $default_email_body;

        return $message;
    }


    /**
    * Get a formatted HTML list of all available email tags
    *
    * @since 1.9
    *
    * @return string
    */
    function getEmailsTagsList()
    {
        // The list
        $list = '';

        // Get all tags
        $email_tags = self::getEmailTags();

        // Check
        if ( count( $email_tags ) > 0 ) {

            // Loop
            foreach ( $email_tags as $email_tag )
            {
                // Add email tag to list
                $list .= '{' . $email_tag['tag'] . '} - ' . $email_tag['description'] . '<br/>';
            }

        }

        // Return the list
        return $list;
    }

    /**
    * Missing Callback
    *
    * If a function is missing for settings callbacks alert the user.
    *
    * @since 1.3.1
    * @param array $args Arguments passed by the setting
    * @return void
    */
    function missingCallback($args)
    {
        printf( __( 'The callback function used for the <strong>%s</strong> setting is missing.', 'pratsinvoice-txt' ), $args['id'] );
    }

    /**
    * Select Callback
    *
    * Renders select fields.
    *
    * @since 1.0
    * @param array $args Arguments passed by the setting
    * @global $pratsroomtypes_options Array of all the EDD Options
    * @return void
    */
    function selectCallback($args)
    {
        $pratsroomtypes_options = get_option( 'pratsroomtypes_settings' );

        if ( isset( $pratsroomtypes_options[ $args['id'] ] ) )
        $value = $pratsroomtypes_options[ $args['id'] ];
        else
        $value = isset( $args['std'] ) ? $args['std'] : '';

        if ( isset( $args['placeholder'] ) )
        $placeholder = $args['placeholder'];
        else
        $placeholder = '';

        $html = '<select id="pratsroomtypes_settings[' . $args['id'] . ']" name="pratsroomtypes_settings[' . $args['id'] . ']" ' . ( $args['select2'] ? 'class="pratsroomtypes-select2"' : '' ) . 'data-placeholder="' . $placeholder . '" />';

        if ( $args['options'] )
        {
            foreach ( $args['options'] as $option => $name )
            {
                $selected = selected( $option, $value, false );
                $html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
            }
        }

        $html .= '</select>';
        $html .= '<label for="pratsroomtypes_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

        echo $html;
    }

    /**
    * Descriptive text callback.
    *
    * Renders descriptive text onto the settings field.
    *
    * @since 2.1.3
    * @param array $args Arguments passed by the setting
    * @return void
    */
    function descriptiveTextCallback( $args )
    {
        echo esc_html( $args['desc'] );
    }

    /**
    * Text Callback
    *
    * Renders text fields.
    *
    * @since 1.0
    * @param array $args Arguments passed by the setting
    * @global $pratsroomtypes_options Array of all the EDD Options
    * @return void
    */
    function textCallback( $args )
    {
        $pratsroomtypes_options = get_option( 'pratsroomtypes_settings' );

        if ( isset( $pratsroomtypes_options[ $args['id'] ] ) )
        $value = $pratsroomtypes_options[ $args['id'] ];
        else
        $value = isset( $args['std'] ) ? $args['std'] : '';

        $size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
        $html = '<input type="text" class="' . $size . '-text" id="pratsroomtypes_settings[' . $args['id'] . ']" name="pratsroomtypes_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
        $html .= '<label for="pratsroomtypes_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

        echo $html;
    }

    /**
    * Checkbox Callback
    *
    * Renders checkboxes.
    *
    * @since 1.0.0
    * @param array $args Arguments passed by the setting
    * @global $pratsroomtypes_options Array of all the EDD Options
    * @return void
    */
    function checkboxCallback( $args )
    {
        $pratsroomtypes_options = get_option( 'pratsroomtypes_settings' );

        $checked = isset( $pratsroomtypes_options[ $args[ 'id' ] ] ) ? checked( 1, $pratsroomtypes_options[ $args[ 'id' ] ], false ) : '';
        $html = '<input type="checkbox" id="pratsroomtypes_settings[' . $args['id'] . ']" name="pratsroomtypes_settings[' . $args['id'] . ']" value="1" ' . $checked . '/>';
        $html .= '<label for="pratsroomtypes_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

        echo $html;
    }


    /**
    * Multicheck Callback
    *
    * Renders multiple checkboxes.
    *
    * @since 1.0.0
    * @param array $args Arguments passed by the setting
    * @global $pratsroomtypes_options Array of all the EDD Options
    * @return void
    */
    function pratsroomtypes_multicheck_callback( $args )
    {
        $pratsroomtypes_options = get_option( 'pratsroomtypes_settings' );

        if ( ! empty( $args['options'] ) ) {
            foreach( $args['options'] as $key => $option ):
                if( isset( $pratsroomtypes_options[$args['id']][$key] ) ) { $enabled = $option; } else { $enabled = NULL; }
                echo '<input name="pratsroomtypes_settings[' . $args['id'] . '][' . $key . ']" id="pratsroomtypes_settings[' . $args['id'] . '][' . $key . ']" type="checkbox" value="' . $option . '" ' . checked($option, $enabled, false) . '/>&nbsp;';
                echo '<label for="pratsroomtypes_settings[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';
            endforeach;
            echo '<p class="description">' . $args['desc'] . '</p>';
        }
    }

    /**
    * Payment method icons callback
    *
    * @since 2.1
    * @param array $args Arguments passed by the setting
    * @global $pratsroomtypes_options Array of all the EDD Options
    * @return void
    */
    function paymentIconsCallback( $args )
    {

        $pratsroomtypes_options = get_option( 'pratsroomtypes_settings' );

        if ( ! empty( $args['options'] ) ) {
            foreach( $args['options'] as $key => $option ) {

                if( isset( $pratsroomtypes_options[$args['id']][$key] ) ) {
                    $enabled = $option;
                } else {
                    $enabled = NULL;
                }

                echo '<label for="pratsroomtypes_settings[' . $args['id'] . '][' . $key . ']" style="margin-right:10px;line-height:16px;height:16px;display:inline-block;">';

                echo '<input name="pratsroomtypes_settings[' . $args['id'] . '][' . $key . ']" id="pratsroomtypes_settings[' . $args['id'] . '][' . $key . ']" type="checkbox" value="' . esc_attr( $option ) . '" ' . checked( $option, $enabled, false ) . '/>&nbsp;';

                if( pratsroomtypes_string_is_image_url( $key ) ) {

                    echo '<img class="payment-icon" src="' . esc_url( $key ) . '" style="width:32px;height:24px;position:relative;top:6px;margin-right:5px;"/>';

                } else {

                    $card = strtolower( str_replace( ' ', '', $option ) );

                    if( has_filter( 'pratsroomtypes_accepted_payment_' . $card . '_image' ) ) {

                        $image = apply_filters( 'pratsroomtypes_accepted_payment_' . $card . '_image', '' );

                    } else {

                        $image       = pratsroomtypes_locate_template( 'images' . DIRECTORY_SEPARATOR . 'icons' . DIRECTORY_SEPARATOR . $card . '.gif', false );
                        $content_dir = WP_CONTENT_DIR;

                        if( function_exists( 'wp_normalize_path' ) ) {

                            // Replaces backslashes with forward slashes for Windows systems
                            $image = wp_normalize_path( $image );
                            $content_dir = wp_normalize_path( $content_dir );

                        }

                        $image = str_replace( $content_dir, WP_CONTENT_URL, $image );

                    }

                    echo '<img class="payment-icon" src="' . esc_url( $image ) . '" style="width:32px;height:24px;position:relative;top:6px;margin-right:5px;"/>';
                }


                echo $option . '</label>';

            }
            echo '<p class="description" style="margin-top:16px;">' . $args['desc'] . '</p>';
        }
    }

    /**
    * Radio Callback
    *
    * Renders radio boxes.
    *
    * @since 1.3.3
    * @param array $args Arguments passed by the setting
    * @global $pratsroomtypes_options Array of all the EDD Options
    * @return void
    */
    function radioCallback( $args )
    {
        $pratsroomtypes_options = get_option( 'pratsroomtypes_settings' );

        foreach ( $args['options'] as $key => $option ) :
            $checked = false;

            if ( isset( $pratsroomtypes_options[ $args['id'] ] ) && $pratsroomtypes_options[ $args['id'] ] == $key )
            $checked = true;
            elseif( isset( $args['std'] ) && $args['std'] == $key && ! isset( $pratsroomtypes_options[ $args['id'] ] ) )
            $checked = true;

            echo '<input name="pratsroomtypes_settings[' . $args['id'] . ']"" id="pratsroomtypes_settings[' . $args['id'] . '][' . $key . ']" type="radio" value="' . $key . '" ' . checked(true, $checked, false) . '/>&nbsp;';
            echo '<label for="pratsroomtypes_settings[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';
        endforeach;

        echo '<p class="description">' . $args['desc'] . '</p>';
    }

    /**
    * Gateways Callback
    *
    * Renders gateways fields.
    *
    * @since 1.0
    * @param array $args Arguments passed by the setting
    * @global $pratsroomtypes_options Array of all the EDD Options
    * @return void
    */
    function gatewaysCallback( $args )
    {
        $pratsroomtypes_options = get_option( 'pratsroomtypes_settings' );

        foreach ( $args['options'] as $key => $option ) :
            if ( isset( $pratsroomtypes_options['gateways'][ $key ] ) )
            $enabled = '1';
            else
            $enabled = null;

            echo '<input name="pratsroomtypes_settings[' . $args['id'] . '][' . $key . ']"" id="pratsroomtypes_settings[' . $args['id'] . '][' . $key . ']" type="checkbox" value="1" ' . checked('1', $enabled, false) . '/>&nbsp;';
            echo '<label for="pratsroomtypes_settings[' . $args['id'] . '][' . $key . ']">' . $option['admin_label'] . '</label><br/>';
        endforeach;
    }

    /**
    * Gateways Callback (drop down)
    *
    * Renders gateways select menu
    *
    * @since 1.5
    * @param array $args Arguments passed by the setting
    * @global $pratsroomtypes_options Array of all the EDD Options
    * @return void
    */
    function gatewaySelectCallback($args)
    {
        $pratsroomtypes_options = get_option( 'pratsroomtypes_settings' );

        echo '<select name="pratsroomtypes_settings[' . $args['id'] . ']"" id="pratsroomtypes_settings[' . $args['id'] . ']">';

        foreach ( $args['options'] as $key => $option ) :
            $selected = isset( $pratsroomtypes_options[ $args['id'] ] ) ? selected( $key, $pratsroomtypes_options[$args['id']], false ) : '';
            echo '<option value="' . esc_attr( $key ) . '"' . $selected . '>' . esc_html( $option['admin_label'] ) . '</option>';
        endforeach;

        echo '</select>';
        echo '<label for="pratsroomtypes_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';
    }


    /**
    * Number Callback
    *
    * Renders number fields.
    *
    * @since 1.9
    * @param array $args Arguments passed by the setting
    * @global $pratsroomtypes_options Array of all the EDD Options
    * @return void
    */
    function numberCallback( $args )
    {
        $pratsroomtypes_options = get_option( 'pratsroomtypes_settings' );

        if ( isset( $pratsroomtypes_options[ $args['id'] ] ) )
        $value = $pratsroomtypes_options[ $args['id'] ];
        else
        $value = isset( $args['std'] ) ? $args['std'] : '';

        $max  = isset( $args['max'] ) ? $args['max'] : 999999;
        $min  = isset( $args['min'] ) ? $args['min'] : 0;
        $step = isset( $args['step'] ) ? $args['step'] : 1;

        $size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
        $html = '<input type="number" step="' . esc_attr( $step ) . '" max="' . esc_attr( $max ) . '" min="' . esc_attr( $min ) . '" class="' . $size . '-text" id="pratsroomtypes_settings[' . $args['id'] . ']" name="pratsroomtypes_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
        $html .= '<label for="pratsroomtypes_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

        echo $html;
    }

    /**
    * Textarea Callback
    *
    * Renders textarea fields.
    *
    * @since 1.0
    * @param array $args Arguments passed by the setting
    * @global $pratsroomtypes_options Array of all the EDD Options
    * @return void
    */
    function textareaCallback( $args )
    {
        $pratsroomtypes_options = get_option( 'pratsroomtypes_settings' );

        if ( isset( $pratsroomtypes_options[ $args['id'] ] ) )
        $value = $pratsroomtypes_options[ $args['id'] ];
        else
        $value = isset( $args['std'] ) ? $args['std'] : '';

        $html = '<textarea class="large-text" cols="50" rows="5" id="pratsroomtypes_settings[' . $args['id'] . ']" name="pratsroomtypes_settings[' . $args['id'] . ']">' . esc_textarea( stripslashes( $value ) ) . '</textarea>';
        $html .= '<label for="pratsroomtypes_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

        echo $html;
    }

    /**
    * Password Callback
    *
    * Renders password fields.
    *
    * @since 1.3
    * @param array $args Arguments passed by the setting
    * @global $pratsroomtypes_options Array of all the EDD Options
    * @return void
    */
    function passwordCallback( $args )
    {
        $pratsroomtypes_options = get_option( 'pratsroomtypes_settings' );

        if ( isset( $pratsroomtypes_options[ $args['id'] ] ) )
        $value = $pratsroomtypes_options[ $args['id'] ];
        else
        $value = isset( $args['std'] ) ? $args['std'] : '';

        $size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
        $html = '<input type="password" class="' . $size . '-text" id="pratsroomtypes_settings[' . $args['id'] . ']" name="pratsroomtypes_settings[' . $args['id'] . ']" value="' . esc_attr( $value ) . '"/>';
        $html .= '<label for="pratsroomtypes_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

        echo $html;
    }


    /**
    * Color select Callback
    *
    * Renders color select fields.
    *
    * @since 1.8
    * @param array $args Arguments passed by the setting
    * @global $pratsroomtypes_options Array of all the EDD Options
    * @return void
    */
    function colorSelectCallback( $args )
    {
        $pratsroomtypes_options = get_option( 'pratsroomtypes_settings' );

        if ( isset( $pratsroomtypes_options[ $args['id'] ] ) )
        $value = $pratsroomtypes_options[ $args['id'] ];
        else
        $value = isset( $args['std'] ) ? $args['std'] : '';

        $html = '<select id="pratsroomtypes_settings[' . $args['id'] . ']" name="pratsroomtypes_settings[' . $args['id'] . ']"/>';

        foreach ( $args['options'] as $option => $color ) :
            $selected = selected( $option, $value, false );
            $html .= '<option value="' . $option . '" ' . $selected . '>' . $color['label'] . '</option>';
        endforeach;

        $html .= '</select>';
        $html .= '<label for="pratsroomtypes_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

        echo $html;
    }

    /**
    * Rich Editor Callback
    *
    * Renders rich editor fields.
    *
    * @since 1.0
    * @param array $args Arguments passed by the setting
    * @global $pratsroomtypes_options Array of all the EDD Options
    * @global $wp_version WordPress Version
    */
    function rich_editorCallback( $args )
    {
        global $wp_version;
        $pratsroomtypes_options = get_option( 'pratsroomtypes_settings' );

        if ( isset( $pratsroomtypes_options[ $args['id'] ] ) ) {
            $value = $pratsroomtypes_options[ $args['id'] ];
        } else {
            $value = isset( $args['std'] ) ? $args['std'] : '';
        }

        $rows = isset( $args['size'] ) ? $args['size'] : 20;

        if ( $wp_version >= 3.3 && function_exists( 'wp_editor' ) ) {
            ob_start();
            wp_editor( stripslashes( $value ), 'pratsroomtypes_settings_' . $args['id'], array( 'textarea_name' => 'pratsroomtypes_settings[' . $args['id'] . ']', 'textarea_rows' => $rows ) );
            $html = ob_get_clean();
        } else {
            $html = '<textarea class="large-text" rows="10" id="pratsroomtypes_settings[' . $args['id'] . ']" name="pratsroomtypes_settings[' . $args['id'] . ']">' . esc_textarea( stripslashes( $value ) ) . '</textarea>';
        }

        $html .= '<br/><label for="pratsroomtypes_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

        echo $html;
    }

    /**
    * Upload Callback
    *
    * Renders upload fields.
    *
    * @since 1.0
    * @param array $args Arguments passed by the setting
    * @global $pratsroomtypes_options Array of all the EDD Options
    * @return void
    */
    function uploadCallback( $args )
    {
        $pratsroomtypes_options = get_option( 'pratsroomtypes_settings' );

        if ( isset( $pratsroomtypes_options[ $args['id'] ] ) )
        $value = $pratsroomtypes_options[$args['id']];
        else
        $value = isset($args['std']) ? $args['std'] : '';

        $size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
        $html = '<input type="text" class="' . $size . '-text" id="pratsroomtypes_settings[' . $args['id'] . ']" name="pratsroomtypes_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
        $html .= '<span>&nbsp;<input type="button" class="pratsroomtypes_settings_upload_button button-secondary" value="' . __( 'Upload File', 'pratsroomtypes-txt' ) . '"/></span>';
        $html .= '<label for="pratsroomtypes_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

        echo $html;
    }


    /**
    * Color picker Callback
    *
    * Renders color picker fields.
    *
    * @since 1.6
    * @param array $args Arguments passed by the setting
    * @global $pratsroomtypes_options Array of all the EDD Options
    * @return void
    */
    function colorCallback( $args ) {
        $pratsroomtypes_options = get_option( 'pratsroomtypes_settings' );

        if ( isset( $pratsroomtypes_options[ $args['id'] ] ) )
        $value = $pratsroomtypes_options[ $args['id'] ];
        else
        $value = isset( $args['std'] ) ? $args['std'] : '';

        $default = isset( $args['std'] ) ? $args['std'] : '';

        $size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
        $html = '<input type="text" class="pratsroomtypes-color-picker" id="pratsroomtypes_settings[' . $args['id'] . ']" name="pratsroomtypes_settings[' . $args['id'] . ']" value="' . esc_attr( $value ) . '" data-default-color="' . esc_attr( $default ) . '" />';
        $html .= '<label for="pratsroomtypes_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

        echo $html;
    }

    /**
    * Shop States Callback
    *
    * Renders states drop down based on the currently selected country
    *
    * @since 1.6
    * @param array $args Arguments passed by the setting
    * @global $pratsroomtypes_options Array of all the EDD Options
    * @return void
    */
    function shopStatesCallback($args)
    {
        $pratsroomtypes_options = get_option( 'pratsroomtypes_settings' );

        if ( isset( $args['placeholder'] ) )
        $placeholder = $args['placeholder'];
        else
        $placeholder = '';

        $states = PratsRoomtypes_Core_Settings::getShopStates();

        $select2= ( $args['select2'] ? ' pratsroomtypes-select2' : '' );
        $class = empty( $states ) ? ' class="pratsroomtypes-no-states' . $select2 . '"' : 'class="' . $select2 . '"';
        $html = '<select id="pratsroomtypes_settings[' . $args['id'] . ']" name="pratsroomtypes_settings[' . $args['id'] . ']"' . $class . 'data-placeholder="' . $placeholder . '"/>';

        foreach ( $states as $option => $name ) :
            $selected = isset( $pratsroomtypes_options[ $args['id'] ] ) ? selected( $option, $pratsroomtypes_options[$args['id']], false ) : '';
            $html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
        endforeach;

        $html .= '</select>';
        $html .= '<label for="pratsroomtypes_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

        echo $html;
    }

    /**
    * Tax Rates Callback
    *
    * Renders tax rates table
    *
    * @since 1.6
    * @param array $args Arguments passed by the setting
    * @global $pratsroomtypes_options Array of all the EDD Options
    * @return void
    */
    function taxRatesCallback($args)
    {
        $pratsroomtypes_options = get_option( 'pratsroomtypes_settings' );
        $rates = pratsroomtypes_get_tax_rates();
        ob_start(); ?>
        <p><?php echo $args['desc']; ?></p>
        <table id="pratsroomtypes_tax_rates" class="wp-list-table widefat fixed posts">
            <thead>
                <tr>
                    <th scope="col" class="pratsroomtypes_tax_country"><?php _e( 'Country', 'pratsroomtypes-txt' ); ?></th>
                    <th scope="col" class="pratsroomtypes_tax_state"><?php _e( 'State / Province', 'pratsroomtypes-txt' ); ?></th>
                    <th scope="col" class="pratsroomtypes_tax_global" title="<?php _e( 'Apply rate to whole country, regardless of state / province', 'pratsroomtypes-txt' ); ?>"><?php _e( 'Country Wide', 'pratsroomtypes-txt' ); ?></th>
                    <th scope="col" class="pratsroomtypes_tax_rate"><?php _e( 'Rate', 'pratsroomtypes-txt' ); ?></th>
                    <th scope="col"><?php _e( 'Remove', 'pratsroomtypes-txt' ); ?></th>
                </tr>
            </thead>
            <?php if( ! empty( $rates ) )
            { ?>
                <?php foreach( $rates as $key => $rate )
                { ?>
                    <tr>
                        <td class="pratsroomtypes_tax_country"><?php
                        echo EDD()->html->select( array(
                            'options'          => pratsroomtypes_get_country_list(),
                            'name'             => 'tax_rates[' . $key . '][country]',
                            'selected'         => $rate['country'],
                            'show_option_all'  => false,
                            'show_option_none' => false,
                            'class'            => 'pratsroomtypes-select pratsroomtypes-tax-country',
                            'select2' => true,
                            'placeholder' => __( 'Choose a country', 'pratsroomtypes-txt' )
                            ) );
                            ?>
                        </td>
                        <td class="pratsroomtypes_tax_state"><?php

                        $states = pratsroomtypes_get_shop_states( $rate['country'] );
                        if( ! empty( $states ) ) {
                            echo EDD()->html->select( array(
                                'options'          => $states,
                                'name'             => 'tax_rates[' . $key . '][state]',
                                'selected'         => $rate['state'],
                                'show_option_all'  => false,
                                'show_option_none' => false,
                                'select2' => true,
                                'placeholder' => __( 'Choose a state', 'pratsroomtypes-txt' )
                                ) );
                            } else {
                                echo EDD()->html->text( array(
                                    'name'             => 'tax_rates[' . $key . '][state]', $rate['state']
                                ) );
                            }
                            ?>
                        </td>
                        <td class="pratsroomtypes_tax_global">
                            <input type="checkbox" name="tax_rates[<?php echo $key; ?>][global]" id="tax_rates[<?php echo $key; ?>][global]" value="1"<?php checked( true, ! empty( $rate['global'] ) ); ?>/>
                            <label for="tax_rates[<?php echo $key; ?>][global]"><?php _e( 'Apply to whole country', 'pratsroomtypes-txt' ); ?></label>
                        </td>
                        <td class="pratsroomtypes_tax_rate"><input type="number" class="small-text" step="0.0001" min="0.0" max="99" name="tax_rates[<?php echo $key; ?>][rate]" value="<?php echo $rate['rate']; ?>"/></td>
                        <td><span class="pratsroomtypes_remove_tax_rate button-secondary"><?php _e( 'Remove Rate', 'pratsroomtypes-txt' ); ?></span>
                        </td>
                    </tr>
                    <?php
                } ?>
                <?php
            }
            else
            { ?>
                <tr>
                    <td class="pratsroomtypes_tax_country"><?php
                    echo EDD()->html->select( array(
                        'options'          => pratsroomtypes_get_country_list(),
                        'name'             => 'tax_rates[0][country]',
                        'show_option_all'  => false,
                        'show_option_none' => false,
                        'class'            => 'pratsroomtypes-select pratsroomtypes-tax-country',
                        'select2' => true,
                        'placeholder' => __( 'Choose a country', 'pratsroomtypes-txt' )
                        ) );
                        ?>
                    </td>
                    <td class="pratsroomtypes_tax_state">
                        <?php echo EDD()->html->text( array(
                            'name'             => 'tax_rates[0][state]'
                        ) ); ?>
                    </td>
                    <td class="pratsroomtypes_tax_global">
                        <input type="checkbox" name="tax_rates[0][global]" value="1"/>
                        <label for="tax_rates[0][global]"><?php _e( 'Apply to whole country', 'pratsroomtypes-txt' ); ?></label>
                    </td>
                    <td class="pratsroomtypes_tax_rate"><input type="number" class="small-text" step="0.0001" min="0.0" name="tax_rates[0][rate]" value=""/>
                    </td>
                    <td><span class="pratsroomtypes_remove_tax_rate button-secondary"><?php _e( 'Remove Rate', 'pratsroomtypes-txt' ); ?></span>
                    </td>
                </tr>
                <?php
            } ?>
        </table>
        <p>
            <span class="button-secondary" id="pratsroomtypes_add_tax_rate"><?php _e( 'Add Tax Rate', 'pratsroomtypes-txt' ); ?></span>
        </p>
        <?php
        echo ob_get_clean();
    }

    /**
    * Header Callback
    *
    * Renders the header.
    *
    * @since 1.0.0
    * @param array $args Arguments passed by the setting
    * @return void
    */
    function headerCallback( $args )
    {
        echo '<hr/>';
    }

    /**
    * Shop States Callback
    *
    * Renders states drop down based on the currently selected country
    *
    * @since 1.6
    * @param array $args Arguments passed by the setting
    * @global $pratsroomtypes_options Array of all the EDD Options
    * @return void
    */
    function shop_statesCallback($args)
    {
        $pratsroomtypes_options = get_option( 'pratsroomtypes_settings' );

        if ( isset( $args['placeholder'] ) )
        $placeholder = $args['placeholder'];
        else
        $placeholder = '';

        $states = PratsRoomtypes_Core_Settings::getShopStates();

        $select2= ( $args['select2'] ? ' pratsroomtypes-select2' : '' );
        $class = empty( $states ) ? ' class="pratsroomtypes-no-states' . $select2 . '"' : 'class="' . $select2 . '"';
        $html = '<select id="pratsroomtypes_settings[' . $args['id'] . ']" name="pratsroomtypes_settings[' . $args['id'] . ']"' . $class . 'data-placeholder="' . $placeholder . '"/>';

        foreach ( $states as $option => $name ) :
            $selected = isset( $pratsroomtypes_options[ $args['id'] ] ) ? selected( $option, $pratsroomtypes_options[$args['id']], false ) : '';
            $html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
        endforeach;

        $html .= '</select>';
        $html .= '<label for="pratsroomtypes_settings[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

        echo $html;
    }


    /**
    * Hook Callback
    *
    * Adds a do_action() hook in place of the field
    *
    * @since 1.0.8.2
    * @param array $args Arguments passed by the setting
    * @return void
    */
    function hookCallback( $args )
    {
        do_action( 'pratsroomtypes_' . $args['id'] );
    }
}
