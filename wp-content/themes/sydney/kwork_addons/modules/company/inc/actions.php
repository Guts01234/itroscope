<?php 
//вешаем обработчик, который будет выводить формы регистрации
add_action("register_page", 'echoforms');

function echoforms(){
    if(get_current_user_id() !=0){
        header('Location: /user/');
    }

    //в админке такого добра не надо
    if(is_admin()){
        return;
    }
    the_reg_radio_buttons();
    
    echo  do_shortcode('[ultimatemember form_id="'. REG_COMPANY_FORM_ID .'"]');
    echo  do_shortcode('[ultimatemember form_id="11"]');
    
}

add_action( "um_registration_complete", "um_121721_change_registration_role" ,1 );
function um_121721_change_registration_role( $user_id ){
    um_fetch_user( $user_id );
    UM()->user()->auto_login( $user_id );
    wp_redirect( '/user/' ); exit;
}


//скрипты стили
add_action('wp_enqueue_scripts', 'reg_styles_script');

function reg_styles_script(){

    //для страницы регестрации
    if(is_page(REG_PAGE_ID)){
        // js для показа скрытия формы
        wp_enqueue_script('reg-script-types-users',
            get_template_directory_uri() . '/kwork_addons/modules/company/assets/js/register.js',
            array('jquery'),
            filemtime( get_theme_file_path('/kwork_addons/modules/company/assets/js/register.js') ),
            'in_footer'
        );
    
        //css
        wp_enqueue_style('reg-css-types-users',
            get_template_directory_uri() . '/kwork_addons/modules/company/assets/css/register.css',
            array(),
            filemtime( get_theme_file_path('/kwork_addons/modules/company/assets/css/register.css') )
        );
    }if(is_page(PROFILE_PAGE_ID)){
        
        // js для логики по добавлению /удалению
        wp_enqueue_script('user-company-logic',
        get_template_directory_uri() . '/kwork_addons/modules/company/assets/js/user-logic-compant.js',
        array('jquery'),
        filemtime( get_theme_file_path('/kwork_addons/modules/company/assets/js/user-logic-compant.js') ),
        'in_footer'
        );

        // js для логики по добавлению /удалению пользователей под руководством компании
        wp_enqueue_script('company-logic',
        get_template_directory_uri() . '/kwork_addons/modules/company/assets/js/company-logic.js',
        array('jquery'),
        filemtime( get_theme_file_path('/kwork_addons/modules/company/assets/js/company-logic.js') ),
        'in_footer'
        );
        
        //css для профиля
        wp_enqueue_style('company-profile',
        get_template_directory_uri() . '/kwork_addons/modules/company/assets/css/company_profile.css',
        array(),
        filemtime( get_theme_file_path('/kwork_addons/modules/company/assets/css/company_profile.css') )
        );
    }
    // js для логики по добавлению /удалению пользователей под руководством компании
    wp_enqueue_script('user-company-invations',
    get_template_directory_uri() . '/kwork_addons/modules/company/assets/js/user-notification.js',
    array('jquery'),
    filemtime( get_theme_file_path('/kwork_addons/modules/company/assets/js/user-notification.js') ),
    'in_footer'
    );
}



//добавление ещё одной "загрузить фотографию" потому что если использовать 2 обычных - возникает баг
/**
* Add new predefined field "Profile Photo" in UM Form Builder.
*/
add_filter("um_predefined_fields_hook","um_predefined_fields_hook_profile_photo2", 99999, 1 );
function um_predefined_fields_hook_profile_photo2( $arr ){


	$arr['profile_photo'] = array(
		'title' => __('Profile Photo','ultimate-member'),
		'metakey' => 'profile_photo',
		'type' => 'image',
		'label' => __('Change your profile photo','ultimate-member'),
		'upload_text' => __('Upload your photo here','ultimate-member'),
		'icon' => 'um-faicon-camera',
		'crop' => 1,
		'max_size' => ( UM()->options()->get('profile_photo_max_size') ) ? UM()->options()->get('profile_photo_max_size') : 999999999,
		'min_width' => str_replace('px','',UM()->options()->get('profile_photosize')),
		'min_height' => str_replace('px','',UM()->options()->get('profile_photosize')),
	);

	return $arr;

}

