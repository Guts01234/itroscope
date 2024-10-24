<?php 
//отправил приглашение стать наставником
add_action( 'wp_ajax_sent_invation_to_teacher', 'sent_invation_to_teacher' ); 
add_action( 'wp_ajax_nopriv_sent_invation_to_teacher', 'sent_invation_to_teacher' );


function sent_invation_to_teacher(){
    $current_user_id = $_POST['current_user_id'];
    $invaiton_user_id = $_POST['invaiton_user_id'];
    $post_id = $_POST['post_id'];


    if(!$current_user_id || !$invaiton_user_id){
        die();
    }

    if($current_user_id == $invaiton_user_id){
        echo "Вы не можете пригласить сами себя!";
        die();
    }
    
    $post_teachers_arr = get_post_meta($post_id, 'post_teachers_arr', true);
    
    if(!$post_teachers_arr){
        $post_teachers_arr = array();
    }

    $post_teachers_arr_waiting = get_post_meta($post_id, 'post_teachers_arr_waiting', true);
    
    if(!$post_teachers_arr_waiting){
        $post_teachers_arr_waiting = array();
    }
    
    if(in_array($invaiton_user_id, $post_teachers_arr)){
        echo "Пользователь уже является наставником для этого урока!";
        die();
    }
    if(in_array($invaiton_user_id, $post_teachers_arr_waiting)){
        echo "Вы уже пригласили этого пользователя к этому уроку!";
        die();
    }

    $notification_title =  'Вас пригласили стать наставником';

    $notification = NotificationDB::add_notification($invaiton_user_id, 
    $notification_title,
     "");


    $notification_content = '<p>Вас пригласили стать наставником к уроку <a href="'. get_permalink($post_id) . '">
    “' . get_the_title($post_id) .'”</a>. Стать наставником? !</p>'.
                            '<div class="invation_to_teacher_buttons" data-notification-id="'. $notification->get_id() .'"
                            data-user-id="' . $invaiton_user_id . '" data-post-id="'. $post_id .'" 
                            data-author-id="' . $current_user_id . '">' .
                            '<button class="invation_teacher_accept">Принять</button>'.
                            '<button class="invation_teacher_cancel">Отказать</button>'.
                            '</div>';


    NotificationDB::change_content_notification($notification->get_id(), $notification_content);

    echo "Приглашение отправлено!";

    array_push($post_teachers_arr_waiting, $invaiton_user_id);

    update_post_meta($post_id, 'post_teachers_arr_waiting', $post_teachers_arr_waiting);

    die();
}

//наставник отказался
add_action( 'wp_ajax_sent_invation_to_teacher_сancel', 'sent_invation_to_teacher_сancel' ); 
add_action( 'wp_ajax_nopriv_sent_invation_to_teacher_cancel', 'sent_invation_to_teacher_сancel' );


