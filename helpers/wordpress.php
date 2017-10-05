<?php


class PratsRoomtypes_Helpers_Wordpress
{

    /**
    * Method to save items
    *
    * @param object $post  Post object
    * @param array $postmeta  $post meta data that needs to be save
    *
    * @return void|boolean
    */
    function savePostMeta($post_id, $postmeta)
    {
        // Make sure it is an array
        $postmeta = (array) $postmeta;

        // Iterate
        foreach ($postmeta as $key => $value)
        {
            if ($post->post_type == 'revision') return;

            if (get_post_meta($post_id, $key, FALSE)) {
                update_post_meta($post_id, $key, $value);
            } else {
                add_post_meta($post_id, $key, $value);
            }

            if (!$value) delete_post_meta($post_id, $key);
        }

        return true;
    }


    /**
    * Method to save items
    *
    * @param object $post  Post object
    * @param array $postmeta  $post meta data that needs to be save
    *
    * @return void|boolean
    */
    function getPostMeta($post_id, $key = NULL, $is_not_array = true)
    {
        $post_id = intval($post_id);
        if ( !$post_id )
        {
            return NULL;
        }

        // If NULL
        if ( !$key )
        {
            return get_post_meta($post_id);

        }

        return get_post_meta($post_id, $key, $is_not_array);
    }

    /**
    * Method to save items
    *
    * @param object $post  Post object
    * @param array $postmeta  $post meta data that needs to be save
    *
    * @return void|boolean
    */
    function getPost($post_id)
    {
        $post_id = intval($post_id);
        if ( !$post_id )
        {
            return NULL;
        }

        return get_post($post_id);
    }

    /**
    * Block comment
    *
    * @param type
    * @return void
    */
    function getAuthorName($user_id)
    {
        return PratsRoomtypes_Helpers_Models::authorCallback($user_id);
    }

    /**
    *
    *
    **/
    public static function getImageIdFromUrl($image_url)
    {
        global $wpdb;
        $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
        return $attachment[0];
    }

    /**
    * Get Attachment By Id
    *
    * @param   integer    $attach_id    Attachment Id
    *
    * @return array Attachment Object
    */
    function getAttachmentById( $attach_id )
    {
        $attachment = get_post( $attach_id );
        return array(
            'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
            'caption' => $attachment->post_excerpt,
            'description' => $attachment->post_content,
            'href' => get_permalink( $attachment->ID ),
            'src' => $attachment->guid,
            'title' => $attachment->post_title,
            'url' => wp_get_attachment_url($attach_id),
            'image' => wp_get_attachment_image_src($attach_id),
            'thumbnail_650x430_crop' => wp_get_attachment_image_src($attach_id, 'thumbnail_650x430_crop'),
            'thumbnail_320x210_crop' => wp_get_attachment_image_src($attach_id, 'thumbnail_320x210_crop')
        );
    }

    /**
    * Block comment
    *
    * @param type
    * @return void
    */
    function getAttachmentIdBySlug( $slug )
    {
        $args = array(
            'post_type' => 'attachment',
            'name' => sanitize_title($slug),
            'posts_per_page' => 1,
            'post_status' => 'inherit',
        );
        $_header = get_posts( $args );
        $header = $_header ? array_pop($_header) : null;

        return $header ? $header->ID : 0;
    }

    /**
    * Block comment
    *
    * @param type
    * @return void
    */
    function getPDFIdBySlug( $slug )
    {
        $args = array(
            'post_type' => 'attachment',
            'title' => sanitize_title($slug),
            'posts_per_page' => 1,
            'post_status' => 'inherit',
            'post_mime_type' => 'application/pdf'
        );
        $_header = get_posts( $args );
        $header = $_header ? array_pop($_header) : null;

        return $header ? $header->ID : 0;
    }

    /**
    * Block comment
    *
    * @param type
    * @return void
    */
    function getAttachmentsFromPost($post_id)
    {
        $args = array(
            'post_type' => 'attachment',
            'numberposts' => -1,
            'post_status' => null,
            'post_parent' => $post_id
        );

        $attachments = get_posts( $args );

        if ( $attachments )
        {
            $images = array();
            foreach ( $attachments as $attachment )
            {
                $images[] = self::getAttachmentById( $attachment->ID );
            }

            return $images; // End of function and nothing happens
        }

        return array();
    }


}