/**
 *  Multiply Profile Photo with different sizes
*/
add_action( 'um_registration_set_extra_data', 'um_registration_set_profile_photo2', 9999, 2 );
function um_registration_set_profile_photo2( $user_id, $args ){

	if ( empty( $args['custom_fields'] ) ) return;
	
	if( ! isset( $args['form_id'] ) ) return;

	if( ! isset( $args['profile_photo'] ) || empty( $args['profile_photo'] ) ) return;

	// apply this to specific form
	//if( $args['form_id'] != 12345 ) return; 


	$files = array();

	$fields = unserialize( $args['custom_fields'] );

	$user_basedir = UM()->uploader()->get_upload_user_base_dir( $user_id, true );

	$profile_photo = get_user_meta( $user_id, 'profile_photo', true ); 

	$image_path = $user_basedir . DIRECTORY_SEPARATOR . $profile_photo;

	$image = wp_get_image_editor( $image_path );

	$file_info = wp_check_filetype_and_ext( $image_path, $profile_photo );
 
	$ext = $file_info['ext'];
	
	$new_image_name = str_replace( $profile_photo,  "profile_photo.".$ext, $image_path );

	$sizes = UM()->options()->get( 'photo_thumb_sizes' );

	$quality = UM()->options()->get( 'image_compression' );

	if ( ! is_wp_error( $image ) ) {
			
		$max_w = UM()->options()->get('image_max_width');
		if ( $src_w > $max_w ) {
			$image->resize( $max_w, $src_h );
		}

		$image->save( $new_image_name );

		$image->set_quality( $quality );

		$sizes_array = array();

		foreach( $sizes as $size ){
			$sizes_array[ ] = array ('width' => $size );
		}

		$image->multi_resize( $sizes_array );

		delete_user_meta( $user_id, 'synced_profile_photo' );
		update_user_meta( $user_id, 'profile_photo', "profile_photo.{$ext}" ); 
		@unlink( $image_path );

	} 

}

//добавляем в шапку профиля нужный функционал

add_action('um_company_header', 'company_header');

function company_header($profile_id){
    $company = new Company($profile_id[0]);

    if(!$company){
        return;
    }

    ?>
    <div class="company_meta">
        <div class="company_meta__count">
            <p>Сейчас в компании: <span><?php echo $company->get_count_members();?></span> участников</p>
            <?php 

                if(get_current_user_id() != $profile_id[0]){

                    $cur_user_company_waiting_arr = get_user_meta(get_current_user_id(), 'company_arr_waiting', true);
                    $cur_user_companies = get_user_meta(get_current_user_id(), 'company_arr', true);
                    $cut_user_invations = get_user_meta(get_current_user_id(), 'company_arr_invations', true);

                    if(!$cur_user_company_waiting_arr){
                        $cur_user_company_waiting_arr = array();
                    }
                    if(!$cur_user_companies){
                        $cur_user_companies = array();
                    }
                    if(!$cur_user_invations){
                        $cur_user_invations = array();
                    }

                    //если пользователь ждёт вступления
                    if(in_array($profile_id[0], $cur_user_company_waiting_arr)){
                        ?>
                        <button disabled class="cute_blue_button">Заявка отправлена</button>
                        <?php
                    }else if(in_array($profile_id[0], $cur_user_companies)){
                        ?>
                        <button id="go_from_company" data-user-id="<?php echo get_current_user_id()?>" 
                        data-company-id="<?php echo $profile_id[0]?>" 
                        class="cute_blue_button" >Выйти из компании</button>
                        <?php
                    }else if(in_array($profile_id[0], $cur_user_invations)){
                        ?>
                        <button disabled class="cute_blue_button" disabled>Вы приглашены в эту компанию</button>
                        <?php
                    }
                    else{
                        if(is_user_logged_in()){
                            ?>
                            <button id="go_in_company" data-user-id="<?php echo get_current_user_id()?>" 
                            data-company-id="<?php echo $profile_id[0]?>" 
                            class="cute_blue_button">Вступить в копанию</button>
                            <?php
                        }else{
                            ?>
                            <a href="https://<?php echo $_SERVER['SERVER_NAME'] ?>/register/">Зарегестрироваться, чтобы присоедениться</a>
                        <?php

                        }
                    }
                }
            ?>
        </div>
    </div>
    <?php
    
}


