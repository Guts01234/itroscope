<?php 
    add_action('kw_notification', 'the_notification_block');
    add_action('kw_notification_mobile', 'the_notification_block');

    function the_notification_block(){
        $current_user_id = get_current_user_id();

        if(!$current_user_id) return;

        $notifications = NotificationDB::get_user_notification($current_user_id);

        if(!$notifications) $notifications = array();
        $count = 0;

        for ($i=0; $i < count($notifications) ; $i++) { 
            if(!$notifications[$i]->get_readed_status()) $count++;
        }

        if($count > 9){
            $count = '9+';
        }

        ?>
            <div class="notification__block">
                <div class="cont_img">
                  <img src="<?php echo get_site_url() . '/wp-content/themes/sydney/kwork_addons/modules/notifications/assets/img/icon.png'?>" alt="notification">
                  <?php if($count):?>
                    <span class="notification__count <?php if($count) echo 'count--active'?>"><?php echo $count?></span>
                  <?php endif;?>
                </div>
                <div class="notifications_row">
                    <?php 
                    if(count($notifications)):
                        
                        foreach ($notifications as $notification) {
                            require get_template_directory() . '/kwork_addons/modules/notifications/templates/mini_notification.php';
                        }

                    else:?>
                        <div class="notification">
                            <p>Уведомлений пока нет :(</p>
                        </div>
                    <?php endif;?>
                </div>
            </div>
        <?php
    }
?>