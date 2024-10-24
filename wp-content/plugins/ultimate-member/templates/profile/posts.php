<?php if ( ! defined( 'ABSPATH' ) ) exit;


if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
	//Only for AJAX loading posts
	if ( ! empty( $posts ) ) {
		foreach ( $posts as $post ) {
			UM()->get_template( 'profile/posts-single.php', '', array( 'post' => $post ), true );
		}
	}
} else {
	if ( ! empty( $posts ) ) { ?>
		<?php
		if(is_company(um_profile_id())){
			echo do_shortcode( '[wpuf_dashboard]' );
		}else{
			if( um_profile_id() == get_current_user_id()){
				echo '<h2>Задания</h2>';
					?>	
					<div class="cute_table_container">
						<h3>Актуальные чаты по поводу обучения</h3>
						<?php 
						$profile_id = um_profile_id();

						$lesson_arr_ids = get_user_meta($profile_id, 'lesson_arr', true);
						if(!is_array($lesson_arr_ids)){
							$lesson_arr_ids = array();
						}
						$lesson_arr = array();
						foreach ($lesson_arr_ids as $lesson_id){
							
								$lesson = Lesson::get_lesson_by_id($lesson_id);
								if($lesson->get_status() != 'start'){
									continue;
								}
								array_push($lesson_arr,$lesson);
						}
						?>
						<?php if(count($lesson_arr) > 0):?>
							<table class="cute_table lesson_table">
								<tr class="cute_table__header">
									<th>Объявление</th>
									<th>Пользователь</th>
									<th>Статус</th>
									<th>Действие</th>
								</tr>
								<?php foreach ($lesson_arr as $lesson):?>
									<?php 
										$cur_user_is_admin = $lesson->get_admin_id() == $profile_id;
									?>
									<tr class="cute_table__item">
										<td colspan='4'>
											<table>
												<tr class="cute_table__item__header lesson_table__item__header">
													<td>
														<?php echo get_post_link_title($lesson->get_post_id())?>
													</td>
													<td>
														<?php echo get_user_link_name(($lesson->get_custumer_id() == um_profile_id()) ? $lesson->get_admin_id() : $lesson->get_custumer_id())?>
													</td>
													<td>
														<?php 
														if($cur_user_is_admin){
															echo 'Обучают Вас';
														}else{
															echo 'Вы обучаете';
														}
														?>
													</td>
													<td>
														<button class='open_chat'>Подробнее</button>
													</td>
												</tr>
												<tr class="cute_table__chat">
													<td colspan='4'>
														<table>
															<tr>
																<td><p class='table_big_text'>Чат</p></td>
															</tr>
															<tr>
																<td>
																	<?php
																		$messages = $lesson->get_chat()->get_messages();
																	?>
																	<div class="table_messages">
																		<?php if($messages && count($messages) > 0):  ?>
																			<?php foreach ($messages as $message):?>
																				<div class="table_message">
																					<div class="table_message__avatar">
																						<div class="table_message__avatar__img">
																							<?php
																								$author_avatar =  get_avatar_url($message['author_id']);
																								if($author_avatar && !strripos($author_avatar, 'default')){
																									echo "<img src='{$author_avatar}' alt='author'>";
																								}else{
																									echo "<img src='https://cdn-icons-png.flaticon.com/512/3593/3593455.png' alt='placeholder'>";
																								} 
																							?>
																						</div>
																						<p>
																							<?php echo get_user_link_name($message['author_id'])?>
																							<?php if($message['author_id'] == um_profile_id()) echo '(Вы)';?>
																						</p>
																					</div>
																					<div class="table_message__text">
																						<p>
																							<?php echo $message['content'];?>
																						</p>
																					</div>
																				</div>
																			<?php endforeach;?>
																		<?php else:?>
																			<p class='chat_first_row'>В чате пока пусто. Напишите первым!</p>
																		<?php endif;?>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<textarea class="input_message"></textarea>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="lesson_btns">
																		<button class="send_message_chat" 
																			data-chat-id="<?php echo $lesson->get_chat()->get_id()?>"
																			data-post-id="<?php echo $lesson->get_post_id()?>"
																			>Отправить</button>

																		<?php if($lesson->get_confirm_first_id() == get_current_user_id()):?>
																			<p class="meeting_text--submited">Вы подтвердили, что всё прошло успешно. Ожидаем подтверждения другого пользователя</p>
																		<?php else:?>
																				<button class="confirm_lesson" data-lesson-id="<?php echo $lesson->get_id()?>" 
																					data-post_id="<?php echo $lesson->get_post_id()?>"
																					data-user_id="<?php echo $lesson->get_admin_id()?>"
																					data-cur_user_id="<?php echo get_current_user_id()?>"
																				><?php echo $cur_user_is_admin ? 'Меня обучили' : 'Я обучил' ?></button>
																		<?php endif;?>
																	</div>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								<?php endforeach;?>
							</table>
						<?php else:?>
							<p>Тут пока пусто...</p>
						<?php endif;?>
					</div>
					
					<?php
				echo_homeworks();
				print_homeworks();
				echo do_shortcode( '[wpuf_dashboard]' ); 
			}else{
			?>

			<div class="um-ajax-items">

				<?php foreach ( $posts as $post ) {
					UM()->get_template( 'profile/posts-single.php', '', array( 'post' => $post ), true );
				}

				if ( $count_posts > 10 ) { ?>
					<div class="um-load-items">
						<a href="javascript:void(0);" class="um-ajax-paginate um-button" data-hook="um_load_posts"
						   data-author="<?php echo esc_attr( um_get_requested_user() ); ?>" data-page="1"
						   data-pages="<?php echo esc_attr( ceil( $count_posts / 10 ) ); ?>">
							<?php _e( 'load more posts', 'ultimate-member' ); ?>
						</a>
					</div>
				<?php } ?>
				
			</div>
			<?php
			}
		}
		?>

 <!-- меняем там записи -->
	<?php } else { ?>


		<div class="um-profile-note_kw"> 

			<span>
				<?php if ( um_profile_id() == get_current_user_id() ) {
					do_action('um_profile_cont');
						?>
					<p>Здесь будут храниться ваши созданные уроки. Пока вы не создали ни одного. Начните учить других, создайте первый урок</p>
					<button type="button" id='reg_as_teacher' name="button">Создать урок</button>
					<?php
				} else {
					_e( 'This user has not created any posts.', 'ultimate-member' );
				} ?>
			</span>
		</div>

	<?php }
}
