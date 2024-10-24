<?php 

class TimeDb{

    public static function add_time_meeting($post_id, $custumer_id, $chat_id){
        $user = get_user_by( "id", $custumer_id );
        if(!$user){
            return 'user not found';
        }

        $my_post = get_post( $post_id );
        $author_id = $my_post->post_author;

        global $wpdb;

        $wpdb->insert( $wpdb->prefix.'time', 
                    [ 'post_id' => $post_id, 'admin_id' => $author_id, 'custumer_id' => $custumer_id, 'status_time' => 'start', 'chat_id' => $chat_id]);

        $time_meeting_id = $wpdb->insert_id;


        return $time_meeting_id;
        
    }

    public static function change_time_status_by_id($id, $status){
        global $wpdb;
        $res = $wpdb->update( $wpdb->prefix . 'time',
                    [ 'status_time' => $status],
                    [ 'id' => $id ]
                    );
        return $res;
    }

    public static function change_time_status_all($admin_id, $post_id, $status){
        global $wpdb;
        $res = $wpdb->update( $wpdb->prefix . 'time',
                    [ 'status_time' => $status],
                    [ 
                        'admin_id' => $admin_id,
                        'post_id' => $post_id,
                        'status_time' => 'start'
                    ]
                    );
        return $res;
    }

    public static function get_time_meeting_by_id($id){
        global $wpdb;

        $time_meeting = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}time WHERE id = $id" );

        return $time_meeting;
    }

    public static function get_time_meeting_messages_by_id($id){
        global $wpdb;

        $time_meeting = $wpdb->get_row( "SELECT messages FROM {$wpdb->prefix}time WHERE id = $id" );

        return $time_meeting;
    }

    public static function get_time_meeting($post_id, $custumer_id){
        global $wpdb;

        $time_meeting = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}time WHERE post_id = $post_id AND custumer_id = $custumer_id" );

        return $time_meeting;

    }

    public static function get_all_time_meeting_custumer($user_id){
        global $wpdb;

        $time_meeting = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}time WHERE custumer_id = $user_id AND status_time != 'start'" );

        return $time_meeting;
    }

    public static function get_all_time_meeting_admin($user_id){
        global $wpdb;

        $time_meeting = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}time WHERE admin_id = $user_id AND status_time != 'start'" );

        return $time_meeting;
    }
    
    public static function get_all_time_meeting_user($user_id){
        global $wpdb;

        $time_meeting = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}time WHERE (admin_id = $user_id OR custumer_id = $user_id) AND status_time != 'start'" );

        return $time_meeting;
    }

    public static function get_all_time_meeting_user_not_archive($user_id){
        global $wpdb;

        $time_meeting = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}time WHERE (admin_id = $user_id OR custumer_id = $user_id) AND status_time != 'archive'" );

        return $time_meeting;
    }

    public static function get_all_time_meeting_user_finished($user_id){
        global $wpdb;

        $time_meeting = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}time WHERE (admin_id = $user_id OR custumer_id = $user_id) AND status_time = 'finished'" );

        return $time_meeting;
    }

    public static function set_confirm_first_id($id, $user_id){
        global $wpdb;

        $res = $wpdb->update( $wpdb->prefix . 'time',
                        [ 'confirm_first_id' => $user_id],
                        [ 'id' => $id ]
                    );

        return $res;
    }


}

?>