//добавляем в табы свои функции
function um_company_users_add_tab( $tabs ) {

	$tabs[ 'company_users' ] = array(
		'name'   => 'Участники',
		'icon'   => 'um-icon-android-person',
		'custom' => true
	);

	UM()->options()->options[ 'profile_tab_' . 'company_users' ] = true;
 
	return $tabs;
}

function um_profile_content_company_users( $args ) {
	echo '<h2>Участники</h2>';

    require get_template_directory() . '/kwork_addons/modules/company/template-parts/user-search-form.php';
    require get_template_directory() . '/kwork_addons/modules/company/template-parts/company-user-tab-waiting.php';
    require get_template_directory() . '/kwork_addons/modules/company/template-parts/company-user-tab-users.php';
}


add_action('profile_main_company', 'profile_main_company');

function profile_main_company($user_id){
    $total_navik = 0;
    $array_navik = array();

    $total_problem = 0;
    $array_problem = array();

    $args = array(
        'author'        =>  $user_id, 
        'orderby'       =>  'post_date',
        'order'         =>  'ASC',
        'posts_per_page' => -1 // no limit
      );
      
      
    $current_user_posts = get_posts( $args );

    foreach ($current_user_posts as $post) {
        $naviks_practice = explode(",", get_post_meta($post->ID, "navik_practice", true));
        $naviks_teor = wp_get_post_terms($post->ID, 'navik');

        $problems_practice = explode(",", get_post_meta($post->ID, "problem_practice", true));
        $problems_teor = wp_get_post_terms($post->ID, 'problem');

        //навыки
        foreach ($naviks_practice as $navik) {
            if(!$navik){
                continue;
              }
            if(isset($array_navik[mb_strtolower(trim($navik))])){
                $array_navik[mb_strtolower(trim($navik))] += 1;
            }else{
                $array_navik[mb_strtolower(trim($navik))] = 1;
            }
            $total_navik +=1;
        }
        
        foreach ($naviks_teor as $navik) {
            if(!$navik){
                continue;
              }
            if(isset($array_navik[mb_strtolower(trim($navik->name))])){
                $array_navik[mb_strtolower(trim($navik->name))] += 1;
            }else{
                $array_navik[mb_strtolower(trim($navik->name))] = 1;
            }
            $total_navik +=1;
        }

        //проблемы
        foreach ($problems_practice as $problem) {
            if(!$problem){
                continue;
              }
            if(isset($array_problem[mb_strtolower(trim($problem))])){
                $array_problem[mb_strtolower(trim($problem))] += 1;
            }else{
                $array_problem[mb_strtolower(trim($problem))] = 1;
            }
            $total_problem +=1;
        }
        
        foreach ($problems_teor as $problem) {
            if(!$problem){
                continue;
              }
            if(isset($array_problem[mb_strtolower(trim($problem->name))])){
                $array_problem[mb_strtolower(trim($problem->name))] += 1;
            }else{
                $array_problem[mb_strtolower(trim($problem->name))] = 1;
            }
            $total_problem +=1;
        }
    }

    ?>
    <div class="statistic__profile">
        <?php
        if(!$array_problem || !$array_navik || (count($array_problem) < 1 && count($array_navik < 1))){
            ?>
            <h2>Данных о статистике нет, так как компания не выпустила ни одного урока</h2>
            <?php
        }else{
            ?>
            <div class="statistic__profile__rounds">
                <div class="statistic_profile__navik">
                    <h3>Навыки</h3>
                    <script src="https://code.highcharts.com/highcharts.js"></script>
                    <div id="container__naviks"></div>
                    <script type="text/javascript">
                        Highcharts.chart('container__naviks', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: ''
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        },
                        accessibility: {
                            point: {
                            valueSuffix: '%'
                            }
                        },
                        plotOptions: {
                            pie: {
                            showInLegend: true,
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: false,
                            }
                            }
                        },
                        series: [{
                            name: 'Навики',
                            colorByPoint: true,
                            data: [
                                <?php
                                    foreach ($array_navik as $navik => $value) {

                                        $resp = $value / $total_navik;

                                        echo '{name: "'. $navik . '", y: ' . $resp  . '},' . PHP_EOL;
                                    }
                                ?>
                            ]
                }]
                });
                    </script>
                </div>

                <div class="statistic_profile__problems">
                    <h3>Проблемы</h3>
                    <script src="https://code.highcharts.com/highcharts.js"></script>
                    <div id="container__problems"></div>
                    <script type="text/javascript">
                        Highcharts.chart('container__problems', {
                        chart: {
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie'
                        },
                        title: {
                            text: ''
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        },
                        accessibility: {
                            point: {
                            valueSuffix: '%'
                            }
                        },
                        plotOptions: {
                            pie: {
                            showInLegend: true,
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: false,
                            }
                            }
                        },
                        series: [{
                            name: 'Проблемы',
                            colorByPoint: true,
                            data: [
                                <?php
                                    foreach ($array_problem as $problem => $value) {

                                        $resp = $value / $total_problem;

                                        echo '{name: "'. $problem . '", y: ' . $resp  . '},' . PHP_EOL;
                                    }
                                ?>
                            ]
                }]
                });
                    </script>
                </div>
            </div>
            
            <?php
        }
        ?>
    </div>
    <?php
    
}

