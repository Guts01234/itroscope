<?php 


add_action( 'wp_ajax_delete_user_from_company', 'delete_user_from_company' );
add_action( 'wp_ajax_nopriv_delete_user_from_company', 'delete_user_from_company' );


function delete_user_from_company(){

    if(!isset($_POST['user_id']) && !isset($_POST['company_id'])) die;
    
    $user_id = $_POST['user_id'];
    $company_id = $_POST['company_id'];
    //переменная отвечающая за то, кто сгенерировал это событие, чтобы показывать разные уведомления
    $responsible= $_POST['responsible'];

    $company = new Company($company_id);

    if(!$company){
        return false;
    }

    $company->delete_user($user_id);


    if($responsible == 'user'){
        NotificationDB::add_notification($company_id, 'Человек покинул компанию!', "<p>". 
        get_user_link_name($user_id) ." покинул компанию </p>");
    }elseif($responsible == 'company'){
        NotificationDB::add_notification($user_id, 'Вас исключили!', "<p>". 
        get_user_link_name($company_id) ." исключила Вас из компании </p>");
    }


    die();

}


?>