<?php 
    function echoYouTubeFrame($url){
        $url = str_replace('watch?v=', 'embed/', $url);
        echo '<iframe width="560" height="315" src="'. $url .'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
    }
?>