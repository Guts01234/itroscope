<?php 
add_shortcode( 'register_page' , 'register_page' );

function register_page(){
    do_action("register_page");
}


//для выбора пользователя по поиску

add_shortcode('search_user', 'search_user');

function search_user($atts){
    $input_id = $atts['input_id'];
    ?>
        <div class="user_input_container">
            <div class="user_input_container__for_img">
                <input type="text" id='<?php echo $input_id?>'>
            </div>

            <div class="users_search_list">
            </div>
        </div>
        <p class="message_invation"></p>
    <?php
}



//создаём шорткод для первой статьи
function shc_firts_post_old($atts){
    // получаем закреплённый посты
    $ups_post = get_option( 'sticky_posts' );

    if(!isset($ups_post[0])){
        return;
    }
    foreach ($ups_post as $post_id) {
        ob_start();
        $has_teory = get_post_meta($post_id, $key = 'has_this_post_teory', $single = true);
        $has_practice = get_post_meta($post_id, $key = 'has_this_post_homework', $single = true);


        //проверяем показывать как теорию или как практику
        $this_practice = 0;
        if($has_teory === '0' && $has_practice == 1){
            $this_practice = 1;
        }
        $post_now = get_post( $post_id, ARRAY_A);
        ?><article  id="main_post2 post-<?php echo $post_id; ?>" <?php post_class(); ?>>

            <div class="container_l main_stat">
            <a href="<?php echo get_permalink($post_id); ?>">
                <?php
                $sfer;
                            if($this_practice == 1){
                                $sfer = get_post_meta( $post_id, $key = 'sfer_practice', $single = true );
                                // echo $sfer;
                                $sfer = explode(',', $sfer);
                            }else{
                                $sfer  = wp_get_post_terms( get_the_ID(), 'sfera', array('fields' => 'names') );
                            }
                echo_cute_sfer($sfer[0], $sfer[0], 'sfer--up');
                ?>
                <div class="cont_img"><?php echo get_the_post_thumbnail($post_id, 'full' ); ?></div>
                <header class="entry-header"><h2 class="title-post entry-title"><?php
                if($this_practice){
                        echo 'Практика. ';
                    }
                if (get_the_author($post_id) !='admin'){
                echo get_the_author($post_id) . '. '.  $post_now['post_title'];
                }
                else{
                echo $post_now['post_title'];
                }
                ?></h2></header>

                <?php $navik_list;
                    if($this_practice){
                        $navik_list = get_post_meta( $post_id, $key = 'navik_practice', $single = true );
                        $navik_list = explode(',', $navik_list);
                    }else{
                        $navik_list = wp_get_post_terms( get_the_ID(), 'navik', array('fields' => 'names') );
                    }
                if(count($navik_list)){
                ?>
                <div class="naviks_lent">
                    <!-- <p class='name_navik_t'>Навыки:</p> -->
                    <?php  foreach ($navik_list as $key => $name) {
                    ?>

                    <div class="navik">
                        <p><?php echo $name; ?></p>
                    </div>

                    <?php
                    } ?>
                </div>
                <?php

                }
                ?>

                <?php $problem_list;
                    if($this_practice){
                        $problem_list = get_post_meta( $post_id, $key = 'problem_practice', $single = true );
                        $problem_list = explode(',', $problem_list);
                    }else{
                        $problem_list = wp_get_post_terms( get_the_ID(), 'problem', array('fields' => 'names') );
                    }
                if(count($problem_list)){
                ?>
                <div class="naviks_lent">
                    <!-- <p class='name_navik_t'>Проблемы:</p> -->
                    <?php  foreach ($problem_list as $key => $name) {
                    ?>

                    <div class="navik ploblem_stat">
                        <p><?php echo $name;  ?></p>
                    </div>

                    <?php
                    } ?>
                </div>
                <?php

                }
                ?>
            </a>
            <p class='price_cart price_cart_main'><?php
                if(get_post_meta($post_id, 'post_homework_price')[0] === '0'){
                    echo 'БЕСПЛАТНО';
                }else{
                    echo get_post_meta($post_id, 'post_homework_price')[0] . 'P';
                }
                ?></p>
            </div>

        </article><!-- #post-## --><?php
        $content .= ob_get_contents();
        ob_end_clean();
    }
    return $content;
}

add_shortcode( 'first_post', 'shc_firts_post' );
?>