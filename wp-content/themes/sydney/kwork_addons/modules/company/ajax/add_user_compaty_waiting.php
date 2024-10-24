<?php 


add_action( 'wp_ajax_add_user_in_company_waiting', 'add_user_in_company_waiting' ); // wp_ajax_{ЗНАЧЕНИЕ ПАРАМЕТРА ACTION!!}
add_action( 'wp_ajax_nopriv_add_user_in_company_waiting', 'add_user_in_company_waiting' );  // wp_ajax_nopriv_{ЗНАЧЕНИЕ ACTION!!}


function add_user_in_company_waiting(){

    if(!isset($_POST['user_id']) && !isset($_POST['company_id'])) die;
    
    $user_id = $_POST['user_id'];
    $company_id = $_POST['company_id'];
    
    $company = new Company($company_id);

    if(!$company){
        return false;
    }

    $company->add_user_in_waiting($user_id);

    $user = get_user_by( 'id', $company_id );
    $link = 'https://' . $_SERVER['SERVER_NAME'] . '/user/' . $user->user_login . "?profiletab=company_users";

    NotificationDB::add_notification($company_id, 'Новая заявка на вступление!', "<p>". 
    get_user_link_name($user_id) ." хочет стать частью вашей команды! Посетите страницу <a href='".
    $link . "'>Управления персоналом</a> </p>");

    return true;

    die();

}


?>