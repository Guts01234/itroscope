<?php
/**
 * @package Sydney
 */
?>

<?php


//проверка а не урокл ли компании и имеет ли к нему доступ пользователь
$author_id = $post->post_author;
if(is_company($author_id)){

	$is_open = get_post_meta($post->ID, 'post_open_status', true);

	$company = new Company($author_id);

	if((!is_user_logged_in() || (!$company->check_user_in_members(get_current_user_id()) && $author_id != get_current_user_id()))
		&& $is_open !== 'open'
	){
		header('Location: /');
	}
}


$disable_title 					= get_post_meta( $post->ID, '_sydney_page_disable_title', true );
$disable_featured 				= get_post_meta( $post->ID, '_sydney_page_disable_post_featured', true );
$single_post_image_placement 	= get_theme_mod( 'single_post_image_placement', 'below' );
$single_post_meta_position		= get_theme_mod( 'single_post_meta_position', 'below-title' );

$post_id = get_the_ID();


$gift_state = get_field('cart_state');
$type_gift = get_field('type_gift');
$gift_money_sum = get_field('gift_money_sum');
$gift_product_type = get_field('gift_product_type');
$ozon_link = get_field('ozon_link');
$gift_product_price = get_field('gift_product_price');
$gift_image_url = get_field('gift_photo') ? wp_get_attachment_image_url( get_field('gift_photo') , 'full') : null;


// Получаем место встречи
$place = '';
if($gift_state == 'give'){
	$delivery_type = get_field('delivery_gift');
	if($delivery_type == 'selftake'){
		$place = 'Встреча по адресу: ' . get_field('gift_city') . ' ' . get_field('gift_address');

	}elseif($delivery_type == 'town'){
		$place = 'Встреча в городе ' . get_field('gift_city');
	}

}elseif($gift_state == 'take'){
	$delivery_take_type = get_field('delivery_gift_take');

	if($delivery_take_type == 'transfer'){
		$place = 'Оплачу пересылку';

	}elseif($delivery_take_type == 'town'){
		$place = 'Встреча в городе ' . get_field('gift_city');
	}
}


get_header();

?>

<?php do_action( 'sydney_before_single_entry' ); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php do_action('sydney_inside_top_post'); ?>

	<?php if ( 'above' === $single_post_image_placement ) : ?>
		<?php sydney_single_post_thumbnail( $disable_featured ); ?>
	<?php endif; ?>

	<?php if ( !$disable_title ) : ?>
	<header class="entry-header">
		<?php if ( 'post' === get_post_type() && 'above-title' === $single_post_meta_position ) : ?>
			<?php sydney_single_post_meta( 'entry-meta-above' ); ?>
		<?php endif; ?>

		<h1 class="title-post entry-title"><?php
		if($gift_state == 'give'){
            echo 'Отдам. ';
        }elseif($gift_state == 'take'){
            echo 'Получу. ';
        }

        if($gift_town){
            echo $gift_town . '. ';
        }

        echo get_the_title();

		 ?></h1>


		<?php if ( 'post' === get_post_type() && 'below-title' === $single_post_meta_position ) : ?>
			<?php sydney_single_post_meta( 'entry-meta-below' ); ?>
		<?php endif; ?>
	</header><!-- .entry-header -->
	<?php endif; ?>

	<?php if ( 'below' === $single_post_image_placement ) : ?>
		<!-- <?php sydney_single_post_thumbnail( $disable_featured ); ?> -->
	<?php endif; ?>

	<div class="entry-content" id='data_ajax' data-user_id=<?php echo get_current_user_id(); ?> data-id= <?php the_id(); ?>>

		<?php sydney_do_schema( 'entry_content' ); ?>

        <?php if($type_gift == 'money'):?>
            <div class="gift_type_money">
                <h3>Деньги</h3>
                <p>Сумма - <?php echo $gift_money_sum?>₽</p>
            </div>

        <?php elseif($type_gift == 'product'):?>
            <div class="gift_type_product">
                <?php if($gift_product_type == 'internet'):?>
                    <h3>Товар из интернета</h3>
                    <p><a href="<?php echo $ozon_link?>">Ссылка на товар</a></p>
                    <p>Примерная цена товара - <?php echo $gift_product_price?>₽</p>
                <?php elseif($gift_product_type == 'selftake'):?>
                    <h3>Товар (Самовывоз)</h3>
                    <p>Способ передачи: <?php echo $place?></p>
                    <p>Примерная цена товара - <?php echo $gift_product_price?>₽</p>
                <?php endif;?>
            </div>

			<?php if($gift_image_url):?>
				<div class="gift_img">
					<img src="<?=$gift_image_url?>" alt="Внешний вид подарка">
				</div>
			<?php endif;?>
        <?php endif;?>

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'sydney' ),
				'after'  => '</div>',
			) );

			do_action('btn_read_gift');
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
	</footer><!-- .entry-footer -->

	<?php do_action('sydney_inside_bottom_post'); ?>

</article><!-- #post-## -->

<?php do_action( 'sydney_after_single_entry' ); ?>

<div class="entry-content">
	<?php do_action('see_also3');?>
</div>


<?php
    get_footer();
?>
