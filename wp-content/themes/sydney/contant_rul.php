<?php
/**
 * @package Sydney
 */
?>

<article  id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	 <div class="container_rul">
     <a href="<?php the_permalink(); ?>">
			 <?php
			 		$sfer = $navik_list = wp_get_post_terms( get_the_ID(), 'sfera', array('fields' => 'names') );
			  ?>
       <div class="cont_img">
         <?php echo_cute_sfer($sfer[0], $sfer[0], 'sfer--up');?>
         <?php 
					$author_avatar =  get_avatar_url(get_the_author_meta('ID'));
					if($author_avatar && !strripos($author_avatar, 'default')){
						echo "<img src='{$author_avatar}' alt='author'>";
					}else{
						the_post_thumbnail(); 
					}
					?>
         </div>
       <div class="right_contant_rul">
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
       </div>
     </a>
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
