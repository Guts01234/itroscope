<?php 
if ( ! defined( 'ABSPATH' ) ) exit;

function um_exp_add_tab( $tabs ) {

	$tabs[ 'exp' ] = array(
		'name'   => 'Опыт',
		'icon'   => 'um-faicon-smile-o',
		'custom' => true
	);

	UM()->options()->options[ 'profile_tab_' . 'exp' ] = true;

	return $tabs;
}



function um_profile_content_exp_default( $args ) {
	$user_id = um_profile_id();
	table_exp_know($user_id);
	table_exp_can($user_id);
	table_exp_learn($user_id);
	table_give_gift($user_id);
	table_give_time($user_id);
	table_take_gift($user_id);
	table_take_time($user_id);
}



/*
* БЛОК ТАБЛИЦА ОПЫТА НАЧАЛО
*/

function table_exp_know($user_id){
	$arr_posts = get_user_meta($user_id, 'post_read_arr')[0];
	?>
	<div class="exp_title_show">
		<h3>Знаю:</h3>
		<button class="exp_title_show__button">Показать</button>
	</div>
	<?php  
		if (!is_array($arr_posts)){
			echo '<p class="exp__not_found">Начните изучать уроки и получать задания  от экспертов</p>';
			return;
		} 
	?>
	<div class="cute_table_container exp__cute_table_container">
		<table class="cute_table">
			<tr class="cute_table__header">
				<td>Урок</td>
				<td>Сфера</td>
				<td>Какие навыки узнал как развивать</td>
				<td>Какие проблемы узнал как решать</td>
				<td>Статус задания</td>
			</tr>
			<?php
				foreach ($arr_posts as $post_id) {
					?>
					<tr>
						<td>
							<a href="<?php echo get_the_permalink($post_id) ?>">
							<?php echo get_the_title($post_id);?>
							</a>
						</td>
						<td>
							<?php $sfers = wp_get_post_terms($post_id, 'sfera');
								foreach ($sfers as $sfer) {
								echo	'<p class="cute_sfer">' .  $sfer->name . '</p>';
								}
							?>
						</td>
						<td>
							<?php $naviks = wp_get_post_terms($post_id, 'navik');
								foreach ($naviks as $navik) {
									echo '<p class="cute_navik">' . $navik->name . '</p>';
								}
							?>
						</td>
						<td>
							<?php $problems = wp_get_post_terms($post_id, 'problem');
								foreach ($problems as $problem) {
									echo '<p class="cute_problem">' . $problem->name . '</p>';
								}
							?>
						</td>
						<td>
							<?php
								$cart_state = get_post_meta($post_id, 'cart_state', true);
								if($cart_state == 'take'){
									$learner_id = LessonDb::get_lesson_meeting_admin($user_id, $post_id, 'finished')[0]->custumer_id;
									echo 'Вас обучил ' . get_user_link_name($learner_id);
								}else{

									$has_this_post_homework = get_post_meta($post_id, 'has_this_post_homework');
									if($has_this_post_homework){
										$homework_arr_paied  = get_user_meta($user_id, 'homework_arr_paied')[0];
											if(!is_array($homework_arr_paied)){
												$homework_arr_paied = array();
											}
											if(in_array($post_id, $homework_arr_paied) || get_post_meta($post_id, 'post_homework_price')[0] == '0'){
												echo '<b class="hw_done">Оплачено</b>';
											}else{
												echo '<b class="hw_redoing">Есть домашнее задание</b>';
											}
									}else{
										echo '<b>Нет задания</b>';
									}
								}
							?>
						</td>
					</tr>
					<?php
				}
			?>
		</table>
	</div>
	
	<?php
}

