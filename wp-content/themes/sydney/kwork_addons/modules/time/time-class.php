<?php 

class Time{
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
        $time_res = TimeDb::get_time_meeting($post_id, $custumer_id);

        //если нет такого, то создаём
        if(!$time_res){
            $chat = new Chat($author_id, $custumer_id);

            $time_res = TimeDb::add_time_meeting($post_id, $custumer_id, $chat->get_id());
            $this->id = $time_res;
            $time_res = TimeDb::get_time_meeting_by_id($time_res);

        }

        $this->chat = new Chat($time_res->chat_id);
        $this->id = $time_res->id;
        $this->status = $time_res->status_time;
        $this->confirm_first_id = $time_res->confirm_first_id;

    }

    //$user_id - это тот, кто нажал кнопку
    public function confirm_meeting($user_id){
        if($this->confirm_first_id == null){
            TimeDB::set_confirm_first_id($this->id, $user_id);
            return;
        }

        $learner_id;
        $teacher_id;

        $cart_state = get_post_meta($this->post_id, 'cart_state', true);

        //если в объявлении - отдам, то ученик - не мы
        if($cart_state == 'give'){
            $learner_id = $this->custumer_id;
            $teacher_id = $this->admin_id;
        }else{
            //иначе мы просили нам время выделить
            $learner_id = $this->admin_id;
            $teacher_id = $this->custumer_id;
        }
        $time_lesson = get_post_meta($this->post_id, 'time_how_much', true);
        $time_lesson_val;

        switch ($time_lesson) {
            case '30m':
                $time_lesson_val = 0.5;
                break;
            case '1h':
                $time_lesson_val = 1;
                break;
            case '2h':
                $time_lesson_val = 2;
                break;
            
            default:
                break;
        }

        $user_learn_time = get_user_meta($learner_id, 'learn_time', true);
        $user_teach_time = get_user_meta($teacher_id, 'teach_time', true);

        if(!$user_learn_time){
            $user_learn_time = 0;
        }else{
            $user_learn_time = intval($user_learn_time);
        }
        if(!$user_teach_time){
            $user_teach_time = 0;
        }else{
            $user_teach_time = intval($user_teach_time);
        }

        $user_learn_time += $time_lesson_val;       
        $user_teach_time += $time_lesson_val;
        
        update_user_meta($learner_id, 'learn_time', $user_learn_time);
        update_user_meta($teacher_id, 'teach_time', $user_teach_time);


        $data_sfer = wp_get_post_terms($this->post_id, 'sfera');
        $data_navik = wp_get_post_terms($this->post_id, 'navik');
        $data_problem = wp_get_post_terms($this->post_id, 'problem');

        $user_practice_sfera = get_user_meta($learner_id, 'practice_sfera', true);
        $user_practice_navik = get_user_meta($learner_id, 'practice_navik', true);
        $user_practice_problem = get_user_meta($learner_id, 'practice_problem', true);


        $new_user_practice_sfers = terms_to_arr_to_db($data_sfer, $user_practice_sfera, 1, true);

        $new_user_practice_naviks = terms_to_arr_to_db($data_navik, $user_practice_navik, 1, true);

        $new_user_practice_problems = terms_to_arr_to_db($data_problem, $user_practice_problem, 1, true);

        update_user_meta($learner_id, 'practice_sfera', $new_user_practice_sfers );
        update_user_meta($learner_id, 'practice_navik', $new_user_practice_naviks );
        update_user_meta($learner_id, 'practice_problem', $new_user_practice_problems);

        $this->status = 'finished';

        TimeDb::change_time_status_by_id($this->id, $this->status);
        // TimeDb::change_time_status_all($this->admin_id, $this->post_id, 'archive');
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


    //функция которая проверяет, существует ли такое Время
    public static function is_created_time($post_id, $custumer_id){
        //пытаемся получить
        $time_res = TimeDb::get_time_meeting($post_id, $custumer_id);

        return ($time_res) ? true : false;

    }

    //получает объект по айди
    public static function get_time_by_id($id){

        $time_res = TimeDb::get_time_meeting_by_id($id);

        if(!$time_res){
            return false;
        }

        return new Time($time_res->post_id, $time_res->custumer_id);

    }

}

?>