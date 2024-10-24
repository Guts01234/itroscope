<?php
/**
 * @package Sydney
 */
?>

<article  id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	 <div class="container_l cart_stat cart_marathon">
     <a href="<?php the_permalink(); ?>">
			 <?php
			 		$navik_list = wp_get_post_terms( get_the_ID(), 'sfera', array('fields' => 'names') );
					?>
          <div class="sfer_lent_m">
            <!-- <p class='name_navik_t'>Навыки:</p> -->
            <?php  foreach ($navik_list as $key => $name) {
              ?>

              <div class="sfet_up_m">
                <p><?php echo $name; ?></p>
              </div>

              <?php
            } ?>
          </div>
					<?php
			  ?>
       <div class="cont_img"><?php the_post_thumbnail(); ?></div>
			 <div class='content_cart'>
       <header class="entry-header"><h2 class="title-post entry-title"><?php
			 if (get_the_author() !='admin'){
				 echo get_the_author() . '. '.  get_the_title();
			 }
			 else{
				 echo get_the_title();
			 }

			  ?></h2></header>

       <?php $navik_list = wp_get_post_terms( get_the_ID(), 'navik', array('fields' => 'names') );
       if(count($navik_list)){
          ?>
          <div class="naviks_lent">
            <!-- <p class='name_navik_t'>Навыки:</p> -->
            <?php  foreach ($navik_list as $key => $name) {
              ?>

              <div class="navik">
                <p><?php echo $name; ?></p>
              </div>

              <?php
            } ?>
          </div>
          <?php

       }
       ?>

       <?php $problem_list = wp_get_post_terms( get_the_ID(), 'problem', array('fields' => 'names') );
       if(count($problem_list)){
          ?>
          <div class="naviks_lent">
            <!-- <p class='name_navik_t'>Проблемы:</p> -->
            <?php  foreach ($problem_list as $key => $name) {
              ?>

              <div class="navik ploblem_stat">
                <p><?php echo $name; ?></p>
              </div>

              <?php
            } ?>
          </div>
          <?php

       }
       ?>
       <div class="entry-post"><?php the_excerpt()  ?></div>

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
			 </div>
			 <p class='price_cart'><?php
			 if(get_post_meta(get_the_id(), 'post_homework_price')[0] == '0'){
				 echo 'БЕСПЛАТНО';
			 }else{
				 echo get_post_meta(get_the_id(), 'post_homework_price')[0] . 'P';
			 }
			 ?></p>
		 </div>
   </div>

<style media="screen">
	.page-wrap{
		padding-top: 10px;
		padding-bottom: 10px;
	}
	@media only screen and (max-width: 768px){
		.page-wrap{
			margin: 0 10px;
		}
	}
</style>
</article><!-- #post-## -->
