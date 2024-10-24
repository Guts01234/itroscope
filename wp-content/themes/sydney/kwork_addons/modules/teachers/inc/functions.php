<?php 

    function get_teachers_posts_arr($post_id){
        $post_teachers_arr = get_post_meta('post_teachers_arr', true);
        if(!$post_teachers_arr){
            $post_teachers_arr = array();
        }

        return $post_teachers_arr;
    }

?>