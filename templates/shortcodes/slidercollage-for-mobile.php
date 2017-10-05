<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



$attachments_mobiles = array();

$id = get_post_thumbnail_id($post->ID);
if ($id) {
    $a = PratsRoomtypes_Helpers_Wordpress::getAttachmentById($id);
    $attachments_mobiles[] = $a;
}

// Add the 3d Links first
$array = array('3d_link', '3d_link_2', '3d_link_3');
foreach ($array as $value) {
    if (!empty($postmeta[$value]) && !empty($postmeta[$value][0])) {
        $attachments_mobiles[] = PratsRoomtypes_Helpers_Matterport::getLink($postmeta[$value][0]);
    }
}

$array = array('video', 'video_2');
foreach ($array as $value) {
    if (!empty($postmeta[$value]) && !empty($postmeta[$value][0])) {
        $attachments_mobiles[] = PratsRoomtypes_Core_Settings::getVideoLink($postmeta[$value][0]);
    }
}

// Now we have to add Google map
/*
if (!empty($postmeta['location'])) {
    
    $google_url = 'http://maps.google.com/';
    $google_href = sprintf('output=embed&f=q&source=s_q&hl=en&amp;geocode=&amp;q=%s&hl=lv&ll=%s&spn=0.00571,0.016512&amp;sll=56.879635,24.603189&amp;sspn=10.280244,33.815918&amp;vpsrc=6&amp;hq=London+Eye&amp;radius=15000&amp;t=h&amp;z=17', urlencode($postmeta['location'][0]), $postmeta['location_map_coordinates'][0]);

    $googlemap = array(
        "type" => 'googlemap',
        "alt" => "googlemap",
		"class" => 'googlemap',
        "href" => $google_url . '?' . $google_href,
        "src" => PRATSROOMTYPES_PLUGIN_URL . "assets/images/google-maps.jpg",
        "thumbnail_320x210_crop" => array(
            PRATSROOMTYPES_PLUGIN_URL . "assets/images/google-maps.jpg",
            225, 150, true
        ),
        "thumbnail_650x430_crop" => array(
            PRATSROOMTYPES_PLUGIN_URL . "assets/images/google-maps.jpg",
            225, 150, true
    ));

    $attachments_mobiles[] = $googlemap;

}
*/
// Get the model
$attachments_mobiles = array_merge($attachments_mobiles, PratsRoomtypes_Helpers_Wordpress::getAttachmentsFromPost($post->ID));

if (!$attachments_mobiles) {
    return NULL;
}

$slides = count($attachments_mobiles);

?>
<div id="carousel-example-generic-mobile" class="hidden-sm hidden-md hidden-lg carousel slide" data-interval="false" style="width: 100%; max-width: 1920px">

    <ol class="carousel-indicators">
        <?php
        for ($i = 0; $i < $slides; $i++) {
            ?>
            <li data-target="#carousel-example-generic-mobile" data-slide-to="<?php echo $i; ?>" class="<?php echo ($i == 0 ? 'active' : ''); ?>">
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

                    <div class="row">
    <?php $attachments_mobile = $attachments_mobiles[$i]; ?>
                        <div class="col-xs-12 thumbnail-slider-mobile">
                        <?php
                        echo getThumbnailForSlider($property, $attachments_mobile);
                        ?>
                        </div>
                    </div> 
                </div>
            </div>
    <?php
}
?>
    </div>


    <a class="left carousel-control" href="#carousel-example-generic-mobile" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#carousel-example-generic-mobile" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>

</div>

<?php

