<?php 


add_action( 'wp_ajax_company_confirm_user', 'company_confirm_user' );
add_action( 'wp_ajax_nopriv_company_confirm_user', 'company_confirm_user' );


function company_confirm_user(){

    if(!isset($_POST['user_id']) && !isset($_POST['company_id'])) die;
    
    $user_id = $_POST['user_id'];
    $company_id = $_POST['company_id'];
    $notification_id = $_POST['notification_id'];

    //переменная отвечающая за то, кто сгенерировал это событие, чтобы показывать разные уведомления
    $responsible= $_POST['responsible'];

    $company = new Company($company_id);

    if(!$company){
        die();
    }

    $company->add_user_in_company($user_id);

    if($responsible == 'user'){


        NotificationDB::change_content_notification($notification_id, "<p>Вы приняли приглашение от " . get_user_link_name($company_id) . "</p>");

        $notification = NotificationDB::add_notification($company_id, 'Пользователь принял приглашени!', 
        "<p>" . get_user_link_name($user_id) . " теперь находится в Вашей компании</p>");

    }else{
        $user = get_user_by( 'id', $user_id );
    
        NotificationDB::add_notification($user_id, 'Запрос Подтверждён!', "<p>Компания ". 
        get_user_link_name($company_id) ." приняла Ваш запрос на вступление</p>");

    }





    die();

}


?>