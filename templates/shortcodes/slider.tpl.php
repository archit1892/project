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
$attachments = PratsRoomtypes_Helpers_Wordpress::getAttachmentsFromPost($post->ID);
if ( !$attachments )
{
    return NULL;
}

?>
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <?php
        $count = 0;
        foreach($attachments as $attachment)
        {
            ?>
            <li data-target="#carousel-example-generic" data-slide-to="<?php echo $count; ?>" class="<?php echo ($count == 0 ? 'active' : ''); ?>"></li>
            <?php
            $count++;
        }
        ?>
    </ol>

    <div class="carousel-inner" role="listbox">
        <?php
        $count = 0;
        foreach($attachments as $attachment)
        {
            ?>
            <div class="item <?php echo ($count == 0 ? 'active' : ''); ?>">
                <img class="img-responsive" src="<?php echo $attachment['src']; ?>" alt="Gallery<?php echo $count; ?>">
            </div>
            <?php
            $count++;
        } ?>
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