add_action('post_form_place_for_script_form_id', 'echo_post_form_id');

//выводим айди формы в скрипты, чтобы потом легко было менять не привязываясь к конкретной форме
function echo_post_form_id(){
    $post_form_id;
    if(is_company(get_current_user_id())){
        $post_form_id = COMPANY_CREATE_LESSON_FORM_ID;
    }else{
        $post_form_id = USER_CREATE_LESSON_FORM_ID;
    }
    
    echo "<div id='create_lesson_form'>";
    input_take_or_give();
    echo "<div id='create_lesson_form_give'>";
    echo '<p class="add_lesson_p">В уроке есть  теоретическая часть и практическая часть в виде задания. Вы можете добавить в систему отдельно урок с теорией, отдельно урок с практическим заданием или сразу комплексный урок в котором будет и теория и практика. </p>';
    echo do_shortcode( '[wpuf_form id="'. $post_form_id . '"]' );
    echo "</div>";
    echo "<div id='create_lesson_form_take'>";
    echo do_shortcode( '[wpuf_form id="'. USER_CREATE_TAKELESSON_FORM_ID . '"]' );
    echo "</div>";
    echo "</div>";
    
    echo "<div id='create_gift_form'>";
    echo do_shortcode( '[wpuf_form id="'. USER_CREATE_GIFT_FORM_ID . '"]' );
    echo "</div>";
    

    echo "<div id='create_time_form'>";
    echo do_shortcode( '[wpuf_form id="'. USER_CREATE_TIME_FORM_ID . '"]' );
    echo "</div>";
    ?>
    <script>
        const post_form_id = <?php echo $post_form_id?>;
        const gift_form_id = <?php echo USER_CREATE_GIFT_FORM_ID?>;
    </script>
    <?php
}


add_shortcode( 'edit_form_post_id', 'edit_form_post_id' );

