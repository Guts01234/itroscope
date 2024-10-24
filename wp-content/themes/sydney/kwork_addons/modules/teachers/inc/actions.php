<?php 

    add_action("post_teachers", 'echo_post_teachers');

    function echo_post_teachers($post_id){
        $post_teachers_arr = get_post_meta($post_id, 'post_teachers_arr', true);

        if(!$post_teachers_arr){
            $post_teachers_arr = array();
        }


        echo "<div class='post_teachers'>";

        foreach ($post_teachers_arr as $key => $teacher_id) {
            echo "<p>" . get_user_link_name($teacher_id) . "</p>";
        }

        if(!count($post_teachers_arr)){
            echo "<p>Наставников нет</p>";
        }

        echo "</div>";

        echo do_shortcode('[search_user input_id="post_'.$post_id.'"]');

        echo '<button data-post-id="'. $post_id .'"  data-current-user-id="'. get_current_user_id() .'" class="add_teacher_to_lesson">Добавить наставника</button>';
    }


    //скрипты стили
    add_action('wp_enqueue_scripts', 'reg_styles_script_teacher');

    function reg_styles_script_teacher(){
        if(is_page(PAGE_LESSON_ID)){
            // js для логики по добавлению наставника
            wp_enqueue_script('teacher-invation-logic',
            get_template_directory_uri() . '/kwork_addons/modules/teachers/assets/js/teacher-logic.js',
            array('jquery'),
            filemtime( get_theme_file_path('/kwork_addons/modules/teachers/assets/js/teacher-logic.js') ),
            'in_footer'
            );
        }
        // js работа с уведомлениями для принятия\отказа от приглашения стать наставником
        wp_enqueue_script('teacher-notification-logic',
        get_template_directory_uri() . '/kwork_addons/modules/teachers/assets/js/teacher-notification.js',
        array('jquery'),
        filemtime( get_theme_file_path('/kwork_addons/modules/teachers/assets/js/teacher-notification.js') ),
        'in_footer'
        );

        // стили
        wp_enqueue_style('teacher-styles',
        get_template_directory_uri() . '/kwork_addons/modules/teachers/assets/css/style.css',
        filemtime( get_theme_file_path('/kwork_addons/modules/teachers/assets/css/style.css') )
        );
    }


?>