function table_exp_can($user_id){
	$arr_posts = get_user_meta($user_id, 'user_practice_done_posts_arr')[0];
	?>
	<div class="exp_title_show">
		<h3>Умею:</h3>
		<button class="exp_title_show__button">Показать</button>
	</div>
	<?php  
		if (!is_array($arr_posts)){
			echo '<p class="exp__not_found">Начните изучать уроки и получать задания  от экспертов</p>';
			return;
		}
	?>
	<div class="cute_table_container exp__cute_table_container">	
		<table class="cute_table">
			<tr class="cute_table__header">
				<td>Задание</td>
				<td>Какой навык прокачал</td>
				<td>Какую проблему решил</td>
			</tr>
			<?php
				foreach ($arr_posts as $post_id) {
					$has_teory = get_post_meta($post_id, $key = 'has_this_post_teory', $single = true);
					$has_practice = get_post_meta($post_id, $key = 'has_this_post_homework', $single = true);


				//проверяем показывать как теорию или как практику
					$this_practice = 0;
					if($has_teory === '0' && $has_practice == 1){
						$this_practice = 1;
					}
					?>
					<tr>
						<td><?php echo get_post_meta($post_id, 'post_homework', true); ?></td>
						<td>
							<?php
							$problems;
							if($this_practice == 1){
								$problems = get_post_meta( $post_id, $key = 'problem_practice', $single = true );
								$problems = explode(',', $problems);
							}else{
								$problems = wp_get_post_terms($post_id, 'problem');
							}
								foreach ($problems as $problem) {
									echo  '<p class="cute_problem">';
									if($this_practice == 1){
										echo $problem;
									}
									else{
										echo $problem->name;
									}
									echo '</p>';
								}
							?>
						</td>
						<td>
							<?php
							if($this_practice == 1){
								$naviks = get_post_meta( $post_id, $key = 'navik_practice', $single = true );
								$naviks = explode(',', $naviks);
							}else{
								$naviks = wp_get_post_terms($post_id, 'navik');
							}
								foreach ($naviks as $navik) {
									echo  '<p class="cute_navik">';
									if($this_practice == 1){
										echo $navik;
									}
									else{
										echo $navik->name;
									}
									echo '</p>';
								}
							?>
						</td>
					</tr>
					<?php
				}
			?>
		</table>

	</div>
	
	<?php
}

function table_exp_learn($user_id){
	global $wpdb;
	$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}homeworks WHERE teacher_id = {$user_id} AND status_homework = 'Проверено'", OBJECT );
	$results_time = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}homeworks WHERE nastavnik_id = {$user_id} AND status_homework = 'Проверено' AND man_last_action = 'nastavnik'", OBJECT );
	$results_take_lesson = LessonDb::get_all_lesson_meeting_custumer_finished($user_id);
	$results = array_merge($results, $results_time, $results_take_lesson);
	?>
	<div class="exp_title_show">
		<h3>Научил:</h3>
		<button class="exp_title_show__button">Показать</button>
	</div>

	<?php
	if(!$results){
			echo '<p class="exp__not_found" >Вы ещё никого не научили</p>';
			return;
		} 
	?>
	<div class="cute_table_container exp__cute_table_container">
		<table class='cute_table'>
			<tr class='cute_table__header'>
				<td>Псевдоним ученика</td>
				<td>Урок</td>
				<td>Город ученика</td>
				<td>Какую проблему помог решить</td>
				<td>Какой навык развил</td>
			</tr>
			<?php
				foreach ($results as $row) {
					$post_id = $row->post_id;
					$has_teory = get_post_meta($post_id, $key = 'has_this_post_teory', $single = true);
					$has_practice = get_post_meta($post_id, $key = 'has_this_post_homework', $single = true);
					$user_id;
					if($row->user_id){
						$user_id = $row->user_id;
					}elseif ($row->admin_id) {
						$user_id = $row->admin_id;
					}


				//проверяем показывать как теорию или как практику
					$this_practice = 0;
					if($has_teory === '0' && $has_practice == 1){
						$this_practice = 1;
					}
					?>
					<tr>
						<td>
							<?php echo get_user_link_name($user_id)?>
						</td>
						<td>
							<a href="<?php echo get_the_permalink($row->post_id) ?>">
							<?php echo get_the_title($row->post_id);?>
							</a>
						</td>
						<td>
							<?php
								if(get_user_meta($user_id, 'user_towm_meta')){
									echo get_user_meta($user_id, 'user_towm_meta')[0];
								}else{
									echo 'Город не указан';
								}
							?>
						</td>
						<td>
							<?php
							$problems;
							if($this_practice == 1){
							$problems = get_post_meta( $post_id, $key = 'problem_practice', $single = true );
							$problems = explode(',', $problems);
						}else{
								$problems = wp_get_post_terms($row->post_id, 'problem');
							}
								foreach ($problems as $problem) {
									echo  '<p class="cute_problem">';
									if($this_practice == 1){
										echo $problem;
									}
									else{
										echo $problem->name;
									}
									echo '</p>';
								}
							?>
						</td>
						<td>
							<?php
							if($this_practice == 1){
							$naviks = get_post_meta( $post_id, $key = 'navik_practice', $single = true );
							$naviks = explode(',', $naviks);
						}else{
								$naviks = wp_get_post_terms($row->post_id, 'navik');
							}
								foreach ($naviks as $navik) {
									echo  '<p class="cute_navik">';
									if($this_practice == 1){
										echo $navik;
									}
									else{
										echo $navik->name;
									}
									echo '</p>';
								}
							?>
						</td>
					</tr>
					<?php
				}
			?>
		</table>
	</div>
	<?php
}

