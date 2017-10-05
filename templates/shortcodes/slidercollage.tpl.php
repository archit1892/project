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
if (!defined('ABSPATH')) {
    exit();
}

/**
 * I need the order to be set as per below

  1Featured Image: Will be the main big image on the left

  2. 3D Video Link

  3. Video

  4. Google Map

  5. Photos...
 * * */
// Get all the postmeta from Post
$postmeta = PratsRoomtypes_Helpers_Wordpress::getPostMeta($post->ID);

// Main container for attachments
$attachments = array();

// Added first flag - The first image takes 4 cell in the grid. hence if 3dlink and video is added first, then we set the flag to ON
$added_first = false;

//$attachments[] = get_the_post_thumbnail( 'full' );          
$id = get_post_thumbnail_id($post->ID);

if ($id) {
    $a = PratsRoomtypes_Helpers_Wordpress::getAttachmentById($id);
    $attachments[] = $a;
    if (!$added_first) {
        $attachments[] = $a;
        $attachments[] = $a;
        $attachments[] = $a;
        $added_first = true;
    }
}



// Add the 3d Links first
$array = array('3d_link', '3d_link_2', '3d_link_3');
foreach ($array as $value) {
    if (!empty($postmeta[$value]) && !empty($postmeta[$value][0])) {
        $attachments[] = PratsRoomtypes_Helpers_Matterport::getLink($postmeta[$value][0]);
        if (!$added_first) {
            $attachments[] = PratsRoomtypes_Helpers_Matterport::getLink($postmeta[$value][0]);
            $attachments[] = PratsRoomtypes_Helpers_Matterport::getLink($postmeta[$value][0]);
            $attachments[] = PratsRoomtypes_Helpers_Matterport::getLink($postmeta[$value][0]);
            $added_first = true;
        }
    }
}

$array = array('video', 'video_2');
foreach ($array as $value) {
    if (!empty($postmeta[$value]) && !empty($postmeta[$value][0])) {
        $attachments[] = PratsRoomtypes_Core_Settings::getVideoLink($postmeta[$value][0]);
        if (!$added_first) {
            $attachments[] = PratsRoomtypes_Core_Settings::getVideoLink($postmeta[$value][0]);
            $attachments[] = PratsRoomtypes_Core_Settings::getVideoLink($postmeta[$value][0]);
            $attachments[] = PratsRoomtypes_Core_Settings::getVideoLink($postmeta[$value][0]);
            $added_first = true;
        }
    }
}



// Get the model
$attachments = array_merge($attachments, PratsRoomtypes_Helpers_Wordpress::getAttachmentsFromPost($post->ID));

if (!$added_first) {
    array_unshift($attachments, $attachments[0]);
    array_unshift($attachments, $attachments[0]);
    array_unshift($attachments, $attachments[0]);
    $added_first = true;
}

if (!$attachments) {
    return NULL;
}


$property = (array) $post;

// Prepare the attachments
$number_per_slide = 8;

// Should in multiples of 8
if ($diff = (count($attachments) % $number_per_slide)) {
    // Fill in the gaps
    $images_to_add = $number_per_slide - $diff;

    $attachments2 = PratsRoomtypes_Helpers_Wordpress::getAttachmentsFromPost($post->ID);
    // Copy the first photos to the end
    for ($i = 0; $i < $images_to_add; $i++) {
        array_push($attachments, $attachments2[$i]);
    }
}



$slides = count($attachments) / $number_per_slide;
?>
<div id="carousel-example-generic" class="hidden-xs carousel slide" data-interval="false" style="width: 100%; max-width: 1920px">

    <ol class="carousel-indicators">
<?php
for ($i = 0; $i < $slides; $i++) {
    ?>
            <li data-target="#carousel-example-generic" data-slide-to="<?php echo $i; ?>" class="<?php echo ($i == 0 ? 'active' : ''); ?>">
            </li>
    <?php
}
?>
    </ol>

    <div class="carousel-inner" role="listbox">

