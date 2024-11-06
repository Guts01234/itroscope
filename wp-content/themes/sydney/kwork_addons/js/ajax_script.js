;(function ($) {
	$.extend({
		debounce: function (fn, timeout, invokeAsap, ctx) {
			if (arguments.length == 3 && typeof invokeAsap != 'boolean') {
				ctx = invokeAsap
				invokeAsap = false
			}
			var timer
			return function () {
				var args = arguments
				ctx = ctx || this
				invokeAsap && !timer && fn.apply(ctx, args)
				clearTimeout(timer)
				timer = setTimeout(function () {
					invokeAsap || fn.apply(ctx, args)
					timer = null
				}, timeout)
			}
		},
		throttle: function (fn, timeout, ctx) {
			var timer, args, needInvoke
			return function () {
				args = arguments
				needInvoke = true
				ctx = ctx || this
				timer ||
					(function () {
						if (needInvoke) {
							fn.apply(ctx, args)
							needInvoke = false
							timer = setTimeout(arguments.callee, timeout)
						} else {
							timer = null
						}
					})()
			}
		},
	})
})(jQuery)
;(function ($) {
	$(document).ready(function () {
		//для копирования ссылки для бонусного начисления
		if ($('#copy_bonus_link').length) {
			$('#copy_bonus_link').on('click', function () {
				const link = $(this).attr('data-link')
				navigator.clipboard.writeText(link)
				console.log(navigator)
				$(this).addClass('cute_blue_button--active')
				$(this).text('Скопировано!')

				setTimeout(() => {
					$(this).removeClass('cute_blue_button--active')
					$(this).text('Скопировать ссылку!')
				}, 3000)
			})
		}

		//Модальное окно показываем если на нужной страницу
		if ($('.um-profile-note_kw').length) {
			$('#reg_as_teacher').on('click', () => {
				let href = window.location.href

				href = href.split('?')[0]
				href += '?profiletab=mycustomtab'
				window.location.replace(href)
			})
			$('#modal_reg_as_teacher .bg').on('click', () => {
				$('#modal_reg_as_teacher').fadeOut()
				$('body').css('overflow', 'scroll')
			})
			$('#btn_end_teacher_reg').on('click', () => {
				var sfer = $('input[name=sfer]:checked').val()
				//если не выбрали сферу
				if (sfer === undefined) {
					$('#error_modal').fadeIn()
					$('#error_modal').html('Пожалуйста, сделайте выбор')
					return 0
				}
				// если нет аватарки
				if (
					$('.um-profile-photo-img img')
						.attr('src')
						.includes('img/default_avatar.jpg')
				) {
					$('#error_modal').fadeIn()
					$('#error_modal').html('Пожалуйста, загрузите свою аватарку')
					return 0
				}
				var cur_user_id = $('.um-profile-photo').attr('data-user_id')

				$.ajax({
					url: site_url_header + '/wp-admin/admin-ajax.php',
					type: 'POST',
					data: {
						action: 'reg_as_teacher',
						sfer: sfer,
						user_id: cur_user_id,
					},
					success: data => {
						$('.modal_reg_main').html(data)
						setTimeout(() => {
							location.reload()
						}, 2000)
					},
					error: data => {
						console.log(data)
						$('#error_modal').html('Произошла ошибка, повторите позже')
						$('#error_modal').fadeIn()
					},
				})
			})
		} else {
			$('#modal_reg_as_teacher').remove()
		}

		//обработка фильтра
		if ($('#sort_filter_ajax').length) {
			$('.has_menu_filter')
				.children('p')
				.on('click', function () {
					//если мы нажали на активный элемент
					if ($(this).hasClass('p_list_nav_active')) {
						$('.p_list_nav_active').removeClass('p_list_nav_active')
						$('.sec_menu_filter').fadeOut()
						return
					}
					//если нажали на другой
					$('.p_list_nav_active').removeClass('p_list_nav_active')
					$('.sec_menu_filter').fadeOut()
					$(this).addClass('p_list_nav_active')
					$(this).parent().children('.sec_menu_filter').fadeIn()
				})
		}

		$(document).mouseup(function (e) {
			// событие клика по веб-документу
			var div = $('.has_menu_filter') // тут указываем ID элемента
			if (
				!div.is(e.target) && // если клик был не по нашему блоку
				div.has(e.target).length === 0
			) {
				// и не по его дочерним элементам
				setTimeout(() => {
					$('.p_list_nav_active').removeClass('p_list_nav_active')
					$('.sec_menu_filter').fadeOut()
				}, 10)
			}
		})

		if ($('#get_free, #pay_for_post, #get_free_practice').length) {
			//обратотка "получить беспатно"
			var post_id = $('#data_ajax').attr('data-id')
			var user_id = $('#data_ajax').attr('data-user_id')
			$('#get_free').on('click', function () {
				$.ajax({
					url: site_url_header + '/wp-admin/admin-ajax.php',
					data: {
						action: 'get_free',
						post_id: post_id,
						user_id: user_id,
					},
					type: 'POST',
					success: function (data) {
						$('#read_done_con').html(
							'<p class="readed">Добавлено в <a href="' +
								site_url_header +
								'/user/">паспорт</a></p>'
						)
					},
				})
			})
			//обработка получить практику
			$('#get_free_practice').on('click', function () {
				$.ajax({
					url: site_url_header + '/wp-admin/admin-ajax.php',
					data: {
						action: 'get_free',
						post_id: post_id,
						user_id: user_id,
						is_practice: true,
					},
					type: 'POST',
					success: function (data) {
						$('#end_of_post').append(
							'<p class="readed_practice">Добавлено в <a href="' +
								site_url_header +
								'/user/">паспорт</a></p>'
						)
						setTimeout(function () {
							$('.readed_practice').fadeOut(400, function () {
								$(this).remove()
							})
						}, 2000)
					},
				})
			})
			//оплата практики
			$('#pay_for_post_practice').on('click', function () {
				$.ajax({
					url: site_url_header + '/wp-admin/admin-ajax.php',
					type: 'POST',
					data: {
						action: 'homework_payment',
						user_id: user_id,
						post_id: post_id,
						is_practice: true,
					},
					success: function (data) {
						window.location.replace(data)
					},
					error: function (data) {
						data = JSON.stringify(data)
						console.log('error' + data)
					},
				})
			})

			$('#pay_for_post').on('click', function () {
				$.ajax({
					url: site_url_header + '/wp-admin/admin-ajax.php',
					type: 'POST',
					data: {
						action: 'homework_payment',
						user_id: user_id,
						post_id: post_id,
					},
					success: function (data) {
						window.location.replace(data)
					},
					error: function (data) {
						data = JSON.stringify(data)
						console.log('error' + data)
					},
				})
			})
		}

		$('#true_loadmore').click(function () {
			$(this).text('Загружаю...') // изменяем текст кнопки, вы также можете добавить прелоадер
			var data = {
				action: 'loadmore',
				query: true_posts,
				page: current_page,
			}
			$.ajax({
				url: ajaxurl, // обработчик
				data: data, // данные
				type: 'POST', // тип запроса
				success: function (data) {
					if (data) {
						$('#true_loadmore').text('Загрузить ещё')
						$('.post_list_kw').append(data) // вставляем новые посты
						current_page++ // увеличиваем номер страницы на единицу
						if (current_page == max_pages) $('#true_loadmore').fadeOut() // если последняя страница, удаляем кнопку
					} else {
						$('#true_loadmore').fadeOut() // если мы дошли до последней страницы постов, скроем кнопку
					}
				},
			})
		})

		$('.sfer_name_filt').on('click', function () {
			if ($(this).hasClass('sfer_name_filt_active')) {
				$(this).removeClass('sfer_name_filt_active')
			} else {
				$(this).addClass('sfer_name_filt_active')
			}
		})

		if ($('#sort_filter_ajax').length) {
			$('#sort_filter_ajax').on('click', function () {
				var sfer = []
				var navik = []
				var problem = []
				var sex = []
				var age = []
				let author = ''

				if ($('#search_desc').css('display') == 'none') {
					author = $('#search_mobile').val()
				} else {
					author = $('#search_desc').val()
				}

				var items = $('.sfer_name_filt_active')
				items.each(function () {
					sfer.push($(this).attr('data-sfer'))
				})

				var items = $('.js_problem')
				items.each(function () {
					if ($(this).is(':checked')) {
						problem.push($(this).attr('data-problem'))
					}
				})

				var items = $('.js_navik')
				items.each(function () {
					if ($(this).is(':checked')) {
						navik.push($(this).attr('data-navik'))
					}
				})

				var items = $('.js_sex')
				items.each(function () {
					if ($(this).is(':checked')) {
						sex.push($(this).attr('data-sex'))
					}
				})

				var items = $('.js_age')
				items.each(function () {
					if ($(this).is(':checked')) {
						age.push($(this).attr('data-age'))
					}
				})

				$.ajax({
					url: site_url_header + '/wp-admin/admin-ajax.php',
					data: {
						action: 'filt',
						sfer: sfer,
						navik: navik,
						problem: problem,
						age: age,
						sex: sex,
						author: author,
					},
					type: 'POST',

					success: function (data) {
						if (data) {
							$('.main_stat').css('display', 'none')
							$('#sort_filter_ajax_return').fadeIn(100)
							$('.post_list_kw').html(' ')
							$('.post_list_kw').append(data)
							console.log(current_page)
							console.log(max_pages)
							if (current_page < max_pages) {
								$('#true_loadmore').fadeIn(100)
							}
						} else {
							$('.main_stat').css('display', 'none')
							$('#sort_filter_ajax_return').fadeIn(100)
							$('.post_list_kw').html('<h2>Данных статей не найдено</h2>')
							$('#true_loadmore').fadeOut(100)
						}
					},
				})
			})
			$('#sort_filter_ajax_return').on('click', function () {
				var sfer = []
				var navik = []
				var problem = []
				var items = $('.sfer_name_filt_active')
				items.removeClass('sfer_name_filt_active')
				$('.js_problem').removeAttr('checked')
				$('.js_navik').removeAttr('checked')
				$('#search_mobile').val('')
				$('#search_desc').val('')
				$.ajax({
					url: site_url_header + '/wp-admin/admin-ajax.php',
					data: {
						action: 'filt',
						sfer: sfer,
						navik: navik,
						problem: problem,
					},
					type: 'POST',
					success: function (data) {
						if (data) {
							$('.main_stat').css('display', 'block')
							$('#sort_filter_ajax_return').fadeOut(100)
							$('#true_loadmore').fadeIn(100)
							$('.post_list_kw').html(' ')
							$('.post_list_kw').append(data)
						}
					},
				})
			})
		}

		if ($('.um-profile-nav-posts').length) {
			$('.um-profile-nav-posts a').attr('original-title', 'Знания')
			$('.um-profile-nav-posts a').attr('title', 'Знания')
			$('.um-profile-nav-posts a span').html('Знания')

			$('.um-profile-nav-main a').attr('original-title', 'Паспорт')
			$('.um-profile-nav-main a').attr('title', 'Паспорт')
			$('.um-profile-nav-main a span').html('Паспорт')
		}
	})

	setTimeout(() => {
		if ($('#end_of_post').length) {
			var target = $('#end_of_post')
			var targetPos = target.offset().top
			var winHeight = $(window).height()
			var scrollToElem = targetPos - winHeight
			let visible = false
			var post_id = $('#data_ajax').attr('data-id')
			var user_id = $('#data_ajax').attr('data-user_id')
			$(window).scroll(function () {
				var winScrollTop = $(this).scrollTop()
				if (!visible) {
					if (winScrollTop > scrollToElem) {
						visible = true
						$.ajax({
							url: site_url_header + '/wp-admin/admin-ajax.php',
							data: {
								action: 'post_scroll',
								user_id: user_id,
								post_id: post_id,
							},
							type: 'POST',
						})
					}
				}
			})
		}
	}, 5000)

	$user_input_container = $('.user_input_container')
	$input = $user_input_container.find('input')
	if ($input) {
		$input.each(function () {
			$(this).on(
				'keyup',
				$.debounce(function () {
					$this_input = $(this)
					$messages = $(this)
						.parent()
						.parent()
						.parent()
						.find('.message_invation')
					$users_search_list = $(this)
						.parent()
						.parent()
						.find('.users_search_list')

					if ($(this).val().length < 1) {
						$users_search_list.css('display', 'none')
						$users_search_list.html('')
						return
					}
					$.ajax({
						url: site_url_header + '/wp-admin/admin-ajax.php',
						method: 'POST',
						data: {
							action: 'search_users',
							max_size: 5,
							search: $(this).val(),
						},
						beforeSend: () => {
							$users_search_list.css('display', 'none')
							$users_search_list.html('')
							$messages.html('')
							$messages.css('display', 'none')
						},
						success: data => {
							if (data.length == 0) {
								$messages.html('Ничего не найдено :(')
								$messages.css('display', 'block')
							}

							$users_search_list.html(data)
							$users_search_list.css('display', 'block')
							$('.selcet_user').on('click', function (e) {
								e.preventDefault()
								let user_id = $(this).attr('data-user-id')
								let text = $(this).text()

								$this_input.val(text)
								$this_input.attr('data-user-id', user_id)

								$users_search_list.fadeOut()

								setTimeout(() => {
									$users_search_list.html('')
									$users_search_list.css('display', 'none')
								}, 402)
							})
						},
						error: data => {
							console.log(data)
						},
					})
				}, 400)
			)
		})
	}

	//кнопка получения времени
	if ($('#get_time').length) {
		$('#get_time').on('click', function () {
			const post_id = $(this).attr('data-post-id')
			const custumer_id = $(this).attr('data-custumer')

			$.ajax({
				url: site_url_header + '/wp-admin/admin-ajax.php',
				method: 'POST',
				data: {
					action: 'create_time',
					post_id: post_id,
					custumer_id: custumer_id,
				},
				success: data => {
					console.log(data)
					$('#read_done_con').html(
						'<p class="readed">Вы откликнулись на это объявление</p>'
					)
				},
				error: data => {
					console.log(data)
				},
			})
		})
	}
	//кнопка получения подарка
	if ($('#get_gift').length) {
		$('#get_gift').on('click', function () {
			const post_id = $(this).attr('data-post-id')
			const custumer_id = $(this).attr('data-custumer')

			$.ajax({
				url: site_url_header + '/wp-admin/admin-ajax.php',
				method: 'POST',
				data: {
					action: 'create_gift',
					post_id: post_id,
					custumer_id: custumer_id,
				},
				success: data => {
					console.log(data)
					$('#read_done_con').html(
						'<p class="readed">Вы откликнулись на это объявление</p>'
					)
				},
				error: data => {
					console.log(data)
				},
			})
		})
	}

	//кнопка получения подарка
	if ($('#get_lesson').length) {
		$('#get_lesson').on('click', function () {
			const post_id = $(this).attr('data-post-id')
			const custumer_id = $(this).attr('data-custumer')

			$.ajax({
				url: site_url_header + '/wp-admin/admin-ajax.php',
				method: 'POST',
				data: {
					action: 'create_lesson',
					post_id: post_id,
					custumer_id: custumer_id,
				},
				success: data => {
					console.log(data)
					$('#read_done_con').html(
						'<p class="readed">Вы откликнулись на это объявление</p>'
					)
				},
				error: data => {
					console.log(data)
				},
			})
		})
	}

	//открывашька чатов
	if ($('.open_chat').length) {
		$('.open_chat').on('click', function () {
			//если нажали на кнопу, которая уже показывает
			if (
				$(this)
					.parents('.cute_table__item')
					.hasClass('cute_table__item--active')
			) {
				$('.cute_table__item--active').find('.cute_table__chat').fadeOut()
				$('.cute_table__item--active').find('.gift-money-meta').fadeOut()
				$('.cute_table__item--active').removeClass('cute_table__item--active')
			} else {
				//скрываем другие активне, если они есть
				if ($('.cute_table__item--active').length) {
					$('.cute_table__item--active').find('.cute_table__chat').fadeOut()
					$('.cute_table__item--active').find('.gift-money-meta').fadeOut()
					$('.cute_table__item--active').removeClass('cute_table__item--active')
				}
				$(this)
					.parents('.cute_table__item')
					.addClass('cute_table__item--active')
				$('.cute_table__item--active').find('.cute_table__chat').fadeIn()
				$('.cute_table__item--active').find('.gift-money-meta').fadeIn()

				if (
					!$(this)
						.parents('.cute_table__item')
						.hasClass('cute_table__item--scrolled')
				) {
					$tableMessage = $(this)
						.parents('.cute_table__item')
						.find('.table_messages')
					const curHeight = $tableMessage.height()
					const autoHeight = $tableMessage.css('height', 'auto').height() // Get Auto Height
					$tableMessage.height(curHeight) // Reset to Default Height
					$tableMessage.scrollTop(autoHeight)
					$(this)
						.parents('.cute_table__item')
						.addClass('cute_table__item--scrolled')
				}
			}
		})
	}

	//отправка сообщений
	if ($('.send_message_chat').length) {
		$('.send_message_chat').on('click', function () {
			const chat_id = $(this).attr('data-chat-id')
			const post_id = $(this).attr('data-post-id')
			const message_text = $(this)
				.parents('.cute_table__chat')
				.find('.input_message')
				.val()

			if (message_text.length < 1 || message_text.length > 1000) {
				return
			}

			$.ajax({
				url: site_url_header + '/wp-admin/admin-ajax.php',
				method: 'POST',
				data: {
					action: 'send_message_chat',
					post_id: post_id,
					chat_id: chat_id,
					message_text: message_text,
				},
				beforeSend: () => {
					$(this).parents('.cute_table__chat').find('.input_message').val('')
				},
				success: data => {
					console.log(data)
					if (data !== 'false') {
						$(this)
							.parents('.cute_table__chat')
							.find('.table_messages')
							.append(data)
						$(this)
							.parents('.cute_table__chat')
							.find('.chat_first_row')
							.remove()
						$tableMessage = $(this)
							.parents('.cute_table__chat')
							.find('.table_messages')
						const curHeight = $tableMessage.height()
						const autoHeight = $tableMessage.css('height', 'auto').height() // Get Auto Height
						$tableMessage.height(curHeight) // Reset to Default Height
						$tableMessage.scrollTop(autoHeight)
					}
				},
				error: data => {
					console.log(data)
				},
			})
		})
	}

	//подтверждение сделки для времени
	if ($('.confirm_time').length) {
		$('.confirm_time').on('click', function () {
			const time_id = $(this).attr('data-time-id')
			var cur_user_id = $(this).attr('data-cur_user_id')

			$.ajax({
				url: site_url_header + '/wp-admin/admin-ajax.php',
				method: 'POST',
				data: {
					action: 'confirm_time_meeting',
					time_id: time_id,
					cur_user_id: cur_user_id,
				},
				success: data => {
					console.log(data)
					$(this).parents('.time_btns').html('<p>Сделка состоялась</p>')
					window.location.reload()
				},
				error: data => {
					console.log(data)
				},
			})
		})
	}

	//подтверждение сделки для подарка
	if ($('.confirm_gift').length) {
		$('.confirm_gift').on('click', function () {
			const gift_id = $(this).attr('data-gift-id')
			var cur_user_id = $(this).attr('data-cur_user_id')

			$.ajax({
				url: site_url_header + '/wp-admin/admin-ajax.php',
				method: 'POST',
				data: {
					action: 'confirm_gift_meeting',
					gift_id: gift_id,
					cur_user_id: cur_user_id,
				},
				success: data => {
					console.log(data)
					$(this).parents('.gift_btns').html('<p>Сделка состоялась</p>')
					window.location.reload()
				},
				error: data => {
					console.log(data)
				},
			})
		})
	}

	//подтверждение обучения
	if ($('.confirm_lesson').length) {
		$('.confirm_lesson').on('click', function () {
			const lesson_id = $(this).attr('data-lesson-id')

			// var post_id = $(this).attr('data-post_id');
			// var user_id = $(this).attr('data-user_id');
			var cur_user_id = $(this).attr('data-cur_user_id')

			$.ajax({
				url: site_url_header + '/wp-admin/admin-ajax.php',
				method: 'POST',
				data: {
					action: 'confirm_lesson_meeting',
					lesson_id: lesson_id,
					cur_user_id: cur_user_id,
				},
				success: data => {
					console.log(data)
					$(this).parents('.gift_btns').html('<p>Сделка состоялась</p>')
					window.location.reload()
				},
				error: data => {
					console.log(data)
				},
			})
		})
	}

	//поиск по навыкам
	if ($('#search_navik_filter')) {
		$('#search_navik_filter').on(
			'keyup',
			$.debounce(function () {
				$this_input = $(this)
				const searchNaviK = $this_input.val()
				$list = $(this)
					.parents('.modal_filter__list__item__navik')
					.find('.modal_filter__list__item__navik__list')

				if (searchNaviK.length < 1) {
					return
				}

				const chechedTermsIds = []
				$list.children('label').each(function () {
					const isChecked = $(this).find('input').is(':checked')
					if (isChecked) {
						const idTerm = $(this).find('input').val()
						chechedTermsIds.push(idTerm)
					} else {
						$(this).remove()
					}
				})
				$('#text_filter_navik_not_search').remove()

				$.ajax({
					url: site_url_header + '/wp-admin/admin-ajax.php',
					method: 'POST',
					data: {
						action: 'search_naviks',
						count: 5,
						search: searchNaviK,
						chechedTermsIds: chechedTermsIds,
					},
					beforeSend: () => {
						$list.prepend("<p id='text_filter_navik_loading'>Загрузка...</p>")
					},
					success: data => {
						$('#text_filter_navik_loading').remove()
						$list.append(data)
					},
					error: data => {
						console.log(data)
					},
				})
			}, 400)
		)
	}

	//поиск по проблемам

	if ($('#search_problem_filter')) {
		$('#search_problem_filter').on(
			'keyup',
			$.debounce(function () {
				$this_input = $(this)
				const searchproblem = $this_input.val()
				$list = $(this)
					.parents('.modal_filter__list__item__problem')
					.find('.modal_filter__list__item__problem__list')

				if (searchproblem.length < 1) {
					return
				}

				const chechedTermsIds = []
				$list.children('label').each(function () {
					const isChecked = $(this).find('input').is(':checked')
					if (isChecked) {
						const idTerm = $(this).find('input').val()
						chechedTermsIds.push(idTerm)
					} else {
						$(this).remove()
					}
				})
				$('#text_filter_problem_not_search').remove()

				$.ajax({
					url: site_url_header + '/wp-admin/admin-ajax.php',
					method: 'POST',
					data: {
						action: 'search_problems',
						count: 5,
						search: searchproblem,
						chechedTermsIds: chechedTermsIds,
					},
					beforeSend: () => {
						$list.prepend("<p id='text_filter_problem_loading'>Загрузка...</p>")
					},
					success: data => {
						$('#text_filter_problem_loading').remove()
						$list.append(data)
					},
					error: data => {
						console.log(data)
					},
				})
			}, 400)
		)
	}

	//работа с новым фильтром
	if ($('#show_filter').length) {
		$('#show_filter').on('click', function () {
			$('#modal_filter__wrapper').fadeIn()
			$('.modal_filter').addClass('modal_filter--active')
			$('body').css('overflow', 'hidden')
		})

		$('.modal_filter').on('click', function (e) {
			e.stopPropagation()
		})

		$('#close_modal_filter, #modal_filter__wrapper').on('click', function () {
			$('#modal_filter__wrapper').fadeOut()
			$('.modal_filter').removeClass('modal_filter--active')
			$('body').css('overflow', 'auto')
		})

		$('input[name="filter_type[]"]').on('change', function () {
			var checkboxes = []
			$('input[name="filter_type[]"]').each(function () {
				if ($(this).is(':checked')) {
					checkboxes.push($(this).val())
					$(`input[name="filter_type[]"][value="${$(this).val()}"]`).attr(
						'checked',
						true
					)
				} else {
					$(`input[name="filter_type[]"][value="${$(this).val()}"]`).attr(
						'checked',
						false
					)
				}
			})

			// вывод дополнительных полей для расширенной фильтрации

			if (checkboxes.includes('post') || checkboxes.includes('time')) {
				$('.modal_filter__list__item__navik').fadeIn()
				$('.modal_filter__list__item__problem').fadeIn()
			} else {
				$('.modal_filter__list__item__navik').fadeOut()
				$('.modal_filter__list__item__problem').fadeOut()
			}
			if (checkboxes.includes('service')) {
				$('.modal_filter__list__item__navik').fadeIn()
				$('.modal_filter__list__item__problem').fadeIn()
			} else {
				$('.modal_filter__list__item__navik').fadeOut()
				$('.modal_filter__list__item__problem').fadeOut()
			}

			if (checkboxes.includes('time')) {
				$('.modal_filter__list__item__time_meeting').fadeIn()
			} else {
				$('.modal_filter__list__item__time_meeting').fadeOut()
			}

			if (checkboxes.includes('gift')) {
				$('.modal_filter__list__item__gift_type').fadeIn()
			} else {
				$('.modal_filter__list__item__gift_type').fadeOut()
			}
		})

		$('input[name="filter_state[]"]').on('change', function () {
			var checkboxes = []
			$('input[name="filter_state[]"]').each(function () {
				if ($(this).is(':checked')) {
					checkboxes.push($(this).val())
					$(`input[name="filter_state[]"][value="${$(this).val()}"]`).attr(
						'checked',
						true
					)
				} else {
					$(`input[name="filter_state[]"][value="${$(this).val()}"]`).attr(
						'checked',
						false
					)
				}
			})
		})

		$('input[name="filter_time_meeting[]"]').on('change', function () {
			var checkboxes = []
			$('input[name="filter_time_meeting[]"]:checked').each(function () {
				checkboxes.push($(this).val())
			})

			if (checkboxes.includes('offline')) {
				$('.modal_filter__list__item__town').fadeIn()
			} else {
				$('.modal_filter__list__item__town').fadeOut()
			}
		})

		//обновляем автоматически фильтр, когда меняется значение фильтра

		$('.modal_filter input, .default_up_filter input').on(
			'change',
			getFilteredMainpageFull
		)
	}

	//работа с показанием скрытием таблиц
	if ($('.exp_title_show__button').length) {
		$('.exp_title_show__button').on('click', function () {
			if ($(this).hasClass('exp_title_show__button--active')) {
				$(this).text('Показать')
				$(this).removeClass('exp_title_show__button--active')
				$(this).parent().next().slideUp()
			} else {
				$(this).text('Скрыть')
				$(this).addClass('exp_title_show__button--active')
				$(this).parent().next().slideDown()
			}
		})
	}

	//скролл на главной
	if ($('.main_button_take').length) {
		console.log('isset')
		$('.main_button_take').on('click', function () {
			$([document.documentElement, document.body]).animate(
				{
					scrollTop: $('.post_list_kw').offset().top,
				},
				0
			)
		})
	}

	//кнопка отдать на главной
	if ($('.main_button_give').length) {
		$('.main_button_give').on('click', function () {
			let redir = window.location.protocol + '//' + window.location.host
			if (isUserLogged) {
				redir += '/user?profiletab=mycustomtab'
			} else {
				redir += '/register'
			}
			window.location.replace(redir)
		})
	}

	/**
	 * Показ типов подарков при выборе в фильтре "подарки"
	 */
	$('input[name="filter_type[]"]').on('change', toggleFilterGiftType)

	function toggleFilterGiftType() {
		if ($('input[name="filter_type[]"][value="gift"]:checked').length) {
			$('#default-filter--gift-type').fadeIn()
		} else {
			$('#default-filter--gift-type').fadeOut()
		}
	}
})(jQuery)

