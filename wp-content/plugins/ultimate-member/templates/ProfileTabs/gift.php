<?php 
if ( ! defined( 'ABSPATH' ) ) exit;


function um_gift_add_tab( $tabs ) {

	$tabs[ 'gift' ] = array(
		'name'   => 'Подарки',
		'icon'   => 'um-faicon-gift',
		'custom' => true
	);

	UM()->options()->options[ 'profile_tab_' . 'gift' ] = true;

	return $tabs;
}



function um_profile_content_gift_default( $args ) {

    if(um_profile_id() != get_current_user_id()){
        paint_all_gift(um_profile_id());
        return;
    }

    $profile_id = um_profile_id();

    $gift_arr_ids = get_user_meta($profile_id, 'gift_arr', true);

    //массив где мы отдаём
    $gift_arr_give = array();
    //массив где мы берём
    $gift_arr_take = array();

    if(!is_array($gift_arr_ids)){
        $gift_arr_ids = array();
    }

    foreach ($gift_arr_ids as $gift_id) {
        $gift = Gift::get_gift_by_id($gift_id);
        
        if(!$gift){
            continue;
        }

        if($gift->get_status() != 'start'){
            continue;
        }

        $admin_id = $gift->get_admin_id();
        $cart_state = get_post_meta($gift->get_post_id(), 'cart_state', true);

        $is_admin_cart = $admin_id == $profile_id;

        if($is_admin_cart){
            if($cart_state == 'give'){
                array_push($gift_arr_give, $gift);
            }else{
                array_push($gift_arr_take, $gift);
            }
        }else{
            if($cart_state == 'give'){
                array_push($gift_arr_take, $gift);
            }else{
                array_push($gift_arr_give, $gift);
            }
        }

        
    }

    ?>
    <div class="cute_table_container">
        <h3>Дарите подарки:</h3>
        <?php paint_gift_table($gift_arr_give)?>
    </div>
    
    <div class="cute_table_container">
        <h3>Получаете подарки:</h3>
        <?php paint_gift_table($gift_arr_take)?>
    </div>
    <?php
    paint_all_gift($profile_id);
}


function paint_gift_table($gift_arr){
    ?>
        <?php if(count($gift_arr) > 0):?>
            <table class="cute_table gift_table">
                <tr class="cute_table__header">
                    <th>Объявление</th>
                    <th>Пользователь</th>
                    <th>Действие</th>
                </tr>
                <?php foreach ($gift_arr as $gift):?>
                    <tr class="cute_table__item">
                        <td colspan='3'>
                            <table>
                                <tr class="cute_table__item__header gift_table__item__header">
                                    <td>
                                        <?php echo get_post_link_title($gift->get_post_id())?>
                                    </td>
                                    <td>
                                        <?php echo get_user_link_name(($gift->get_custumer_id() == um_profile_id()) ? $gift->get_admin_id() : $gift->get_custumer_id())?>
                                    </td>
                                    <td>
                                        <button class='open_chat'>Подробнее</button>
                                    </td>
                                </tr>
                                <?php if(get_field('type_gift', $gift->get_post_id()) == 'money'
                                        && get_field('cart_state', $gift->get_post_id()) == 'take'):?>
                                    <tr class='gift-money-meta'>
                                        <td colspan='3'>
                                            <table>
                                                <tr class='gift-money-meta__price-title'>
                                                    <td>
                                                        <p><?= __('Данные для перевода денег:', 'itroscope')?></p>
                                                    </td>
                                                </tr>
                                                <tr class='gift-money-meta__price-value'>
                                                    <td>
                                                        <p><?= get_field('gift_money_transaction', $gift->get_post_id())?></p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                <?php endif;?>
                                <tr class="cute_table__chat">
                                    <td colspan='3'>
                                        <table>
                                            <tr>
                                                <td><p class='table_big_text'>Чат</p></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <?php
                                                        $messages = $gift->get_chat()->get_messages();
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
                                                    <div class="gift_btns">
                                                        <button class="send_message_chat" 
                                                            data-chat-id="<?php echo $gift->get_chat()->get_id()?>"
                                                            data-post-id="<?php echo $gift->get_post_id()?>"
                                                            >Отправить</button>
                                                        <?php if($gift->get_confirm_first_id() == get_current_user_id()):?>
                                                            <p class="meeting_text--submited">Вы подтвердили, что всё прошло успешно. Ожидаем подтверждения другого пользователя</p>
                                                        <?php else:?>
                                                            <button class="confirm_gift" data-gift-id="<?php echo $gift->get_id()?>" 
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

function paint_all_gift($user_id){
    if(um_profile_id() == get_current_user_id()){
        echo "<h3>Ваши подарки:</h3>";
    }else{
        echo "<h3>Подарки этого пользователя:</h3>";
    }
    $query_time = new WP_Query([
        'author' => $user_id,
        'post_type' => 'gift',
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