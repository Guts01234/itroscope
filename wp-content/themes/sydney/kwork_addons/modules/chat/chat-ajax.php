<?php 

add_action( 'wp_ajax_send_message_chat', 'send_message_chat' ); // wp_ajax_{ЗНАЧЕНИЕ ПАРАМЕТРА ACTION!!}
add_action( 'wp_ajax_nopriv_send_message_chat', 'send_message_chat' );  // wp_ajax_nopriv_{ЗНАЧЕНИЕ ACTION!!}

function send_message_chat(){
    $post_id = $_POST['post_id'];
    $chat_id = $_POST['chat_id'];
    $message_text = $_POST['message_text'];
    $$message_text = trim($message_text);

    $cur_user_id = get_current_user_id();

    $chat = new Chat($chat_id);
        
    if(!$chat){
        echo 'false';
        die();
    }

    $user_id_1 = $chat->get_user_id_1();
    $user_id_2 = $chat->get_user_id_2();

    //если айди текущего пользователя никак не связано с объявлением, то ничего не делаем
    if($user_id_1 != $cur_user_id 
        && $user_id_2 != $cur_user_id)
        {
            echo 'false';
            die();
        }

    $res = $chat->add_message($cur_user_id, $message_text);

    if(!$res){
        echo 'false';
        die();
    }

    $not_cur_id = ($user_id_1 == $cur_user_id) ? $user_id_2 : $user_id_1;

    NotificationDB::add_notification($not_cur_id, 'Сообщение', '<p>По объявлению <a href="'
            . get_permalink($post_id) .'">“' 
            . get_the_title($post_id). '”</a> новое сообщение от '. get_user_link_name($cur_user_id) .'</p>');

    ?>
        <div class="table_message">
            <div class="table_message__avatar">
                <div class="table_message__avatar__img">
                    <?php
                        $author_avatar =  get_avatar_url($cur_user_id);
                        if($author_avatar && !strripos($author_avatar, 'default')){
                            echo "<img src='{$author_avatar}' alt='author'>";
                        }else{
                            echo "<img src='https://cdn-icons-png.flaticon.com/512/3593/3593455.png' alt='placeholder'>";
                        } 
                    ?>
                </div>
                <p>
                    <?php echo get_user_link_name($cur_user_id)?>
                    <?php echo '(Вы)';?>
                </p>
            </div>
            <div class="table_message__text">
                <p>
                    <?php echo $message_text;?>
                </p>
            </div>
        </div>
    <?php
    
    die();
}

?>