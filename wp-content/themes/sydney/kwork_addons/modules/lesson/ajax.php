<?php 
add_action( 'wp_ajax_create_lesson', 'create_lesson_meeting' ); // wp_ajax_{ЗНАЧЕНИЕ ПАРАМЕТРА ACTION!!}
add_action( 'wp_ajax_nopriv_create_lesson', 'create_lesson_meeting' );  // wp_ajax_nopriv_{ЗНАЧЕНИЕ ACTION!!}

function create_lesson_meeting(){
    $post_id = $_POST['post_id'];
    $custumer_id = $_POST['custumer_id'];

    $lesson_meeting = new lesson($post_id, $custumer_id);

    //раз создали, то нужно добавить в массив сделок по времени для наших пользователей
    add_in_meta_array($lesson_meeting->get_custumer_id(), 'lesson_arr', $lesson_meeting->get_id(), 'user');
    add_in_meta_array($lesson_meeting->get_admin_id(), 'lesson_arr', $lesson_meeting->get_id(), 'user');

    $user =  get_user_by( 'id', $lesson_meeting->get_admin_id());
    $link = 'https://' . $_SERVER['SERVER_NAME'] . '/user/' . $user->user_login . "?profiletab=posts";

    NotificationDB::add_notification($lesson_meeting->get_admin_id(), 'Обучение', '<p>По вашему объявлению <a href="'
            . get_permalink($lesson_meeting->get_post_id()) .'">“' 
            . get_the_title($lesson_meeting->get_post_id()). '”</a> новая заявка. <a href="'
            . $link . '">Свяжитесь</a> как можно скорее!</p>');


    die();
}

add_action( 'wp_ajax_confirm_lesson_meeting', 'confirm_lesson_meeting' ); // wp_ajax_{ЗНАЧЕНИЕ ПАРАМЕТРА ACTION!!}
add_action( 'wp_ajax_nopriv_confirm_lesson_meeting', 'confirm_lesson_meeting' );  // wp_ajax_nopriv_{ЗНАЧЕНИЕ ACTION!!}

function confirm_lesson_meeting(){
    $lesson_id = $_POST['lesson_id'];
    $cur_user_id = $_POST['cur_user_id'];

    $lesson = Lesson::get_lesson_by_id($lesson_id);

    $custumer_id = $lesson->get_custumer_id();
    $admin_id = $lesson->get_admin_id();
    $post_id = $lesson->get_post_id(); 

    $lesson->confirm_meeting($cur_user_id);

    if($lesson->get_confirm_first_id() != null){
        NotificationDB::add_notification($admin_id, 'Обучение', '<p>По вашему объявлению <a href="'
                . get_permalink($lesson->get_post_id()) .'">“' 
                . get_the_title($lesson->get_post_id()). '”</a> произошло обучение. Поздравляем!</p>');

        NotificationDB::add_notification($custumer_id, 'Обучение', '<p>Вы обучили автора этого объявления <a href="'
                . get_permalink($lesson->get_post_id()) .'">“' 
                . get_the_title($lesson->get_post_id()). '”</a>. Поздравляем!</p>');
    }

    die();
}

?>