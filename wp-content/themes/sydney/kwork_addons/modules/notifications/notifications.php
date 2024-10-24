<?php
require_once get_template_directory() . '/kwork_addons/modules/notifications/notificationsDB.php';
require_once get_template_directory() . '/kwork_addons/modules/notifications/inc/functions.php';
require_once get_template_directory() . '/kwork_addons/modules/notifications/inc/ajax.php';
require_once get_template_directory() . '/kwork_addons/modules/notifications/inc/actions.php';

function kw_enqueue_styles() {
	wp_enqueue_style( 'notification-css', get_template_directory_uri() . '/kwork_addons/modules/notifications/assets/styles/style.css', array(), true );
	wp_enqueue_script( 'notification-js', get_template_directory_uri() . '/kwork_addons/modules/notifications/assets/js/notification.js',  array('jquery'), false, true );
}
add_action( 'wp_enqueue_scripts', 'kw_enqueue_styles', 9 );

    class Notification{
        private $content;
        private $id;
        private $user_id;
        private $title;
        private $readed_status;
        private $date;

        function __construct($id, $user_id, $readed_status, $title, $content, $date){
            $this->id = $id;
            $this->user_id = $user_id;
            $this->readed_status = $readed_status;
            $this->content = $content;
            $this->title = $title;
            $this->date = $date;
        }

        public function get_content(){
            return $this->content;
        }
        public function get_id(){
            return $this->id;
        }
        public function get_user_id(){
            return $this->user_id;
        }
        public function get_readed_status(){
            return $this->readed_status;
        }
        public function get_title(){
            return $this->title;
        }
        public function get_date(){
            return $this->date;
        }

    }
    
?>