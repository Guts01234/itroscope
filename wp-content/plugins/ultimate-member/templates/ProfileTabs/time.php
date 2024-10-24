<?php 
if ( ! defined( 'ABSPATH' ) ) exit;


function um_time_add_tab( $tabs ) {

	$tabs[ 'time' ] = array(
		'name'   => 'Время',
		'icon'   => 'um-icon-clock',
		'custom' => true
	);

	UM()->options()->options[ 'profile_tab_' . 'time' ] = true;

	return $tabs;
}



function um_profile_content_time_default( $args ) {

    if(um_profile_id() != get_current_user_id()){
        paint_all_time(um_profile_id());
        return;
    }

    $profile_id = um_profile_id();

    $time_arr_ids = get_user_meta($profile_id, 'time_arr', true);

    //массив где мы отдаём
    $time_arr_give = array();
    //массив где мы берём
    $time_arr_take = array();

    if(!is_array($time_arr_ids)){
        $time_arr_ids = array();
    }

    foreach ($time_arr_ids as $time_id) {
        $time = Time::get_time_by_id($time_id);
        
        if(!$time){
            continue;
        }

        if($time->get_status() != 'start'){
            continue;
        }

        $admin_id = $time->get_admin_id();
        $cart_state = get_post_meta($time->get_post_id(), 'cart_state', true);

        $is_admin_cart = $admin_id == $profile_id;

        if($is_admin_cart){
            if($cart_state == 'give'){
                array_push($time_arr_give, $time);
            }else{
                array_push($time_arr_take, $time);
            }
        }else{
            if($cart_state == 'give'){
                array_push($time_arr_take, $time);
            }else{
                array_push($time_arr_give, $time);
            }
        }
        
    }

    ?>
    <div class="cute_table_container">
        <h3>Уделяете время:</h3>
        <?php paint_time_table($time_arr_give)?>
    </div>
    
    <div class="cute_table_container">
        <h3>Получаете время:</h3>
        <?php paint_time_table($time_arr_take)?>
    </div>
    <?php
    
    paint_all_time($profile_id);
}


function paint_time_table($time_arr){
    ?>
        <?php if(count($time_arr) > 0):?>
            <table class="cute_table time_table">
                <tr class="cute_table__header">
                    <th>Объявление</th>
                    <th>Пользователь</th>
                    <th>Действие</th>
                </tr>
                <?php foreach ($time_arr as $time):?>
                    <tr class="cute_table__item">
                        <td colspan='3'>
                            <table>
                                <tr class="cute_table__item__header time_table__item__header">
                                    <td>
                                        <?php echo get_post_link_title($time->get_post_id())?>
                                    </td>
                                    <td>
                                        <?php echo get_user_link_name(($time->get_custumer_id() == um_profile_id()) ? $time->get_admin_id() : $time->get_custumer_id())?>
                                    </td>
                                    <td>
                                        <button class='open_chat'>Подробнее</button>
                                    </td>
                                </tr>
                                <tr class="cute_table__chat">
                                    <td colspan='3'>
                                        <table>
                                            <tr>
                                                <td><p class='table_big_text'>Чат</p></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <?php
                                                        $messages = $time->get_chat()->get_messages();
                                                    ?>
                                                    <div class="table_messages">
                                                        <?php if($messages && count($messages) > 0):  ?>
                                                            <?php foreach ($messages as $message):?>
                                                                <div class="table_message">
                                                                    <div class="table_message__avatar">
                                                                        <div class="table_message__avatar__img">
                                                                            <?php
                                                                                $author_avatar =  get_avatar_url($message['author_id']);
                                                                                if($author_avatar && !strripos($author_avatar, 'default')){
                                                                                    echo "<img src='{$author_avatar}' alt='author'>";
                                                                                }else{
                                                                                    echo "<img src='https://cdn-icons-png.flaticon.com/512/3593/3593455.png' alt='placeholder'>";
                                                                                } 
                                                                            ?>
                                                                        </div>
                                                                        <p>
                                                                            <?php echo get_user_link_name($message['author_id'])?>
                                                                            <?php if($message['author_id'] == um_profile_id()) echo '(Вы)';?>
                                                                        </p>
                                                                    </div>
                                                                    <div class="table_message__text">
                                                                        <p>
                                                                            <?php echo $message['content'];?>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach;?>
                                                        <?php else:?>
                                                            <p class='chat_first_row'>В чате пока пусто. Напишите первым!</p>
                                                        <?php endif;?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <textarea class="input_message"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="time_btns">
                                                        <button class="send_message_chat" 
                                                            data-chat-id="<?php echo $time->get_chat()->get_id()?>"
                                                            data-post-id="<?php echo $time->get_post_id()?>"
                                                            >Отправить</button>
                                                            <?php if($time->get_confirm_first_id() == get_current_user_id()):?>
                                                            <p class="meeting_text--submited">Вы подтвердили, что всё прошло успешно. Ожидаем подтверждения другого пользователя</p>
                                                        <?php else:?>
                                                            <button class="confirm_time" data-time-id="<?php echo $time->get_id()?>" 
                                                                                        data-cur_user_id="<?php echo get_current_user_id()?>">Сделка состоялась</button>
                                                        <?php endif;?>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                <?php endforeach;?>
            </table>
        <?php else:?>
            <p>Тут пока пусто...</p>
        <?php endif;?>
    <?php
}

function paint_all_time($user_id){
    if(um_profile_id() == get_current_user_id()){
        echo "<h3>Ваше время:</h3>";
    }else{
        echo "<h3>Объявления времени этого пользователя:</h3>";
    }
    $query_time = new WP_Query([
        'author' => $user_id,
        'post_type' => 'time',
        'posts_per_page' => -1,
    ]);

    if($query_time->have_posts()):
        ?>
        <div class="cute_table_container">
            <table class="cute_table time_table">
                    <tr class="cute_table__header">
                        <th>Объявление</th>
                        <th>Хочу</th>
                    </tr>
        <?php
        while ( $query_time->have_posts() ): $query_time->the_post();?>
            
            <tr>
                <td><?php echo get_post_link_title(get_the_ID())?></td>
                <td>
                    <?php 
                        $cart_state = get_post_meta(get_the_ID(), 'cart_state', true);
                        switch ($cart_state) {
                            case 'take':
                                echo 'Получить';
                                break;
                            case 'give':
                                echo 'Отдать';
                                break;
                            default:
                                break;
                        }
                    ?>
                </td>
            </tr>
            
        <?php endwhile;?>
            </table>
        </div>
    <?php else:?>
        <?php 
            if(um_profile_id() == get_current_user_id()){
                echo "<p>Вы ещё не создали ни одного объявления</p>";
            }else{
                echo "<p>Пользователь ещё не создал ни одного объявления</p>";
            }
        ?>
    <?php endif;?>

    <?php
    
    wp_reset_postdata(); 
}
