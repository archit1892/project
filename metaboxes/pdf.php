<?php

/**
* PratsTb - Travel Blog
*
* @category    Widget
* @package     PratsTb
* @copyright   Copyright (c) 2016 Prateeksha Web Design (http://www.prateeksha.com)
* @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
* @link        http://www.prateeksha.com/
* @author      Sumeet Shroff (sumeet@prateeksha.com)
*/

// No direct access allowed
if (!defined('ABSPATH')) exit('Please do not load this file directly.');

/**
* Class reference
*
* @category    Widget
* @package     PratsTb
* @copyright   Copyright (c) 2016 Prateeksha Web Design (http://www.prateeksha.com)
* @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
* @link        http://www.prateeksha.com/
* @author      Sumeet Shroff (sumeet@prateeksha.com)
*/

class PratsRoomtypes_MetaBoxes_Pdf extends PratsRoomtypes_Helpers_MetaBox
{
    /**
    * Method to register a metabox meta box
    *
    * @param array $args
    *
    * @return NULL
    */
    public static function register()
    {
        $args = array(
            'posttype' => 'roomtypes',
            'classname' => __CLASS__,
            'function' => 'show',
            'id' => 'pdf',
            'label' => __('Upload Room Layout'),
            'position' => 'normal',
            'save_function' => 'save',
            'show_priority' => 'default',
            'save_priority' => 5,
            'callback_args' => array()
        );

        parent::registerA($args);
    }

    /**
    * Method to show the pdfs box
    * Shows all the form elements in normal form
    *
    * @return NULL
    */
    public static function show($post)
    {
        // Load Scripts
        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');

        add_action('admin_print_scripts', 'joe_admin_scripts');
        add_action('admin_print_styles', 'joe_admin_styles');

        $image_src = '';
        $count = 1;

        ?>
        <input type="hidden" name="pdfs_meta_noncename"
        id="pdfs_meta_noncename"
        value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>" />
        <?php

        // Get all the pdfs...
        $pdfs = get_post_meta($post->ID, 'pdfs', true);
        if ($pdfs)
        {
            ?>
            <div style="margin: 10px;" id="pdf-list">
                <?php

                $count_img = 0;
                foreach ($pdfs as $key => $attach_id)
                {
                    // Convert to integer
                    $attach_id = intval($attach_id);
                    if ($attach_id)
                    {
                        ?>
                        <div class="pdf-item" style="margin-bottom: 30px; text-align: center; border-botto: 2px solid #cccccc; padding-botton: 10px;">
                            <?php
                            $b = PratsRoomtypes_Helpers_Wordpress::getAttachmentById($attach_id);
                            if ($b)
                            {
                                $a = wp_get_attachment_image_src($attach_id, 'thumbnail_360x230_crop');
                                if (!$a)
                                {
                                    // Set to default
                                    $a = array(
                                        0 => PRATSROOMTYPES_PLUGIN_URL . "assets/images/floorplan.png",
                                        1 => 125,
                                        2 => 125,
                                        3 => true);
                                    }
                                }
                                ?>
                                <img src="<?php echo $a[0]; ?>" width="<?php echo $a[1]; ?>" height="<?php echo $a[2]; ?>" />
                                <input type="hidden" name="attachments[]" value="<?php echo $attach_id; ?>" />
                                <div class="filename"><?php echo $b['url']; ?></div>
                                <a href="javascript:void" class="remove-button" onclick="jQuery(this).parent().remove(); alert('Removed PDF');">
                                    <?php echo __('Remove PDF'); ?>
                                </a>
                                <?php

                                ?>
                            </div><?php
                        }
                    }  ?>
                </div>
                <?php
            }

            /**
            * Now add the option to load more files
            */
            ?>
            <div style="margin: 10px; clear: both">
                <div id="imagefiles"></div>
                <input id="_btn" class="upload_image_button button" type="button"
                value="Upload PDF" />
            </div>
            <script type="text/javascript">

            count = <?php echo $count; ?>

            jQuery(document).ready(function() {

                var formfield;
                jQuery('.upload_image_button').click(function() {
                    jQuery('html').addClass('Image');
                    formfield = 'imagefiles';
                    tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
                    return false;
                });

                window.original_send_to_editor = window.send_to_editor;
                window.send_to_editor = function(html)
                {
                    if (formfield)
                    {
                        html = jQuery.trim(html);
                        fileurl = jQuery(html).attr('href');

                        //alert(fileurl);

                        if ( fileurl !== undefined )
                        {
                            // Add the html
                            pdfhtml = "<div>"+html;

                            // Add the fileurl to input value
                            pdfhtml += "<input type=\"hidden\" name=\"pdfs[]\" value=\""+fileurl+"\" />";
                            pdfhtml += "<br /><a href=\"javascript:void\" class=\"remove-button\" onclick=\"jQuery(this).parent().remove(); alert('Removed PDF');\"><?php echo __('Remove PDF'); ?></a>";

                            pdfhtml += "</div>";

                            jQuery('#'+formfield).append( pdfhtml );
                        }
                        else if ( html )
                        {
                            // This may be only the slud
                            pdfhtml = "<div>"+html;

                            // Add the fileurl to input value
                            pdfhtml += "<input type=\"hidden\" name=\"pdfs[]\" value=\""+html+"\" />";
                            pdfhtml += "<br /><a href=\"javascript:void\" class=\"remove-button\" onclick=\"jQuery(this).parent().remove(); alert('Removed PDF');\"><?php echo __('Remove PDF'); ?></a>";

                            pdfhtml += "</div>";

                            jQuery('#'+formfield).append( pdfhtml );
                        }
                        else
                        {
                            alert('Not found PDF url');
                        }

                        tb_remove();
                        jQuery('html').removeClass('Image');
                        count++;

                    }
                    else
                    {
                        window.original_send_to_editor(html);
                    }
                };
            });
            </script>
            <?php

            return;
        }