var doingAjax = null

function getFilteredMainpageFull() {
	const sfers = []
	const state = []
	const types = []
	const naviks = []
	const problems = []
	const time_meeting = []
	const gift_type = []
	let town = ''

	$('input[name="filter_sfera[]"]:checked').each(function () {
		sfers.push($(this).val())
	})
	$('input[name="filter_state[]"]:checked').each(function () {
		state.push($(this).val())
	})
	$('input[name="filter_type[]"]:checked').each(function () {
		types.push($(this).val())
	})
	$('input[name="filter_navik[]"]:checked').each(function () {
		naviks.push($(this).val())
	})
	$('input[name="filter_problem[]"]:checked').each(function () {
		problems.push($(this).val())
	})
	$('input[name="filter_time_meeting[]"]:checked').each(function () {
		time_meeting.push($(this).val())
	})
	$('input[name="gift_type[]"]:checked').each(function () {
		gift_type.push($(this).val())
	})

	town = $('#filter_town').val()

	if (doingAjax) {
		doingAjax.abort()
	}

	doingAjax = $.ajax({
		url: site_url_header + '/wp-admin/admin-ajax.php',
		method: 'POST',
		data: {
			action: 'filter_new',
			sfers: sfers,
			state: state,
			types: types,
			naviks: naviks,
			problems: problems,
			time_meeting: time_meeting,
			gift_type: gift_type,
			town: town,
		},
		beforeSend: () => {
			$('#true_loadmore').fadeOut(1)
			$('.loader_mainpage').fadeIn()
			$('.post_list_kw').html('')
		},
		success: data => {
			$('.loader_mainpage').fadeOut()
			$('.main_stat').css('display', 'none')
			$('.post_list_kw').append(data)
			if (
				current_page < max_pages &&
				data != "<p id='filter_not_found'>Ничего не найдено</p>"
			) {
				$('#true_loadmore').fadeIn(100)
			}
		},
		error: data => {
			console.log(data)
		},
	})
}

;(function ($) {
	$.extend({
		debounce: function (fn, timeout, invokeAsap, ctx) {
			if (arguments.length == 3 && typeof invokeAsap != 'boolean') {
				ctx = invokeAsap
				invokeAsap = false
			}
			var timer
			return function () {
				var args = arguments
				ctx = ctx || this
				invokeAsap && !timer && fn.apply(ctx, args)
				clearTimeout(timer)
				timer = setTimeout(function () {
					invokeAsap || fn.apply(ctx, args)
					timer = null
				}, timeout)
			}
		},
		throttle: function (fn, timeout, ctx) {
			var timer, args, needInvoke
			return function () {
				args = arguments
				needInvoke = true
				ctx = ctx || this
				timer ||
					(function () {
						if (needInvoke) {
							fn.apply(ctx, args)
							needInvoke = false
							timer = setTimeout(arguments.callee, timeout)
						} else {
							timer = null
						}
					})()
			}
		},
	})
})(jQuery)
