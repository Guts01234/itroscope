<?php 

function is_company($id){
    $user = get_user_by('id', $id);
    $roles = $user->roles;
    if($roles[0] == 'company'){
        return true;
    }
}


class Company{
    private $id;
    

    public function __construct($id){
        if(!is_company($id)){
            return false;
        }
        $this->id = $id;
        return true;
    }

    public function get_count_members(){
        $arr = $this->get_members_list();

        if(!$arr){
            return 0;
        }

        return count($arr);
    }
    
    //проверка на то, есть ли пользоваель в массиве приглашённых
    public function check_user_in_invations($user_id){
        $user_company_arr_invations= get_user_meta($user_id, 'company_arr_invations', true);
        $company_members_invations = get_user_meta($this->id, 'members_id_arr_invations', true);

        if(!$user_company_arr_invations){
            $user_company_arr_invations = array();
        }
        if(!$company_members_invations){
            $company_members_invations = array();
        }

        if(in_array($this->id, $user_company_arr_invations) || in_array($user_id, $company_members_invations)){
            return true;
        }
        return false;
    }

    //проверка на то, есть ли пользоваель в массиве ожидания
    public function check_user_in_waiting($user_id){
        $user_company_arr_waiting = get_user_meta($user_id, 'company_arr_waiting', true);
        $company_members_waiting = get_user_meta($this->id, 'members_id_arr_waiting', true);

        if(!$user_company_arr_waiting){
            $user_company_arr_waiting = array();
        }
        if(!$company_members_waiting){
            $company_members_waiting = array();
        }

        if(in_array($user_id, $user_company_arr_waiting) || in_array($user_id, $company_members_waiting)){
            return true;
        }
        return false;
    }

    //проверка на то, есть ли пользоваель в списке участников
    public function check_user_in_members($user_id){
        $user_company_arr = get_user_meta($user_id, 'company_arr', true);
        $company_members = get_user_meta($this->id, 'members_id_arr', true);
        
        if(!$user_company_arr){
            $user_company_arr = array();
        }
        if(!$company_members){
            $company_members = array();
        }
        

        if(in_array($this->id, $user_company_arr) || in_array($user_id, $company_members)){
            return true;
        }
        return false;
    }

    public function delete_user($user_id){

        $user_company_arr = get_user_meta($user_id, 'company_arr', true);
        $company_members = get_user_meta($this->id, 'members_id_arr', true);
        
        if(!$user_company_arr){
            $user_company_arr = array();
        }
        if(!$company_members){
            $company_members = array();
        }
        

        if(!$this->check_user_in_members($user_id)){
            return;
        }


        unset($user_company_arr[array_search( $this->id,$user_company_arr)]);
        unset($company_members[array_search($user_id,$company_members)]);
        
        update_user_meta($user_id, 'company_arr', $user_company_arr);
        update_user_meta($this->id, 'members_id_arr', $company_members);


    }

    public function add_user_in_company($user_id){

        if($this->check_user_in_waiting($user_id)){
            $this->delete_user_from_waiting($user_id);
        }elseif ($this->check_user_in_invations($user_id)) {
            $this->delete_user_from_invations($user_id);
        }
        
        $user_company_arr = get_user_meta($user_id, 'company_arr', true);
        $company_members = get_user_meta($this->$id, 'members_id_arr', true);
        
        if(!$user_company_arr){
            $user_company_arr = array();
        }
        if(!$company_members){
            $company_members = array();
        }
        
        array_push($user_company_arr, $this->id);
        array_push($company_members, $user_id);
        
        update_user_meta($user_id, 'company_arr', $user_company_arr);
        update_user_meta($this->id, 'members_id_arr', $company_members);
        
    }


