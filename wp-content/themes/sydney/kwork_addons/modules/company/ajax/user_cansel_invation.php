<?php 


add_action( 'wp_ajax_user_cansel_invation', 'user_cansel_invation' ); 
add_action( 'wp_ajax_nopriv_user_cansel_invation', 'user_cansel_invation' );  


function user_cansel_invation(){

    if(!isset($_POST['user_id']) && !isset($_POST['company_id'])) die;
    
    $user_id = $_POST['user_id'];
    $company_id = $_POST['company_id'];
    $notification_id = $_POST['notification_id'];

    $company = new Company($company_id);

    if(!$company){
        return false;
    }

    $company->delete_user_from_invations($user_id);

    NotificationDB::change_content_notification($notification_id, "<p>Вы отказались от приглашения " . get_user_link_name($company_id) . "</p>");

    $notification = NotificationDB::add_notification($company_id, 'Пользователь отказался!', 
    "<p>" . get_user_link_name($user_id) . " отклонил Ваше предложение вступить в компанию</p>");

    die();

}


?>