<?php

/**
* PratsRoomtypes
*
* @category   Templates
* @package    PratsRoomtypes
* @author     Sumeet Shroff <sumeet@prateeksha.com>
* @copyright  2016 Sumeet Shroff (http://www.prateeksha.com)
* @license    see license.txt
* @link       http://www.prateeksha.com/
*/

// Exit if accessed directly.
if (!defined('ABSPATH'))
{
    exit();
}

// Get the model
$model = PratsRoomtypes::getModel('roomtypes');
$rows = $model->setProperty($post->ID)->getList(array('debug' => 0));
if ( !$rows )
{
    return NULL;
}

$a = PratsRoomtypes::getInstance();
global $standard_roomtypes;
$standard_roomtypes =  array_flip(array_keys($a->getOption('standard_roomtypes')));

// We have to sort it
usort ($rows, array('PratsRoomtypes_Posttypes_Roomtypes','sortRoomtypes'));

?>
<div class="tabber tbpad3">
    <ul class="nav nav-tabs" role="tablist">

        <li role="presentation" class="active">
            <a href="#allroomtypes" aria-controls="allroomtypes" role="tab" data-toggle="tab">
                <?php echo __('Room Types') ?>
            </a>
        </li>

        <?php
        $flag = true;
        foreach($rows as $row)
        {
            $key = sanitize_key($row->post_name); ?>
            <li role="presentation">
                <a href="#<?php echo $key; ?>" aria-controls="<?php echo $key; ?>" role="tab" data-toggle="tab">
                    <?php echo $row->post_title; ?>
                </a>
            </li>
            <?php
        }
        ?>
    </ul>

    <div class="tab-content">

        <div role="tabpanel" class="tab-pane active" id="allroomtypes">
            <table width="100%">
                <?php
                foreach($rows as $row)
                { ?>
                    <tr>
                        <td><p><?php echo esc_html($row->post_title); ?></p></td>
                        <td><p><?php

                        $price_min = trim(PratsRoomtypes_Helpers_Wordpress::getPostMeta($row->ID, 'price_min'));
                        $price_max = trim(PratsRoomtypes_Helpers_Wordpress::getPostMeta($row->ID, 'price_max'));

                        if ( !empty($price_max) )
                        {
                            echo sprintf( __("%s to %s per month"), esc_html($price_min), esc_html($price_max));
                        }
                        else
                        {
                            echo sprintf( __("%s per month"), esc_html($price_min));
                        }
                        ?>
                    </p></td>
                    <td>

                        <?php
                        // current_time( 'timestamp' ) is used to get the current time from Wordpress
                        $availability = (boolean) PratsRoomtypes_Posttypes_Roomtypes::isAvailable(array('checkdate' => date('d-m-Y', current_time( 'timestamp' )), 'post' => $row));
                        if ( !$availability )
                        {
                            echo do_shortcode('[roomtypes_waitlistform post_id='.$post->ID.' suite='.$row->ID.']');
                        }
                        else
                        {
                            ?>
                            <div class="button-green">
                                <?php _e('Available'); ?>
                            </div>
                            <?php
                        } ?>

                    </td>
                </tr>
                <?php
            } ?>
        </table>
    </div>

    <?php
    $flag = true;

    foreach($rows as $row)
    {
        $key = sanitize_key($row->post_name);
        $active = '';
        if ( $flag )
        {
            $active = 'active';
            $flag = false;
        }
        ?>
        <div role="tabpanel" class="tab-pane" id="<?php echo $key; ?>">
            <p><?php echo $row->post_content; ?></p>
            <div class="float-left">
                <div class="price">
                    <p>
                        <?php
                        $price_min = PratsRoomtypes_Helpers_Wordpress::getPostMeta($row->ID, 'price_min');
                        $price_max = trim(PratsRoomtypes_Helpers_Wordpress::getPostMeta($row->ID, 'price_max'));

                        if ( !empty($price_max) )
                        {
                            echo sprintf( __("%s to %s per month"), esc_html($price_min), esc_html($price_max));
                        }
                        else
                        {
                            echo sprintf( __("%s per month"), esc_html($price_min));
                        }

                        ?>
                    </p>
                </div>

                <?php
                if ( !$availability )
                {
                    echo do_shortcode('[roomtypes_waitlistform post_id='.$post->ID.' suite='.$row->ID.']');
                }
                else
                {
                    ?>
                    <div class="button-green">
                        <?php _e('Available'); ?>
                    </div>
                    <?php
                } ?>

            </div>
            <div class="float-left">
                <ul class="floorplans">
                    <?php
                    $documents = PratsRoomtypes_Helpers_Wordpress::getPostMeta($row->ID, 'pdfs');
                    if ( $documents )
                    {
                        foreach($documents as $doc_id)
                        {
                            $attachment = (object)PratsRoomtypes_Helpers_Wordpress::getAttachmentById( $doc_id );
                            if ( !$attachment->image )
                            {
                                $attachment->image = array(
                                    0 => PRATSROOMTYPES_PLUGIN_URL . "assets/images/floorplan.png",
                                    1 => 125,
                                    2 => 125,
                                    3 => true
                                );
                            }
                            ?>
                            <li>

                                <a href="<?php echo $attachment->url; ?>" target="_blank">
                                    <img src="<?php echo $attachment->image[0]; ?>" alt="<?php echo $attachment->caption; ?>" style="width: 100px; height: 100px;">
                                    <?php echo $attachment->caption; ?>
                                </a>
                                <h4><?php
                                if ( !empty($attachment->caption) )
                                {
                                    echo $attachment->caption;
                                }
                                else
                                {
                                    echo $attachment->title;
                                }
                                ?></h4>
                            </li>
                            <?php
                        }
                    }

                    if ( !empty($view3d = PratsRoomtypes_Helpers_Wordpress::getPostMeta($row->ID, 'view3d') ) )
                    {
                        $view3d = trim($view3d);
                        $array = explode(' ', $view3d);
                        foreach($array as $view3d)
                        {
                            $view3d = trim($view3d);
                            ?>
                            <li style="padding-left: 40px; width: 280px">
                                <div class="col-md-12">
                                <a href="<?php echo $view3d; ?>" class="property_overview_thumb_thumbnail_size fancybox_image iframe" rel="room-3dplan">

                                    <div class="play-button-overlay-small"></div>
                                    <div class="play-button-small">
                                        <p class="title"><?php echo $property[ 'post_title' ]; ?></p>
                                        <i class="fa fa-play" aria-hidden="true"></i>
                                        <div><?php echo __('Explore 3d View'); ?></div>
                                    </div>

                                    <img class="img-responsive" src="<?php echo PratsRoomtypes_Helpers_Matterport::getThumbnail($view3d); ?>" alt="<?php echo $attachment['alt']; ?>" style="height: auto; width: 100%">
                                </a>
                            </div>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
        <?php
    }
    ?>
</div>
</div>
