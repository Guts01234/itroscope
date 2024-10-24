<?php 

?>

<div class="user_search_form_container">
    <?php echo do_shortcode('[search_user input_id="user_invate_in_company"]');?>
    <button id="send_user_invation" data-company-id="<?php echo get_current_user_id()?>">Отправить приглашение в компанию</button>
</div>
