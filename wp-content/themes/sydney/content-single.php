<?php

/**
 * @package Sydney
 */
?>

<?php


//проверка а не урокл ли компании и имеет ли к нему доступ пользователь
$author_id = $post->post_author;
if (is_company($author_id)) {

	$is_open = get_post_meta($post->ID, 'post_open_status', true);

	$company = new Company($author_id);

	if ((!is_user_logged_in() || (!$company->check_user_in_members(get_current_user_id()) && $author_id != get_current_user_id()))
		&& $is_open !== 'open'
	) {
		header('Location: /');
	}
}


//youtube
require get_template_directory() . '/kwork_addons/youtube/template_youtube.php';

$disable_title 					= get_post_meta($post->ID, '_sydney_page_disable_title', true);
$disable_featured 				= get_post_meta($post->ID, '_sydney_page_disable_post_featured', true);
$single_post_image_placement 	= get_theme_mod('single_post_image_placement', 'below');
$single_post_meta_position		= get_theme_mod('single_post_meta_position', 'below-title');

$post_id = get_the_ID();

$has_teory = get_post_meta(get_the_ID(), $key = 'has_this_post_teory', $single = true);
$has_practice = get_post_meta(get_the_ID(), $key = 'has_this_post_homework', $single = true);


//проверяем показывать как теорию или как практику
$this_practice = 0;
if ($has_teory === '0' && $has_practice == 1) {
	$this_practice = 1;
}

$cart_state = get_post_meta($post_id, 'cart_state', true);

?>

<?php do_action('sydney_before_single_entry'); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php do_action('sydney_inside_top_post'); ?>

	<?php if ('above' === $single_post_image_placement) : ?>
		<?php sydney_single_post_thumbnail($disable_featured); ?>
	<?php endif; ?>

	<?php if (!$disable_title) : ?>
		<header class="entry-header">
			<?php if ('post' === get_post_type() && 'above-title' === $single_post_meta_position) : ?>
				<?php sydney_single_post_meta('entry-meta-above'); ?>
			<?php endif; ?>

			<h1 class="title-post entry-title"><?php
																					if ($this_practice) {
																						echo 'Практика. ';
																					}
																					if (get_the_author() != 'admin' && get_the_author() != '') {
																						echo get_the_author() . '. ' .  get_the_title();
																					} else {
																						echo get_the_title();
																					}

																					?></h1>



			<?php if ('post' === get_post_type() && 'below-title' === $single_post_meta_position) : ?>
				<?php sydney_single_post_meta('entry-meta-below'); ?>
			<?php endif; ?>
		</header><!-- .entry-header -->
	<?php endif; ?>

	<?php if ('below' === $single_post_image_placement) : ?>
		<!-- <?php sydney_single_post_thumbnail($disable_featured); ?> -->
	<?php endif; ?>

	<div class="entry-content" id='data_ajax' data-user_id=<?php echo get_current_user_id(); ?> data-id=<?php the_id(); ?> data-sfer="<?php
																																																																		$terms;
																																																																		$post_id =  get_the_ID();
																																																																		if ($this_practice == 1) {
																																																																			$terms = get_post_meta($post_id, $key = 'sfer_practice', $single = true);
																																																																			// echo $sfer;
																																																																			$terms = explode(',', $terms);
																																																																		} else {
																																																																			$terms  = wp_get_post_terms(get_the_ID(), 'sfera');
																																																																		}
																																																																		$c = count($terms);
																																																																		if ($c) {
																																																																			foreach ($terms as $term) {
																																																																				if ($this_practice == 1) {
																																																																					echo $term . ';';
																																																																				} else {
																																																																					echo $term->name . ';';
																																																																				}
																																																																			}
																																																																		} else {
																																																																			echo 'none;';
																																																																		}

																																																																		?>" data-problem="<?php
																																																																											if ($this_practice == 1) {
																																																																												$terms = get_post_meta($post_id, $key = 'problem_practice', $single = true);
																																																																												// echo $sfer;
																																																																												$terms = explode(',', $terms);
																																																																											} else {
																																																																												$terms  = wp_get_post_terms(get_the_ID(), 'problem');
																																																																											}
																																																																											$c = count($terms);
																																																																											if ($c) {
																																																																												foreach ($terms as $term) {
																																																																													if ($this_practice == 1) {
																																																																														echo $term . ';';
																																																																													} else {
																																																																														echo $term->name . ';';
																																																																													}
																																																																												}
																																																																											} else {
																																																																												echo 'none;';
																																																																											}

																																																																											?>" data-navik="<?php
																																																																																			if ($this_practice == 1) {
																																																																																				$terms = get_post_meta($post_id, $key = 'navik_practice', $single = true);
																																																																																				// echo $sfer;
																																																																																				$terms = explode(',', $terms);
																																																																																			} else {
																																																																																				$terms  = wp_get_post_terms(get_the_ID(), 'navik');
																																																																																			}
																																																																																			$c = count($terms);
																																																																																			if ($c) {
																																																																																				foreach ($terms as $term) {
																																																																																					if ($this_practice == 1) {
																																																																																						echo $term . ';';
																																																																																					} else {
																																																																																						echo $term->name . ';';
																																																																																					}
																																																																																				}
																																																																																			} else {
																																																																																				echo 'none;';
																																																																																			}
																																																																																			?>"
		<?php sydney_do_schema('entry_content'); ?>
		<?php do_action('post_before_cont_kw') ?>
		<?php
		$youtube_video_url = get_post_meta($post_id, $key = 'youtube_href', $single = true);
		if ($youtube_video_url) {
			echoYouTubeFrame($youtube_video_url);
		}
		?>
		<?php
		if ($this_practice) {
			echo get_post_meta($post_id, $key = 'post_homework', $single = true);
		} else {
			the_content();
		}

		if (get_post_type() === 'service') {
			get_template_part('kwork_addons/modules/payment/pay', 'widget');
		}
		?>

		<?php
		wp_link_pages(array(
			'before' => '<div class="page-links">' . __('Pages:', 'sydney'),
			'after'  => '</div>',
		));

		if ($cart_state != 'take') {
			do_action('btn_read');
		} else {
			do_action('btn_read_lesson_take');
		}

		?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
		</footer><!-- .entry-footer -->

		<?php do_action('sydney_inside_bottom_post'); ?>


</article><!-- #post-## -->
<?php do_action('sydney_after_single_entry'); ?>
<div class="entry-content">
	<?php do_action('see_also3'); ?>
</div>
<style media="screen">
	p {
		margin-bottom: 0 !important;
	}
</style>