<?php 
if ( ! defined( 'ABSPATH' ) ) exit;


function um_check_homework_add_tab( $tabs ) {

	$tabs[ 'check_homework' ] = array(
		'name'   => 'Задания',
		'icon'   => 'um-faicon-check-square-o',
		'custom' => true
	);

	UM()->options()->options[ 'profile_tab_' . 'check_homework' ] = true;
 
	return $tabs;
}

function um_profile_content_check_homework( $args ) {
	echo '<h2>Задания</h2>';
	echo_homeworks();
	print_homeworks();
}



function echo_homeworks(){
	echo '<h3>Делаю:</h3>';
	$user_id = um_profile_id();
	$hw_arr = get_user_meta($user_id, 'homework_arr')[0];
	if(!is_array($hw_arr)){
		$hw_arr = array();
	} 
	table_homework($hw_arr, $user_id);
	?>
		<script type="text/javascript">
		let site_url = "<?php echo get_site_url(); ?>";
		let url = site_url + '/wp-admin/admin-ajax.php';
		let curent_user = '<?php echo $user_id;?>'

			//вывод дз
			if($('.show_dz').length){
				$('.show_dz').on('click', function(){
					$(this).parent().parent().next('.show_homework').fadeToggle();
					$(this).parent().parent().next().next('.show_homework').fadeToggle();
				});

				//отправка домашнего задания
				$('.send_homework').on('click', function(){
					console.log('1313');
					let homework_text = $(this).siblings('textarea').val();
					let meta_id = $(this).attr('data-homework-meta-id');

					let file_obj  = $('#hw_imgs').prop('files');
					let form_data = new FormData();
					for(i=0; i<file_obj.length; i++) {
            form_data.append('file[]', file_obj[i]);
        	}
					form_data.append('action', 'add_done_homework');
					form_data.append('meta_id', meta_id);
					form_data.append('homework_text', homework_text);

					$.ajax({
						url: url,
						type: 'POST',
						contentType: false,
            processData: false,
						data: form_data,
						success: function(data){
							console.log(data);
							location.reload();
						}
					});
				});
			//перепроверка дз
			$('.send_homewor_redoing').on('click', function(){
				let homework_text = $(this).siblings('textarea').val();
				let meta_id = $(this).attr('data-homework-meta-id');

				let file_obj  = $('#hw_imgs_r').prop('files');
				let form_data = new FormData();
				for(i=0; i<file_obj.length; i++) {
            form_data.append('file[]', file_obj[i]);
        	}
				form_data.append('action', 'redoing_user_homework');
				form_data.append('meta_id', meta_id);
				form_data.append('homework_text', homework_text);
				$.ajax({
					url: url,
					type: 'POST',
					contentType: false,
            processData: false,
					data: form_data,
					success: function(data){
						console.log(data);
						location.reload();
					}
				});
			});
			}


		</script>
	<?php
}


