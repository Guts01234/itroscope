<?php 
add_action( 'wp_ajax_create_time', 'create_time_meeting' ); // wp_ajax_{ЗНАЧЕНИЕ ПАРАМЕТРА ACTION!!}
add_action( 'wp_ajax_nopriv_create_time', 'create_time_meeting' );  // wp_ajax_nopriv_{ЗНАЧЕНИЕ ACTION!!}

function create_time_meeting(){
    $post_id = $_POST['post_id'];
    $custumer_id = $_POST['custumer_id'];

    $time_meeting = new Time($post_id, $custumer_id);

    //раз создали, то нужно добавить в массив сделок по времени для наших пользователей
    add_in_meta_array($time_meeting->get_custumer_id(), 'time_arr', $time_meeting->get_id(), 'user');
    add_in_meta_array($time_meeting->get_admin_id(), 'time_arr', $time_meeting->get_id(), 'user');

    $user =  get_user_by( 'id', $time_meeting->get_admin_id());
    $link = 'https://' . $_SERVER['SERVER_NAME'] . '/user/' . $user->user_login . "?profiletab=time";

    NotificationDB::add_notification($time_meeting->get_admin_id(), 'Время', '<p>По вашему объявлению <a href="'
            . get_permalink($time_meeting->get_post_id()) .'">“' 
            . get_the_title($time_meeting->get_post_id()). '”</a> новая заявка. <a href="'
            . $link . '">Свяжитесь</a> как можно скорее!</p>');


    die();
}

add_action( 'wp_ajax_confirm_time_meeting', 'confirm_time_meeting' ); // wp_ajax_{ЗНАЧЕНИЕ ПАРАМЕТРА ACTION!!}
add_action( 'wp_ajax_nopriv_confirm_time_meeting', 'confirm_time_meeting' );  // wp_ajax_nopriv_{ЗНАЧЕНИЕ ACTION!!}

function confirm_time_meeting(){
    $time_id = $_POST['time_id'];
    $cur_user_id = $_POST['cur_user_id'];

    $time = Time::get_time_by_id($time_id);

    $custumer_id = $time->get_custumer_id();
    $admin_id = $time->get_admin_id();
    $post_id = $time->get_post_id(); 

    $time->confirm_meeting($cur_user_id);

    if($time->get_confirm_first_id() != null){
            NotificationDB::add_notification($admin_id, 'Время', '<p>По вашему объявлению <a href="'
                    . get_permalink($time->get_post_id()) .'">“' 
                    . get_the_title($time->get_post_id()). '”</a> состоялась сделка. Поздравляем!</p>');
        
            NotificationDB::add_notification($custumer_id, 'Время', '<p>По объявлению <a href="'
                    . get_permalink($time->get_post_id()) .'">“' 
                    . get_the_title($time->get_post_id()). '”</a> состоялась сделка. Поздравляем!</p>');
    }



    die();
}

?>