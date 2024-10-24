<?php 

class Lesson{
    private $id;
    private $post_id;
    private $admin_id;
    private $custumer_id;
    private $chat;
    private $status;
    private $confirm_first_id;

    public function __construct($post_id, $custumer_id){
        //индитифицировать можно только айди записи + покупателя айди
        $this->post_id = $post_id;
        $this->custumer_id = $custumer_id;

        $my_post = get_post( $post_id );
        $author_id = $my_post->post_author;

        $this->admin_id = $author_id;

        //пытаемся получить
        $lesson_res = LessonDb::get_lesson_meeting($post_id, $custumer_id);

        //если нет такого, то создаём
        if(!$lesson_res){
            $chat = new Chat($author_id, $custumer_id);

            $lesson_res = LessonDb::add_lesson_meeting($post_id, $custumer_id, $chat->get_id());
            $this->id = $lesson_res;
            $lesson_res = LessonDb::get_lesson_meeting_by_id($lesson_res);

        }

        $this->chat = new Chat($lesson_res->chat_id);
        $this->id = $lesson_res->id;
        $this->status = $lesson_res->status_lesson;
        $this->confirm_first_id = $lesson_res->confirm_first_id;

    }

    //$user_id - это тот, кто нажал кнопку
    public function confirm_meeting($user_id){
        if($this->confirm_first_id == null){
            LessonDb::set_confirm_first_id($this->id, $user_id);
            return;
        }

        wp_remote_post( admin_url('admin-ajax.php'), [
            'body' => [
                'action' => 'get_free',
                'post_id' => $this->post_id,
                'user_id' => $this->admin_id 
            ]
        ]);

        $donate_money = get_user_meta($this->admin_id, 'donate_money', true);
        $take_money = get_user_meta($this->custumer_id, 'take_money', true);

        $price = get_post_meta($this->post_id, 'post_price_take', true);

        if(!$price){
            $price = 0;
        }else{
            $price = intval($price);
        }

        if(!$take_money){
            $take_money = 0;
        }else{
            $take_money = intval($take_money);
        }

        $take_money += $price;

        if(!$donate_money){
            $donate_money = 0;
        }else{
            $donate_money = intval($donate_money);
        }

        $donate_money += $price;

        update_user_meta($this->custumer_id, 'take_money', $take_money);
        update_user_meta($this->admin_id, 'donate_money', $donate_money);

        //делаем пост архивным
        
        update_post_meta($this->post_id, 'is_archive_post', true);

        $this->status = 'finished';

        LessonDb::change_lesson_status_by_id($this->id, $this->status);
        LessonDb::change_lesson_status_all($this->admin_id, $this->post_id, 'archive');
    }

    public function get_id(){
        return $this->id;
    }

    public function get_post_id(){
        return $this->post_id;
    }

    public function get_admin_id(){
        return $this->admin_id;
    }

    public function get_custumer_id(){
        return $this->custumer_id;
    }

    public function get_status(){
        return $this->status;
    }

    public function get_chat(){
        return $this->chat;
    }

    public function get_confirm_first_id(){
        return $this->confirm_first_id;
    }

    //функция которая проверяет, существует ли такой урок
    public static function is_created_lesson($post_id, $custumer_id){
        //пытаемся получить
        $lesson_res = lessonDb::get_lesson_meeting($post_id, $custumer_id);

        return ($lesson_res) ? true : false;

    }

    //получает объект по айди
    public static function get_lesson_by_id($id){

        $lesson_res = lessonDb::get_lesson_meeting_by_id($id);
        
        if(!$lesson_res){
            return false;
        }

        return new lesson($lesson_res->post_id, $lesson_res->custumer_id);

    }

}

?>