//функкия выводит таблицу с дз
function table_homework($hw_arr, $user_id){
	// переменная определяет есть ли хотя бы 1 урок
	$has_dz = false;
	?>
	<table>

	<?php
	// перебераем айди
	foreach ($hw_arr as $homework_id) {

			//проверка на оплачено или нет
			$homework_arr_paied  = get_user_meta($user_id, 'homework_arr_paied')[0];
			if(!is_array($homework_arr_paied)){
				$homework_arr_paied = array();
			}

			global $wpdb;
			$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}homeworks WHERE id = {$homework_id}", OBJECT )[0];
			$hw_status = $results->status_homework;
			$post_id = $results->post_id;

			// проверка бесплатный ли урок
			$free_post_homework = get_post_meta($post_id, 'post_homework_price')[0];

			if(in_array($post_id, $homework_arr_paied) || $free_post_homework == '0'){
				?>
				<tr>
					<td><?php echo get_the_title($post_id) ?></td>
					<td><button class="show_dz">Задание</button></td>
					<td><?php
						if($hw_status == "На проверке"){
						 echo	'<b class="hw_check">На проверке</b>';
						}else if($hw_status == 'Проверено'){
							echo '<b class="hw_done">Проверено</b>';
						}else if($hw_status == 'Отправлено на доработку'){
							echo '<b class="hw_redoing">'.$hw_status . '</b>';
						}else{
							echo '<b>Не выполнено</b>';
						}
					 ?></td>
				</tr>
				<tr class='show_homework'><td colspan="4"><?php echo get_post_meta($post_id, 'post_homework')[0]; ?></td></tr>
				<tr class='show_homework'><td colspan="4">
					<p>Ваш ответ на задание:</p>
					<?php
						//проверка отправлено ли задание на проверку или нет
						if($hw_status == "На проверке"){
							?>
								<p><?php echo $results->homework_text;?></p>
								<p>Статус домашнего задания: <b class="hw_check"><?php echo $hw_status;?></b></p>
								<?php
								$arr_imgs = unserialize($results->homework_imgs);
									if(!empty($arr_imgs)){
										?>
										<p>Прикреплённые файлы:</p>
										<div class="add_files">
											<?php
												foreach ($arr_imgs as $path) {
													echo '<div class="cont_img"><img src="' . $path . '" alt="img" /></div>';
												}
											 ?>
										</div>
										<?php
									}
								 ?>
							<?php
						}else if($hw_status == 'Проверено'){
							?>
								<p><?php echo $results->homework_text;?></p>
								<p>Комментарий наставника:</p>
								<p><?php echo $results->comments;?></p>
								<p>Статус домашнего задания: <b class='hw_done'><?php echo $hw_status;?></b></p>
								<?php
								$arr_imgs = unserialize($results->homework_imgs);
									if(!empty($arr_imgs)){
										?>
										<p>Прикреплённые файлы:</p>
										<div class="add_files">
											<?php
												foreach ($arr_imgs as $path) {
													echo '<div class="cont_img"><img src="' . $path . '" alt="img" /></div>';
												}
											 ?>
										</div>
										<?php
									}
								 ?>
							<?php
						}else if($hw_status == 'Отправлено на доработку'){
							?>
							<textarea name="name" rows="8" cols="80"><?php echo $results->homework_text; ?></textarea>
							<p>Коментарий от наставника:</p>
							<p><?php echo $results->comments; ?></p>
							<p>Статус домашнего задания: <b class="hw_redoing"><?php echo $hw_status;?></b></p>
							<?php
							$arr_imgs = unserialize($results->homework_imgs);
								if(!empty($arr_imgs)){
									?>
									<p>Прикреплённые файлы:</p>
									<div class="add_files">
										<?php
											foreach ($arr_imgs as $path) {
												echo '<div class="cont_img"><img src="' . $path . '" alt="img" /></div>';
											}
										 ?>
									</div>
									<?php
								}
							 ?>
							 <input type="file" id='hw_imgs_r' name="kv_multiple_attachments[]" multiple="multiple" accept=".jpg,.jpeg,.png,.pdf">
							<button data-homework-meta-id='<?php echo $results->id; ?>' type="button"
							name="button" class='send_homewor_redoing'>Отправить заново</button>
							<?php
						}else{
					 ?>
					<textarea name="name" rows="8" cols="80"></textarea>
					<input type="file" id='hw_imgs' name="kv_multiple_attachments[]" multiple="multiple" accept=".jpg,.jpeg,.png,.pdf">
					<button data-homework-meta-id='<?php echo $results->id; ?>' type="button"
					name="button" class='send_homework'>Отправить</button>
					<?php }?>
				</td></tr>
					<?php
					$has_dz = true;
			}else{
			?>
				<tr>
					<td><?php the_title($post_id) ?></td>
					<td>Задания не доступны</td>
					<td><button class='homework_pay' data-user-id="<?php echo $user_id ?>"
						data-post-id="<?php echo $post_id ?>">
					Оплатить</button></td>
				</tr>
				<?php
				$has_dz = true;
			}
	}
	if(!$has_dz){
		?><tr><td>Начните изучать уроки и получать задания  от экспертов.</td></tr><?php
	}

	?></table>
	<?php
}


