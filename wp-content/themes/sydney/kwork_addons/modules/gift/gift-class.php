<?php 

class Gift{
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
        $gift_res = GiftDb::get_gift_meeting($post_id, $custumer_id);

        //если нет такого, то создаём
        if(!$gift_res){
            $chat = new Chat($author_id, $custumer_id);

            $gift_res = GiftDb::add_gift_meeting($post_id, $custumer_id, $chat->get_id());
            $this->id = $gift_res;
            $gift_res = GiftDb::get_gift_meeting_by_id($gift_res);

        }

        $this->chat = new Chat($gift_res->chat_id);
        $this->id = $gift_res->id;
        $this->status = $gift_res->status_gift;
        $this->confirm_first_id = $gift_res->confirm_first_id;

    }

    //$user_id - это тот, кто нажал кнопку
    public function confirm_meeting($user_id){
        if($this->confirm_first_id == null){
            GiftDb::set_confirm_first_id($this->id, $user_id);
            return;
        }

        $gifter_id;
        $taсker_id;

        $cart_state = get_post_meta($this->post_id, 'cart_state', true);

        //если в объявлении - отдам, то дарит админ
        if($cart_state == 'give'){
            $gifter_id = $this->admin_id;
            $taсker_id = $this->custumer_id;
        }else{
            //иначе админ просил подарить
            $gifter_id = $this->custumer_id;
            $taсker_id = $this->admin_id;
        }


        $data_sfer = array('Духовное');

        $data_navik = array('Благотворительность');
        $data_navik_money = array('Щедрость');
        $data_problem = array('Жадность');

        $user_practice_sfer = get_user_meta($user_id, 'practice_sfera', true);
        $user_practice_navik = get_user_meta($gifter_id, 'practice_navik', true);
        $user_practice_problem = get_user_meta($gifter_id, 'practice_problem', true);


        $gift_money;
        $type_gift = get_post_meta($this->post_id, 'type_gift', true);

        if($type_gift == 'money'){
            $gift_money = intval(get_post_meta($this->post_id, 'gift_money_sum', true));

        }elseif($type_gift == 'product'){
            $gift_product_type = get_post_meta($this->post_id, 'gift_product_type', true);
            $gift_money = intval(get_post_meta($this->post_id, 'gift_product_price', true));
        }


        $donate_money = get_user_meta($gifter_id, 'donate_money', true);
        $take_money = get_user_meta($taсker_id, 'take_money', true);

        if(!$donate_money){
            $donate_money = 0;
        }else{
            $donate_money = intval($donate_money);
        }

        $donate_money += $gift_money;

        if(!$take_money){
            $take_money = 0;
        }else{
            $take_money = intval($take_money);
        }

        $take_money += $gift_money;


        update_user_meta($gifter_id, 'donate_money', $donate_money);
        update_user_meta($taсker_id, 'take_money', $take_money);

        //делаем пост архивным
        
        update_post_meta($this->post_id, 'is_archive_post', true);

        // Проверяем состоялась ли встреча в городе
        $is_town_meeting = false;

        if($type_gift == 'product' && $gift_product_type == 'selftake'){
            if($cart_state == 'give'){
                $is_town_meeting = get_post_meta($this->post_id, 'delivery_gift', true) == 'town';
            }elseif($cart_state == 'take'){
                $is_town_meeting = get_post_meta($this->post_id, 'delivery_gift_take', true) == 'town';
            }
        }

        $new_user_practice_sfer = terms_to_arr_to_db($data_sfer, $user_practice_sfer, 1);

        update_user_meta($gifter_id, 'practice_sfera', $new_user_practice_sfer );

        if($type_gift == 'product'){
            $new_user_practice_naviks = terms_to_arr_to_db($data_navik, $user_practice_navik, ($is_town_meeting ? 1.5 : 1));
            update_user_meta($gifter_id, 'practice_navik', $new_user_practice_naviks );
            
        }else{
            $new_user_practice_naviks = terms_to_arr_to_db($data_navik_money, $user_practice_navik, 1);
    
            $new_user_practice_problems = terms_to_arr_to_db($data_problem, $user_practice_problem, 1);
    
    
            update_user_meta($gifter_id, 'practice_navik', $new_user_practice_naviks );
            update_user_meta($gifter_id, 'practice_problem', $new_user_practice_problems);

        }


        $this->status = 'finished';

        GiftDb::change_gift_status_by_id($this->id, $this->status);
        GiftDb::change_gift_status_all($this->admin_id, $this->post_id, 'archive');
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

    //функция которая проверяет, существует ли такой Подарок
    public static function is_created_gift($post_id, $custumer_id){
        //пытаемся получить
        $gift_res = GiftDb::get_gift_meeting($post_id, $custumer_id);

        return ($gift_res) ? true : false;

    }

    //получает объект по айди
    public static function get_gift_by_id($id){

        $gift_res = GiftDb::get_gift_meeting_by_id($id);
        
        if(!$gift_res){
            return false;
        }

        return new Gift($gift_res->post_id, $gift_res->custumer_id);

    }

}

function get_gift_money($post_id){
    $gift_money;
    $type_gift = get_post_meta($post_id, 'type_gift', true);

    if($type_gift == 'money'){
        $gift_money = intval(get_post_meta($post_id, 'gift_money_sum', true));
    }elseif($type_gift == 'product'){
        $gift_money = intval(get_post_meta($post_id, 'gift_product_price', true));
    }
    return $gift_money;
}

?>