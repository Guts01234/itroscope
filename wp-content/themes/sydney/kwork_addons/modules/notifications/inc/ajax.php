<?php 
add_action( 'wp_ajax_change_status_notification', 'ajax_change_status_notification' ); // wp_ajax_{ЗНАЧЕНИЕ ПАРАМЕТРА ACTION!!}
add_action( 'wp_ajax_nopriv_change_status_notification', 'ajax_change_status_notification' );  // wp_ajax_nopriv_{ЗНАЧЕНИЕ ACTION!!}


function ajax_change_status_notification(){
    
    if(!isset($_POST['notification_array'])) die;
    
    $notification_array = $_POST['notification_array'];
    
    
    for ($i=0; $i < count($notification_array); $i++) { 
        NotificationDB::change_status_notification($notification_array[$i], 1);
    }
    

    die;
}

?>