        /**
        * Method to save the meta box
        *
        * @return integer
        */
        public static function save($post_id)
        {
            // Verify NOnce
            if (!wp_verify_nonce($_POST ['pdfs_meta_noncename'],
            plugin_basename(__FILE__)))
            {
                return $post_id;
            }

            // Is the user allowed to edit the post or page?
            if (!current_user_can('edit_post', $post->ID)) return $post->ID;

            // Request object
            $request = PratsRoomtypes::getInput();

            // Now Save
            $postmeta = array ();

            // Get the request variables
            $pdfs = $request->post('pdfs', array(), 'array');
            $attachments = $request->post('attachments', array(), 'array');


            // Prepare pdfs
            foreach($pdfs as $pdf)
            {
                if ( empty($pdf) )
                {
                    continue;
                }

                // Remove slashes
                $html = trim(stripcslashes($pdf));
                $pdf = NULL;

                // Check if it is a URL
                if (filter_var($html, FILTER_VALIDATE_URL) !== FALSE)
                {
                    $pdf = PratsRoomtypes_Helpers_Wordpress::getImageIdFromUrl($html);
                }
                else if ( $pdf = PratsRoomtypes_Helpers_Wordpress::getPDFIdBySlug($html) )
                {
                }
                else
                {
                    // Extract the url from the HTML ...
                    // Prepare the dom document
                    $dom = new DOMDocument();
                    @$dom->loadHTML($html);

                    $a = $dom->getElementsByTagName('a');
                    if ( !$a->length )
                    {
                        continue;
                    }

                    for ($i; $i < $a->length; $i++)
                    {
                        if ( $a->item($i)->hasAttribute('href') )
                        {
                            $attr = $a->item($i)->getAttribute('href');
                            $attr = trim($attr, "'");
                            $filetype = wp_check_filetype($attr);
                            if ( $filetype['ext'] == 'pdf')
                            {
                                $pdf = PratsRoomtypes_Helpers_Wordpress::getImageIdFromUrl($attr);
                            }
                        }
                    }
                }

                if ( $pdf )
                {
                    $postmeta ['pdfs'][] = $pdf;
                }
            }

            // Prepare the attachments
            if ( $attachments )
            {
                foreach($attachments as $attach_id)
                {
                    $postmeta ['pdfs'][] = $attach_id;
                }
            }

            // Make the array unique
            $postmeta ['pdfs'] = PratsRoomtypes_Helpers_Array::toUnique($postmeta ['pdfs']);
            $postmeta ['pdfs'] = PratsRoomtypes_Helpers_Array::toInteger($postmeta ['pdfs']);

            // Save items
            PratsRoomtypes_Helpers_Wordpress::savePostMeta($post_id, $postmeta);

            return $post_id;
        }
    }