    //функция, которая добавляет пользователя в массив ожидания, чтобы небыло несанционированного доступа
    public function add_user_in_waiting($user_id){
        $user_company_arr_waiting = get_user_meta($user_id, 'company_arr_waiting', true);
        $company_members_waiting = get_user_meta($this->id, 'members_id_arr_waiting', true);

        if(!$user_company_arr_waiting){
            $user_company_arr_waiting = array();
        }
        if(!$company_members_waiting){
            $company_members_waiting = array();
        }

        if(in_array($user_id, $user_company_arr_waiting) || in_array($user_id, $company_members_waiting)){
            return;
        }

        array_push($user_company_arr_waiting, $this->id);
        array_push($company_members_waiting, $user_id);

        update_user_meta($user_id, 'company_arr_waiting', $user_company_arr_waiting);
        update_user_meta($this->id, 'members_id_arr_waiting', $company_members_waiting);
    }

    //получить массив ожидания
    public function get_waiting_user_list(){
        $company_members_waiting = get_user_meta($this->id, 'members_id_arr_waiting', true);

        if(!$company_members_waiting){
            $company_members_waiting = array();
        }

        return $company_members_waiting;
    }

    //получить массив приглашённых
    public function get_invations_user_list(){
        $company_members_invations = get_user_meta($this->id, 'members_id_arr_invations', true);

        if(!$company_members_invations){
            $company_members_invations = array();
        }

        return $company_members_invations;
    }

    //получить массив участников
    public function get_members_list(){
        $company_members_waiting = get_user_meta($this->id, 'members_id_arr', true);

        if(!$company_members_waiting){
            $company_members_waiting = array();
        }

        return $company_members_waiting;
    }

    //функция, которая удаляет пользователя из массива ожидания, чтобы небыло несанционированного доступа
    public function delete_user_from_waiting($user_id){
        $user_company_arr_waiting = get_user_meta($user_id, 'company_arr_waiting', true);
        $company_members_waiting = get_user_meta($this->id, 'members_id_arr_waiting', true);

        if(!$user_company_arr_waiting){
            $user_company_arr_waiting = array();
        }
        if(!$company_members_waiting){
            $company_members_waiting = array();
        }

        if(!in_array($this->id, $user_company_arr_waiting) || !in_array($user_id, $company_members_waiting)){
            return;
        }

        
        unset($user_company_arr_waiting[array_search( $this->id,$user_company_arr_waiting)]);
        unset($company_members_waiting[array_search($user_id,$company_members_waiting)]);


        update_user_meta($user_id, 'company_arr_waiting', $user_company_arr_waiting);
        update_user_meta($this->id, 'members_id_arr_waiting', $company_members_waiting);
    }

    public function delete_user_from_invations($user_id){
        $user_company_arr_invations = get_user_meta($user_id, 'company_arr_invations', true);
        $company_members_invations = get_user_meta($this->id, 'members_id_arr_invations', true);

        if(!$user_company_arr_invations){
            $user_company_arr_invations = array();
        }
        if(!$company_members_invations){
            $company_members_invations = array();
        }

        if(!$this->check_user_in_invations($user_id)){
            return;
        }

        
        unset($company_members_invations[array_search( $this->id,$user_company_arr_invations)]);
        unset($company_members_invations[array_search($user_id,$company_members_invations)]);


        update_user_meta($user_id, 'company_arr_invations', $user_company_arr_waiting);
        update_user_meta($this->id, 'members_id_arr_invations', $company_members_waiting);
    }

    public function add_user_invation($user_id){
        $user_company_arr_invations= get_user_meta($user_id, 'company_arr_invations', true);
        $company_members_invations = get_user_meta($this->id, 'members_id_arr_invations', true);

        if(!$user_company_arr_invations){
            $user_company_arr_invations = array();
        }
        if(!$company_members_invations){
            $company_members_invations = array();
        }

        if($this->check_user_in_invations($user_id)){
            return;
        }

        array_push($user_company_arr_invations, $this->id);
        array_push($company_members_invations, $user_id);

        update_user_meta($user_id, 'company_arr_invations', $user_company_arr_invations);
        update_user_meta($this->id, 'members_id_arr_invations', $company_members_invations);
    }

    
}

?>