function sent_invation_to_teacher_сancel(){
    $author_id = $_POST['author_id'];
    $notification_id = $_POST['notification_id'];
    $invaiton_user_id = $_POST['invaiton_user_id'];
    $post_id = $_POST['post_id'];
    
    
    if(!$author_id || !$invaiton_user_id
    || !$notification_id || !$post_id){
           print_r($_POST);
        die();
    }
    

    $post_teachers_arr_waiting = get_post_meta($post_id, 'post_teachers_arr_waiting', true);
    
    if(!$post_teachers_arr_waiting){
        $post_teachers_arr_waiting = array();
    }
    
    if(in_array($invaiton_user_id, $post_teachers_arr_waiting)){
        unset($post_teachers_arr_waiting[array_search( $invaiton_user_id,$post_teachers_arr_waiting)]);
    }

    $notification_title =  'Пользователь отказался быть наставником';

    $notification_content = '<p>'. get_user_link_name($invaiton_user_id) .' отказался стать наставником к уроку <a href="'. get_permalink($post_id) . '">
    “' . get_the_title($post_id) .'”</a></p>';

    $notification = NotificationDB::add_notification($author_id, 
    $notification_title,
    $notification_content);


    NotificationDB::change_content_notification($notification_id, '<p>Вы отказались стать наставником к уроку <a href="'. get_permalink($post_id) . '">
    “' . get_the_title($post_id) .'”</a></p>');



    update_post_meta($post_id, 'post_teachers_arr_waiting', $post_teachers_arr_waiting);

    die();
}


//наставник согласился
add_action( 'wp_ajax_sent_invation_to_teacher_сonfirm', 'sent_invation_to_teacher_сonfirm' ); 
add_action( 'wp_ajax_nopriv_sent_invation_to_teacher_сonfirm', 'sent_invation_to_teacher_сonfirm' );


function sent_invation_to_teacher_сonfirm(){
    $author_id = $_POST['author_id'];
    $notification_id = $_POST['notification_id'];
    $invaiton_user_id = $_POST['invaiton_user_id'];
    $post_id = $_POST['post_id'];


    if(!$author_id || !$invaiton_user_id
       || !$notification_id || !$post_id){
        die();
    }
    

    $post_teachers_arr_waiting = get_post_meta($post_id, 'post_teachers_arr_waiting', true);

    //массив наставников прикреплённых к этому уроку
    $post_teachers_arr = get_post_meta($post_id, 'post_teachers_arr', true);

    //массив айди уроков, в которых он обучает
    $user_posts_teach_arr = get_user_meta($invaiton_user_id, 'user_posts_teach_arr', true);
    
    if(!$post_teachers_arr_waiting){
        $post_teachers_arr_waiting = array();
    }
    if(!$post_teachers_arr){
        $post_teachers_arr = array();
    }
    if(!$user_posts_teach_arr){
        $user_posts_teach_arr = array();
    }
    
    if(in_array($invaiton_user_id, $post_teachers_arr_waiting)){
        unset($post_teachers_arr_waiting[array_search( $invaiton_user_id,$post_teachers_arr_waiting)]);
    }

    if(!in_array($invaiton_user_id, $post_teachers_arr)){
        array_push($post_teachers_arr, $invaiton_user_id);
    }
    if(!in_array($post_id, $user_posts_teach_arr)){
        array_push($user_posts_teach_arr, $post_id);
    }

    $notification_title =  'Пользователь согласилс быть наставником';

    $notification_content = '<p>'. get_user_link_name($invaiton_user_id) .' согласился стать наставником к уроку <a href="'. get_permalink($post_id) . '">
    “' . get_the_title($post_id) .'”</a></p>';

    $notification = NotificationDB::add_notification($author_id, 
    $notification_title,
    $notification_content);


    NotificationDB::change_content_notification($notification_id, '<p>Вы согласились стать наставником к уроку <a href="'. get_permalink($post_id) . '">
    “' . get_the_title($post_id) .'”</a></p>');



    update_post_meta($post_id, 'post_teachers_arr_waiting', $post_teachers_arr_waiting);
    update_post_meta($post_id, 'post_teachers_arr', $post_teachers_arr);
    update_user_meta($invaiton_user_id, 'user_posts_teach_arr', $user_posts_teach_arr);

    die();
}

//наставник принял задание
add_action( 'wp_ajax_nastavnik_add_lesson__confirm', 'nastavnik_add_lesson__confirm' ); 
add_action( 'wp_ajax_nopriv_nastavnik_add_lesson__confirm', 'nastavnik_add_lesson__confirm' );

function nastavnik_add_lesson__confirm(){
    
    $user_id = $_POST['user_id'];
    $hw_id = $_POST['homework_id'];

    if(!$hw_id || !$user_id){
        die();
    }

    global $wpdb;

    $homework = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}homeworks WHERE id = {$hw_id} ", OBJECT )[0];

    $nastavnik_id = $homework->nastavnik_id;

    if($nastavnik_id){
        echo "Задание уже принял кто-то другой";
        die();
    }


    $post_id = $homework->post_id;
    
    

    $notification_arr = get_post_meta($post_id, 'notification_homework_'.$hw_id, true);


    if(!$notification_arr){
        $notification_arr = array();
    }
    
    $cur_id;
    
    foreach ($notification_arr as $key => $notification_id) {
        $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}notification WHERE id = {$notification_id}", OBJECT )[0];
        
        //уведомление тому, кто принял приглашение
        if($results->user_id == $user_id){
            NotificationDB::change_content_notification($results->id, '<p>Вы приняли задание к уроку <a href="'. get_permalink($post_id) . '">
            “' . get_the_title($post_id) .'”</a></p>');
        }else{
            NotificationDB::change_content_notification($results->id, '<p>Другой наставник принял задание к уроку <a href="'. get_permalink($post_id) . '">
            “' . get_the_title($post_id) .'”</a></p>');
        }
    }

    $wpdb->update($wpdb->prefix . "homeworks",
        ['nastavnik_id' => $user_id],
        ['id' => $hw_id]
    );

    echo 'Вы приняли задание';

    die();

}


//наставник отказался от задания
add_action( 'wp_ajax_nastavnik_add_lesson__cansel', 'nastavnik_add_lesson__cansel' ); 
add_action( 'wp_ajax_nopriv_nastavnik_add_lesson__cansel', 'nastavnik_add_lesson__cansel' );

function nastavnik_add_lesson__cansel(){

    $user_id = $_POST['user_id'];
    $hw_id = $_POST['homework_id'];

    if(!$hw_id || !$user_id){
        die();
    }

    global $wpdb;

    $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}homeworks WHERE id = {$hw_id} ", OBJECT )[0];

    $post_id = $results->post_id;
    

    $notification_arr = get_post_meta($post_id, 'notification_homework_'.$hw_id, true);


    if(!$notification_arr){
        $notification_arr = array();
    }
    
    $cur_id;

    foreach ($notification_arr as $key => $notification_id) {
        $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}notification WHERE id = {$notification_id} AND user_id = {$user_id}", OBJECT )[0];
        
        if($results->id){
            $cur_id = $results->id;
        }
    }


    $n = NotificationDB::change_content_notification($cur_id, '<p>Вы отказались от задания к уроку <a href="'. get_permalink($post_id) . '">
    “' . get_the_title($post_id) .'”</a></p>');

    if($n){
        echo 'Вы отказались';
    }else{
        echo "Произошла ошибка";
    }


    die();

}

?>