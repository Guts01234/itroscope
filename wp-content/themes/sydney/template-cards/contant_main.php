<?php
/**
 * @package Sydney
 */

$post_id = get_the_ID();
 $has_teory = get_post_meta(get_the_ID(), $key = 'has_this_post_teory', $single = true);
 $has_practice = get_post_meta(get_the_ID(), $key = 'has_this_post_homework', $single = true);

if(!$has_teory){
	$navik_list = wp_get_post_terms( get_the_ID(), 'navik', array('fields' => 'names') );
	$has_teory = count($navik_list) > 0 ? 1 : 0;
}
if($has_practice){
	$navik_list = get_post_meta( $post_id, $key = 'navik_practice', $single = true );
	$navik_list = explode(',', $navik_list);
	$navik_list = array_filter($navik_list, 'filter_empty_tax');
	$has_practice = count($navik_list) > 0 ? 1 : 0;
}


//проверяем показывать как теорию или как практику
 $this_practice = 0;
 if($has_teory === '0' && $has_practice == 1){
	 $this_practice = 1;
 }
 $cart_state = get_field('cart_state');
 $sum; 
 if($cart_state == 'take'){
	 $sum = get_post_meta(get_the_id(), 'post_price_take', true);
 }else{
	$sum = get_post_meta(get_the_id(), 'post_homework_price', true);
 }

 if($sum == '0' || !$sum){
	$sum = 'БЕСПЛАТНО';
 }else{
	$sum .= ' ₽';
 }
?>



<article  id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="container_l cart_stat cart_lesson">
     <a href="<?php the_permalink(); ?>">
		<?php
			$sfer;

			if($has_practice == 1 && get_post_meta( $post_id, $key = 'sfer_practice', $single = true ) != '-1'){
				$sfer = get_post_meta( $post_id, $key = 'sfer_practice', $single = true );
				$sfer = explode(',', $sfer);
				echo_cute_sfer($sfer[0], $sfer[0], 'sfer--up sfer--up__practic');
			}

			if($has_teory == 1){
				$sfer = wp_get_post_terms( get_the_ID(), 'sfera', array('fields' => 'names') );
				echo_cute_sfer($sfer[0], $sfer[0], 'sfer--up sfer--up__teor');
			}
		?>
		<div class='content_cart'>
			<div class="content_cart__up">
				<div class="cont_img">
					<?php 
					$author_avatar =  get_avatar_url(get_the_author_meta('ID'));
					if($author_avatar && !strripos($author_avatar, 'default')){
						echo "<img src='{$author_avatar}' alt='author'>";
					}elseif(get_the_post_thumbnail()){
						the_post_thumbnail(); 
					}else{
                        echo "<img src='https://cdn-icons-png.flaticon.com/512/3593/3593455.png' alt='placeholder'>";
                    }
					?>
				</div>
				<header class="entry-header cart-title"><h2 class="title-post entry-title"><?php
					if($this_practice){
						echo 'Практика. ';
					}
					if (get_the_author() !='admin' && get_the_author() != ''){
						echo get_the_author() . '. '.  get_the_title();
					}
					else{
						echo get_the_title();
					}

					?></h2>
				</header>
			</div>

      		<div class="naviks_and_problems">
				<div class="naviks_and_problems__teor">
					<?php if($has_teory == 1):?>
						<p class="cart_punkt_title">Теория</p>
						<div class="naviks">
							
							<?php
							$navik_list = wp_get_post_terms( get_the_ID(), 'navik', array('fields' => 'names') );
							if(count($navik_list)):
								?>
								<p class="navik_uptitle">Навык</p>
								<div class="naviks_lent">
									<?php  foreach ($navik_list as $key => $name) {
									?>
									<div class="navik">
										<p><?php echo $name; ?> <b>+1</b></p>
									</div>
									<?php
									} ?>
								</div>
							<?php endif;?>
						</div>
						<div class="propblems">
							<?php
							$problem_list = wp_get_post_terms( get_the_ID(), 'problem', array('fields' => 'names') );
							if(count($problem_list)):
								?>
								<p class="propblems_uptitle">Проблема</p>
								<div class="naviks_lent">
									<?php  foreach ($problem_list as $key => $name) {
									?>
									<div class="navik ploblem_stat">
										<p><?php echo $name; ?> <b>-1</b></p>
									</div>
									<?php
									} ?>
								</div>
							<?php endif;?>
						</div>
			  		<?php endif?>
				</div>
				<div class="naviks_and_problems__practic">
					<?php if($has_practice == 1):?>
						<p class="cart_punkt_title">Практика</p>
						<div class="naviks">
							<?php
							$navik_list = get_post_meta( $post_id, $key = 'navik_practice', $single = true );
							$navik_list = explode(',', $navik_list);
							if(count($navik_list)):?>
								<p class="navik_uptitle">Навык</p>
								<div class="naviks_lent">
									<?php  foreach ($navik_list as $key => $name) {
									?>
									<div class="navik">
										<p><?php echo $name; ?> <b>+1</b></p>
									</div>
									<?php
									} ?>
								</div>
							<?php endif;?>
						</div>
						<div class="propblems">
							<?php
							$problem_list = get_post_meta( $post_id, $key = 'problem_practice', $single = true );
							$problem_list = explode(',', $problem_list);
							if(count($problem_list)):?>
								<p class="propblems_uptitle">Проблема</p>
								<div class="naviks_lent">
									<?php  foreach ($problem_list as $key => $name) {
									?>
									<div class="navik ploblem_stat">
										<p><?php echo $name; ?> <b>-1</b></p>
									</div>
									<?php
									} ?>
								</div>
							<?php endif;?>
						</div>
					<?php endif?>
				</div>
			</div>
	    </div>
     </a>
		 <div class='stat_read_post_cart'>
			 <div>
				 <span><img src="<?php echo get_template_directory_uri(); ?>/images/person.svg" title='Прочитали' alt="Просмотрено"></span>
   			<span><?php
   			$user_scroll = get_post_meta( get_the_ID(), $key = 'users_scroll')[0];
   			if(!isset($user_scroll)){
   				$user_scroll = 0;
   			}
   			echo $user_scroll;

   			 ?></span>
   			<span><img src="<?php echo get_template_directory_uri(); ?>/images/money.svg" title='Купили' alt="Добавлено в паспорт"></span>
   			<span><?php
   			$users_buy = get_post_meta( get_the_ID(), $key = 'users_buy')[0];
   			if(!isset($users_buy)){
				   $users_buy = 0;
				}
				echo $users_buy;
   			 ?></span>
			 <?php 
			 	$video_dur = get_post_meta( $post_id, $key = 'youtube_status_vidio', $single = true );
				 if($video_dur){
					 if($video_dur == 'long'){
						?>
						<span><img src="<?php echo get_template_directory_uri(); ?>/images/youtube_long.svg" title='Длинное видео' alt="Длинное видео"></span>
						<?php 						
					}else if($video_dur == 'short'){
						?>
						<span><img src="<?php echo get_template_directory_uri(); ?>/images/youtube_short.svg" title='Короткое видео' alt="Короткое видео"></span>
						<?php 						
					}
				}
			 ?>
			 </div>
			 <p class='price_cart'><?php echo $sum?></p>
		 </div>
   </div>

</article><!-- #post-## -->