function edit_form_post_id( $atts ){
	$post_form_id;
    if(is_company(get_current_user_id())){
        $post_form_id = COMPANY_CREATE_LESSON_FORM_ID;
    }else{
        $post_form_id = USER_CREATE_LESSON_FORM_ID;
    }
    ?>
    <script>
        const post_form_id = <?php echo $post_form_id?>;
    </script>
    <script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
		<script>
		//отслеживает сколько текста осталось
		function get_leght_post_textarea(){
			if($(`#post_content_${post_form_id}_ifr`).contents().find('body').length){

                $(`#post_content_${post_form_id}_ifr`).contents().find('body').on('keyup', ()=>{

                    var items_p = $(`#post_content_${post_form_id}_ifr`).contents().find('body p');
                    var items_li = $(`#post_content_${post_form_id}_ifr`).contents().find('body li');
                    var count_elems = 0;

                    items_p.each(function(){
                        count_elems += $(this).text().length;
                    });
                    items_li.each(function(){
                        count_elems += $(this).text().length;
                    });
                    if(count_elems>5000){
                        $(`.wpuf_post_content_${post_form_id} .wpuf-help`).html('<span style="color:red">Вы превысили лимит в 5000 символов!</span>');
                    }
                    else{
                        $(`.wpuf_post_content_${post_form_id} .wpuf-help`).html(`Осталось ${5000 - count_elems} символов`);
                    }
                    });
                }
		}

		//поменять айди
		//функция следит чтобы не превышали название
		function get_lenght_post_title(){
			$(`.wpuf_post_title_${post_form_id}`).on('keyup', ()=>{
				let val = $(`.wpuf_post_title_${post_form_id}`).val();
				if(val.length>200){
					$('.wpuf-form-add .post_title .wpuf-help').last().html('<span style="color:red">Вы превысили лимит в 200 символов!</span>');
				}
				else{
					$('.wpuf-form-add .post_title .wpuf-help').last().html(`Кратко расскажите о чем будет ваш урок. Осталось ${200 - val.length} символов`);
				}
			});
		}
		//функция следит чтобы не превышали навык
		function get_lenght_post_navik(){
			$('#navik').on('keyup', ()=>{
				let val = $('#navik').val();
				if(val.length>25){
					$('.wpuf-form-add #navik').next().next().next().html('<span style="color:red">Вы превысили лимит в 25 символов!</span>');
				}
				else{
					$('.wpuf-form-add #navik').next().next().next().html(`Напишите какой навык развивает ваш урок. Осталось ${25 - val.length} символов`);
				}
			});
		}
		//функция следит чтобы не превышали проблему
		function get_lenght_post_problem(){
			$('#problem').on('keyup', ()=>{
				let val = $('#problem').val();
				if(val.length>25){
					$('.wpuf-form-add #problem').next().next().next().html('<span style="color:red">Вы превысили лимит в 25 символов!</span>');
				}
				else{
					$('.wpuf-form-add #problem').next().next().next().html(`Напишите какую проблему решает ваш урок. Осталось ${25 - val.length} символов`);
				}
			});
		}
        //функция следит чтобы не превышали навык
		function get_lenght_post_navik_practice(){
			$(`.wpuf_navik_practice_${post_form_id}`).on('keyup', ()=>{
				let val = $(`.wpuf_navik_practice_${post_form_id}`).val();
				if(val.length>25){
					$(`.wpuf-form-add .wpuf_navik_practice_${post_form_id}`).next().next().html('<span style="color:red">Вы превысили лимит в 25 символов!</span>');
				}
				else{
					$(`.wpuf-form-add .wpuf_navik_practice_${post_form_id}`).next().next().html(`Введите через запятую навыки, которые развивает это практическое упражнение. Осталось ${25 - val.length} символов`);
				}
			});
		}
		//функция следит чтобы не превышали проблему
		function get_lenght_post_problem_practice(){
			$(`.wpuf_problem_practice_${post_form_id}`).on('keyup', ()=>{
				let val = $(`.wpuf_problem_practice_${post_form_id}`).val();
				if(val.length>25){
					$(`.wpuf-form-add .wpuf_problem_practice_${post_form_id}`).next().next().html('<span style="color:red">Вы превысили лимит в 25 символов!</span>');
				}
				else{
					$(`.wpuf-form-add .wpuf_problem_practice_${post_form_id}`).next().next().html(`Введите через запятую проблемы, которые развивает это практическое упражнение. Осталось ${25 - val.length} символов`);
				}
			});
		}

		//поменять айди
		//функция следит чтобы не превышали отрывок
		function get_lenght_post_excerpt(){
			$(`.wpuf_post_excerpt_${post_form_id}`).on('keyup', ()=>{
				let val = $(`.wpuf_post_excerpt_${post_form_id}`).val();
				if(val.length>200){
					$('.wpuf-form-add .post_excerpt .wpuf-help').last().html('<span style="color:red">Вы превысили лимит в 200 символов!</span>');
				}
				else{
					$('.wpuf-form-add .post_excerpt .wpuf-help').last().html(`Осталось ${200 - val.length} символов`);
				}
			});
		}

		function get_length_post_homework(){
			$(`.wpuf_post_homework_${post_form_id}`).on('keyup', ()=>{
				let val = $(`wpuf_post_homework_${post_form_id}`).val();
				if(val.length>200){
					$(`.wpuf_post_homework_${post_form_id}`).next().next().html('<span style="color:red">Вы превысили лимит в 200 символов!</span>');
				}
				else{
					$(`.wpuf_post_homework_${post_form_id}`).next().next().html(`Осталось ${200 - val.length} символов`);
				}
			});
		}

		
    //показывать или нет дз
    setTimeout(() => {
        if ($('input[name="has_this_post_homework"]').length) {
            //показывать дз или нет
            let show_start = $('input[name="has_this_post_homework"]:checked').val();
            if (show_start == 1) {
                $('li.post_homework').fadeIn();
                $('li.sfer_practice').fadeIn();
                $('li.navik_practice').fadeIn();
                $('li.problem_practice ').fadeIn();
                $('.hr_practice').fadeIn();
                $('.title_form_practice').fadeIn();
            }
            // показывать теорию или нет
            let show_start_teory = $('input[name="has_this_post_teory"]:checked').val();
            if (show_start_teory == 1) {
                $('li.post_content').fadeIn();
                $('li.sfera_teory').fadeIn();
                $('li.navil_teory').fadeIn();
                $('li.problem_teory').fadeIn();
                $('.hr_teory').fadeIn();
                $('.title_form_teory').fadeIn();
                $('.youtube_href').fadeIn();
            }
            //переключалка практика
            $('input[name="has_this_post_homework"]').click(function () {
                let show = $(this).val();
                if (show == 1) {
                    $('li.post_homework').fadeIn();
                    $('li.sfer_practice').fadeIn();
                    $('li.navik_practice').fadeIn();
                    $('li.problem_practice').fadeIn();
                    $('.hr_practice').fadeIn();
                    $('.title_form_practice').fadeIn();
                } else {
                    $('li.post_homework').fadeOut();
                    $('li.sfer_practice').fadeOut();
                    $('li.navik_practice').fadeOut();
                    $('li.problem_practice').fadeOut();
                    $('.hr_practice').fadeOut();
                    $('.title_form_practice').fadeOut();
                }
            });
            // переключалка теория
            $('input[name="has_this_post_teory"]').click(function () {
                let show = $(this).val();
                if (show == 1) {
                    $('li.post_content').fadeIn();
                    $('li.sfera_teory').fadeIn();
                    $('li.navil_teory').fadeIn();
                    $('li.problem_teory').fadeIn();
                    $('.hr_teory').fadeIn();
                    $('.title_form_teory').fadeIn();
                    $('.youtube_href').fadeIn();
                    let button = $('.wpuf-insert-image');
                    $('.wpuf-insert-image').insertAfter('#mceu_8');
                    $('.wpuf-insert-image').html(
                        '<img src="https://itroscope.com/wp-content/uploads/2022/09/free-icon-image-5124630-1.png" alt="" />',
                    );
                } else {
                    $('li.post_content').fadeOut();
                    $('li.sfera_teory').fadeOut();
                    $('li.navil_teory').fadeOut();
                    $('li.problem_teory').fadeOut();
                    $('.hr_teory').fadeOut();
                    $('.title_form_teory').fadeOut();
                    $('.youtube_href').fadeOut();
                }
            });
        }
    }, 1000);

    // добавить кнопку "загрузить изображение в панель"
    setTimeout(() => {
        let button = $('.wpuf-insert-image');
        $('.wpuf-insert-image').insertAfter('#mceu_8');
        $('.wpuf-insert-image').html(
            '<img src="https://itroscope.com/wp-content/uploads/2022/09/free-icon-image-5124630-1.png" alt="" />',
        );
        get_lenght_post_title();
        get_lenght_post_navik();
        get_lenght_post_problem();
        get_lenght_post_navik_practice();
        get_lenght_post_problem_practice();
        get_length_post_homework();
        setTimeout(()=>{
            get_leght_post_textarea();
        }, 1500);
    }, 2000);


		</script>
    <?php
}

?>