<?php 


add_action( 'wp_ajax_search_users', 'search_users' );
add_action( 'wp_ajax_nopriv_search_users', 'search_users' );


function search_users(){

    if(!isset($_POST['search'])) die;

    $search = $_POST["search"];
    $max_size = $_POST["max_size"];

    $users = get_users([
        'role__not_in' => array('company'),
        'search' => '*' . $search . "*",
        'search_columns' => array("login", 'nicename'),
        'fields' => array('ID', 'user_login'),
    ]);
    
    $data = "";

    $count_now = 0;

    foreach($users as $user){

        $name = get_user_link_name($user->id);

        $select = '<div class="selcet_user" data-user-id="' . $user->ID . '">' . $name . ' (' . $user->user_login .')</div>';

        $data .= $select;

        $count_now += 1;

        if($count_now >= $max_size){
            break;
        }
    }
    
    echo $data;

    die();

}


?>