/*
* БЛОК ТАБЛИЦА ОПЫТА КОНЕЦ
*/


/*
* Уделил времени
*/
function table_give_time($user_id){
	global $wpdb;
	
	$cutumers_times = TimeDb::get_all_time_meeting_user_finished($user_id);
	$cutumers_times_filter = [];

	//фильтруем на то, что мы сделали

	$profile_id = um_profile_id();
	foreach ($cutumers_times as $time) {

		$admin_id = $time->admin_id;
        $cart_state = get_post_meta($time->post_id, 'cart_state', true);

        $is_admin_cart = $admin_id == $profile_id;

        if($is_admin_cart){
            if($cart_state == 'give'){
                array_push($cutumers_times_filter, $time);
            }else{
                continue;
            }
        }else{
            if($cart_state == 'give'){
                continue;
            }else{
                array_push($cutumers_times_filter, $time);
            }
        }
	}


	if(!$cutumers_times_filter){
		return;
	} 
	?>

	<div class="exp_title_show">
		<h3>Уделил времени:</h3>
		<button class="exp_title_show__button">Показать</button>
	</div>
	<div class="cute_table_container exp__cute_table_container">
		<table class='cute_table'>
			<tr class='cute_table__header'>
				<th>Кому</th>
				<th>Сколько</th>
				<th>Как</th>
				<th>Навык</th>
				<th>Проблема</th>
			</tr>
			<?php
				foreach ($cutumers_times_filter as $row):
					$post_id = $row->post_id;
					$admin_id = $row->admin_id;
					$cutumer_id = $row->custumer_id;
					$user_id = um_profile_id();
					$other_id = ($cutumer_id == $user_id) ? $row->admin_id : $cutumer_id;
					?>
					<tr>
						<td>
							<?php echo get_user_link_name($other_id) ?>
						</td>
						<td>
							<?php 
								$time_much = get_post_meta($post_id, 'time_how_much', true);

								switch ($time_much) {
									case '30m':
										echo '30 мин';
										break;
									case '1h':
										echo '1 час';
										break;
									case '2h':
										echo '2 часа';
										break;
									
									default:
										break;
								}
							?>
							</a>
						</td>
						<td class="time_table_how">
							<p>
								<?php 
									$time_meeting = explode(' | ', get_post_meta($post_id, 'time_meeting', true));

									if(in_array('online', $time_meeting)){
										echo "Виртуально";
									}
									if(count($time_meeting) > 1){
										echo ', ';
									}
									if(in_array('offline', $time_meeting)){
										echo "Физически";
									}

								?>
							</p>
						</td>
						<td>
						<?php
							$problems = wp_get_post_terms($post_id, 'navik');
								foreach ($problems as $problem) {
									echo  '<p class="cute_navik">';
									echo $problem->name;
									echo '</p>';
								}
							?>
						</td>
						<td>
							<?php
							$problems = wp_get_post_terms($post_id, 'problem');
								foreach ($problems as $problem) {
									echo  '<p class="cute_problem">';
									echo $problem->name;
									echo '</p>';
								}
							?>
						</td>
					</tr>
			<?php endforeach;?>
		</table>
	</div>

	<?php
}

