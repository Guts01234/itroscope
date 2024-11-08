<?php
/*
Template Name: Страница заявок на вывод средств
Template Post Type: post, page
*/


$args = array(
	'post_type' => 'zayavka',
	'posts_per_page' => -1
);

$zayavki = new WP_Query($args);

get_header();
?>
<style>
	.zayaka-post {
		border: 1px solid #ccc;
		padding: 10px;
		margin-bottom: 10px;
	}

	.post-title {
		font-size: 1.2em;
		margin-bottom: 5px;
	}
</style>
<h3 style="text-align: center;">Заявки на вывод средств</h3>
<?php
if ($zayavki->have_posts()) : ?>

	<?php while ($zayavki->have_posts()) : $zayavki->the_post();
		$price = get_post_meta($post->ID, 'zayavka_price', true);
		$card = get_post_meta($post->ID, 'zayavka_card', true);
	?>

		<div class="zayaka-post">
			<h2 class="post-title"><?php the_title(); ?> </h2>
			<p class="post-date">Дата создания заявки: <?php echo get_the_date('H:i j-n-Y'); ?></p>
			<p class="card-number">Номер карты пользователя: <?php echo $card ?></p>
			<p class="price">Сумма вывода: <?php echo $price ?></p>
			<button class="delete-button" data-post-id="<?php echo get_the_ID(); ?>">Подтвердить</button>
		</div>

	<?php endwhile; ?>
<?php endif;

wp_reset_postdata();
?>




<?php get_footer(); ?>