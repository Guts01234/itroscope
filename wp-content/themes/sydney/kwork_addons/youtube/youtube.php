<?php 
add_action( 'wp_ajax_ytD', 'getYouTubeDur' ); 
add_action( 'wp_ajax_nopriv_ytD', 'getYouTubeDur' );  

 
function getYouTubeDur(){
    if(isset($_POST['video_url'])){
        echo getVideoDuration($_POST['video_url']);
    }
    die;
}



function getYouTubeVideoID($url) {
    $queryString = parse_url($url, PHP_URL_QUERY);
    parse_str($queryString, $params);
    if (isset($params['v']) && strlen($params['v']) > 0) {
        return $params['v'];
    } else {
        return "";
    }
}

function getVideoDuration($url){
    $api_key = 'AIzaSyBWObYrHG4pSOycviSs0DaD1f-ZY-bMWfI';
 
    $api_url = 'https://www.googleapis.com/youtube/v3/videos?part=snippet%2CcontentDetails%2Cstatistics&id=' . getYouTubeVideoID($url) . '&key=' . $api_key;
 
    $data = json_decode(file_get_contents($api_url));
    return $data->items[0]->contentDetails->duration;
}   
 

?>