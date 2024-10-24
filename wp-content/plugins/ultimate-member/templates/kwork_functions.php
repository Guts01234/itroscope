<?php
 if ( ! defined( 'ABSPATH' ) ) exit;
 //для вкладки главная
 require_once __DIR__ . '/ProfileTabs/main.php';
 //для вкладки опыт
 require_once __DIR__ . '/ProfileTabs/expiriens.php';
 //для вкладки добавить урок
 require_once __DIR__ . '/ProfileTabs/addLesson.php';
 //для вкладки проверка заданий
 require_once __DIR__ . '/ProfileTabs/homeworks.php';
 //для вкладки времени
 require_once __DIR__ . '/ProfileTabs/time.php';
 //для вкладки подарков
 require_once __DIR__ . '/ProfileTabs/gift.php';






function kwork_tabs(){
	do_action('modal_error_add_post');
	add_action( 'um_profile_content_time_default', 'um_profile_content_time_default' );
	add_filter( 'um_profile_tabs', 'um_time_add_tab', 1000 );
	add_action( 'um_profile_content_gift_default', 'um_profile_content_gift_default' );
	add_filter( 'um_profile_tabs', 'um_gift_add_tab', 1000 );
	if(um_profile_id() == get_current_user_id()){
		// add_action( 'um_profile_content_check_homework', 'um_profile_content_check_homework' );
		// add_filter( 'um_profile_tabs', 'um_check_homework_add_tab', 1000 );
		add_action( 'um_profile_content_mycustomtab_default', 'um_profile_content_mycustomtab_default' );
		add_filter( 'um_profile_tabs', 'um_mycustomtab_add_tab', 1500 );
        
		if(!is_company(um_profile_id())){
			add_action( 'um_profile_content_exp_default', 'um_profile_content_exp_default' );
			add_filter( 'um_profile_tabs', 'um_exp_add_tab', 2000 );
		}

		//для профиля компании
		if(is_company(um_profile_id())){

			add_action( 'um_profile_content_company_users', 'um_profile_content_company_users' );
			add_filter( 'um_profile_tabs', 'um_company_users_add_tab', 1001 );
		}
	}
}

add_action('kwork_tabs', 'kwork_tabs');

 ?>
