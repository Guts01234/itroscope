<?php 

function the_reg_radio_buttons(){
    ?>
    <div class="reg_page_radios">
        <p>Привет. <br>
        Чтобы получать или делиться с другими временем, подарками или актуальными знаниями присоединяйтесь в наше сообщество. 
        Вы человек или компания? </p>

        <div class="reg_page_radios__list">

            <div class="reg_page_radios__radio">
                <label for="radio_type_user">Человек</label>
                <input type="radio" name="type_user" id="radio_type_user" value="user">
            </div>
            
            <div class="reg_page_radios__radio">
                <label for="radio_type_company">Компания</label>
                <input type="radio" name="type_user" id="radio_type_company" value="company">
            </div>

        </div>
    </div>
    <?php
}



function get_user_all_navik($user_id, $count){
    $ans_arr = array();

    $navik_read_arr = get_user_meta($user_id, 'navik_read_arr', true);
    $practice_navik_arr = get_user_meta($user_id, 'practice_navik', true);

    if($navik_read_arr){
        foreach ($navik_read_arr as $navik => $value) {
            if(isset($ans_arr[$navik])){
                $ans_arr[$navik] += $value;
            }else{
                $ans_arr[$navik] = $value;
            }
        }
    }

    if($practice_navik_arr){

        foreach ($practice_navik_arr as $navik => $value) {
            if(isset($ans_arr[$navik])){
                $ans_arr[$navik] += $value;
            }else{
                $ans_arr[$navik] = $value;
            }
        }
    }

    arsort($ans_arr);
    
    return array_slice($ans_arr, 0, $count);

}

function get_user_all_problems($user_id, $count){
    $ans_arr = array();

    $problems_read_arr = get_user_meta($user_id, 'problem_read_arr', true);
    $practice_problems_arr = get_user_meta($user_id, 'practice_problem', true);

    if($problems_read_arr){
        foreach ($problems_read_arr as $problem => $value) {
            if(isset($ans_arr[$problem])){
                $ans_arr[$problem] += $value;
            }else{
                $ans_arr[$problem] = $value;
            }
        }
    }

    if($practice_problems_arr){
        foreach ($practice_problems_arr as $problem => $value) {
            if(isset($ans_arr[$problem])){
                $ans_arr[$problem] += $value;
            }else{
                $ans_arr[$problem] = $value;
            }
        }
    }
        
    arsort($ans_arr);
    
    return array_slice($ans_arr, 0, $count);

}
function get_user_all_sfers($user_id, $count){
    $ans_arr = array();

    $sfers_read_arr = get_user_meta($user_id, 'sfeta_read_arr', true);
    $practice_sfers_arr = get_user_meta($user_id, 'practice_sfera', true);

    if($sfers_read_arr){
        foreach ($sfers_read_arr as $sfer => $value) {
            if(isset($ans_arr[$sfer])){
                $ans_arr[$sfer] += $value;
            }else{
                $ans_arr[$sfer] = $value;
            }
        }
    }

    if($practice_sfers_arr){
        foreach ($practice_sfers_arr as $sfer => $value) {
            if(isset($ans_arr[$sfer])){
                $ans_arr[$sfer] += $value;
            }else{
                $ans_arr[$sfer] = $value;
            }
        }
    }

    arsort($ans_arr);
    
    return array_slice($ans_arr, 0, $count);

}






?>