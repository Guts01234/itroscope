<?php 


add_action( 'wp_ajax_company_send_user_invation', 'company_send_user_invation' ); 
add_action( 'wp_ajax_nopriv_company_send_user_invation', 'company_send_user_invation' );  


function company_send_user_invation(){

    if(!isset($_POST['user_id']) && !isset($_POST['company_id'])) die;
    
    $user_id = $_POST['user_id'];
    $company_id = $_POST['company_id'];

    $company = new Company($company_id);

    if(!$company){
        return false;
    }

    
    if($company->check_user_in_invations($user_id)){
        echo 'Пользовтель уже приглашён';
        die();
    }elseif ($company->check_user_in_waiting($user_id)) {
        echo "Пользователь уже отправил Вам заявку";
        die();
    }elseif($company->check_user_in_members($user_id)){
        echo "Пользовтель уже состоит в Вашей компании";
        die();
    }

    $company->add_user_invation($user_id);

    echo "<span class='success_ajax'>Приглашение отправлено</span>";

    $notification = NotificationDB::add_notification($user_id, 'Вас пригласили в компанию!', "");


    $content = "<div><p>Вас пригласили в команду ". 
    get_user_link_name($company_id) ."</p>
        <div class='message_buttons' data-notification-id=" . $notification->get_id(). 
        " data-user-id=" . $user_id . " data-company-id=" . $company_id . ">
            <button class='user_confirm_invation'>Принять</button>
            <button class='user_cansel_invation'>Отказаться</button>
        </div>
    </div>";

    NotificationDB::change_content_notification($notification->get_id(), $content);

    die();

}


?>