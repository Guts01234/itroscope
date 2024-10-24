<?php 

class GiftDb{

    public static function add_gift_meeting($post_id, $custumer_id, $chat_id){
        $user = get_user_by( "id", $custumer_id );
        if(!$user){
            return 'user not found';
        }

        $my_post = get_post( $post_id );
        $author_id = $my_post->post_author;

        global $wpdb;

        $wpdb->insert( $wpdb->prefix.'gift', 
                    [ 'post_id' => $post_id, 'admin_id' => $author_id, 'custumer_id' => $custumer_id, 'status_gift' => 'start', 'chat_id' => $chat_id]);

        $gift_meeting_id = $wpdb->insert_id;


        return $gift_meeting_id;
        
    }

    public static function change_gift_status_by_id($id, $status){
        global $wpdb;
        $res = $wpdb->update( $wpdb->prefix . 'gift',
                    [ 'status_gift' => $status],
                    [ 'id' => $id ]
                    );
        return $res;
    }

    public static function change_gift_status_all($admin_id, $post_id, $status){
        global $wpdb;
        $res = $wpdb->update( $wpdb->prefix . 'gift',
                    [ 'status_gift' => $status],
                    [ 
                        'admin_id' => $admin_id,
                        'post_id' => $post_id,
                        'status_gift' => 'start'
                    ]
                    );
        return $res;
    }

    public static function get_gift_meeting_by_id($id){
        global $wpdb;

        $gift_meeting = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}gift WHERE id = $id" );

        return $gift_meeting;
    }

    public static function get_gift_meeting_messages_by_id($id){
        global $wpdb;

        $gift_meeting = $wpdb->get_row( "SELECT messages FROM {$wpdb->prefix}gift WHERE id = $id" );

        return $gift_meeting;
    }

    public static function get_gift_meeting($post_id, $custumer_id){
        global $wpdb;

        $gift_meeting = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}gift WHERE post_id = $post_id AND custumer_id = $custumer_id" );

        return $gift_meeting;

    }

    public static function get_all_gift_meeting_custumer($user_id){
        global $wpdb;

        $gift_meeting = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}gift WHERE custumer_id = $user_id AND status_gift != 'start'" );

        return $gift_meeting;
    }

    public static function get_all_gift_meeting_admin($user_id){
        global $wpdb;

        $gift_meeting = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}gift WHERE admin_id = $user_id AND status_gift != 'start'" );

        return $gift_meeting;
    }

    public static function get_all_gift_meeting_user($user_id){
        global $wpdb;

        $gift_meeting = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}gift WHERE (admin_id = $user_id OR custumer_id = $user_id) AND status_gift != 'start'" );

        return $gift_meeting;
    }

    public static function get_all_gift_meeting_user_not_archive($user_id){
        global $wpdb;

        $gift_meeting = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}gift WHERE (admin_id = $user_id OR custumer_id = $user_id) AND status_gift != 'archive'" );

        return $gift_meeting;
    }

    public static function get_all_gift_meeting_user_finished($user_id){
        global $wpdb;

        $gift_meeting = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}gift WHERE (admin_id = $user_id OR custumer_id = $user_id) AND status_gift = 'finished'" );

        return $gift_meeting;
    }

    public static function set_confirm_first_id($id, $user_id){
        global $wpdb;

        $res = $wpdb->update( $wpdb->prefix . 'gift',
                        [ 'confirm_first_id' => $user_id],
                        [ 'id' => $id ]
                    );

        return $res;
    }
}

?>