<?php
for ($i = 0; $i < $slides; $i++) {
    ?>
            <div class="item <?php if ($i == 0) echo 'active'; ?>">
                <div class="item-collage">
            <?php
            if ($i == 0) {
                $start = $i * 8;
                ?>
                        <div class="row">
                <?php $attachment = $attachments[$start]; ?>
                            <div class="col-xs-6 thumbnail-slider-big">
                <?php
                echo getThumbnailForSlider($property, $attachment);
                ?>
                            </div>
                            <div class="col-xs-6">
                                <div class="row">
                <?php
                $attachment = $attachments[$start + 4];
                ?>
                                    <div class="col-xs-6 thumbnail-slider">
                        <?php
                        echo getThumbnailForSlider($property, $attachment);
                        ?>
                                    </div>
                        <?php
                        $attachment = $attachments[$start + 6];
                        ?>
                                    <div class="col-xs-6 thumbnail-slider">
                            <?php
                            echo getThumbnailForSlider($property, $attachment);
                            ?>
                                    </div>
                                </div>
                                <div class="row">
        <?php
        $attachment = $attachments[$start + 5];
        ?>
                                    <div class="col-xs-6 thumbnail-slider">
                                    <?php
                                    echo getThumbnailForSlider($property, $attachment);
                                    ?>
                                    </div>
                                        <?php
                                        $attachment = $attachments[$start + 7];
                                        ?>
                                    <div class="col-xs-6 thumbnail-slider">
                                    <?php
                                    echo getThumbnailForSlider($property, $attachment);
                                    ?>
                                    </div>
                                </div><!-- end of row2 -->
                            </div>
                        </div><!-- end of row -->
                                    <?php
                                } else {
                                    $start = $i * 8;
                                    ?>
                        <div class="row">
                                        <?php $attachment = $attachments[$start]; ?>
                            <div class="col-xs-3 thumbnail-slider">
                                        <?php
                                        echo getThumbnailForSlider($property, $attachment);
                                        ?>
                            </div>
                                    <?php
                                    $attachment = $attachments[$start + 2];
                                    ?>
                            <div class="col-xs-3 thumbnail-slider">
                                        <?php
                                        echo getThumbnailForSlider($property, $attachment);
                                        ?>
                            </div>
                        <?php
                        $attachment = $attachments[$start + 4];
                        ?>
                            <div class="col-xs-3 thumbnail-slider">
                        <?php
                        echo getThumbnailForSlider($property, $attachment);
                        ?>
                            </div>
                            <?php
                            $attachment = $attachments[$start + 6];
                            ?>
                            <div class="col-xs-3 thumbnail-slider">
                                <?php
                                echo getThumbnailForSlider($property, $attachment);
                                ?>
                            </div>
                        </div>
                        <div class="row">
                                <?php
                                $attachment = $attachments[$start + 1];
                                ?>
                            <div class="col-xs-3 thumbnail-slider">
                            <?php
                            echo getThumbnailForSlider($property, $attachment);
                            ?>
                            </div>
                                <?php
                                $attachment = $attachments[$start + 3];
                                ?>
                            <div class="col-xs-3 thumbnail-slider">
                            <?php
                            echo getThumbnailForSlider($property, $attachment);
                            ?>
                            </div>
                                <?php
                                $attachment = $attachments[$start + 5];
                                ?>
                            <div class="col-xs-3 thumbnail-slider">
        <?php
        echo getThumbnailForSlider($property, $attachment);
        ?>
                            </div>
                            <?php
                            $attachment = $attachments[$start + 7];
                            ?>
                            <div class="col-xs-3 thumbnail-slider">
                                <?php
                                echo getThumbnailForSlider($property, $attachment);
                                ?>
                            </div>
                        </div>
                            <?php
                        }
                        ?>
                </div>
            </div>
                        <?php
                    }
                    ?>
    </div>


    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>

</div>

                <?php

                function getThumbnailForSlider($property, $attachment, $small_size = true) {
                    if (isset($attachment['type']) && ($attachment['type'] == 'video' || $attachment['type'] == 'googlemap' || $attachment['type'] == '3dlink')) {
                        ?>
        <a href="<?php echo $attachment['href']; ?>" title="<?php echo $property['post_title'] . (!empty($property['parent_title']) ? __(' of ', ud_get_wp_property()->domain) . $property['parent_title'] : "" ); ?>"
           class="property_overview_thumb_thumbnail_size fancybox_image iframe"
           rel="<?php echo $attachment['type']; ?>">

        <?php
        if ($attachment['type'] == '3dlink') {
            if (!$small_size) {
                ?>
                    <div class="play-button-overlay"></div>
                    <div class="play-button">
                        <p class="title"><?php echo $property['post_title']; ?></p>
                        <i class="fa fa-play" aria-hidden="true"></i>
                        <div><?php echo __('Explore 3d View'); ?></div>
                    </div>
                <?php
            } else {
                ?>
                    <div class="play-button-overlay-slider-small"></div>
                    <div class="play-button-slider-small">
                        <p class="title"><?php echo $property['post_title']; ?></p>
                        <i class="fa fa-play" aria-hidden="true"></i>
                        <div><?php echo __('Explore 3d View'); ?></div>
                    </div>
                <?php
            }
        }
        ?>
            <img class="img-responsive" src="<?php echo $attachment['thumbnail_650x430_crop'][0]; ?>" alt="<?php echo $attachment['alt']; ?>" style="border-right: 2px solid white; margin-right: 0px; height: 100%; width: 100%">

        </a>
            <?php
        } else {
            ?>
        <a href="<?php echo $attachment['src']; ?>" title="<?php echo $property['post_title'] . (!empty($property['parent_title']) ? __(' of ', ud_get_wp_property()->domain) . $property['parent_title'] : "" ); ?>" class="property_overview_thumb_thumbnail_size fancybox_image" rel="photos">
            <img class="img-responsive" src="<?php echo $attachment['thumbnail_650x430_crop'][0]; ?>" alt="<?php echo $attachment['alt']; ?>" style="height: auto; width: 100%" />
        </a>
        <?php
    }
}

include('slidercollage-for-mobile.php');
