<?php 
add_action( 'wp_ajax_create_gift', 'create_gift_meeting' ); // wp_ajax_{ЗНАЧЕНИЕ ПАРАМЕТРА ACTION!!}
add_action( 'wp_ajax_nopriv_create_gift', 'create_gift_meeting' );  // wp_ajax_nopriv_{ЗНАЧЕНИЕ ACTION!!}

function create_gift_meeting(){
    $post_id = $_POST['post_id'];
    $custumer_id = $_POST['custumer_id'];

    $gift_meeting = new Gift($post_id, $custumer_id);

    //раз создали, то нужно добавить в массив сделок по времени для наших пользователей
    add_in_meta_array($gift_meeting->get_custumer_id(), 'gift_arr', $gift_meeting->get_id(), 'user');
    add_in_meta_array($gift_meeting->get_admin_id(), 'gift_arr', $gift_meeting->get_id(), 'user');

    $user =  get_user_by( 'id', $gift_meeting->get_admin_id());
    $link = 'https://' . $_SERVER['SERVER_NAME'] . '/user/' . $user->user_login . "?profiletab=gift";

    NotificationDB::add_notification($gift_meeting->get_admin_id(), 'Подарок', '<p>По вашему объявлению <a href="'
            . get_permalink($gift_meeting->get_post_id()) .'">“' 
            . get_the_title($gift_meeting->get_post_id()). '”</a> новая заявка. <a href="'
            . $link . '">Свяжитесь</a> как можно скорее!</p>');


    die();
}

add_action( 'wp_ajax_confirm_gift_meeting', 'confirm_gift_meeting' ); // wp_ajax_{ЗНАЧЕНИЕ ПАРАМЕТРА ACTION!!}
add_action( 'wp_ajax_nopriv_confirm_gift_meeting', 'confirm_gift_meeting' );  // wp_ajax_nopriv_{ЗНАЧЕНИЕ ACTION!!}

function confirm_gift_meeting(){
    $gift_id = $_POST['gift_id'];
    $cur_user_id = $_POST['cur_user_id'];

    $gift = gift::get_gift_by_id($gift_id);

    $custumer_id = $gift->get_custumer_id();
    $admin_id = $gift->get_admin_id();
    $post_id = $gift->get_post_id(); 

    $gift->confirm_meeting($cur_user_id);

    if($gift->get_confirm_first_id() != null){
        NotificationDB::add_notification($admin_id, 'Подарок', '<p>По вашему объявлению <a href="'
                . get_permalink($gift->get_post_id()) .'">“' 
                . get_the_title($gift->get_post_id()). '”</a> состоялась сделка. Поздравляем!</p>');

        NotificationDB::add_notification($custumer_id, 'Подарок', '<p>По объявлению <a href="'
                . get_permalink($gift->get_post_id()) .'">“' 
                . get_the_title($gift->get_post_id()). '”</a> состоялась сделка. Поздравляем!</p>');
    }

    die();
}

?>