/*
* Получил времени
*/
function table_take_time($user_id){
	global $wpdb;
	$cutumers_times = TimeDb::get_all_time_meeting_user_finished($user_id);
	$cutumers_times_filter = [];

	//фильтруем на то, что мы сделали

	$profile_id = um_profile_id();
	foreach ($cutumers_times as $time) {

		$admin_id = $time->admin_id;
        $cart_state = get_post_meta($time->post_id, 'cart_state', true);

        $is_admin_cart = $admin_id == $profile_id;

        if($is_admin_cart){
            if($cart_state == 'take'){
                array_push($cutumers_times_filter, $time);
            }else{
                continue;
            }
        }else{
            if($cart_state == 'take'){
                continue;
            }else{
                array_push($cutumers_times_filter, $time);
            }
        }
	}

	if(!$cutumers_times_filter){
		return;
	} 
	?>

	<div class="exp_title_show">
		<h3>Получил времени:</h3>
		<button class="exp_title_show__button">Показать</button>
	</div>
	<div class="cute_table_container exp__cute_table_container">
		<table class='cute_table'>
			<tr class='cute_table__header'>
				<th>Объявление</th>
				<th>Сколько</th>
				<th>Как</th>
				<th>От кого?</th>
				<th>Навык</th>
				<th>Проблема</th>
			</tr>
			<?php
				foreach ($cutumers_times_filter as $row):
					$post_id = $row->post_id;
					$admin_id = $row->admin_id;
					$cutumer_id = $row->custumer_id;
					$user_id = um_profile_id();
					$other_id = ($cutumer_id == $user_id) ? $row->admin_id : $cutumer_id;
					?>
					<tr>
						<td>
							<a href="<?php echo get_the_permalink($post_id) ?>">
							<?php echo get_the_title($post_id);?>
							</a>
						</td>
						<td>
							<?php 
								$time_much = get_post_meta($post_id, 'time_how_much', true);

								switch ($time_much) {
									case '30m':
										echo '30 мин';
										break;
									case '1h':
										echo '1 час';
										break;
									case '2h':
										echo '2 часа';
										break;
									
									default:
										break;
								}
							?>
							</a>
						</td>
						<td class="time_table_how">
							<p>
								<?php 
									$time_meeting = explode(' | ', get_post_meta($post_id, 'time_meeting', true));

									if(in_array('online', $time_meeting)){
										echo "Виртуально";
									}
									if(count($time_meeting) > 1){
										echo ', ';
									}
									if(in_array('offline', $time_meeting)){
										echo "Физически";
									}

								?>
							</p>
						</td>
						<td>
							<?php echo get_user_link_name($other_id) ?>
						</td>
						<td>
						<?php
							$problems = wp_get_post_terms($post_id, 'navik');
								foreach ($problems as $problem) {
									echo  '<p class="cute_navik">';
									echo $problem->name;
									echo '</p>';
								}
							?>
						</td>
						<td>
							<?php
							$problems = wp_get_post_terms($post_id, 'problem');
								foreach ($problems as $problem) {
									echo  '<p class="cute_problem">';
									echo $problem->name;
									echo '</p>';
								}
							?>
						</td>
					</tr>
			<?php endforeach;?>
		</table>
	</div>
	<?php
}


