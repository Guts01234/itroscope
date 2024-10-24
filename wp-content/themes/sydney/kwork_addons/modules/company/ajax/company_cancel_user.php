<?php 


add_action( 'wp_ajax_company_cancel_user', 'company_cancel_user' ); // wp_ajax_{ЗНАЧЕНИЕ ПАРАМЕТРА ACTION!!}
add_action( 'wp_ajax_nopriv_company_cancel_user', 'company_cancel_user' );  // wp_ajax_nopriv_{ЗНАЧЕНИЕ ACTION!!}


function company_cancel_user(){

    if(!isset($_POST['user_id']) && !isset($_POST['company_id'])) die;
    
    $user_id = $_POST['user_id'];
    $company_id = $_POST['company_id'];

    $company = new Company($company_id);

    if(!$company){
        return false;
    }

    $company->delete_user_from_waiting($user_id);

    NotificationDB::add_notification($user_id, 'Запрос отклонён!', "<p>Компания ". 
    get_user_link_name($company_id) ." отказала Вам во вступлении</p>");

    return true;

    die();

}


?>