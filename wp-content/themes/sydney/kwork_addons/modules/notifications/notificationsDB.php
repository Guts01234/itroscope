<?php 
class NotificationDB
{
    /*
    * create new notificaiton in DB and return this
    */
 
    public static function add_notification($user_id, $title, $content){
        global $wpdb;
        $user = get_user_by( "id", $user_id );
        if(!$user){
            return 'user not found';
        }

        $time = time();

        $wpdb->insert( $wpdb->prefix.'notification', 
                    [ 'user_id' => $user_id, 'readed_status' => false, 'title' => $title, 'content' => $content, 'date' => $time ], 
                    [ '%d', '%s', '%s', '%s', '%d' ] );
        
        $notigication_id = $wpdb->insert_id;
        
        $notigication_arr = get_user_meta($user_id, 'notifications', true);
        
        if(!$notigication_arr){
            $notigication_arr  = array();
        }

        array_push($notigication_arr, $notigication_id);
        update_user_meta($user_id, 'notifications', $notigication_arr);
        
        
        return new Notification($notigication_id, $user_id, 0, $title, $content, $time);
    }

    /*
    * change contenet for notification. Content - htmlchange status for notification. Status - readed(1) or not(0)
    */

    public static function change_content_notification($notigication_id, $content){
        global $wpdb;
        $res = $wpdb->update( $wpdb->prefix . 'notification',
                    [ 'content' => $content],
                    [ 'id' => $notigication_id ],
                    ['%s'],
                    ['%d']
                    );
        return $res;
    }

    /*
    * change status for notification. Status - readed(1) or not(0)
    */

    public static function change_status_notification($notigication_id, $status){
        global $wpdb;
        $res = $wpdb->update( $wpdb->prefix . 'notification',
                    [ 'readed_status' => $status],
                    [ 'id' => $notigication_id ],
                    ['%d'],
                    ['%d']
                    );
        return $res;
    }

    /*
    * return notification obj
    */

    public static function get_notification_by_id($notigication_id){
        global $wpdb;

        $notification = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}notification WHERE id = $notigication_id" );
        
        return new Notification($notification->id, $notification->user_id, $notification->readed_status, $notification->title, $notification->content, $notification->date);
    }

    /*
    * return array of notification obj
    */

    public static function get_user_notification($user_id){
        $notigication_arr = get_user_meta($user_id, 'notifications', true);
        
        if(!$notigication_arr){
            $notigication_arr  = array();
            return null;
        }

        $notigication_arr = array_reverse($notigication_arr);
        $ans_arr = array();

        for ($i=0; $i < count($notigication_arr); $i++) { 
            array_push($ans_arr, NotificationDB::get_notification_by_id($notigication_arr[$i]));
        }

        return $ans_arr;
    }

}
?>