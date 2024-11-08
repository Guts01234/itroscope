<?php
// создание мета поля баланс для пользователя
add_user_meta(get_current_user_id(), 'user_balance', 0, true);

/**
 * Получаем баланс пользователя
 * @param $user_id - айди пользователя
 * @return number значение баланса 
 */
function get_user_balance($user_id = null)
{
	if (!$user_id) {
		$user_id = get_current_user_id();
	}
	return +get_user_meta($user_id, 'user_balance', true);
}

/**
 * Изменяем значение баланса пользователя
 * @param $user_balance - новое значение баланса
 * @param $user_id - айди пользователя
 * @return $user_balance - новое значение баланса
 */
function update_user_balance($user_balance, $user_id = null)
{
	if (!$user_id) {
		$user_id = get_current_user_id();
	}
	update_user_meta($user_id, 'user_balance', $user_balance);
	return get_user_balance($user_id);
}

/**
 * Плюсуем прежнее значение баланса с новым, которое передается во второй аргумент функции
 * @param $user_new_balance - новое значение баланса
 * @param $user_id - айди пользователя
 * @return сумма прежнего баланса с новым
 */
function sum_user_balance($user_new_balance, $user_id = null)
{
	if (!$user_id) {
		$user_id = get_current_user_id();
	}
	$user_current_balance = get_user_balance($user_id);
	$user_total_balance = $user_current_balance + $user_new_balance;
	update_user_meta($user_id, 'user_balance', $user_total_balance);
	return get_user_balance($user_id);
}

/**
 * Минусуем прежнее значение баланса с новым, которое передается во второй аргумент функции
 * @param $user_new_balance - новое значение баланса
 * @param $user_id - айди пользователя
 * @return разность прежнего значения баланса с новым
 */
function minus_user_balance($user_new_balance, $user_id = null)
{
	if (!$user_id) {
		$user_id = get_current_user_id();
	}

	$user_current_balance = get_user_balance($user_id);
	$user_total_balance = $user_current_balance - $user_new_balance;
	update_user_meta($user_id, 'user_balance', $user_total_balance);
	return get_user_balance($user_id);
}

// создание мета поля номера карты пользолователя
add_user_meta(get_current_user_id(), 'user_number_card', '', true);

/**
 * Получаем номер карты пользователя
 * @param $user_id - айди пользователя
 * @return номер карты
 */
function get_user_number_card($user_id = null)
{
	if (!$user_id) {
		$user_id = get_current_user_id();
	}
	return get_user_meta($user_id, 'user_number_card', true);
}

/**
 * Изменяем номер карты пользователя
 * @param $user_number_card - новый счет карты пользователя
 * @param $user_id - айди пользователя
 * @return новый измененный счет карты пользователя
 */
function update_user_number_card($user_number_card, $user_id = null)
{
	if (!$user_id) {
		$user_id = get_current_user_id();
	}
	update_user_meta($user_id, 'user_number_card', $user_number_card);
	return get_user_number_card($user_id);
}