/*
* Подарил подарков
*/
function table_give_gift($user_id){
	global $wpdb;
	
	$cutumers_gift = GiftDb::get_all_gift_meeting_user_finished($user_id);
	$cutumers_gift_filter = [];

	//фильтруем на то, что мы сделали

	$profile_id = um_profile_id();
	foreach ($cutumers_gift as $gift) {

		$admin_id = $gift->admin_id;
        $cart_state = get_post_meta($gift->post_id, 'cart_state', true);

        $is_admin_cart = $admin_id == $profile_id;

        if($is_admin_cart){
            if($cart_state == 'give'){
				array_push($cutumers_gift_filter, $gift);
			}else{
                continue;
            }
        }else{
            if($cart_state == 'give'){
                continue;
			}else{
				array_push($cutumers_gift_filter, $gift);
            }
        }
	}


	if(!$cutumers_gift_filter){
		return;
	} 
	?>

	<div class="exp_title_show">
		<h3>Подарил подарков:</h3>
		<button class="exp_title_show__button">Показать</button>
	</div>
	<div class="cute_table_container exp__cute_table_container">
		<table class='cute_table cute_table__gift'>
			<tr class='cute_table__header'>
				<th>Объявление</th>
				<th>Сумма</th>
				<th>Кому</th>
			</tr>
			<?php
				foreach ($cutumers_gift_filter as $row):
					$post_id = $row->post_id;
					$cutumer_id = $row->custumer_id;
					$user_id = um_profile_id();
					$other_id = ($cutumer_id == $user_id) ? $row->admin_id : $cutumer_id;
					?>
					<tr>
						<td>
							<a href="<?php echo get_the_permalink($post_id) ?>">
							<?php echo get_the_title($post_id);?>
							</a>
						</td>
						<td>
							<?php 
								echo get_gift_money($post_id) . ' ₽';
							?>	
						</td>
						<td>
							<?php echo get_user_link_name($other_id) ?>
						</td>
					</tr>
			<?php endforeach;?>
		</table>
	</div>

	<?php
}

/*
* Получил подарков
*/
function table_take_gift($user_id){
	global $wpdb;
	$cutumers_gift = GiftDb::get_all_gift_meeting_user_not_archive($user_id);
	$cutumers_gift_filter = [];

	//фильтруем на то, что мы сделали

	$profile_id = um_profile_id();
	foreach ($cutumers_gift as $gift) {

		$admin_id = $gift->admin_id;
        $cart_state = get_post_meta($gift->post_id, 'cart_state', true);

        $is_admin_cart = $admin_id == $profile_id;


        if($is_admin_cart){
            if($cart_state == 'take'){
                array_push($cutumers_gift_filter, $gift);
            }else{
                continue;
            }
        }else{
            if($cart_state == 'take'){
                continue;
            }else{
                array_push($cutumers_gift_filter, $gift);
            }
        }
	}

	if(!$cutumers_gift_filter){
		return;
	} 
	?>

	<div class="exp_title_show">
		<h3>Получил подарков:</h3>
		<button class="exp_title_show__button">Показать</button>
	</div>
	<div class="cute_table_container exp__cute_table_container">
		<table class='cute_table cute_table__gift'>
			<tr class='cute_table__header'>
				<th>Объявление</th>
				<th>Сумма</th>
				<th>Ссылка</th>
				<th>Статус</th>
			</tr>
			<?php
				foreach ($cutumers_gift_filter as $row):
					$post_id = $row->post_id;
					$admin_id = $row->admin_id;

					$user_id = um_profile_id();
					$other_id = ($admin_id == $user_id) ? $row->custumer_id : $admin_id;
					?>
					<tr>
						<td>
							<a href="<?php echo get_the_permalink($post_id) ?>">
							<?php echo get_the_title($post_id);?>
							</a>
						</td>
						<td>
							<?php 
								echo get_gift_money($post_id) . ' ₽';
							?>	
						</td>
						<td>
							<?php 
								$link = get_post_meta($post_id, 'ozon_link', true);
								$type_gift = get_post_meta($post_id, 'type_gift', true);
								if($type_gift == 'money'){
									echo "Ссылка отсутствует";
								}else{
									if(!$link){
										$gift_city = get_post_meta($post_id, 'gift_city', true);
										echo "<p>Самовывоз. Город - $gift_city</p>";
									}else{
										echo "<a href='$link'>Ссылка на товар</a>";
									}
								}
							?>
						</td>
						<td>
							<?php 
								$status = $row->status_gift;

								if($status == 'start'){
									echo '<p class="status_archive">Жду</p>';
								}elseif($status == 'finished'){
									echo '<p class="status_finished">Получил</p>';
								}
							?>
						</td>
					</tr>
			<?php endforeach;?>
		</table>
	</div>
	<?php
}



?>