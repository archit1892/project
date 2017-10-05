<?php
/**
* Array Helper class
*
* @category   Helpers
* @package    PratsRoomtypes
* @author     Sumeet Shroff <sumeet@prateeksha.com>
* @copyright  2016 Sumeet Shroff Prateeksha Web Design (http://www.prateeksha.com)
* @license    see license.txt
* @link       http://www.prateeksha.com/
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit();
}

class PratsRoomtypes_Helpers_Matterport
{

    /**
    * Method to save items
    *
    * @param object $post  Post object
    * @param array $postmeta  $post meta data that needs to be save
    *
    * @return void|boolean
    */
    function getThumbnail($link)
    {
        // https://my.matterport.com/show/?m=8nRQXFDBXUT

        $string = 'https://my.matterport.com/api/v1/player/models/8nRQXFDBXUT/thumb';

        $querystring = parse_url ( $link, PHP_URL_QUERY );
        parse_str($querystring, $get_array);
        if ( isset($get_array['m']) )
        {
            $string = sprintf('https://my.matterport.com/api/v1/player/models/%s/thumb', $get_array['m']);
        }

        return $string;
    }


    function getLink($link)
    {
        $thumbnail = self::getThumbnail($link);

        return array(
            "type" => '3dlink',
            "alt" => "3dlink",
            "href" => $link,
            "src" => $thumbnail,
            "thumbnail_320x210_crop" => array(
                $thumbnail,
                225, 150, true
            ),
            "thumbnail_650x430_crop" => array(
                $thumbnail,
                225, 150, true
            )
        );

        /*
        return array(
            "type" => '3dlink',
            "alt" => "3dlink",
            "href" => $link,
            "src" => PRATSROOMTYPES_PLUGIN_URL . "assets/images/3d-view.jpg",
            "thumbnail_320x210_crop" => array(
                PRATSROOMTYPES_PLUGIN_URL . "assets/images/3d-view.jpg",
                225, 150, true
            ),
            "thumbnail_650x430_crop" => array(
                PRATSROOMTYPES_PLUGIN_URL . "assets/images/3d-view.jpg",
                225, 150, true
            )
        );*/
    }


}
