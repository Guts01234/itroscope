<?php

/**
 * @package Sydney
 */

$post_id = get_the_ID();
$services_price = get_field('time_how_much');

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="container_l cart_stat cart_services">
		<a href="<?php the_permalink(); ?>">
			<?php
			$sfer = wp_get_post_terms(get_the_ID(), 'sfera', array('fields' => 'names'));
			echo_cute_sfer($sfer[0], $sfer[0], 'sfer--up sfer--up__teor');
			?>
			<div class='content_cart'>
				<div class="content_cart__up">
					<div class="cont_img">
						<?php
						$author_avatar =  get_avatar_url(get_the_author_meta('ID'));
						if ($author_avatar && !strripos($author_avatar, 'default')) {
							echo "<img src='{$author_avatar}' alt='author'>";
						} elseif (get_the_post_thumbnail()) {
							the_post_thumbnail();
						} else {
							echo "<img src='https://cdn-icons-png.flaticon.com/512/3593/3593455.png' alt='placeholder'>";
						}
						?>
					</div>
					<header class="entry-header cart-title">
						<h2 class="title-post entry-title"><?php
																								if ($this_practice) {
																									echo 'Практика. ';
																								}
																								if (get_the_author() != 'admin' && get_the_author() != '') {
																									echo get_the_author() . '. ' .  get_the_title();
																								} else {
																									echo get_the_title();
																								}

																								?></h2>
					</header>
				</div>

				<div class="naviks_and_problems">
					<div class="naviks_and_problems__teor">
						<div class="naviks">
							<?php
							$navik_list = wp_get_post_terms(get_the_ID(), 'navik', array('fields' => 'names'));
							if (count($navik_list)):
							?>
								<p class="navik_uptitle">Навык</p>
								<div class="naviks_lent">
									<?php foreach ($navik_list as $key => $name) {
									?>
										<div class="navik">
											<p><?php echo $name; ?> <b>+1</b></p>
										</div>
									<?php
									} ?>
								</div>
							<?php endif; ?>
						</div>
						<div class="propblems">
							<?php
							$problem_list = wp_get_post_terms(get_the_ID(), 'problem', array('fields' => 'names'));
							if (count($problem_list)):
							?>
								<p class="propblems_uptitle">Проблема</p>
								<div class="naviks_lent">
									<?php foreach ($problem_list as $key => $name) {
									?>
										<div class="navik ploblem_stat">
											<p><?php echo $name; ?> <b>-1</b></p>
										</div>
									<?php
									} ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</a>
		<div class='stat_read_post_cart'>
			<div>
				<span><img src="<?php echo get_template_directory_uri(); ?>/images/person.svg" title='Прочитали' alt="Просмотрено"></span>
				<span><?php
							$user_scroll = get_post_meta(get_the_ID(), $key = 'users_scroll')[0];
							if (!isset($user_scroll)) {
								$user_scroll = 0;
							}
							echo $user_scroll;

							?></span>
				<span><img src="<?php echo get_template_directory_uri(); ?>/images/money.svg" title='Купили' alt="Добавлено в паспорт"></span>
				<span><?php
							$users_buy = get_post_meta(get_the_ID(), $key = 'users_buy')[0];
							if (!isset($users_buy)) {
								$users_buy = 0;
							}
							echo $users_buy;
							?></span>
			</div>
			<p class='price_cart'>
				<?php
				$services_price
				?>
			</p>
		</div>
	</div>

</article><!-- #post-## -->