function print_homeworks(){
	if(um_profile_id() != get_current_user_id()){
  	return;
  }
	echo '<h3>Проверяю:</h3>';
	global $wpdb;
	$cur_id = get_current_user_id();
	$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}homeworks WHERE teacher_id = {$cur_id}", OBJECT );
	
  	$user_posts_teach_arr = get_user_meta($cur_id, 'user_posts_teach_arr', true);


	//если пользователь прикреплён в виде наставника к чему-то
	if($user_posts_teach_arr){
		$results_time = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}homeworks WHERE nastavnik_id = {$cur_id}", OBJECT );
		$results = array_merge($results, $results_time);
	}

	if($results){
		?>
		<table>
			<tr>
				<td>Псевдоним</td>
				<td>Имя и Фамилия ученика</td>
				<td>Задание</td>
				<td>Статус</td>
			</tr>
			<?php
				foreach ($results as $row) {
					if($row->status_homework == 'Не сделано'){
						continue;
					}
					?>
					<tr>
						<td><a href="<?php echo ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/user/' . get_the_author_meta('user_login', $row->user_id) ?>">
							<?php echo get_the_author_meta('nickname', $row->user_id); ?></a></td>
						<td>
							<?php if( get_the_author_meta('first_name', $row->user_id) or
								get_the_author_meta('last_name', $row->user_id)){
									echo get_the_author_meta('first_name', $row->user_id) . ' ' . get_the_author_meta('last_name', $row->user_id);
								}else{
									echo get_the_author_meta('user_login', $row->user_id);
								} ?>
						</td>
						<td><button class='show_homework_for_teacher'>Задание</button></td>
						<td>
							<?php $status = $row->status_homework;
								if($status == 'На проверке'){
									echo '<b class="hw_check">Нужно проверить</b>';
								}else if($status == 'Проверено'){
										echo '<b class="hw_done">'.$status .'</b>';
								}else if($status == 'Отправлено на доработку'){
										echo '<b class="hw_redoing">'.$status . '</b>';
								}else{
									echo '<b>'.$status . '</b>';
								}
							?>
						</td>
					</tr>
					<tr class='homework_for_teacher'><td colspan='4'>
						<p>Ваше задание:</p>
						<p><?php echo get_post_meta($row->post_id, 'post_homework')[0]; ?></p>
						<br>
						<p>Ответ ученика:</p>
						<p><?php echo $row->homework_text?></p>
						<?php
						$arr_imgs = unserialize($row->homework_imgs);
							if(!empty($arr_imgs)){
								?>
								<p>Прикреплённые файлы:</p>
								<div class="add_files">
									<?php
										foreach ($arr_imgs as $path) {
											echo '<div class="cont_img"><img src="' . $path . '" alt="img" /></div>';
										}
									 ?>
								</div>
								<?php
							}
						 ?>
						<br>
						<p>Коментарий к работе:</p>
						<?php
						 if($status == 'Проверено'){
								echo '<p>' . $row->comments . '</p>';
						 }else{
						 ?>
						<textarea name="name" rows="8" cols="80"><?php echo $row->comments ?></textarea>
						<div class="teacher_btns">
							<button class='btn_homework_done' data-homework-meta-id='<?php echo $row->id; ?>'>Засчитать</button>
							<button class='btn_homework_redoing' data-homework-meta-id='<?php echo $row->id; ?>'>Отправить на доработку</button>
						</div>
					<?php } ?>
					</td></tr>
					<?php
				}
			 ?>
		</table>
		<script type="text/javascript">
			//переключалка
			$('.show_homework_for_teacher').on('click', function(){
				$(this).parent().parent().next().fadeToggle();
			});
			//кнопка "засчитать"
			$('.btn_homework_done').on('click', function(){
				let meta_id = $(this).attr('data-homework-meta-id');
				let comment = $(this).parent().siblings('textarea').val();

				$.ajax({
					url: url,
					type: 'POST',
					data:{
						action: 'teacher_homework_done',
						meta_id: meta_id,
						comment: comment,
					},
					success: function(data){
						console.log(data);
						location.reload();
					},
				});
			});
			//кнопка "На доработку"
			$('.btn_homework_redoing').on('click', function(){
				let meta_id = $(this).attr('data-homework-meta-id');
				let comment = $(this).parent().siblings('textarea').val();

				$.ajax({
					url: url,
					type: 'POST',
					data:{
						action: 'teacher_homework_redoing',
						meta_id: meta_id,
						comment: comment,
					},
					success: function(data){
						console.log(data);
						location.reload();
					},
				});
			});
		</script>

		<?php
	}else{
		?><p>Вам ещё не пришли домашние задания на проверку</p><?php
	}
}

?>