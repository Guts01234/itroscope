<?php
/**
 * @package Sydney
 */

$post_id = get_the_ID();
$gift_state = get_field('cart_state');
$type_gift = get_field('type_gift');
$town_gift = get_field('gift_city');
$gift_product_type;
$sum;

if($type_gift == 'product'){
    $gift_product_type = get_field('gift_product_type');
}


if($type_gift == 'money'){
    $sum = get_field('gift_money_sum');
}else if($type_gift == 'product'){
    $sum = get_field('gift_product_price');
}

?>

<article  id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="container_l cart_stat cart_gift">
     <a href="<?php the_permalink(); ?>">
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
                    if($gift_state == 'give'){
                        echo 'Отдам. ';
                    }elseif($gift_state == 'take'){
                        echo 'Получу. ';
                    }

                    if($town_gift){
                        echo $town_gift . '. ';
                    }

                    echo get_the_title();

					?></h2>
				</header>
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
			 </div>
			 <div class="prices">
			 <?php
			 if($sum != '0'){
				 echo '<p class="old_price">' . $sum . '₽' . '</p>';
			 }
			 ?>
			 <p class='price_cart'>БЕСПЛАТНО</p>
				
			 </div>
		 </div>
   </div>

</article><!-- #post-## -->