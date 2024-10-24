<?php 

class ChatDb{

    public static function add_chat($user_id_1, $user_id_2){
        $user_1 = get_user_by( "id", $user_id_1 );
        $user_2 = get_user_by( "id", $user_id_2 );

        if(!$user_1 || !$user_2){
            return 'user not found';
        }

        global $wpdb;

        $wpdb->insert( $wpdb->prefix.'chat', 
                    [ 'user_id_1' => $user_id_1, 'user_id_2' => $user_id_2]);

        $chat_id = $wpdb->insert_id;


        return $chat_id;
        
    }

    public static function update_messages($id, $messages){
        global $wpdb;
        $res = $wpdb->update( $wpdb->prefix . 'chat',
                    [ 'messages' => $messages],
                    [ 'id' => $id ]
                    );
        return $res;
    }

    public static function get_chat_by_id($id){
        global $wpdb;

        $chat = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}chat WHERE id = $id" );

        return $chat;
    }

}

?>