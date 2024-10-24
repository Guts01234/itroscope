
    <div class="notification <?php if($notification->get_readed_status()){
        echo 'notification__readed';
    } 
     else{
        echo 'notification__not_readed';
     }?>  " data-id="<?php echo $notification->get_id()?>">
        <div class="notification__title">
            <p class='time'>
                <?php echo date("G:i d.m.Y", $notification->get_date() + 60*60*3)?>
            </p>
            <p class="title">
                    <?php echo $notification->get_title();?>
            </p>
        </div>    
        <div class="notification__content">
            <?php echo $notification->get_content();?>
        </div>
    </div>

