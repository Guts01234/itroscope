<?php //Template name: Страница получения бонса ?>

<?php 

//проверка есть ли этот аргумент вообще и можно ли его раскодировать
if(!isset($_GET['post_id']) || !simple_decode($_GET['post_id'])){
    header('Location: /');
    die();
}

$post_id = simple_decode($_GET['post_id']);

//проверяем дальше, есть переданный id не урок, то тоже не подходит
if(get_post_type($post_id) != 'post'){
    header('Location: /');
    die();
}

$user_post_arr_readed = get_user_meta(get_current_user_id(), 'post_read_arr', true);
$posts_arr_pay = get_user_meta($user_id_read, 'posts_arr_pay', true);

//проверка, если мы уже получили по этому уроку плюхи, то редирект
if(is_array($user_post_arr_readed) && in_array($post_id, $user_post_arr_readed)
    || is_array($posts_arr_pay) && in_array($post_id, $posts_arr_pay)
){
    header('Location: /');
    die();
}

//проверка для доступа к урокам компании
$author_id = get_post_field( 'post_author', $post_id);
if(is_company($author_id)){
    
    $is_open = get_post_meta($post_id, 'post_open_status', true);
    
    $company = new Company($author_id);
    
    if((!is_user_logged_in() || (!$company->check_user_in_members(get_current_user_id()) && $author_id != get_current_user_id()))
    && $is_open !== 'open'
    ){
        header('Location: /');
        die();
    }
}


$bonus_url_redirect =  add_query_arg('post_id', $_GET['post_id'], get_permalink());

//редирект нужен только не для авторизованных пользователей
if(get_current_user_id() == 0){
    $_SESSION['bonus_redirect'] = $bonus_url_redirect;
}

?>


<?php get_header();?>


<div id="primary" class="content-area <?php echo esc_attr( apply_filters( 'sydney_content_area_class', $width ) ); ?>">
    <main id="main" class="post-wrap bonus_page" role="main">
        <?php if(get_current_user_id() == 0):?>
            <p><b>Wow!</b> Вы развили новый навык, но Вы не вошли в систему. Войдите или зарегистрируйтесь, чтобы отметить развитие навыка в вашем паспорте навыков</p>
            <a href="<?php echo wp_login_url($bonus_url_redirect); ?>" title="Войти">Войти</a>
        <?php else:?>
            <?php if($author_id == get_current_user_id()):?>
                <p>Вы автор текущего урока. Вы не можете получить навыки за свой урок</p>
            <?php else:?>
                <?php 
                    $terms;
                    $has_teory = get_post_meta($post_id, 'has_this_post_teory', true);
                    $has_practice = get_post_meta($post_id, 'has_this_post_homework', true);
                    $price = get_post_meta($post_id, 'post_homework_price', true); 
                    
                    $this_practice = 0;
                    if($has_teory === '0' && $has_practice == 1){
                        $this_practice = 1;
                    }
                ?>

                <?php if($price == 0):?>
                    <p id="message_free"></p>
                    <div id="success_block">
                        <p>Вам начислены баллы, продолжайте развиваться</p>
                        <p>Вы получили:</p>
                        <div class="terms_single_post">
                            <?php if($this_practice == 0): ?>

                                <div class="terms_teory data-plus-info">
                                    <p>Теория:</p>
                                    <?php
                                        $terms = wp_get_post_terms( $post_id, 'sfera');
                                        $c = count($terms);
                                    ?>
                                    <?php if ($c):?>
                                        <ul class='sfer'>
                                            <li><p class='name_sfer'>Сферы:</p></li>

                                            <?php foreach ($terms as $term):?>
                                                <li><p class='cute_sfer'><?php echo "+1 " . $term->name;?></p></li>
                                            <?php endforeach;?>
                                        </ul>
                                    <?php endif;?>
                                    
                                    <?php
                                        $terms  = wp_get_post_terms( $post_id, 'problem');
                                        $c = count($terms);
                                    ?>
                                    <?php if ($c):?>
                                        <ul class='sfer'>
                                            <li><p class='name_sfer'>Проблемы:</p></li>
                                            <?php foreach ($terms as $term):?>
                                                <li><p class='cute_problem'><?php echo "+1 " .$term->name;?></p></li>
                                            <?php endforeach?>
                                        </ul>
                                    <?php endif;?>

                                    <?php
                                        $terms  = wp_get_post_terms( $post_id, 'navik');
                                        $c = count($terms);
                                    ?>
                                    <?php if ($c):?>
                                        <ul class='sfer'>
                                            <li><p class='name_sfer'>Навыки:</p></li>
                                            <?php foreach ($terms as $term):?>
                                                <li><p class='cute_navik'><?php echo "+1 " .$term->name;?></p></li>
                                            <?php endforeach?>
                                        </ul>
                                    <?php endif;?>
                                </div>
                            <?php endif;?>
    
                            <?php if($this_practice == 1):?>
                                <div class="terms_practice data-plus-info">
                                    <p>Практика:</p>
                                    <?php
                                        $terms = get_post_meta( $post_id, $key = 'sfer_practice', $single = true );
                                        $terms = explode(',', $terms);
                                        $c = count($terms);
                                    ?>
                                    <?php if ($c):?>
                                        <ul class='sfer'>
                                            <li><p class='name_sfer'>Сферы:</p></li>
                                            <?php foreach ($terms as $term):?>
                                                <li><p class="cute_sfer"><?php echo "+1 " . $term->name;?></p></li>
                                            <?php endforeach;?>
                                        </ul>
                                    <?php endif;?>
                                    <?php
                                        $terms = get_post_meta( $post_id, $key = 'problem_practice', $single = true );
                                        $terms = explode(',', $terms);
                                        $c = count($terms);
                                    ?>
                                    <?php if ($c):?>
                                        <ul class='sfer'>
                                            <li><p class='name_sfer'>Проблемы:</p></li>
                                            <?php foreach ($terms as $term):?>
                                                <li><p class='cute_problem'><?php echo "+1 " . $term;?></p></li>
                                            <?php endforeach;?>
                                        </ul>
                                    <?php endif;?>
                                    <?php
                                        $terms = get_post_meta( $post_id, $key = 'navik_practice', $single = true );
                                        $terms = explode(',', $terms);
                                        $c = count($terms);
                                    ?>
                                    <?php if ($c):?>
                                        <ul class='sfer'>
                                            <li><p class='name_sfer'>Навыки:</p></li>
                                            <?php foreach ($terms as $term):?>
                                                <li><p class='cute_navik'><?php echo "+1 " . $term;?></p></li>
                                            <?php endforeach;?>
                                        </ul>
                                    <?php endif;?>
                                </div>
                            <?php endif;?>
                        </div>
                    </div>

                <?php else:?>
                    <p>Данный урок платный,чтобы получить все навыки, пожалуйста, оплатите его</p>
                    <p>Цена: <span><b><?php echo $price?> ₽</b></span></p>
                    <p id="place_for_link">Загрузка ссылки на оплату...</p>
                <?php endif;?>

            <?php endif;?>
            
        <?php endif;?>
    </main>
</div>

<script>
    var bonus_post_id = '<?php echo $_GET['post_id'] ?>' <?php echo "\n" ?>;
    var bonus_user_id = <?php echo get_current_user_id()?> <?php echo "\n" ?>
    var bonus_this_practice = <?php echo $this_practice?> <?php echo "\n" ?>
    var bonus_need_pay = <?php echo ($price == 0) ? ('false') : ('true')?> <?php echo "\n" ?>
</script>





<?php get_footer();?>