<?php 

class Chat{

    private $id;
    private $messages;
    private $user_id_1;
    private $user_id_2;


    public function __construct($id1, $id2 = false){

        //если передали одно айди, то значит передали айди чата
        if($id2 == false){
            $this->id = $id1;

            $chat_row = ChatDb::get_chat_by_id($id1);

            $this->user_id_1  = $chat_row->user_id_1;
            $this->user_id_2  = $chat_row->user_id_2;
            $this->messages = json_decode($chat_row->messages, true);
        }else{
            //иначе создаём
            $this->user_id_1  = $id1;
            $this->user_id_2  = $id2;
            $chat_id = ChatDb::add_chat($id1, $id2);
            $this->id = $chat_id;
        }

    }

    public function add_message($author_id, $content){

        //если автора сообщения нет в этом чате
        if($author_id != $this->user_id_1
            && $author_id != $this->user_id_2
        ){
            return false;
        }

        $message = [];
        $message['author_id'] = $author_id;
        $message['content'] = $content;

        $chat_new = new Chat($this->id);
        $messages = $chat_new->get_messages();

        if(!is_array($messages)){
            $messages = array();
        }

        array_push($messages, $message);

        $this->messages = $messages;

        $messages_encoded = json_encode($messages);

        $res = ChatDb::update_messages($this->id, $messages_encoded);


        return ($res) ? true : false;
    }

    public function get_messages(){
        return $this->messages;
    }

    public function get_id(){
        return $this->id;
    }

    public function get_user_id_1(){
        return $this->user_id_1;
    }

    public function get_user_id_2(){
        return $this->user_id_2;
    }
}

?>