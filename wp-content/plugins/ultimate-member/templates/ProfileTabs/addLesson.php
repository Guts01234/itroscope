<?php 
if ( ! defined( 'ABSPATH' ) ) exit;


function um_mycustomtab_add_tab( $tabs ) {

	$tabs[ 'mycustomtab' ] = array(
		'name'   => 'Отдать/Получить',
		'icon'   => 'um-faicon-pencil',
		'custom' => true
	);

	UM()->options()->options[ 'profile_tab_' . 'mycustomtab' ] = true;

	return $tabs;
}

function um_profile_content_mycustomtab_default( $args ) {
    ?>

        <p class='want_form_text'>Я хочу разместить объявление:</p>
        <div class="forms_want_radios has_cute_radios_toggle">
            <label for="lesson">
                <input type="radio" name="type_form" id="lesson" value='lesson'>
                <p>Урок</p>
            </label>
            <label for="gift">
                <input type="radio" name="type_form" id="gift"  value='gift'>
                <p>Подарок</p>
            </label>
            <label for="time">
                <input type="radio" name="type_form" id="time" value='time'>
                <p>Время</p>
            </label>
        </div>

    <?php
	do_action('post_form_place_for_script_form_id');
	?>
    <style>
        .wpuf-wordlimit-message{
            display: none!important;
        }
    </style>
	<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
  <script>
        //функция следит чтобы не превышали название
		function get_lenght_input($el, limit){
			$el.on('keyup', ()=>{
				let val = $el.val();
				if(val.length>limit){
					$el.parent().find('.wpuf-help').last().html('<span style="color:red">Вы превысили лимит в '+ limit +' символов!</span>');
				}
				else{
					$el.parent().find('.wpuf-help').last().html(`Осталось ${limit - val.length} символов`);
				}
			});
		}

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
                console.log('efwefwf')
				let val = $(`.wpuf_post_title_${post_form_id}`).val();
				if(val.length>150){
					$('.wpuf-form-add .post_title .wpuf-help').last().html('<span style="color:red">Вы превысили лимит в 150 символов!</span>');
				}
				else{
					$('.wpuf-form-add .post_title .wpuf-help').last().html(`Кратко расскажите о чем будет ваш урок. Осталось ${150 - val.length} символов`);
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
		function get_lenght_post_problem_new(){
			$('.problem_time input').on('keyup', ()=>{
				let val = $('.problem_time input').val();
				if(val.length>25){
					$('.problem_time').find('.wpuf-help').html('<span style="color:red">Вы превысили лимит в 25 символов!</span>');
				}
				else{
					$('.problem_time').find('.wpuf-help').html(`Напишите какую проблему решает вашe объявление. Осталось ${25 - val.length} символов`);
				}
			});
		}
        //функция следит чтобы не превышали навык
		function get_lenght_post_navik_new(){
			$('.navik_time  input').on('keyup', ()=>{
				let val = $('.navik_time  input').val();
				if(val.length>25){
					$('.navik_time .wpuf-help').html('<span style="color:red">Вы превысили лимит в 25 символов!</span>');
				}
				else{
					$('.navik_time .wpuf-help').html(`Напишите какой навык развивает вашe объявление. Осталось ${25 - val.length} символов`);
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
                    $('.hr_practice  ').fadeIn();
                    $('.title_form_practice  ').fadeIn();
                } else {
                    $('li.post_homework').fadeOut();
                    $('li.sfer_practice').fadeOut();
                    $('li.navik_practice').fadeOut();
                    $('li.problem_practice ').fadeOut();
                    $('.hr_practice  ').fadeOut();
                    $('.title_form_practice  ').fadeOut();
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
                    $('.hr_teory  ').fadeIn();
                    $('.title_form_teory  ').fadeIn();
                    $('.youtube_href').fadeIn();
                    
                } else {
                    $('li.post_content').fadeOut();
                    $('li.sfera_teory').fadeOut();
                    $('li.navil_teory').fadeOut();
                    $('li.problem_teory ').fadeOut();
                    $('.hr_teory  ').fadeOut();
                    $('.title_form_teory  ').fadeOut();
                    $('.youtube_href').fadeOut();
                }
            });
        }
    }, 1000);

    // добавить кнопку "загрузить изображение в панель"
    setTimeout(() => {

        let buttonLesson = $('#create_lesson_form .wpuf-insert-image');
        $('#create_lesson_form .wpuf-insert-image').insertAfter('#create_lesson_form #mceu_8');
        $('#create_lesson_form .wpuf-insert-image').html(
            '<img src="https://itroscope.com/wp-content/uploads/2022/09/free-icon-image-5124630-1.png" alt="" />',
        );
        let buttonGift = $('#create_gift_form .wpuf-insert-image');
        $('#create_gift_form .wpuf-insert-image').insertAfter('#create_gift_form #mceu_62');
        $('#create_gift_form .wpuf-insert-image').html(
            '<img src="https://itroscope.com/wp-content/uploads/2022/09/free-icon-image-5124630-1.png" alt="" />',
        );


        get_lenght_post_navik();
        get_lenght_post_problem();
        get_lenght_post_navik_practice();
        get_lenght_post_problem_practice();
        get_length_post_homework();
        setTimeout(()=>{
            get_leght_post_textarea();
        }, 1500);

        get_lenght_input($('#create_lesson_form_give input[name="post_title"]'), 200);
        get_lenght_input($('#create_gift_form input[name="post_title"]'), 200);
        get_lenght_input($('#create_time_form input[name="post_title"]'), 200);
        get_lenght_input($('#create_lesson_form_take input[name="post_title"]'), 200);
        get_lenght_input($('#create_lesson_form_take .problem_teory_take input[name="problem"]'), 25);
        get_lenght_input($('#create_lesson_form_take .navik_teory_take input[name="navik"]'), 25);
        get_lenght_post_navik_new();
        get_lenght_post_problem_new();
    }, 2000);


		</script><?php
}
?>