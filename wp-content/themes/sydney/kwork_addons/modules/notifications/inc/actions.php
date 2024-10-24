<?php 

add_action('transition_post_status', 'send_notification_after_save_post', 10, 3 );

function send_notification_after_save_post($new_status, $old_status, $post){
    if(
        'publish' === $new_status && 
        'publish' !== $old_status && 
        'post' === $post->post_type
      ) {
        $author_id = $post->post_author;
        $post_title = $post->post_title;
        $post_id = $post->ID;
    
        NotificationDB::add_notification($author_id, 'Ваши уроки', '<p>Ваш урок <a href="'
                                                                    . get_permalink($post_id) .'">“' 
                                                                    . $post_title. '”</a> опубликован на платформе</p>');
      }

    if(
        'publish' === $new_status && 
        'publish' !== $old_status && 
        ('time' === $post->post_type
          || 'gift' === $post->post_type 
        )
      ) {
        $author_id = $post->post_author;
        $post_title = $post->post_title;
        $post_id = $post->ID;
    
        NotificationDB::add_notification($author_id, 'Объявление', '<p>Ваше объявление <a href="'
                                                                    . get_permalink($post_id) .'">“' 
                                                                    . $post_title. '”</a> опубликовано на платформе</p>');
      }
}

?>