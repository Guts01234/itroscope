<?php 

class LessonDb{

    public static function add_lesson_meeting($post_id, $custumer_id, $chat_id){
        $user = get_user_by( "id", $custumer_id );
        if(!$user){
            return 'user not found';
        }

        $my_post = get_post( $post_id );
        $author_id = $my_post->post_author;

        global $wpdb;

        $wpdb->insert( $wpdb->prefix.'lesson', 
                    [ 'post_id' => $post_id, 'admin_id' => $author_id, 'custumer_id' => $custumer_id, 'status_lesson' => 'start', 'chat_id' => $chat_id]);

        $lesson_meeting_id = $wpdb->insert_id;


        return $lesson_meeting_id;
        
    }

    public static function change_lesson_status_by_id($id, $status){
        global $wpdb;
        $res = $wpdb->update( $wpdb->prefix . 'lesson',
                    [ 'status_lesson' => $status],
                    [ 'id' => $id ]
                    );
        return $res;
    }

    public static function change_lesson_status_all($admin_id, $post_id, $status){
        global $wpdb;
        $res = $wpdb->update( $wpdb->prefix . 'lesson',
                    [ 'status_lesson' => $status],
                    [ 
                        'admin_id' => $admin_id,
                        'post_id' => $post_id,
                        'status_lesson' => 'start'
                    ]
                    );
        return $res;
    }

    public static function get_lesson_meeting_by_id($id){
        global $wpdb;

        $lesson_meeting = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}lesson WHERE id = $id" );

        return $lesson_meeting;
    }

    public static function get_lesson_meeting_messages_by_id($id){
        global $wpdb;

        $lesson_meeting = $wpdb->get_row( "SELECT messages FROM {$wpdb->prefix}lesson WHERE id = $id" );

        return $lesson_meeting;
    }

    public static function get_lesson_meeting($post_id, $custumer_id){
        global $wpdb;

        $lesson_meeting = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}lesson WHERE post_id = $post_id AND custumer_id = $custumer_id" );

        return $lesson_meeting;

    }

    public static function get_all_lesson_meeting_custumer($user_id){
        global $wpdb;

        $lesson_meeting = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}lesson WHERE custumer_id = $user_id AND status_lesson != 'start'" );

        return $lesson_meeting;
    }

    public static function get_all_lesson_meeting_admin($user_id){
        global $wpdb;

        $lesson_meeting = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}lesson WHERE admin_id = $user_id AND status_lesson != 'start'" );

        return $lesson_meeting;
    }

    public static function get_all_lesson_meeting_custumer_finished($user_id){
        global $wpdb;

        $lesson_meeting = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}lesson WHERE custumer_id = $user_id AND status_lesson = 'finished'" );

        return $lesson_meeting;
    }

    public static function get_all_lesson_meeting_user($user_id){
        global $wpdb;

        $lesson_meeting = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}lesson WHERE (admin_id = $user_id OR custumer_id = $user_id) AND status_lesson != 'start'" );

        return $lesson_meeting;
    }

    public static function get_all_lesson_meeting_user_not_archive($user_id){
        global $wpdb;

        $lesson_meeting = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}lesson WHERE (admin_id = $user_id OR custumer_id = $user_id) AND status_lesson != 'archive'" );

        return $lesson_meeting;
    }

    public static function get_all_lesson_meeting_user_finished($user_id){
        global $wpdb;

        $lesson_meeting = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}lesson WHERE (admin_id = $user_id OR custumer_id = $user_id) AND status_lesson = 'finished'" );

        return $lesson_meeting;
    }

    public static function get_lesson_meeting_admin($user_id, $post_id, $status){
        global $wpdb;

        $lesson_meeting = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}lesson WHERE admin_id = $user_id AND status_lesson = '$status' AND post_id = $post_id" );

        return $lesson_meeting;
    }

    public static function set_confirm_first_id($id, $user_id){
        global $wpdb;

        $res = $wpdb->update( $wpdb->prefix . 'lesson',
                        [ 'confirm_first_id' => $user_id],
                        [ 'id' => $id ]
                    );

        return $res;
    }
}

?>